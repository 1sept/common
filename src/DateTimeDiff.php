<?php

declare(strict_types=1);

namespace Sept\Common;

/**
 * Получить данные о разнице между датами
 *
 * @author Тимофей Соловейчик <timofey@1sept.ru>
 *
 * getText() >> [через] 12 дней [назад]
 *
 * getTextWithDate() >> [через] 12 дней [назад], 11 января в 0:00 (среда)
 *
 * getSmart() >> [через] 12 дней [назад], 11 января в полночь (среда)
 *
 * getAllCounters() >> 2 месяца 15 дней 11 часов 38 минут 28 секунд
 *
 *
 *   [date] => 2017-01-19 06:06:21.000000
 *   [timezone_type] => 3
 *   [timezone] => Europe/Moscow
 *   [dateTo:protected] => Sept\Common\DateTime Object
 *       (
 *           [microseconds:protected] => 0
 *           [date] => 2012-01-05 05:06:06.000000
 *           [timezone_type] => 3
 *           [timezone] => Europe/Moscow
 *       )
 *
 *   [punctuality:protected] => 0
 *   [interval:protected] => Sept\Common\DateInterval Object
 *       (
 *           [micro] => 0
 *           [totalDays:protected] => 1840
 *           [totalSeconds:protected] => 158983215
 *           [weekday] => 0
 *           [weekday_behavior] => 0
 *           [first_last_day_of] => 0
 *           [days] =>
 *           [special_type] => 0
 *           [special_amount] => 0
 *           [have_weekday_relative] => 0
 *           [have_special_relative] => 0
 *           [y] => 5
 *           [m] => 0
 *           [d] => 14
 *           [h] => 2
 *           [i] => 0
 *           [s] => 15
 *           [invert] => 0
 *       )
 *
 *   [was:protected] =>
 *   [text:protected] => через 5 лет
 *   [allCounters:protected] => 5 лет 14 дней 2 часа 15 секунд
 *   [dateString:protected] => 19 января в 6:06 (четверг)
 *   [smart:protected] => через 5 лет, 19 января в 6:06 (четверг)
 *   [micros:protected] =>
 *   [seconds:protected] => 15 секунд
 *   [minutes:protected] =>
 *   [hours:protected] => 2 часа
 *   [days:protected] => 14 дней
 *   [months:protected] =>
 *   [years:protected] => 5 лет
 *   [ages:protected] =>
 *   [millenniums:protected] =>
 *   [totalSeconds] => 159069615
 *   [totalMinutes] => 2651160
 *   [totalHours] => 44186
 *   [totalDays] => 1841
 *   [totalMonths] => 61
 *   [totalYears] => 5
 *   [totalAges] => 0
 *   [totalMils] => 0
 *   [strTotalSeconds] => через 159 069 615 секунд
 *   [strTotalMinutes] => через 2 651 160 минут
 *   [strTotalHours] => через 44 186 часов
 *   [strTotalDays] => через 1 841 день
 *   [strTotalMonths] => через 61 месяц
 *   [strTotalYears] => через 5 лет
 *   [strTotalAges] => через 0 веков
 *   [strTotalMils] => через 0 тысячелетий
 *   [past:protected] => назад
 *   [will:protected] => через
 *   [fields:protected] => Array
 *   [microseconds:protected] => 0
 */
class DateTimeDiff extends DateTime
{
    /** @var integer */
    const MICROSECONDS = 256;

    /** @var integer */
    const SECONDS = 1;

    /** @var integer */
    const MINUTES = 2;

    /** @var integer */
    const HOURS = 4;

    /** @var integer */
    const DAYS = 8;

    /** @var integer */
    const MONTHS = 16;

    /** @var integer */
    const YEARS = 32;

    /** @var integer */
    const AGES = 64;

    /** @var integer */
    const MILS = 128;

    /** @var <string, int> */
    const TIME_UNITS = [
        "microseconds" => self::MICROSECONDS,
        "seconds"      => self::SECONDS,
        "minutes"      => self::MINUTES,
        "hours"        => self::HOURS,
        "days"         => self::DAYS,
        "months"       => self::MONTHS,
        "years"        => self::YEARS,
        "ages"         => self::AGES,
        "millenniums"  => self::MILS,
    ];

