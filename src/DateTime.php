<?php

declare(strict_types=1);

namespace Sept\Common;

/**
 * Дата + Время
 *
 * @author Александр Васильев <a.vasilyev@1sept.ru>
 * @uses \DateTime
 *
 * @see http://php.net/manual/ru/datetime.formats.relative.php "last day of" "first day of next month"
 */
class DateTime extends \DateTime
{
    /**
     * @var int максимальное значение для получения временной метки по UNIX времени
     */
    const MAX_TIMESTAMP  = 9222999999999999999;

    /**
     * @var string
     */
    const DEFAULT_TIMEZONE_NAME  = "Europe/Moscow";

    /**
     * @var string MySQL формат для указания отрицания в поле даты/времени
     */
    const MYSQL_FALSE  = '0000-00-00 00:00:00';

    /**
     * @var string MySQL формат даты/времени
     */
    const MYSQL_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var string MySQL формат даты/времени и микросекунды
     */
    const MYSQL_FORMAT_WITH_MICRO = self::MYSQL_FORMAT . '.u';
    /**
     * @deprcated
     */
    const FORMAT_MYSQL_WITH_MICRO = self::MYSQL_FORMAT_WITH_MICRO;

    /**
     * @var string RegExp MySQL формат дата/время и миллисекунды/микросекунды
     */
    const REGEXP_MYSQL_FORMAT_WITH_MICRO = /** @lang RegExp */
        '/^' . self::REGEXP_PART_MYSQL_FORMAT_WITH_MICRO . '$/';

    /**
     * @var string RegExp MySQL формат дата/время и миллисекунды/микросекунды
     */
    const REGEXP_PART_MYSQL_FORMAT_WITH_MICRO = /** @lang RegExp */
        '\d{4}(?|-\d{2}){2}\ \d{2}(?|:\d{2}){2}\.(?<microseconds>\d{1,8})\s*';

    /**
     * @var int 1 + 2 + 4 + 8 + 16 + 32 = 63 / секуды, минуты, часы, дни, месяца, годы
     */
    const INTERVAL_STRING_FORMAT = DateTimeDiff::SECONDS|DateTimeDiff::MINUTES|DateTimeDiff::HOURS|DateTimeDiff::DAYS|DateTimeDiff::MONTHS|DateTimeDiff::YEARS;

    /**
     * Дата введения юлианского календаря -4713-01-01 12:00:00
     *
     * @var string
     *
     * @see https://en.wikipedia.org/wiki/Julian_day#Calculation
     */
    const JULIAN_START = "-4714-11-24";

    /**
     * Дата введения григорианского календаря
     *
     * @var string
     *
     * В 1578 году папа Римский Григорий XIII принимает решение усовершенствовать старый стиль летосчисления, заменив юлианский календарь более точным, новым. С его благословения группа европейских учёных во главе с астрономом Луиджи Лиллио и выработала более точный современный так называемый григорианский календарь, которым мы пользуемся и поныне. В соответствии с ним после 4 октября 1582 года сразу наступило 15 октября.
     */
    const GREGORIAN_START = "1582-10-05";

    /**
     * Дата введения григорианского календаря в России
     *
     * @var string
     *
     * @see http://www.calend.ru/event/5889/
     * Период с 1 по 13 февраля 1918 года выпал из российского календаря. 24 января 1918 года декретом СНК РСФСР был введен григорианский календарь, в соответствии с которым была введена поправка в 13 суток. После 31 января 1918 года в России наступил день 14 февраля – в стране был введён календарь нового стиля (григорианский).
     */
    const GREGORIAN_START_RU = "1918-02-01";

    /**
     * @var string[]
     */
    const MONTHS_RU = [
        'Январь'   => 'января',
        'Февраль'  => 'февраля',
        'Март'     => 'марта',
        'Апрель'   => 'апреля',
        'Май'      => 'мая',
        'Июнь'     => 'июня',
        'Июль'     => 'июля',
        'Август'   => 'августа',
        'Сентябрь' => 'сентября',
        'Октябрь'  => 'октября',
        'Ноябрь'   => 'ноября',
        'Декабрь'  => 'декабря',
    ];

    /**
     * @var string[]
     */
    const MONTHS_RU_SHORT = [
        'янв.',
        'фев.',
        'марта',
        'апр.',
        'мая',
        'июня',
        'июля',
        'авг.',
        'сен.',
        'окт.',
        'ноя.',
        'дек.',
    ];

    /**
     * @var string[]
     */
    const WEEK_DAYS_RU = [
        "понедельник",
        "вторник",
        "среда",
        "четверг",
        "пятница",
        "суббота",
        "воскресенье",
    ];

    /**
     * @var string[]
     */
    const WEEK_DAYS_RU_SHORT = [
        "пн",
        "вт",
        "ср",
        "чт",
        "пт",
        "сб",
        "вс",
    ];

    /**
     * @deprecated DateTime::MONTHS_RU
     *
     * @var string[]
     */
    protected static $monthsReplacementRU = self::MONTHS_RU;

    /**
     * @var string[]
     */
    protected static $shortMonthsRecoveryRU = [
        'Мар.' => 'Март',
        'Май.' => 'Май',
        'Июн.' => 'Июнь',
        'Июл.' => 'Июль',
    ];

    /**
     * Работать и с микросекундами
     * @var boolean
     */
    protected static $withMicro = false;

    /**
     * Микросекунды – 1 000 000
     * @var int
     */
    protected $microseconds = 0;

    /**
     * Можно ли менять значение объекта
     * @var boolean
     */
    protected $changeable = true;

    /**
     * !!! C аргументами использовать \Sept\Common\DateTime::get($date, $timezone) !!!
     *
     * @param string|\DateTimeInterface $dateTime  Дата (пример: 2015-06-01)
     * @param null|\DateTimeZone        $timeZone  Часовой пояс
     * @param null|boolean              $withMicro Работать и с микросекундами
     */
    public function __construct (string|\DateTimeInterface $dateTime = 'now', null|\DateTimeZone $timeZone = null, null|bool $withMicro = null)
    {
        if ($timeZone !== null) {
            Data::instanceOfClass($timeZone, \DateTimeZone::class, "Неверно указано значение аргумента временной зоны для создания объекта " . __CLASS__ . "!");
        }

        $dateTimeStr = $dateTime;
        if ($dateTime instanceof \DateTimeInterface or $dateTime instanceof static) {
            if (!$timeZone)
                $timeZone = $dateTime->getTimezone();
            $dateTimeStr = $dateTime->format(static::MYSQL_FORMAT_WITH_MICRO);
        }

        parent::__construct($dateTimeStr, $timeZone);

        if ($this instanceof Date) {
            $this->setTime(0, 0, 0, 0);

        } else {
            if (preg_match(static::REGEXP_MYSQL_FORMAT_WITH_MICRO, $dateTimeStr, $matches))
                $this->setMicroseconds((int) $matches["microseconds"]);

            if ((!$dateTimeStr or $dateTimeStr == "now") and ($withMicro or ($withMicro !== false and static::isWithMicro()))) {
                $this->setMicroseconds(static::getCurrentMicroseconds());
            }
        }
    }

