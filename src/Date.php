<?php

declare(strict_types=1);

namespace App\Common;

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
    public function formatDigits ()
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
    public function __toString ()
    {
        return $this->format('j F Y г.');
    }

    /**
     * Сравнивает 2 даты (перед сравнением приводятся к одному часовому поясу)
     * @param string|\DateTime|\Parus\DateTime|\Parus\Date $dateA          Дата А
     * @param string|\DateTime|\Parus\DateTime|\Parus\Date $dateB          Дата Б
     * @param null|\DateTimeZone                           $timeZone       Временная зона для сравнения (по умолчанию — временная зона $dateA)
     * @param bool|string|\Exception                       $throwException Текст или исключение для дополнения текста исключений
     *
     * @return integer
     * $dateA < $dateB returns < 0
     * $dateA = $dateB returns = 0
     * $dateA > $dateB returns > 0
     */
    public static function compare ($dateA, $dateB, $timeZone = null, $throwException = "")
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

        return $dateA->format('Ymd') - $dateB->format('Ymd');
    }

    /**
     * Сконвертировать и получить объект DateTime (дата+время)
     * @return DateTime
     */
    public function getDateTime ()
    {
        return DateTime::get($this, $this->getTimezone());
    }

    /**
     * Получить объект Date (только дата)
     * @return Date
     */
    public function getDate ()
    {
        return $this;
    }
}