    /**
    * Если дата/время не указаны, то сравнивает с Московским временем, временем сервера
    * @var self
    */
    protected $dateTimeTo;

    /**
    * @var integer Точность указания разницы, константы: SECONDS,MINUTES,HOURS,DAYS,MONTHS
    */
    protected $punctuality;

    /**
    * @var \Sept\Common\DateInterval
    * y - Количество лет.
    * m - Количество месяцев.
    * d - Количество дней.
    * h - Количество часов.
    * i - Количество минут.
    * s - Количество секунд.
    * micro - Количество микросекунд.
    * invert - Принимает 1, если интервал представляет отрицательный период времени и 0 в противном случае. См. DateInterval::format().
    * days - Если объект DateInterval создан методом DateTime::diff(), то это суммарное число дней между начальной и конечной датами. В противном случае days примет значение FALSE.
    *
    * @see http://www.php.net/manual/ru/dateinterval.format.php
    */
    protected $interval;

    /**
    * @var bool
    */
    protected bool $toNow = false;

    /**
    * @var integer Если 1 – позже сравниваемой даты, 0 – ранее сравниваемой даты
    */
    protected $was;

    /**
    * @var string
    * Строка с указанием разницы в текстовом формате
    */
    protected $text;

    /**
    * @var string
    * Строка с указанием разницы во всех не нулевых измерителях
    */
    protected $allCounters;

    /**
    * @var string
    * Строка с датой
    */
    protected $dateString;

    /**
    * @var string
    * Строка с датой
    */
    protected $smart;


    /** @var string */
    protected $micros;

    /** @var string */
    protected $seconds;

    /** @var string */
    protected $minutes;

    /** @var string */
    protected $hours;

    /** @var string */
    protected $days;

    /** @var string */
    protected $months;

    /** @var string */
    protected $years;

    /** @var string */
    protected $ages;

    /** @var string */
    protected $millenniums;



    /** @var integer */
    public $totalSeconds;

    /** @var integer */
    public $totalMinutes;

    /** @var integer */
    public $totalHours;

    /** @var integer */
    public $totalDays;

    /** @var integer */
    public $totalMonths;

    /** @var integer */
    public $totalYears;

    /** @var integer */
    public $totalAges;

    /** @var integer */
    public $totalMils;


    /** @var string */
    public string $strTotalSeconds;

    /** @var string */
    public string $strTotalMinutes;

    /** @var string */
    public string $strTotalHours;

    /** @var string */
    public string $strTotalDays;

    /** @var string */
    public string $strTotalMonths;

    /** @var string */
    public string $strTotalYears;

    /** @var string */
    public string $strTotalAges;

    /** @var string */
    public string $strTotalMils;


    /** @var null|string */
    protected ?string $past = "назад";

    /** @var null|string */
    protected ?string $will = "через";


    /** @var string[] */
    protected $fields = [
         "seconds"
        ,"minutes"
        ,"hours"
        ,"days"
        ,"months"
        ,"years"
        ,"ages"
        ,"millenniums"
    ];


    /**
     * @param string|\DateTimeInterface $dateTime      Строка даты и времени, конвертируемая в объект даты и времени, для которого нужно вычислить разницу
     * @param null|string|\DateTimeZone $timeZone
     * @param null|string|\DateTime     $dateToCompare [опция] Дата и время с которыми сравнивать, если не указано, то сравнит с настоящим
     * @param integer                   $punctuality Точность указания разницы, константы: SECONDS,MINUTES,HOURS,DAYS,MONTHS
     */
    function __construct ($dateTime = "now", $timeZone = null, $dateToCompare = "now", int $punctuality = 0)
    {
        parent::__construct($dateTime, $timeZone);

        $this->getDiff($dateToCompare, $punctuality);
    }