    /**
     * Возвращает объект DateTime или null/false (используется для преобразования строк даты-времени, полученных из БД)
     *
     * @param null|false|string|\DateTimeInterface $dateTime  Дата-Время (пример: 2015-06-01 12:00:00)
     * @param null|string|\DateTimeZone            $timeZone  Часовой пояс
     * @param null|boolean                         $withMicro Работать и с микросекундами
     *
     * @return false|null|static
     */
    public static function get (null|false|string|\DateTimeInterface $dateTime = null, null|string|\DateTimeZone $timeZone = null, null|bool $withMicro = null) : false|null|static
    {
        if ($dateTime === null or $dateTime === false) {
            return $dateTime;
        }

        if (is_string($timeZone))
            $timeZone = new \DateTimeZone($timeZone);

        if ($timeZone !== null)
            Data::instanceOfClass($timeZone, \DateTimeZone::class, "Неверно указано значение аргумента временной зоны для создания объекта " . __CLASS__ . "!");

        if ( $dateTime instanceof \DateTimeInterface or $dateTime instanceof static ) {
            if (!$timeZone)
                $timeZone = $dateTime->getTimezone();

            $dateTime = $dateTime->format(static::MYSQL_FORMAT_WITH_MICRO);
        }

        /**
         * Возвращать FALSE если:
         * array(), 0, "0", "", Date::MYSQL_FALSE или DateTime::MYSQL_FALSE
         */
        if (in_array($dateTime, [false, Date::MYSQL_FALSE, DateTime::MYSQL_FALSE])) {
            return false;
        }

        /**
         * Возвращать FALSE если:
         * 0000-00-00 00:00:00.0+
         */
        if (str_starts_with($dateTime, static::MYSQL_FALSE . ".0") and preg_match(static::REGEXP_MYSQL_FORMAT_WITH_MICRO, $dateTime)) {
            return false;
        }

        if (!$timeZone)
            $timeZone = new \DateTimeZone(date_default_timezone_get());

        return new static($dateTime, $timeZone);
    }

    /**
     * @return bool
     */
    public function isChangeable () : bool
    {
        return $this->changeable;
    }

    /**
     * @param bool $changeable
     *
     * @return $this
     */
    public function setChangeable (bool $changeable) : self
    {
        $this->changeable = $changeable;
        return $this;
    }

    /**
     * Локализация DateTime::format() через strftime()
     *
     * @param string $format The format of the outputted date string
     *
     * @see \Sept\Common\DateTime::format()
     * @see \DateTime::format()
     * @see date() – http://php.net/manual/ru/function.date.php
     *
     * @example
     * "d M Y at H:i"    returns "08 Mar 2000 at 09:37"
     * "d.m.Y"           returns "08.03.2000
     * "j F Y в H:i:s"   returns "5 марта 2011 в 22:25:19"
     * "l, j F Y at H:i" returns "wednesday, 1 September 2010 at 12:01"
     * "в R, j F в H:i"  returns "в субботу, 1 Сентября в 12:01"
     * "D, G:i:s"        returns "Mon, 6:34:05"
     * "c"               returns "2000-05-21T16:24:36+03:00"
     * "r"               returns "Thu, 23 Feb 2000 16:05:09 +0300"
     * "u"               returns "004532" – Микросекунды
     *
     * @return string
     */
    public function format (string $format) : string
    {
        // Найти латинские буквы:
        preg_match_all('/[djFMlDuR]/', $format, $matches);
        if (!empty($matches[0]))
        {
            $months = array_values(array_keys(self::MONTHS_RU));
            // Если в запрошенном формате есть запрос на день месяца, то выводить месяц в Родительном падеже:
            if (array_intersect($matches[0], ['d', 'j']))
                $months = array_values(self::MONTHS_RU);

            foreach ($matches[0] as $char)
            {
                $replaceTo = "";

                // Месяц
                if ($char == 'F')
                    $replaceTo = $months[(parent::format('n') - 1)];
                // Месяц коротко
                elseif ($char == 'M')
                    $replaceTo = self::MONTHS_RU_SHORT[(parent::format('n') - 1)];
                // День недели
                elseif ($char == 'l')
                    $replaceTo = self::WEEK_DAYS_RU[(parent::format('N') - 1)];
                // День недели, коротко
                elseif ($char == 'D')
                    $replaceTo = self::WEEK_DAYS_RU_SHORT[(parent::format('N') - 1)];
                // Микросекунды
                elseif ($char == 'u')
                    $replaceTo = (string) $this->getMicroseconds();
                // День недели в винительном падеже
                elseif ($char == 'R')
                {
                    // Для Вторника если в строке есть предлог «в», заменить на «во»
                    if (parent::format("N") == 2)
                        $format = preg_replace('/\bв\s+(?=\bR\b)/uXs', 'во' . Text::SPACE_NOBR, $format, 1);
                    $replaceTo = self::getWeekDays((int) parent::format("N"),true);
                }

                if ($replaceTo)
                    $format = preg_replace("/{$char}/u", $replaceTo, $format, 1);
            }
        }

        return parent::format($format);
    }

    /**
     * Возвращает текущую ДатуВремя
     * @deprecated new \Sept\Common\DateTime()
     * @param null|bool $withMicro Работать и с микросекундами
     * @return static
     */
    public static function getCurrent (null|bool $withMicro = null) : static
    {
        return new static("now", null, $withMicro);
    }

    /**
     * Возвращает текущее ДатуВремя по указанной временной метке
     *
     * @param null|int|float $timeStamp
     *
     * @return static
     */
    public static function getByTimeStamp (null|int|float $timeStamp = null) : static
    {
        $timeStampInt = intval($timeStamp);
        $microseconds = abs($timeStamp) - abs($timeStampInt);

        $timeStampInt = Data::getInteger($timeStampInt, "Неверно указано значения аргумента UNIX временной метки!", -static::MAX_TIMESTAMP, static::MAX_TIMESTAMP);

        $dateTime = new static("@{$timeStampInt}");

        if ($microseconds)
            $dateTime->setMicroseconds($microseconds);

        return $dateTime;
    }

