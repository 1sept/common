<?php

declare(strict_types=1);

namespace Sept\Common;

/**
 * Интервал времени (расширение функционала \DateInterval)
 *
 * @author Александр Васильев <a.vasilyev@1sept.ru>
 * @uses \DateInterval
 */
class DateInterval extends \DateInterval
{
    /**
     * Разница микросекунд
     * @var float
     */
    public $micro;

    /**
     * Невозможно установить $this->days, всегда ставится false, потому продублировано
     *
     * Если объект DateInterval создан методом DateTime::diff(), то это суммарное число дней между начальной и конечной датами. В противном случае days примет значение FALSE.
     * @see http://php.net/manual/ru/class.dateinterval.php#dateinterval.props.days
     *
     * @var false|int
     */
    protected $totalDays = 0;

    /**
     * @var int
     */
    protected $totalSeconds = 0;


    /**
     * @param string $interval_spec
     */
    public function __construct ($interval_spec)
    {
        if (preg_match("/(?<micro>\d+)MICRO/" ,$interval_spec ,$matches))
        {
            $interval_spec = preg_replace("/(?<micro>\d+)MICRO/" ,"" ,$interval_spec);
            $this->micro = DateTime::convertMicrosecondsStringToFloat($matches["micro"]);
        }

        parent::__construct($interval_spec);

        $reference = new \DateTimeImmutable;
        $endTime = $reference->add($this);

        $this->countTotalSeconds();
        $this->countTotalDays();

        return $this;
    }

    /**
     * Количество секунд в интервале
     * @return int секунды
     */
    public function format ($format)
    {
        if (preg_match("/%a/u" ,$format))
            $format = preg_replace('/%a/u' ,$this->getTotalDays() ,$format);

        return parent::format($format);
    }

    /**
     * Количество секунд в интервале
     * @return int секунды
     */
    public function toSeconds ()
    {
        return $this->countTotalSeconds();
    }


    /**
     * Конвертирует в объект данного класса
     *
     * @param \DateInterval $fromInterval Объект родительского класса
     * @param float         $micro        Разница микросекунд
     *
     * @return \Parus\DateInterval
     */
    public static function convertFrom(\DateInterval $fromInterval ,$micro = 0.0)
    {
        if ($fromInterval instanceOf static) {
            return clone $fromInterval;
        }

        $interval = new static('P0D');
        foreach (get_object_vars($fromInterval) as $field => $val)
            $interval->$field = $fromInterval->$field;

        $interval->micro = $micro;
        $interval->countTotalDays();

        return $interval;
    }


    /**
     * @return float
     */
    public
    function getMicro ()
    {
        return $this->micro;
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
    function countTotalSeconds ()
    {
        $reference = new \DateTimeImmutable;
        $endTime = $reference->add($this);

        return $this->totalSeconds = abs($endTime->getTimestamp() - $reference->getTimestamp());
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
    function countTotalDays ()
    {
        if (!$this->getTotalSeconds())
            $this->countTotalSeconds();

        return $this->totalDays = floor($this->getTotalSeconds() / (24 * 60 * 60));
    }


    /**
     * @return int
     */
    public
    function getYears ()
    {
        return $this->y;
    }


    /**
     * @return int
     */
    public
    function getMonths ()
    {
        return $this->m;
    }


    /**
     * @return int
     */
    public
    function getDays ()
    {
        return $this->d;
    }


    /**
     * @return int
     */
    public
    function getHours ()
    {
        return $this->h;
    }


    /**
     * @return int
     */
    public
    function getMinutes ()
    {
        return $this->i;
    }


    /**
     * @return int
     */
    public
    function getSeconds ()
    {
        return $this->s;
    }


    /**
     * @return int
     */
    public
    function getInvert ()
    {
        return $this->invert;
    }

}