    /**
     * Получить разницу между датами в формате читаемого текста
     *
     * @param null|string|\DateTimeInterface $dateTimeTo  Дата и время с которыми сравнивать
     * @param integer                        $punctuality Точность указания разницы, константы: SECONDS,MINUTES,HOURS,DAYS,MONTHS
     *
     * @return self
     */
    function getDiff (\DateTimeInterface|string|null $dateTimeTo = "now", int $punctuality = 0) : self
    {
        $this->toNow = false;
        if ($dateTimeTo === '' || strtolower($dateTimeTo) === 'now')
            $this->toNow = true;

        if (!($dateTimeTo instanceof parent))
            $dateTimeTo = new parent($dateTimeTo);

        $this->dateTimeTo = $dateTimeTo;

        $this->punctuality = $punctuality;

        $this->interval = $dateTimeTo->diff($this);

        /**
         * 1 - было,
         * 0 - ещё не было
         */
        $was = $this->getInterval()->getInvert();

        $micros  = $this->getInterval()->getMicro();
        $seconds = $this->getInterval()->getSeconds();
        $minutes = $this->getInterval()->getMinutes();
        $hours   = $this->getInterval()->getHours();
        $days    = $this->getInterval()->getDays();
        $months  = $this->getInterval()->getMonths();
        $years   = $this->getInterval()->getYears();
        $ages    = intval($this->getInterval()->getYears() / 100) % 10;
        $mils    = intval($this->getInterval()->getYears() / 1000);

        $microInt = false;
        if ($micros)
        {
            $micros   = parent::convertMicrosecondsToString($micros ,6 ,"Для сбора данных о разнице временных меток!") . Text::SPACE_NOBR;
            $microInt = (int) $micros;
        }

        $this->micros  = !$microInt ? "" : ($micros . Data::countWordForm($microInt,["у" ,"ы" ,""],false,"микросекунд"));
        $this->seconds = !$seconds ? "" : Data::countWordForm($seconds,[ "у",   "ы",   ""    ],true,"секунд"    );
        $this->minutes = !$minutes ? "" : Data::countWordForm($minutes,[ "у",   "ы",   ""    ],true,"минут"     );
        $this->hours   = !$hours   ? "" : Data::countWordForm($hours,  [ "",    "а",   "ов"  ],true,"час"       );
        $this->days    = !$days    ? "" : Data::countWordForm($days,   [ "день","дня", "дней"],true             );
        $this->months  = !$months  ? "" : Data::countWordForm($months, [ "",    "а",   "ев"  ],true,"месяц"     );
        $this->years   = !$years   ? "" : Data::countWordForm($years,  [ "год", "года","лет" ],true             );
        $this->ages    = !$ages    ? "" : Data::countWordForm($ages,   [ "",    "а",   "ов"  ],true,"век"       );
        $this->millenniums = !$mils    ? "" : Data::countWordForm($mils,   [ "е",   "я",   "й"   ],true,"тысячелети");

        $this->totalSeconds = abs($dateTimeTo->getTimestamp() - $this->getTimestamp());
        $this->totalMinutes = (int) floor($this->getTotalSeconds() / 60);
        $this->totalHours   = (int) floor($this->getTotalMinutes() / 60);
        $this->totalDays    = (int) floor($this->getTotalHours() / 24);
        $this->totalMonths  = (int) floor($this->getTotalDays()  / 30);
        // Февраль, 28/29 дней:
        if (!$this->totalMonths && $months)
            $this->totalMonths = $months;
        $this->totalYears   = $years;
        $this->totalAges    = (int) floor($this->getTotalYears() / 100);
        $this->totalMils    = (int) floor($this->getTotalYears() / 1000);


        $this->strTotalSeconds = Data::countWordForm($this->getTotalSeconds(),["у"   ,"ы"   ,""    ],true,"секунд"    );
        $this->strTotalMinutes = Data::countWordForm($this->getTotalMinutes(),["у"   ,"ы"   ,""    ],true,"минут"     );
        $this->strTotalHours   = Data::countWordForm($this->getTotalHours()  ,[""    ,"а"   ,"ов"  ],true,"час"       );
        $this->strTotalDays    = Data::countWordForm($this->getTotalDays()   ,["день","дня" ,"дней"],true             );
        $this->strTotalMonths  = Data::countWordForm($this->getTotalMonths() ,[""    ,"а"   ,"ев"  ],true,"месяц"     );
        $this->strTotalYears   = Data::countWordForm($this->getTotalYears()  ,["год" ,"года","лет" ],true             );
        $this->strTotalAges    = Data::countWordForm($this->getTotalAges()   ,[""    ,"а"   ,"ов"  ],true,"век"       );
        $this->strTotalMils    = Data::countWordForm($this->getTotalMils()   ,["е"   ,"я"   ,"й"   ],true,"тысячелети");


        $past = null;
        $will = null;

        if ($this->isToNow())
        {
//            dump($this->isToNow());
            if ($was)
                $past = Text::SPACE_NOBR . $this->getPast();
            else
                $will = $this->getWill() . Text::SPACE_NOBR;
        }

        $fields = [
             "strTotalSeconds"
            ,"strTotalMinutes"
            ,"strTotalHours"
            ,"strTotalDays"
            ,"strTotalMonths"
            ,"strTotalYears"
            ,"strTotalAges"
            ,"strTotalMils"
        ];

        foreach( $fields as $field )
        {
            $this->$field = $will . $this->$field . $past;
        }

        $append = true;

        $smart = "";

        $sp = Text::SPACE_NOBR;

        /**
         * Разница в СЕКУНДАХ
         */
        if (!$this->getTotalMinutes())
        {
            $text = $this->getSeconds();

            /**
             * Только что
             */
            if ($this->getTotalSeconds() <= 3) {
                $append = false;
                $text = "только{$sp}что";
            }
        }
        /**
         * Разница в МИНУТАХ
         */
        elseif (!$this->getTotalHours())
        {
            $text = $this->getMinutes();

            if ($seconds >= 10 && $minutes < 10)
                $text = "{$text}{$sp}и{$sp}{$this->getSeconds()}";

            if ($minutes > 25 && $minutes < 35)
            {
                if ($was)
                    $smart = "полчаса{$sp}{$this->getPast()}";
                else
                    $smart = "{$this->getWill()}{$sp}полчаса";
            }
            else if ($minutes >= 45)
            {
                if ($was)
                    $smart = "менее{$sp}часа{$sp}{$this->getPast()}";
                else
                    $smart = "менее{$sp}чем{$sp}{$this->getWill()}час";
            }
        }
        /**
         * Разница в ЧАСАХ
         */
        elseif (!$this->getTotalDays())
        {
            $smart = "сегодня";

            $text = $this->getHours();

            if ($hours == 1)
            {
                $smart = $this->getHours();

                if ($minutes >= 29 && $minutes <= 31)
                    $smart = "полтора{$sp}часа";

                else if ($minutes > 4)
                    $smart = "{$smart}{$sp}и{$sp}{$this->getMinutes()}";

                if ($was)
                    $smart = "{$smart}{$sp}{$this->getPast()}";
                else
                    $smart = "{$this->getWill()}{$sp}{$smart}";

                if ($minutes >= 32)
                {
                    if ($was)
                        $smart = "менее{$sp}2-x{$sp}часов{$sp}{$this->getPast()}";
                    else
                        $smart = "менее{$sp}чем{$sp}{$this->getWill()}{$sp}2{$sp}часа";
                }
            }
            else
            {
                $text = $this->getHours();

                if ($minutes >= 45)
                    $text = preg_replace("/(^\d+)/" ,("\${1}" . '¾') ,$this->getHours());

                elseif ($minutes >= 30)
                    $text = preg_replace("/(^\d+)/" ,("\${1}" . Text::SYMBOL_HALF) ,$this->getHours());

                elseif ($minutes >= 15)
                    $text = preg_replace("/(^\d+)/" ,("\${1}" . '¼') ,$this->getHours());

                if ($hours >= 20)
                {
                    if ($was)
                        $smart = "менее{$sp}дня{$sp}{$this->getPast()}";
                    else
                        $smart = "менее{$sp}чем{$sp}{$this->getWill()}{$sp}день";
                }
            }

            if ($was && intval($dateTimeTo->format("G")) - $hours <= 0)
            {
                switch (true)
                {
                    case intval($dateTimeTo->format("G")) - $hours < 0:
                    case intval($dateTimeTo->format("i")) - $minutes < 0:
                    case intval($dateTimeTo->format("i")) - $minutes <= 0 && intval($dateTimeTo->format("s")) - $seconds < 0:
                        $smart = "вчера";
                        break;
                }
            }
            elseif (!$was && intval($dateTimeTo->format("G")) + $hours >= 23)
            {
                switch (true)
                {
                    case intval($dateTimeTo->format("G")) + $hours >= 24:
                    case intval($dateTimeTo->format("i")) + $minutes >= 60:
                    case intval($dateTimeTo->format("i")) + $minutes >= 59 && intval($dateTimeTo->format("s")) + $seconds >= 60:
                        $smart = "завтра";
                        break;
                }
            }
        }
        /**
         * Разница в ДНЯХ
         */
        elseif (!$this->getTotalMonths())
        {
            $text = "день";

            /**
             * Разница в 1 день
             */
            if ($days == 1)
            {
                if ($hours > 2)
                    $text = "день{$sp}и{$sp}{$this->getHours()}";

                if ($hours >= 11 && $hours <= 13)
                {
                    $text = "полтора{$sp}дня";
                }

                if ($was)
                {
                    if ($hours > 14)
                        $smart = "менее{$sp}2-х{$sp}дней{$sp}{$this->getPast()}";
                    else
                        $smart = "вчера";

                    if (intval($dateTimeTo->format("G")) - $hours <= 0)
                    {
                        switch (true)
                        {
                            case intval($dateTimeTo->format("G")) - $hours < 0:
                            case intval($dateTimeTo->format("i")) - $minutes < 0:
                            case intval($dateTimeTo->format("i")) - $minutes <= 0 && intval($dateTimeTo->format("s")) - $seconds < 0:
                                $smart = "позавчера";
                                break;
                        }
                    }
                }
                elseif (!$was)
                {
                    if ($hours > 14)
                        $smart = "менее{$sp}чем{$sp}{$this->getWill()}{$sp}2{$sp}дня";
                    else
                        $smart = "завтра";

                    if (intval($dateTimeTo->format("G")) + $hours >= 23)
                    {
                        switch (true){
                            case intval($dateTimeTo->format("G")) + $hours >= 24:
                            case intval($dateTimeTo->format("i")) + $minutes >= 60:
                            case intval($dateTimeTo->format("i")) + $minutes >= 59 && intval($dateTimeTo->format("s")) + $seconds >= 60:
                                $smart = "послезавтра";
                                break;
                        }
                    }
                }
            }
            else
            {
                $text = $this->getDays();

                if ($hours >= 11 && $hours <= 13)
                {
                    $text = preg_replace("/(^\d+)/" ,("\${1}" . Text::SYMBOL_HALF) ,$this->getDays());
                }
            }

            /**
             * Разница в 2 дня
             * /
            if ($days == 2)
            {
                if ($was && intval($dateTo->format("G")) + $hours <= 23)
                {
                    switch (true){
                        case intval($dateTo->format("G")) + $hours < 23:
                        case intval($dateTo->format("i")) + $minutes >= 60:
                        case intval($dateTo->format("i")) + $minutes >= 59 && intval($dateTo->format("s")) + $seconds >= 60:
                            $smart = "позавчера";
                            break;
                    }
                }
                else if (!$was && intval($dateTo->format("G")) - $hours <= 0)
                {
                    switch (true){
                        case intval($dateTo->format("G")) - $hours < 0:
                        case intval($dateTo->format("i")) - $minutes < 0:
                        case intval($dateTo->format("i")) - $minutes <= 0 && intval($dateTo->format("s")) - $seconds < 0:
                            $smart = "послезавтра";
                            break;
                    }
                }
            }
            /**/

            if (in_array($days ,[7 ,14/*Кажется слишком сурово, я бы не понял,21*/]))
            {
                $smart = "неделю";

                if ($days == 14)
                    $smart = "2{$sp}недели";

                if ($was)
                    $smart = "$smart{$sp}{$this->getPast()}";
                else
                    $smart = "{$this->getWill()}{$sp}$smart";
            }
        }
        /**
         * Разница в МЕСЯЦАХ
         */
        elseif (!$this->getTotalYears())
        {
            /**
             * Не более одного месяца
             */
            if ($months == 1)
            {
                $text = "месяц";

                if ($days > 2)
                {
                    $text = "один{$sp}месяц{$sp}и{$sp}{$this->getDays()}";

                    if ($days >= 14 && $days <= 17)
                        $text = "полтора{$sp}месяца";

                    else if ($days <= 17)
                    {
                        if ($was)
                            $smart = "$text{$sp}{$this->getPast()}";
                        else
                            $smart = "{$this->getWill()}{$sp}$text";
                    }
                    else
                    {
                        if ($was)
                            $smart = "менее{$sp}2-x{$sp}месяцев{$sp}{$this->getPast()}";
                        else
                            $smart = "менее{$sp}чем{$sp}{$this->getWill()}{$sp}2-а{$sp}месяца";
                    }
                }
            }
            else
            {
                $text = $this->getMonths();

                if ($days > 13 && $days < 18)
                {
                    $text = preg_replace("/(^\d+)/" ,("\${1}" . Text::SYMBOL_HALF) ,$this->getMonths());
                }
            }
        }
        /**
         * Разница в ГОДАХ
         */
        elseif ($this->getTotalYears() < 100)
        {
            if ($years == 1)
            {
                $text = "год";

                if ($months > 2)
                {
                    $text = "год{$sp}и{$sp}{$this->getMonths()}";
                }

                if ($months < 5)
                {
                    if ($was)
                        $smart = "более{$sp}года{$sp}{$this->getPast()}";
                    else
                        $smart = "более{$sp}чем{$sp}{$this->getWill()}{$sp}год";
                }
                else if ($months <= 7)
                {
                    $text = "полтора{$sp}года";
                }
                else
                {
                    if ($was)
                        $smart = "менее{$sp}2-х{$sp}лет{$sp}{$this->getPast()}";
                    else
                        $smart = "менее{$sp}чем{$sp}{$this->getWill()}{$sp}2{$sp}года";
                }
            }
            else
            {
                $text = $this->getYears();

                if ($months > 13 && $months < 18)
                {
                    $text = preg_replace("/(^\d+)/" ,("\${1}" . Text::SYMBOL_HALF) ,$this->getYears());
                }
            }
        }
        /**
         * Разница в ВЕКАХ
         */
        elseif ($this->getTotalYears() <= 1500)
        {
            $text = $this->getStrTotalAges();
        }
        /**
         * Разница в ТЫСЯЧЕЛЕТИЯХ
         */
        else
        {
            $text = $this->getStrTotalMils();
        }


        /**
         * Если указано добавить слово
         */
        if ($append)
        {
            $append = [];

            /**
             * Добавить века
             */
            if ($ages && $punctuality & self::AGES)
                $append[] = $this->getAges();

            /**
             * Добавить годы
             */
            if ($years && $punctuality & self::YEARS)
                $append[] = $this->getYears();

            /**
             * Добавить месяцы
             */
            if ($months && $punctuality & self::MONTHS)
                $append[] = $this->getMonths();

            /**
             * Добавить дни
             */
            if ($days && $punctuality & self::DAYS)
                $append[] = $this->getDays();

            /**
             * Добавить часы
             */
            if ($hours && $punctuality & self::HOURS)
                $append[] = $this->getHours();

            /**
             * Добавить минуты
             */
            if ($minutes && $punctuality & self::MINUTES)
                $append[] = $this->getMinutes();

            /**
             * Добавить секунды
             */
            if ($seconds && $punctuality & self::SECONDS)
                $append[] = $this->getSeconds();

            /**
             * Добавить микросекунды
             */
            if ($this->getMicros() && $punctuality & self::MICROSECONDS)
                $append[] = $this->getMicros();


            if (count($append))
            {
                /**
                 * Убрать уже взятое
                 */
                $append = array_diff([$text] ,$append);

                $last = array_pop($append);

                $text = $text . $sp . implode($sp ,$append) . " и{$sp}" . $last;
            }

            /**
             * Уже прошло
             */
            if ($was)
                $text = "{$text}{$sp}{$this->getPast()}";
            /**
             * Будет
             */
            else
                $text = "{$this->getWill()}{$sp}{$text}";
        }


        /**
         * Дата и время
         */
        $dateString = $this->format("в{$sp}G:i:s");
        if (!$this->getTotalDays() && $this->format("l") != DateTime::getCurrent()->format("l"))
            $dateString = $dateString . $this->format(" (l)");
        else if ($this->getTotalDays())
        {
            $dateString = $this->format("j{$sp}F Y{$sp}года в{$sp}G:i (l)");

            if ($this->isToNow() && $this->format("Y") == date("Y"))
                $dateString = $this->format("j{$sp}F в{$sp}G:i (l)");
        }

        $this->text       = $text;
        $this->dateString = $dateString;

        if (empty($smart))
            $smart = $text;

        $this->smart = $smart;

        $this->getAllCounters();

        // В полночь:
        if ($this->getDateString())
        {
            if (intval($this->format("G")) == 0 && intval($this->format("i")) <= 5)
                $this->smart = $this->getSmart() . " в полночь, " . preg_replace("/в\s/u" ,"" ,$this->getDateString());
            else
                $this->smart = $this->getSmart() . ", " . $this->getDateString();
        }

        // Если проверяется не по сейчас:
        if (!$this->isToNow())
        {
            $this->text  = $this->getCountersByBit($punctuality) . $sp .  self::getIntervalString($this, $this->dateTimeTo, $punctuality);
            $this->smart = $this->getCountersByBit($punctuality) . $sp .  self::getIntervalString($this, $this->dateTimeTo, $punctuality);
        }

        return $this;

    }