    /**
     * Миллисекунды
     *
     * @param int $digits
     *
     * @return int
     */
    public static function getCurrentMicroseconds (int $digits = 6) : int
    {
        $digits = Data::getInteger($digits, new \InvalidArgumentException("Неверное указано значение аргумента кол-ва цифр микросекунд!"), 1, 8);

        $microseconds = (string) hrtime()[1];

        // Если менее 9-и символов, то значит значение менее 0.1 секунды, заменить на «0»:
        if (strlen($microseconds) > 9)
            return 0;

        // hrtime()
        $int = (int) substr($microseconds, 0, $digits);

        return $int;
    }

    /**
     * Проверить значение микросекунд
     *
     * @param float|int $microseconds
     *
     * @return float
     */
    public static function checkMicroseconds (float|int $microseconds) : float
    {
        // Если не дробное, то преобразовать в дробное:
        if (! is_float($microseconds))
            $microseconds = (float) ("0." . ((string) $microseconds));
        // Если дробное больше 1, то вычесть из дробного целое:
        elseif ($microseconds > 1)
            $microseconds = $microseconds - (int) $microseconds;

        if ($microseconds < Data::FLOATVAL_LOWEST)
            $microseconds = Data::FLOATVAL_LOWEST;

        return $microseconds;
    }

    /**
     * Получить значение микросекунд в виде строки
     *
     * @param float|int $microseconds
     * @param int       $digits
     *
     * @return string
     */
    public static function convertMicrosecondsToString (float|int $microseconds, int $digits = 6) : string
    {
        $microseconds = static::checkMicroseconds($microseconds);

        return '0.' . substr((string)$microseconds, 2, $digits);
    }

    /**
     * Получить дробное значение микросекунд из строки
     *
     * @param string|float|int $microseconds
     *
     * @return float
     */
    public static function convertMicrosecondsStringToFloat (string|float|int $microseconds) : float
    {
        if (! is_string($microseconds)) {
            $microseconds = (string) $microseconds;
        }

        if (strpos($microseconds, '.')) {
            $parts = explode(".", $microseconds);
        }

        $microseconds = (int) $microseconds;

        return self::checkMicroseconds($microseconds);
    }

    /**
     * Получить объект Date (только дата)
     *
     * @param string|float|int $microseconds
     *
     * @return int
     */
    public static function convertMicrosecondsToInteger (string|float|int $microseconds) : int
    {
        $microseconds = static::checkMicroseconds($microseconds);

        $microseconds = explode('.', (string) $microseconds)[1];

        return (int) $microseconds;
    }

    /**
     * Получить дробное значение разницы двух значений микросекунд
     *
     * @param float|string|int $micro1
     * @param float|string|int $micro2
     *
     * @return float
     */
    public static function getMicrosecondsDiff (float|string|int $micro1, float|string|int $micro2) : float
    {
        $micro1 = static::convertMicrosecondsStringToFloat($micro1);
        $micro2 = static::convertMicrosecondsStringToFloat($micro2);

        return abs($micro1 - $micro2);
    }

    /**
     * Получить миллисекунды в виде строки
     *
     * @param int $digits Кол-во символов
     *
     * @return string
     */
    public static function getCurrentMicrosecondsAsString (int $digits = 6) : string
    {
        return static::convertMicrosecondsToString(static::getCurrentMicroseconds($digits), $digits, "Взятие микросекунд системы в виде строки.");
    }

    /**
     * Взять микросекунды
     *
     * @return int
     */
    public function getMicroseconds () : int
    {
        return $this->microseconds;
    }

    /**
     * Взять микросекунды в виде строки дополненной нулями до указанной длины: 004560
     *
     * @param int $digits
     *
     * @return string
     */
    public function getMicrosecondsAsString (int $digits = 6) : string
    {
        return static::convertMicrosecondsToString($this->microseconds);
    }

