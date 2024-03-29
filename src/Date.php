<?php

declare(strict_types=1);

namespace Sept\Common;

/**
 * Дата
 *
 * @author Александр Васильев <a.vasilyev@1sept.ru>
 * @uses DateTime
 */
class Date extends DateTime
{

    /**
     * @var string MySQL формат для указания отрицания в поле даты
     */
    const MYSQL_FALSE  = '0000-00-00';

    /**
     * @var string MySQL формат даты
     */
    const MYSQL_FORMAT = 'Y-m-d';

    /**
     * @var int 8 + 16 + 32 = 56 / дни, месяца, годы
     */
    const INTERVAL_STRING_FORMAT = 56;

    /**
     * Возвращает строку даты, состоящую только из цифр
     * @return string
     * @example 21.05.1984
     */
    public function formatDigits () : string
    {
        return $this->format('d.m.Y');
    }

    /**
     * Приведение типа к дате-времени
     * @return DateTime
     */
    public function convertToDateTime (): DateTime
    {
        return new DateTime($this);
    }

    /**
     * Преобразовать объект в строку
     * @example 5 января 1970 г.
     * @return string
     */
    public function __toString () : string
    {
        return $this->format('j F Y г.');
    }

    /**
     * Сравнивает 2 даты (перед сравнением приводятся к одному часовому поясу)
     * @param string|\DateTimeInterface $dateA     Дата А
     * @param string|\DateTimeInterface $dateB     Дата Б
     * @param null|string|\DateTimeZone $timeZone  Временная зона для сравнения (по умолчанию — временная зона $dateA)
     * @param null|bool                 $withMicro Сравнивать с микросекундами
     *
     * @return integer
     * $dateA < $dateB returns < 0
     * $dateA = $dateB returns = 0
     * $dateA > $dateB returns > 0
     */
    public static function compare (
        string|\DateTimeInterface $dateA,
        string|\DateTimeInterface $dateB,
        null|string|\DateTimeZone $timeZone = null,
        null|bool $withMicro = null
    ) : int
    {
        // Проверка аргументов:
        parent::compare($dateA, $dateB, $timeZone);

        if (is_string($dateA))
            $dateA = new static($dateA);

        if (is_string($dateB))
            $dateB = new static($dateB);

        if (is_string($timeZone))
            $timeZone = new \DateTimeZone($timeZone);

        if ($timeZone) {
            $dateA->setTimezone($timeZone);
        } else {
            $timeZone = $dateA->getTimezone();
        }

        $dateB->setTimezone($timeZone);

        return (int) $dateA->format('Ymd') - $dateB->format('Ymd');
    }

    /**
     * Сконвертировать и получить объект DateTime (дата+время)
     * @return DateTime
     */
    public function getDateTime () : DateTime
    {
        return DateTime::get($this, $this->getTimezone());
    }

    /**
     * Получить объект Date (только дата)
     * @return Date
     */
    public function getDate () : static
    {
        return $this;
    }
}