    /**
     * Взять интервал в виде строки со значениями
     *
     * @param int $min Минимальное значение, после которого не будут браться другие значения, использовать константы
     *
     * @return null|string
     */
    function getAllCounters (int $min = 0) : null|string
    {
        $this->allCounters = null;

        $minCheck = self::MILS;
        foreach (array_reverse($this->fields) as $field)
        {
            if ($this->$field)
                $this->allCounters[] = $this->$field;

            if ($minCheck == $min)
                break;

            $minCheck = $minCheck / 2;
        }

        /**
         * Если есть разница
         */
        if (is_array($this->allCounters) and count($this->allCounters)) {
            $this->allCounters = implode(" " ,$this->allCounters);
        }

        return $this->allCounters;
    }


    /**
     * @param integer $punctuality Точность указания разницы, константы: SECONDS,MINUTES,HOURS,DAYS,MONTHS
     *
     * @return string
     */
    function getCountersByBit (int $punctuality = 0) : string
    {
        $strings = [];
        foreach (array_reverse($this->fields) as $field)
        {
            $bit = self::TIME_UNITS[$field];

            if (!($punctuality & $bit))
                continue;

            if ($this->$field)
                $strings[] = $this->$field;
        }

        /**
         * Если есть разница
         */
        if ($strings)
            $strings = implode(" ", $strings);
        else
            $strings = "";

        return $strings;
    }