    /**
     * Установить микросекунды
     *
     * @param int $microseconds
     *
     * @return $this
     */
    public function setMicroseconds (int $microseconds) : static
    {
        $this->microseconds = self::convertMicrosecondsToInteger($microseconds);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param int $hour
     * @param int $minute
     * @param int $second
     * @param int $microsecond
     *
     * @return $this
     * @link http://php.net/manual/en/datetime.settime.php
     */
    public function setTime (int $hour, int $minute, int $second = 0, int $microsecond = 0) : static
    {
        if (!$this->isChangeable())
            throw new \BadMethodCallException("У объекта временной метки «{$this->formatMySQL()}» указано, что нельзя менять! Можно клонировать объект и убрать метку о том, что нельзя менять!");

        parent::setTime($hour, $minute, $second);

        if ($microsecond)
            $this->setMicroseconds($microsecond);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $modifier
     *
     * @return self
     * @see https://www.php.net/manual/ru/datetime.formats.relative.php
     */
    public function modify (string $modifier) : static
    {
        if (!$this->isChangeable())
            throw new \BadMethodCallException("У объекта временной метки «{$this->formatMySQL()}» указано, что нельзя менять! Можно клонировать объект и убрать метку о том, что нельзя менять!");

        $micro = 0.0;
        $pattern = "/(?<sign>\+|\-)?(?<micro>(0\.)?\d+)\s+micro(second(s)?)?/u";
        if (preg_match($pattern, $modifier, $matches))
        {
            $modifier = trim(preg_replace($pattern, "", $modifier));
            $micro = static::convertMicrosecondsStringToFloat($matches["micro"]);
            if ($matches["sign"] == "-")
                $micro = -$micro;
        }

        if ($modifier)
            parent::modify($modifier);

        if ($micro)
        {
            $new = $this->getMicroseconds() + $micro;

            if ($new < 0)
            {
                $this->modify("-1 seconds");
                $new = $new + 1;
            }
            elseif ($new > 1)
            {
                $this->modify("+1 seconds");
                $new = $new - 1;
            }

            $this->setMicroseconds($new);
        }

        return $this;
    }

    /**
     * Возвращает текущее ДатуВремя для отправки в MySQL
     * @deprecated Используй передачу объекта new DataTime()
     * @return string
     */
    public static function getCurrentFormatMySQL () : string
    {
        return date(static::MYSQL_FORMAT);
    }

    /**
     * MySQL формат с микросекундами
     *
     * @return string
     */
    public static function getCurrentFormatMySQLWithMicro () : string
    {
        return (new static(null, null, true))->format(static::MYSQL_FORMAT_WITH_MICRO);
    }

    /**
     * Возвращает строку даты-времени, состоящую только из цифр
     * @example 05.01.1970 09:15
     * @return string
     */
    public function formatDigits () : string
    {
        return $this->format('d.m.Y H:i');
    }

    /**
     * Возвращает строку даты-времени в формате MySQL
     *
     * @return string
     */
    public function formatMySQL () : string
    {
        return $this->format(static::MYSQL_FORMAT);
    }

    /**
     * Возвращает строку даты-времени в формате MySQL с микросекундами
     *
     * @return string
     */
    public function formatMySQLWithMicro () : string
    {
        return $this->format(static::MYSQL_FORMAT_WITH_MICRO);
    }

    /**
     * Приведение типа к дате
     * @return Date
     */
    public function convertToDate () : Date
    {
        return new Date($this);
    }

    /**
     * Преобразовать объект в строку
     * @example 05 января 1970 г. в 09:15
     * @return string
     */
    public function __toString () : string
    {
        return $this->format('d F Y г. в H:i');
    }

    /**
     * Отдаёт массив типа [ 1 => 'январь', '2' => 'февраль'… ] для заданной локали
     *
     * @param string $locale Локаль (по умолчанию — ru_RU)
     *
     * @return string[]
     */
    public static function getAllMonthsList (string $locale = 'ru_RU') : array
    {
        $currentTimeLocale = setlocale(LC_TIME, 0);
        setlocale (LC_ALL, $locale .'.UTF-8');

        $date = new static('2000-01-01');
        $result = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthName = strftime("%B", $date->getTimestamp());
            if ($locale == 'ru_RU') {
                $monthName = str_replace(array_flip(static::$monthsReplacementRU), static::$monthsReplacementRU, strftime("%B", $date->getTimestamp()));
            }
            $result[$date->format('n')] = $monthName;
            $date->modify('+1 month');
        }

        setlocale(LC_TIME, $currentTimeLocale);
        return $result;
    }

    /**
     * {@inheritdoc}
     * @var string|int|\DateTimeInterface $dateTime
     *
     * @return \Sept\Common\DateInterval
     */
    public function diff (string|int|\DateTimeInterface $dateTime = "now", $absolute = false) : DateInterval
    {
        $dateTime = static::get($dateTime);

        $micro = static::getMicrosecondsDiff($this->getMicroseconds(), $dateTime->format("u"));
        $parentInterval = parent::diff($dateTime, $absolute);

        return DateInterval::convertFrom($parentInterval, DateTime::convertMicrosecondsToInteger($micro));
    }

    /**
     * Интервал до даты в виде читаемой строки
     *
     * @param null|string|\DateTimeInterface $dateTime Дата с которой сравнивать
     * @param boolean                        $asString Получить строку, иначе получить объект \Sept\Common\DateTimeDiff
     *
     * @return \Sept\Common\DateTimeDiff|string
     * @see \Sept\Common\DateTimeDiff
     */
    public function smart (null|string|\DateTimeInterface $dateTime = "now", bool $asString = true) : string|DateTimeDiff
    {
        $diff = new DateTimeDiff($this, $this->getTimezone(), $dateTime);

        return $asString ? $diff->getSmart() : $diff;
    }

    /**
     * Объект для выведения интервала дат
     *
     * @param null|string|\DateTimeInterface $dateTimeTo Дата с которой сравнивать
     *
     * @return \Sept\Common\DateTimeDiff
     *
     * @see \Sept\Common\DateTimeDiff
     */
    public function getDiff (\DateTimeInterface|string|null $dateTimeTo = "now") : DateTimeDiff
    {
        return new DateTimeDiff($this, $this->getTimezone(), $dateTimeTo);
    }

    /**
     * Получить массив с днями недели,
     * от 1 (понедельник) до 7 (воскресенье)
     *
     * @param null|integer $day        Запрос на конкретный день, от 1 (понедельник) до 7 (воскресенье)
     * @param bool         $accusative Получить массив с названиями дней недели в Винительном падеже
     *
     * @return string|string[]
     */
    static
    function getWeekDays (int $day = null, bool $accusative = false) : string|array
    {
        if (!$accusative)
            return $day ? static::WEEK_DAYS_RU[$day-1] : static::WEEK_DAYS_RU;

        /** Собрать в винительном */
        $days = static::WEEK_DAYS_RU;
        array_walk($days, function(&$val,$index){
            if (Text::endsWith($val, "а"))
                $val = preg_replace('/а/u','у',$val);
        });

        $days = array_values($days);

        return $day ? $days[$day-1] : $days;
    }

    /**
     * Сравнивает 2 даты-время (могут быть в разных часовых поясах)
     *
     * @param string|\DateTimeInterface $dateA     Дата А
     * @param string|\DateTimeInterface $dateB     Дата Б
     * @param null|string|\DateTimeZone $timeZone  Не используется (нужно для потомка класса — Date)
     * @param null|bool                 $withMicro Сравнивать с микросекундами
     *
     * @return float|int $dateA &lt; $dateB returns &lt; 0
     *
     * <pre>
     * $dateA &lt; $dateB returns &lt; 0
     * $dateA = $dateB returns = 0
     * $dateA &gt; $dateB returns &gt; 0
     * </pre>
     *
     * @see   \Sept\Common\Date::compare()
     */
    public static function compare (
        string|\DateTimeInterface $dateA,
        string|\DateTimeInterface $dateB,
        null|string|\DateTimeZone $timeZone = null,
        null|bool $withMicro = null
    ) : float|int
    {
        if (is_string($dateA)) {
            $dateA = new static($dateA);
        }

        if (is_string($dateB)) {
            $dateB = new static($dateB);
        }

        if (is_string($timeZone)) {
            $timeZone = new \DateTimeZone($timeZone);
        }

        Data::instanceOfClass($dateA, \DateTimeInterface::class, "Неверно указано значение первого аргумента – временной метки для сравнения со второй временной меткой (второй аргумент)!");
        Data::instanceOfClass($dateB, \DateTimeInterface::class, "Неверно указано значение второго аргумента – временной метки для сравнения с первой временной меткой (первый аргумент)! Первая временная метка «{$dateA->format(static::MYSQL_FORMAT_WITH_MICRO)}»!");

        if ($timeZone !== null) {
            Data::instanceOfClass($timeZone, \DateTimeZone::class, "Неверно указано значение аргумента временной зоны для сравнения двух временных меток! Временные метки: «{$dateA->format(static::MYSQL_FORMAT_WITH_MICRO)}» и «{$dateB->format(static::MYSQL_FORMAT_WITH_MICRO)}»");
        }

        $int = intval($dateA->format('U')) - intval($dateB->format('U'));

        if ($withMicro === false or ($withMicro === null and !static::isWithMicro())) {
            $total = $int;

        } else {
            $float = floatval("0." . $dateA->format('u')) - floatval("0." . $dateB->format('u'));
            $total = $int + $float;
            $int = Data::getInteger($total, false);

            if ($int !== false) {
                $total = $int;
            }
        }

        return $total;
    }

    /**
     * Сравнивает массив дат и вернуть наибольшую
     *
     * @param \DateTimeInterface[] $dateTimes
     * @param null|\DateTimeZone   $timeZone
     * @param null|bool            $withMicro Сравнивать с микросекундами
     *
     * @return \Sept\Common\DateTime
     */
    public static function max (array $dateTimes = [], null|\DateTimeZone $timeZone = null, null|bool $withMicro = null) : static
    {
        Data::typeOf($dateTimes, Data::TYPE_ARRAY, "Неверное значение аргумента массива временных меток для сравнения и получения наибольшей!");

        if ($timeZone !== null) {
            Data::instanceOfClass($timeZone, \DateTimeZone::class, "Неверно указано значение аргумента временной зоны!");
        }

        $max = null;
        foreach ($dateTimes as $indexOrKey => $dateTime) {
            Data::instanceOfClass($dateTime, \DateTimeInterface::class, "Неверное значения элемента «{$indexOrKey}» массива вр. меток для получения максимальной!");

            if (!$max) {
                $max = $dateTime;
                continue;
            }

            if ((!$timeZone and static::compare($dateTime, $max, null, $withMicro) > 0) or static::compare($dateTime, $max, $timeZone, $withMicro) > 0)
                $max = $dateTime;
        }

        if (!Data::instanceOfClass($max, __CLASS__, false)) {
            $max = new static($max);
        }

        return $max;
    }

    /**
     * Сравнение даты (даты-время) с другой
     *
     * @param string|\DateTimeInterface $date      Дата, с которой сравнивается текущая
     * @param null|string|\DateTimeZone $timeZone  Временная зона для сравнения (по умолчанию — Europe/Moscow)
     * @param null|bool                 $withMicro Сравнивать с микросекундами
     *
     * @return int Вывод аналогичен статичному методу {@link DateTime::compare()}
     */
    public function compareWith (string|\DateTimeInterface $date, null|string|\DateTimeZone $timeZone = null, null|bool $withMicro = null) : int
    {
        return static::compare($this, $date, $timeZone, $withMicro);
    }

    /**
     * Совпадают ли 2 даты-время (могут быть в разных часовых поясах)
     *
     * @param string|\DateTimeInterface $dateA     Дата А
     * @param string|\DateTimeInterface $dateB     Дата Б
     * @param null|string|\DateTimeZone $timeZone  Не используется (нужно для потомка класса — Date)
     * @param null|bool                 $withMicro Сравнивать с микросекундами
     *
     * @return bool
     */
    public static function isEqual (string|\DateTimeInterface $dateA, string|\DateTimeInterface $dateB, null|string|\DateTimeZone $timeZone = null, null|bool $withMicro = null) : bool
    {
        return (static::compare($dateA, $dateB, $timeZone, $withMicro) == 0);
    }

    /**
     * Совпадает ли дата (дата-время) с другой
     *
     * @param string|\DateTimeInterface $date      Дата, с которой сравнивается текущая
     * @param null|string|\DateTimeZone $timeZone  Не используется (нужно для потомка класса — Date)
     * @param null|bool                 $withMicro Сравнивать с микросекундами
     *
     * @return bool
     */
    public function isEqualWith (string|\DateTimeInterface $date, null|string|\DateTimeZone $timeZone = null, null|bool $withMicro = null) : bool
    {
        return static::isEqual($this, $date, $timeZone, $withMicro);
    }

    /**
     * Первый день месяца/недели/года
     *
     * @see http://php.net/manual/ru/datetime.formats.relative.php "last day of" "first day of next month"
     *
     * @param string $dayOf
     *                      [+-]int/first/second/third/fourth/fifth/sixth/seventh/eighth/ninth/tenth/eleventh/twelfth/next/last/previous/this
     *                      year/month/week
     *
     * @return \Sept\Common\DateTime
     */
    public function toFirstDayOf (string $dayOf = "this month") : static
    {
        $dayOf = Text::prepareSingleLine($dayOf, true, "Неверно указано значение аргумента указывающего на запрашиваемый период для установки временной метки на первый день: недели, месяца, года!");

        $this->modify("first day of {$dayOf}");

        // Для \Sept\Common\Date, а то меняет время:
        if (is_a($this, 'Sept\Common\Date'))
            $this->setTime(0, 0);

        return $this;
    }

    /**
     * Последний день месяца/недели/года
     *
     * @see http://php.net/manual/ru/datetime.formats.relative.php "last day of" "first day of next month"
     *
     * @param string $dayOf
     *                      [+-]int/first/second/third/fourth/fifth/sixth/seventh/eighth/ninth/tenth/eleventh/twelfth/next/last/previous/this
     *                      year/month/week
     *
     * @return \Sept\Common\DateTime
     */
    public function toLastDayOf (string $dayOf = "this month") : static
    {
        $dayOf = Text::prepareSingleLine($dayOf, true, "Неверно указано значение аргумента указывающего на запрашиваемый период для установки временной метки на последний день: недели, месяца, года!");

        // Для \Sept\Common\Date, а то меняет время:
        $this->modify("last day of {$dayOf}");

        if (is_a($this, Date::class)) {
            $this->setTime(0, 0, 0 , 0);
        }

        return $this;
    }

    /**
     * Получить объект DateTime (дата+время) без Sept\Common, но с микросекундами (если они есть)
     *
     * @return \DateTime
     */
    public function getBaseClass () : \DateTime
    {
        return \DateTime::createFromFormat(static::MYSQL_FORMAT_WITH_MICRO, $this->formatMySQLWithMicro(), $this->getTimezone());
    }

    /**
     * Получить объект DateTime (дата+время)
     * @return \Sept\Common\DateTime
     */
    public function getDateTime () : self
    {
        return $this;
    }

    /**
     * Сконвертировать и получить объект Date (только дата)
     *
     * @return \Sept\Common\Date
     */
    public function getDate () : Date
    {
        return Date::get($this->format(Date::MYSQL_FORMAT), $this->getTimezone());
    }

    /**
     * Попадает ли заданное время в заданный диапазон?
     * Время начала и окончания проверяется _включительно_ (как в MySQL)!
     * @param null|string|\DateTimeInterface $begin Начало
     * @param null|string|\DateTimeInterface $end   Окончания
     * @return bool
     */
    function isBetween (null|string|\DateTimeInterface $begin = null, null|string|\DateTimeInterface $end = null) : bool
    {
        if ($begin and is_string($begin)) {
            $begin = new static($begin);
        }

        if ($end and is_string($end)) {
            $end = new static($end);
        }

        return (
            (!$begin or static::compare($this, $begin) >= 0)
                and
            (!$end or static::compare($this, $end) <= 0)
        );
    }

    /**
     * Меньше переданной даты
     * @param null|string|\DateTimeInterface $date Дата
     * @return bool
     */
    function isBefore (null|string|\DateTimeInterface $date) : bool
    {
        return $this->compareWith($date) < 0;
    }

    /**
     * Больше переданной даты
     * @param null|string|\DateTimeInterface $date Дата
     * @return bool
     */
    function isAfter (null|string|\DateTimeInterface $date) : bool
    {
        return $this->compareWith($date) > 0;
    }

    /**
     * Время в будущем?
     * \Sept\Common\DateTime::isDateTimeInFuture($dateTime);
     *
     * @param null|string|\DateTimeInterface $dateTime
     * @param null|string|\DateTimeZone      $timeZone  Не используется (нужно для потомка класса — Date)
     * @param null|bool                      $withMicro Сравнивать с микросекундами
     *
     * @return bool
     */
    public static function isDateTimeInFuture (null|string|\DateTimeInterface $dateTime, null|string|\DateTimeZone $timeZone = null, null|bool $withMicro = null) : bool
    {
        return (new static())->compareWith($dateTime, $timeZone, $withMicro) < 0;
    }

    /**
     * Время в прошлом?
     * \Sept\Common\DateTime::isInFuture($dateTime);
     *
     * @param null|string|\DateTimeInterface $dateTime
     * @param null|string|\DateTimeZone      $timeZone  Не используется (нужно для потомка класса — Date)
     * @param null|bool                      $withMicro Сравнивать с микросекундами
     *
     * @return bool
     */
    public static function isDateTimeInPast (null|string|\DateTimeInterface $dateTime, null|string|\DateTimeZone $timeZone = null, null|bool $withMicro = null) : bool
    {
        return (new static())->compareWith($dateTime, $timeZone, $withMicro) > 0;
    }

    /**
     * Время в будущем?
     * @return bool
     */
    function inFuture () : bool
    {
        return (bool) ($this->compareWith(new static) > 0);
    }

    /**
     * Время в прошлом?
     * @return bool
     */
    function inPast () : bool
    {
        return (bool) ($this->compareWith(new static) < 0);
    }

    /**
     * @param bool|null $changeable
     *
     * @return \Sept\Common\DateTime
     */
    function getClone (bool $changeable = null) : static
    {
        $clone = clone $this;
        if ($changeable !== null)
            $clone->setChangeable($changeable);

        return $clone;
    }

    /**
     * Попадает ли текущая Дата/ДатаВремя в заданный диапазон?
     *
     * @param string|\DateTimeInterface|false|null $dateTimeFrom Дата начала
     * @param string|\DateTimeInterface|false|null $dateTimeTo   Дата окончания
     * @param bool|string|\Exception      $throwException
     * @param null|bool                   $withMicro    Сравнивать с микросекундами
     *
     * @return bool
     */
    static function isActualPeriod (
        string|\DateTimeInterface|false|null $dateTimeFrom = null,
        string|\DateTimeInterface|false|null $dateTimeTo = null,
        bool|string|\Exception$throwException = true,
        null|bool $withMicro = null
    ) : bool
    {
        if ($dateTimeFrom and $dateTimeTo and static::compare($dateTimeFrom, $dateTimeTo, null, $withMicro) > 0)
        {
            if ($throwException)
                throw new \RuntimeException("Неверно указаны даты интервала: дата завершения «{$dateTimeTo->format(static::MYSQL_FORMAT)}» не должна быть раньше даты начала «{$dateTimeFrom->format(static::MYSQL_FORMAT)}»!");

            return false;
        }

        /*
         * Если передано время начала, и оно ещё не началось, то FALSE
         */
        if ($dateTimeFrom and static::compare($dateTimeFrom, new static, null, $withMicro) > 0) {
            return false;
        }

        /*
         * Если передано время завершения, и оно уже прошло, то FALSE
         */
        if ($dateTimeTo and static::compare($dateTimeTo, new static, null, $withMicro) < 0) {
            return false;
        }

        return true;
    }

    /**
     * Вывести строкой интервал дат [и времени]
     *
     * 8 + 16 + 32 = 56 / дни, месяца, годы
     * 1 + 2 + 4 + 8 + 16 + 32 = 63 / секунды, минуты, часы, дни, месяца, годы
     * 1 + 2 + 4 + 8 + 16 + 32 + 64 + 128 = 255 / + века и тысячелетия
     *
     * @param \DateTimeInterface $dateTimeFrom Дата начала
     * @param \DateTimeInterface $dateTimeTo   Дата окончания
     * @param null|integer       $format       Побитовый формат выведения
     * @param boolean            $checkYear    Проверять год, если совпадает с годом сегодняшним, то не отображать
     * @param string             $glue
     *
     * @return string
     */
    public static function getIntervalString (\DateTimeInterface $dateTimeFrom, \DateTimeInterface $dateTimeTo, null|int $format = null, bool $checkYear = true, string $glue = "") : string
    {
        if (static::compare($dateTimeFrom, $dateTimeTo) > 0)
            [$dateTimeFrom, $dateTimeTo] = [ $dateTimeTo, $dateTimeFrom ];

        if ($format == null) {
            if (defined($dateTimeFrom::class . '::INTERVAL_STRING_FORMAT'))
                $format = $dateTimeFrom::INTERVAL_STRING_FORMAT;
            else
                $format = self::INTERVAL_STRING_FORMAT;
        }

        $seconds1 = ":" . $dateTimeFrom->format("s");
        $seconds2 = ":" .   $dateTimeTo->format("s");

        if (!($format & DateTimeDiff::SECONDS))
            $seconds1 = $seconds2 = "";

        $mins1 = ":" . $dateTimeFrom->format("i");
        $mins2 = ":" .   $dateTimeTo->format("i");

        if (!($format & DateTimeDiff::MINUTES))
            $mins1 = $mins2 = ":00";

        $hour1 = "" . $dateTimeFrom->format("h");
        $hour2 = "" .   $dateTimeTo->format("h");

        if (!($format & DateTimeDiff::HOURS))
            $hour1 = $hour2 = $mins1 = $mins2 = "";

        $day1 = " " . $dateTimeFrom->format("j");
        $day2 = " " .   $dateTimeTo->format("j");

        if (!($format & DateTimeDiff::DAYS)) {
            $day1 = $day2 = "";
        }

        if ($day1 or !$glue) {
            $month1 = Text::SPACE_NOBR . array_values(static::MONTHS_RU)[$dateTimeFrom->format("n") - 1];
            $month2 = Text::SPACE_NOBR . array_values(static::MONTHS_RU)[$dateTimeTo->format("n") - 1];
        } else {
            $month1 = Text::SPACE_NOBR . $dateTimeFrom->format("F");
            $month2 = Text::SPACE_NOBR . $dateTimeTo->format("F");
        }

        if (!($format & DateTimeDiff::MONTHS)) {
            $month1 = $month2 = "";
        }

        if ($month2 and $glue and !$day2) {
            $month2 = trim($month2);
        }

        $year1 = " " . $dateTimeFrom->format("Y") . Text::SPACE_NOBR . "года";
        $year2 = " " .   $dateTimeTo->format("Y") . Text::SPACE_NOBR . "года";

        // Если без числа, совпадают месяца и года, то один месяц убрать:
        if (!($format & DateTimeDiff::DAYS) and trim($month1) == trim($month2) and trim($year1) == trim($year2)) {
            $month1 = "";
        }

        if (!($format & DateTimeDiff::YEARS)) {
            $year1 = $year2 = "";
        }

        if ($format == DateTimeDiff::YEARS and $year1 == $year2) {
            $year1 = $year2 = "";
        }

        $age1 = " " . floor($dateTimeFrom->format("Y") / 100) . Text::SPACE_NOBR . "века";
        $age2 = " " .   floor($dateTimeTo->format("Y") / 100) . Text::SPACE_NOBR . "века";

        if (!($format & DateTimeDiff::AGES)) {
            $age1 = $age2 = "";
        }

        $mil1 = " " . floor($dateTimeFrom->format("Y") / 1000) . Text::SPACE_NOBR . "тысячилетия";
        $mil2 = " " .   floor($dateTimeTo->format("Y") / 1000) . Text::SPACE_NOBR . "тысячилетия";

        if (!($format & DateTimeDiff::MILS))
            $mil1 = $mil2 = "";

        if ($mil1 == $mil2) {
            $mil1 = "";

            if ($age1 == $age2) {
                $age1 = "";

                if ($year1 == $year2) {
                    $year1 = "";

                    if ($checkYear and date("Y") . Text::SPACE_NOBR . "года" == trim($year2))
                        $year2 = "";

                    if ($month1 == $month2) {
                        $month1 = "";

                        if ($day1 == $day2) {
                            $day1 = "";

                            // Не убирать время
                            // if ($hour1 == $hour2) {
                            //     $hour1 = "";
                            //
                            //     // НЕ убирать минуты
                            //     // if ($mins1 == $mins2) {
                            //     //     $mins1 = "";
                            //     //
                            //     //     if ($seconds1 == $seconds2) {
                            //     //         $seconds1 = "";
                            //     //     }
                            //     // }
                            // }

                            if ($seconds1 == $seconds2) {
                                if ($mins1 == $mins2)
                                    if ($hour1 == $hour2)
                                        $seconds1 = $hour1 = $mins1 = "";
                            }

                            if ($seconds1 == $seconds2) {

                            }
                        }
                    }
                }
            }
        }

        $from = trim("{$hour1}{$mins1}{$seconds1}{$day1}{$month1}{$year1}{$age1}{$mil1}");
        $to   = trim("{$hour2}{$mins2}{$seconds2}{$day2}{$month2}{$year2}{$age2}{$mil2}");

        $from = trim($from, Text::SPACE_NOBR);
        $to   = trim($to  , Text::SPACE_NOBR);

        if (!$from)
            $text = $to;
        else
        {
            $fromStartStr = "с";
            if (preg_match("/\b2[\s ]/", $from))
                $fromStartStr = "со";

            if (!$glue)
                $text = "{$fromStartStr} {$from} по {$to}";
            else
                $text = "{$from}{$glue}{$to}";
        }

        // Если только один год:
        $text = preg_replace("/^(\d{4})" . Text::SPACE_NOBR . "года$/", "$1" . Text::SPACE_NOBR . "год", $text);

        return $text;
    }


    /**
     * Получить разницу в днях для даты по Юлианскому календарю
     *
     * @param string|\DateTimeInterface $dateTime
     *
     * @return float|int
     */
    public static function getJulianCalendarDiffDays (string|\DateTimeInterface $dateTime) : float|int
    {
        $dateTime = static::get($dateTime);

        $year = (int) $dateTime->format("Y");

        // Если до нашей эры:
        if ($year < 0)
            $year = abs($year) - 1;

        $diffDays  = ($year / 100) - ($year / 400);

        if ((int) $dateTime->format("Y") >= 0)
            $diffDays = $diffDays - 2;
        // Если до нашей эры:
        else
        {
            $diffDays = $diffDays + 2;
            $diffDays = -$diffDays;
        }

        return $diffDays;
    }


    /**
     * Дата указывает на временную метку до введения Григорианского календаря
     *
     * @param string|\DateTimeInterface $dateTime
     *
     * @return boolean
     */
    public static function isBeforeGregorianStart (string|\DateTimeInterface $dateTime) : bool
    {
        $dateTime = static::get($dateTime);

        $dateTimeGregorianStart = new static(static::GREGORIAN_START);
        if ($dateTime->isBefore($dateTimeGregorianStart))
            $result = true;
        else
            $result = false;

        return $result;
    }

    /**
     * Дата указывает на временную метку после введения Григорианского календаря в России
     *
     * @param string|\DateTimeInterface $dateTime
     *
     * @return boolean
     */
    public static function isAfterGregorianStartInRussia (string|\DateTimeInterface $dateTime) : bool
    {
        $dateTime = static::get($dateTime);

        $dateTimeOfStart = new static(static::GREGORIAN_START_RU);
        if ($dateTime->isAfter($dateTimeOfStart))
            $result = true;
        else
            $result = false;

        return $result;
    }

    function __clone()
    {

    }

    /**
     * Получить дату по Юлианскому календарю
     *
     * @return static
     */
    public function getInJulianCalendar () : static
    {
        $rc = static::getJulianCalendarDiffDays($this);

        $round = round(abs($rc));
        if ($rc < 0)
            $round = -$round;

        $clone = clone $this;

        return $clone->modify("-{$round} days");
    }

    /**
     * @see DateTime::getByJulianCalendar()
     */
    public function getOrthodox () : static
    {
        return $this->getInJulianCalendar();
    }

    /**
     * Получить строку даты с указанием даты по Юлианскому календарю
     *
     * @param int $first
     * @param int $formatWith
     *
     * @return string
     * @see https://www.php.net/manual/ru/function.cal-info.php
     */
    public function getAsStringWithJulian (int $first = CAL_GREGORIAN, int $formatWith = DateTimeDiff::MONTHS) : string
    {
        $julian = $this->getInJulianCalendar();
        $second = $this;

        $sp = Text::SPACE_NOBR;

        if ($first == CAL_JULIAN)
            $first = $julian;
        else
        {
            $first = $this;
            $second = $julian;
        }

        if ($formatWith & DateTimeDiff::YEARS or $second->format("Y") != $first->format("Y"))
            $string = $first->format("j{$sp}F Y{$sp}года") . " ({$second->format("j{$sp}F Y{$sp}года")})";
        else
        {
            if ($formatWith & DateTimeDiff::MONTHS or $second->format("F") != $first->format("F"))
                $string = $first->format("j{$sp}F") . " ({$second->format("j{$sp}F")})" . $first->format(" Y{$sp}года");
            else
            {
                if ($formatWith & DateTimeDiff::DAYS or $second->format("j") != $first->format("j"))
                    $string = $first->format("j{$sp}({$second->format("j")}){$sp}F Y{$sp}года");

                else
                    $string = $first->format("j{$sp}F Y{$sp}года");
            }
        }

        return $string;
    }

    /**
     * Получить строку даты с указанием даты по Юлианскому календарю
     *
     * @param int $formatWith
     *
     * @return string
     */
    public function getWithJulian (int $formatWith = DateTimeDiff::MONTHS) : string
    {
        return $this->getAsStringWithJulian(CAL_GREGORIAN, $formatWith);
    }

    /**
     * Получить строку даты по Юлианскому календарю с указанием даты Григорианскому
     *
     * @param int $formatWith
     *
     * @return string
     */
    public function getJulianWithGregorian (int $formatWith = DateTimeDiff::MONTHS) : string
    {
        return $this->getAsStringWithJulian(CAL_JULIAN, $formatWith);
    }

    /**
     * Получить значение метку "Работать с микросекундами"
     * @return bool
     */
    public static function isWithMicro () : bool
    {
        return self::$withMicro;
    }

    /**
     * Установить значение метку "Работать с микросекундами"
     *
     * @param bool $withMicro
     */
    public static function setWithMicro (bool $withMicro) : void
    {
        $withMicro = Data::typeOf($withMicro, [ Data::TYPE_BOOLEAN ], "Неверно указано значение аргумента для указания статичной метки – использовать ли микросекунды при работе с временными метками!");

        self::$withMicro = $withMicro;
    }

    /**
     * @param int $yearNumber
     *
     * @return $this
     */
    function setYear (int $yearNumber) : static
    {
        return $this->setDate($yearNumber, (int) $this->format("n"), (int) $this->format("j"));
    }

    /**
     * @param int $monthsNumber
     * @param int $dayOfMonth
     *
     * @return $this
     */
    function setMonthAndDay (int $monthsNumber, int $dayOfMonth) : static
    {
        return $this->setDate((int) $this->format("Y"), $monthsNumber, $dayOfMonth);
    }

    /**
     * @param int $weekNumber
     *
     * @return $this
     */
    function setWeekNumber (int $weekNumber) : static
    {
        Data::getInteger($weekNumber, "В году не более 53-х недель! 53 бывает редко, обычно 52.", 0, 53);

        $nowWeekNumber = $this->format("W");
        // Если номер недели не совпал, то изменить на нужное количество недель:
        if ($diff = $weekNumber - $nowWeekNumber)
            $this->modify("{$diff} weeks");

        return $this;
    }

    /**
     * @var int $dayOfWeek
     * @return $this
     */
    function setDayOfWeek (int $dayOfWeek) : static
    {
        $currentDayNumberOfWeek = $this->format("N");
        $diff = $dayOfWeek - $currentDayNumberOfWeek;
        if ($diff)
            $this->modify($diff . " days");

        return $this;
    }

    /**
     * @return $this
     */
    function modifyToYearStart () : static
    {
        $this->modifyToDayStart();
        $this->setDate((int) $this->format("Y"), 1, 1);

        return $this;
    }

    /**
     * @return $this
     */
    function modifyToYearEnd () : static
    {
        $this->modifyToDayEnd();
        $this->modify("last day of December");

        return $this;
    }

    /**
     * @return $this
     */
    function modifyToMonthStart () : static
    {
        $this->modifyToDayStart();
        $this->setDate((int) $this->format("Y"), (int) $this->format("n"), 1);

        return $this;
    }

    /**
     * @return $this
     */
    function modifyToMonthEnd () : static
    {
        $this->modifyToDayEnd();
        $this->setDate((int) $this->format("Y"), (int) $this->format("n"), (int) $this->format("t"));

        return $this;
    }

    /**
     * @return $this
     */
    function modifyToWeekStart () : static
    {
        if ($this->format("N") != 1)
            $this->modify("previous monday");

        $this->modifyToDayStart();

        return $this;
    }

    /**
     * @return $this
     */
    function modifyToWeekEnd () : static
    {
        if ($this->format("N") != 7)
            $this->modify("next sunday");

        $this->modifyToDayEnd();

        return $this;
    }

    /**
     * @return $this
     */
    function modifyToDayStart () : static
    {
        return $this->setTime(0,0,0);
    }

    /**
     * @return $this
     */
    function modifyToDayEnd () : static
    {
        return $this->setTime(23,59,59);
    }

    /**
     * Sets the current date of the DateTime object to a different date.
     * @param int $year
     * @param int $month
     * @param int $day
     * @return static
     * @link https://php.net/manual/en/datetime.setdate.php
     */
    public function setDate (int $year, int $month, int $day) : static
    {
        if (!$this->isChangeable())
            throw new \BadMethodCallException("У объекта временной метки «{$this->formatMySQL()}» указано, что нельзя менять! Можно клонировать объект и убрать метку о том, что нельзя менять!");

        parent::setDate($year, $month, $day);
        return $this;
    }

    /**
     * Проверять, попадает ли временная метка в сегодня
     *
     * @param \DateTimeInterface $dateTime
     *
     * @return boolean
     */
    public function isDateTimeToday (\DateTimeInterface $dateTime) : bool
    {
        $dayBegin = (new self)->modifyToDayStart();
        $dayEnd = (new self)->modifyToDayEnd();
        // Если не менее времени начала дня и не более времени конца дня:
        return ($dayBegin->isBefore($dateTime) || $dayBegin->isEqualWith($dateTime)) && ($dayEnd->isEqualWith($dateTime) || $dayEnd->isAfter($dateTime));
    }

    /**
     * Проверять, попадает ли данная временная метка в сегодня
     * @return boolean
     */
    public function isToday () : bool
    {
        return DateTime::isDateTimeToday($this);
    }
}
