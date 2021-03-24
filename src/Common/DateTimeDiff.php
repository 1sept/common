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
 *   [dateTo:protected] => Parus\DateTime Object
 *       (
 *           [microseconds:protected] => 0
 *           [date] => 2012-01-05 05:06:06.000000
 *           [timezone_type] => 3
 *           [timezone] => Europe/Moscow
 *       )
 *
 *   [punctuality:protected] => 0
 *   [interval:protected] => Parus\DateInterval Object
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
 *   [mils:protected] =>
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
    /**
     * @var integer
     */
    const MICROSECONDS = 256;
    /**
     * @var integer
     */
    const SECONDS = 1;
    /**
     * @var integer
     */
    const MINUTES = 2;
    /**
     * @var integer
     */
    const HOURS   = 4;
    /**
     * @var integer
     */
    const DAYS    = 8;
    /**
     * @var integer
     */
    const MONTHS  = 16;
    /**
     * @var integer
     */
    const YEARS   = 32;
    /**
     * @var integer
     */
    const AGES    = 64;
    /**
     * @var integer
     */
    const MILS    = 128;



    /**
    * @var DateTime
    * Если дата/время не указаны, то сравнивает с Московским временем, временем сервера
    */
    protected $dateTo;



    /**
    * @var integer Точность указания разницы, константы: SECONDS,MINUTES,HOURS,DAYS,MONTHS
    */
    protected $punctuality;



    /**
    * @var \Parus\DateInterval
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



    /**
    * @var string
    */
    protected $micros;

    /**
    * @var string
    */
    protected $seconds;

    /**
    * @var string
    */
    protected $minutes;

    /**
    * @var string
    */
    protected $hours;

    /**
    * @var string
    */
    protected $days;

    /**
    * @var string
    */
    protected $months;

    /**
    * @var string
    */
    protected $years;

    /**
    * @var string
    */
    protected $ages;

    /**
    * @var string
    */
    protected $mils;



    /**
    * @var integer
    */
    public $totalSeconds;
    /**
    * @var integer
    */
    public $totalMinutes;
    /**
    * @var integer
    */
    public $totalHours;
    /**
    * @var integer
    */
    public $totalDays;
    /**
    * @var integer
    */
    public $totalMonths;
    /**
    * @var integer
    */
    public $totalYears;
    /**
    * @var integer
    */
    public $totalAges;
    /**
    * @var integer
    */
    public $totalMils;

    /**
    * @var string
    */
    public $strTotalSeconds;
    /**
    * @var string
    */
    public $strTotalMinutes;
    /**
    * @var string
    */
    public $strTotalHours;
    /**
    * @var string
    */
    public $strTotalDays;
    /**
    * @var string
    */
    public $strTotalMonths;
    /**
    * @var string
    */
    public $strTotalYears;
    /**
    * @var string
    */
    public $strTotalAges;
    /**
    * @var string
    */
    public $strTotalMils;


    /**
    * @var string
    */
    protected $past = "назад";

    /**
    * @var string
    */
    protected $will = "через";


    /**
    * @var string[]
    */
    protected $fields = [
         "seconds"
        ,"minutes"
        ,"hours"
        ,"days"
        ,"months"
        ,"years"
        ,"ages"
        ,"mils"
    ];


    /**
     * @param string|\DateTime          $dateTime      Строка даты и времени, конвертируемая в объект даты и времени, для которого нужно вычислить разницу
     * @param null|string|\DateTimeZone $timeZone
     * @param null|string|\DateTime     $dateToCompare [опция] Дата и время с которыми сравнивать, если не указано, то сравнит с настоящим
     *
     * @throws \Exception
     * @throws \Parus\Exception\InvalidArgumentException
     */
    function __construct ($dateTime = "now" ,$timeZone = null ,$dateToCompare = "now")
    {
        parent::__construct($dateTime ,$timeZone);

        $this->getDiff($dateToCompare);
    }


    /**
     * Получить разницу между датами в формате читаемого текста
     *
     * @param string|\DateTime $dateTo      Дата и время с которыми сравнивать
     * @param integer          $punctuality Точность указания разницы, константы: SECONDS,MINUTES,HOURS,DAYS,MONTHS
     *
     * @return $this
     *
     * @throws \Exception
     * @throws \Parus\Exception\Exception
     * @throws \Parus\Exception\InvalidArgumentException
     */
    function getDiff ($dateTo = "now" ,$punctuality = 0)
    {
        if (!($dateTo instanceof parent))
            $dateTo = new parent($dateTo);

        $this->dateTo = $dateTo;

        $this->punctuality = $punctuality;

        $this->interval = $dateTo->diff($this);

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

        $this->micros  = !$microInt ? "" : $micros . Data::countWordForm($microInt,["у" ,"ы" ,""],false,"микросекунд");
        $this->seconds = !$seconds ? "" : Data::countWordForm($seconds,[ "у",   "ы",   ""    ],true,"секунд"    );
        $this->minutes = !$minutes ? "" : Data::countWordForm($minutes,[ "у",   "ы",   ""    ],true,"минут"     );
        $this->hours   = !$hours   ? "" : Data::countWordForm($hours,  [ "",    "а",   "ов"  ],true,"час"       );
        $this->days    = !$days    ? "" : Data::countWordForm($days,   [ "день","дня", "дней"],true             );
        $this->months  = !$months  ? "" : Data::countWordForm($months, [ "",    "а",   "ев"  ],true,"месяц"     );
        $this->years   = !$years   ? "" : Data::countWordForm($years,  [ "год", "года","лет" ],true             );
        $this->ages    = !$ages    ? "" : Data::countWordForm($ages,   [ "",    "а",   "ов"  ],true,"век"       );
        $this->mils    = !$mils    ? "" : Data::countWordForm($mils,   [ "е",   "я",   "й"   ],true,"тысячелети");


        $this->totalSeconds = abs($dateTo->getTimestamp() - $this->getTimestamp());
        $this->totalMinutes = floor($this->getTotalSeconds() / 60);
        $this->totalHours   = floor($this->getTotalMinutes() / 60);
        $this->totalDays    = floor($this->getTotalHours() / 24);
        $this->totalMonths  = floor($this->getTotalDays()  / 30);
        // Февраль, 28/29 дней:
        if (!$this->totalMonths && $months)
            $this->totalMonths = $months;
        $this->totalYears   = $years;
        $this->totalAges    = floor($this->getTotalYears() / 100);
        $this->totalMils    = floor($this->getTotalYears() / 1000);


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
        if ($was)
            $past = Text::SPACE_NOBR . $this->getPast();
        else
            $will = $this->getWill() . Text::SPACE_NOBR;

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

            if ($hours == 1)
            {
                $text = "час";

                if ($minutes >= 29 && $minutes <= 31)
                    $text = "полтора{$sp}часа";

                else if ($minutes > 4)
                    $text = "{$text}{$sp}и{$sp}{$this->getMinutes()}";

                if ($minutes >= 32)
                {
                    if ($was)
                        $smart = "менее{$sp}2-x{$sp}часов{$sp}{$this->getPast()}";
                    else
                        $smart = "менее{$sp}чем{$this->getWill()}{$sp}2{$sp}часа";
                }
            }
            else
            {
                $text = $this->getHours();

                if ($minutes >= 30)
                    $text = preg_replace("/(^\d+)/" ,("\${1}" . Text::SYMBOL_HALF) ,$this->getHours());

                if ($hours >= 20)
                {
                    if ($was)
                        $smart = "менее{$sp}дня{$sp}{$this->getPast()}";
                    else
                        $smart = "менее{$sp}чем{$this->getWill()}{$sp}день";
                }
            }

            if ($was && intval($dateTo->format("G")) - $hours <= 0)
            {
                switch (true)
                {
                    case intval($dateTo->format("G")) - $hours < 0:
                    case intval($dateTo->format("i")) - $minutes < 0:
                    case intval($dateTo->format("i")) - $minutes <= 0 && intval($dateTo->format("s")) - $seconds < 0:
                        $smart = "вчера";
                        break;
                }
            }
            elseif (!$was && intval($dateTo->format("G")) + $hours >= 23)
            {
                switch (true)
                {
                    case intval($dateTo->format("G")) + $hours >= 24:
                    case intval($dateTo->format("i")) + $minutes >= 60:
                    case intval($dateTo->format("i")) + $minutes >= 59 && intval($dateTo->format("s")) + $seconds >= 60:
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

                    if (intval($dateTo->format("G")) - $hours <= 0)
                    {
                        switch (true)
                        {
                            case intval($dateTo->format("G")) - $hours < 0:
                            case intval($dateTo->format("i")) - $minutes < 0:
                            case intval($dateTo->format("i")) - $minutes <= 0 && intval($dateTo->format("s")) - $seconds < 0:
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

                    if (intval($dateTo->format("G")) + $hours >= 23)
                    {
                        switch (true){
                            case intval($dateTo->format("G")) + $hours >= 24:
                            case intval($dateTo->format("i")) + $minutes >= 60:
                            case intval($dateTo->format("i")) + $minutes >= 59 && intval($dateTo->format("s")) + $seconds >= 60:
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
             */
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

            else if (in_array($days ,[7 ,14/*Кажется слишком сурово, я бы не понял,21*/]))
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
                    else if ($days > 17)
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
                else if ($months >= 5 && $months <= 7)
                {
                    $text = "полтора{$sp}года";
                }
                else if ($months > 7)
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
            $dateString = $this->format("j{$sp}F в{$sp}G:i (l)");

            if ($this->format("Y") != date("Y"))
                $dateString = $this->format("j{$sp}F Y{$sp}года в{$sp}G:i (l)");
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

        return $this;

    }


    /**
     * Взять интервал в виде строки со значениями
     *
     * @param int $min Минимальное значение, после которого не будут браться другие значения, использовать константы
     *
     * @return string|null
     */
    function getAllCounters ($min = 0)
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
     * @return \Parus\DateTime
     */
    function getDateTo ()
    {
        return $this->dateTo;
    }


    /**
     * через 12 дней
     *
     * @return string
     */
    function getText ()
    {
        return $this->text;
    }


    /**
     * через 12 дней, 11 января в 0:00 (среда)
     *
     * @return string
     */
    function getTextWithDate ()
    {
        return $this->text . "," . Text::SPACE_NOBR . $this->dateString;
    }


    /**
     * через 12 дней, 11 января в полночь (среда)
     *
     * @return string
     */
    function getSmart()
    {
        return $this->smart;
    }


    /**
     * @return int
     */
    public
    function getWas ()
    {
        return $this->was;
    }


    /**
     * @return \Parus\DateInterval
     */
    public
    function getInterval ()
    {
        return $this->interval;
    }


    /**
     * @return int
     */
    public
    function getPunctuality ()
    {
        return $this->punctuality;
    }


    /**
     * @return string
     */
    public
    function getDateString ()
    {
        return $this->dateString;
    }


    /**
     * @return string
     */
    public
    function getMicros ()
    {
        return $this->micros;
    }


    /**
     * @return string
     */
    public
    function getSeconds ()
    {
        return $this->seconds;
    }


    /**
     * @return string
     */
    public
    function getMinutes ()
    {
        return $this->minutes;
    }


    /**
     * @return string
     */
    public
    function getHours ()
    {
        return $this->hours;
    }


    /**
     * @return string
     */
    public
    function getDays ()
    {
        return $this->days;
    }


    /**
     * @return string
     */
    public
    function getMonths ()
    {
        return $this->months;
    }


    /**
     * @return string
     */
    public
    function getYears ()
    {
        return $this->years;
    }


    /**
     * @return string
     */
    public
    function getAges ()
    {
        return $this->ages;
    }


    /**
     * @return string
     */
    public
    function getMils ()
    {
        return $this->mils;
    }


    /**
     * @return int
     */
    public
    function getTotalSeconds ()
    {
        return $this->totalSeconds;
    }


    /**
     * @return int
     */
    public
    function getTotalMinutes ()
    {
        return $this->totalMinutes;
    }


    /**
     * @return int
     */
    public
    function getTotalHours ()
    {
        return $this->totalHours;
    }


    /**
     * @return int
     */
    public
    function getTotalDays ()
    {
        return $this->totalDays;
    }


    /**
     * @return int
     */
    public
    function getTotalMonths ()
    {
        return $this->totalMonths;
    }


    /**
     * @return int
     */
    public
    function getTotalYears ()
    {
        return $this->totalYears;
    }


    /**
     * @return int
     */
    public
    function getTotalAges ()
    {
        return $this->totalAges;
    }


    /**
     * @return int
     */
    public
    function getTotalMils ()
    {
        return $this->totalMils;
    }


    /**
     * @return string
     */
    public
    function getStrTotalSeconds ()
    {
        return $this->strTotalSeconds;
    }


    /**
     * @return string
     */
    public
    function getStrTotalMinutes ()
    {
        return $this->strTotalMinutes;
    }


    /**
     * @return string
     */
    public
    function getStrTotalHours ()
    {
        return $this->strTotalHours;
    }


    /**
     * @return string
     */
    public
    function getStrTotalDays ()
    {
        return $this->strTotalDays;
    }


    /**
     * @return string
     */
    public
    function getStrTotalMonths ()
    {
        return $this->strTotalMonths;
    }


    /**
     * @return string
     */
    public
    function getStrTotalYears ()
    {
        return $this->strTotalYears;
    }


    /**
     * @return string
     */
    public
    function getStrTotalAges ()
    {
        return $this->strTotalAges;
    }


    /**
     * @return string
     */
    public
    function getStrTotalMils ()
    {
        return $this->strTotalMils;
    }


    /**
     * @return string
     */
    public
    function getPast ()
    {
        return $this->past;
    }


    /**
     * @return string
     */
    public
    function getWill ()
    {
        return $this->will;
    }


    /**
     * @return \string[]
     */
    public
    function getFields ()
    {
        return $this->fields;
    }

}