    /**
     * @deprcated
     * @return self
     */
    function getDateTo () : self
    {
        return $this->getDateTimeTo();
    }


    /** @return self */
    function getDateTimeTo () : self
    {
        return $this->dateTimeTo;
    }


    /**
     * через 12 дней
     *
     * @return string
     */
    function getText () : string
    {
        return $this->text;
    }


    /**
     * через 12 дней, 11 января в 0:00 (среда)
     *
     * @return string
     */
    function getTextWithDate () : string
    {
        return $this->text . "," . Text::SPACE_NOBR . $this->dateString;
    }


    /**
     * через 12 дней, 11 января в полночь (среда)
     *
     * @return string
     */
    function getSmart() : string
    {
        return $this->smart;
    }


    /** @return int */
    public function getWas () : int
    {
        return $this->was;
    }


    /** @return \Sept\Common\DateInterval */
    public
    function getInterval () : DateInterval
    {
        return $this->interval;
    }


    /**
     * @return int
     */
    public
    function getPunctuality () : int
    {
        return $this->punctuality;
    }


    /**
     * @return string
     */
    public
    function getDateString () : string
    {
        return $this->dateString;
    }


    /**
     * @return string
     */
    public
    function getMicros () : string
    {
        return $this->micros;
    }


    /**
     * @return string
     */
    public
    function getSeconds () : string
    {
        return $this->seconds;
    }


    /**
     * @return string
     */
    public
    function getMinutes () : string
    {
        return $this->minutes;
    }


    /**
     * @return string
     */
    public
    function getHours () : string
    {
        return $this->hours;
    }


    /**
     * @return string
     */
    public
    function getDays () : string
    {
        return $this->days;
    }


    /**
     * @return string
     */
    public
    function getMonths () : string
    {
        return $this->months;
    }


    /**
     * @return string
     */
    public
    function getYears () : string
    {
        return $this->years;
    }


    /**
     * @return string
     */
    public
    function getAges () : string
    {
        return $this->ages;
    }


    /**
     * @return string
     */
    public
    function getMillenniums () : string
    {
        return $this->millenniums;
    }


    /**
     * @return int
     */
    public
    function getTotalSeconds () : int
    {
        return $this->totalSeconds;
    }


    /**
     * @return int
     */
    public
    function getTotalMinutes () : int
    {
        return $this->totalMinutes;
    }


    /**
     * @return int
     */
    public
    function getTotalHours () : int
    {
        return $this->totalHours;
    }


    /**
     * @return int
     */
    public
    function getTotalDays () : int
    {
        return $this->totalDays;
    }


    /**
     * @return int
     */
    public
    function getTotalMonths () : int
    {
        return $this->totalMonths;
    }


    /**
     * @return int
     */
    public
    function getTotalYears () : int
    {
        return $this->totalYears;
    }


    /**
     * @return int
     */
    public
    function getTotalAges () : int
    {
        return $this->totalAges;
    }


    /**
     * @return int
     */
    public
    function getTotalMils () : int
    {
        return $this->totalMils;
    }


    /**
     * @return string
     */
    public
    function getStrTotalSeconds () : string
    {
        return $this->strTotalSeconds;
    }


    /**
     * @return string
     */
    public
    function getStrTotalMinutes () : string
    {
        return $this->strTotalMinutes;
    }


    /**
     * @return string
     */
    public
    function getStrTotalHours () : string
    {
        return $this->strTotalHours;
    }


    /**
     * @return string
     */
    public
    function getStrTotalDays () : string
    {
        return $this->strTotalDays;
    }


    /**
     * @return string
     */
    public
    function getStrTotalMonths () : string
    {
        return $this->strTotalMonths;
    }


    /**
     * @return string
     */
    public
    function getStrTotalYears () : string
    {
        return $this->strTotalYears;
    }


    /**
     * @return string
     */
    public
    function getStrTotalAges () : string
    {
        return $this->strTotalAges;
    }


    /**
     * @return string
     */
    public
    function getStrTotalMils () : string
    {
        return $this->strTotalMils;
    }


    /**
     * @return string
     */
    public
    function getPast () : string
    {
        return $this->past;
    }


    /**
     * @return string
     */
    public
    function getWill () : string
    {
        return $this->will;
    }


    /**
     * @return string[]
     */
    public
    function getFields () : array
    {
        return $this->fields;
    }

    /**
     * @return bool
     */
    public function isToNow () : bool
    {
        return $this->toNow;
    }

}
