<?php

declare(strict_types=1);

namespace Sept\Common;

/**
 * Общие статичные вспомогательные функции для работы с классами
 *
 * @author Тимофей Соловейчик <timofey@1sept.ru>
 */
class Data
{
    /**
     * Наименьшее значение дробного значения,
     * которое не будет конвертировано функцией floatval
     * в экспоненциальное представление значения ("9,9999999999999E-5")
     *
     * Данное значение через floatval будут конвертировано в 0.0001
     *
     * if ($val < Data::FLOATVAL_LOWEST)
     *     $val = floatval(Data::FLOATVAL_LOWEST);
     *
     * @var float
     */
    const FLOATVAL_LOWEST = 0.0000999999999999995;

    /**
     * Массив: индексный/ассоциативный
     *
     * @var string
     */
    const TYPE_ARRAY = "array";

    /**
     * Верно/неверно: true/false
     *
     * @var string
     */
    const TYPE_BOOLEAN = "boolean";

    /**
     * Дробное число: 0.475
     *
     * @var string
     */
    const TYPE_DOUBLE = "double";

    /**
     * Целое число: 45 / -35
     *
     * @var string
     */
    const TYPE_INTEGER = "integer";

    /**
     * Пустое значение: null
     *
     * @var string
     */
    const TYPE_NULL = "NULL";

    /**
     * Объект класса
     *
     * @var string
     */
    const TYPE_OBJECT = "object";

    /**
     * Ресурс: fopen / socket
     *
     * @var string
     */
    const TYPE_RESOURCE = "resource";

    /**
     * Строка
     *
     * @var string
     */
    const TYPE_STRING = "string";

    /**
     * Вызываемая функция
     *
     * @var string
     */
    const TYPE_CALLABLE = "callable";

    /**
     * Строка
     *
     * @var string[]
     */
    const TYPES = [
          self::TYPE_ARRAY    => "массив"
        , self::TYPE_BOOLEAN  => "утверждение «верно – true» / «неверно - false»"
        , self::TYPE_DOUBLE   => "дробное значение"
        , self::TYPE_INTEGER  => "целое"
        , self::TYPE_NULL     => "пустое значение – «NULL»"
        , self::TYPE_OBJECT   => "объект"
        , self::TYPE_RESOURCE => "ссылка на внешний ресурс"
        , self::TYPE_STRING   => "строка"

        , self::TYPE_CALLABLE => "функция"
    ];


    /**
     * Для функция и методов которые возвращают ссылки на переменные, но нужно вернуть false
     *
     * @return false
     */
    public static
    function &getFalse ()
    {
        $false = false;

        return $false;
    }


    /**
     * Получить массив с типами
     *
     * @param null|string $key
     * @param bool        $asString
     *
     * @return string|\string[]
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public static
    function getTypes ($key = null ,$asString = false)
    {
        if ($key)
        {
            $key = Text::prepareSingleLine($key ,true ,"Неверное значению ключа типа переменой, возможные варианты:" . Arrays::multiImplode(", " ,self::TYPES) . "!" ,4 ,8);

            if (!in_array($key ,array_keys(self::TYPES)))
                throw new \OutOfBoundsException("Нет ключа «{$key}», есть такие: " . Arrays::multiImplode(", " ,self::TYPES) . "!");

            if ($asString)
                return $key . ": " . self::TYPES[$key];

            return self::TYPES[$key];
        }

        if ($asString)
            return Arrays::multiImplode(", " ,self::TYPES);

        return self::TYPES;
    }


    /**
     * @deprecated Использовать Text::prepareSingleLine($string)
     * @see        Text::prepareSingleLine()
     *
     * @param string $string
     *
     * @return string
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    static public function getStringAsOneLine ( $string )
    {
        return Text::prepareSingleLine($string);
    }


    /**
     * @deprecated Использовать Text::prepare($string)
     * @see        Text::prepare()
     *
     * @param string   $string
     * @param bool|int $noTabs
     * @param bool     $removeEmptyLines
     * @param bool     $trim
     *
     * @return string
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    static public function prepareText ($string, $noTabs = true, $removeEmptyLines = false, $trim = true)
    {
        return Text::prepare($string, $noTabs, $removeEmptyLines, $trim);
    }


    /**
     * @deprecated Использовать Data::getInteger($string)
     * @see        Data::getInteger()
     *
     * @param string|int|float          $input          Переменная для проверки
     * @param boolean|string|\Exception $throwException [опция] Выбрасывать исключение с текстом, в случае неверного значения если указан текст, то он будет добавлен в тексте исключения
     * @param null|int                  $max            [опция] Максимальное значение
     *
     * @return false|int
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public static function isPositiveInteger ($input ,$throwException = false ,$max = null)
    {
        return self::getInteger($input ,$throwException ,1 ,$max);
    }


    /**
     * @param string|int|float          $input          Переменная для проверки
     * @param boolean|string|\Exception $throwException [опция] Выбрасывать исключение с текстом, в случае неверного значения если указан текст, то он будет добавлен в тексте исключения
     * @param null|int                  $min
     * @param null|int                  $max
     *
     * @return false|int
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public static function getInteger ($input, $throwException = true, $min = null, $max = null)
    {
        if (!Text::isInteger($input, $throwException, $min, $max))
            return false;

        return (int) $input;
    }


    /**
     * Функция возвращающая подходящую форму слова для переданного количества:
     *    <ol>
     *        <li>1 лошадь;</li>
     *        <li>2-4 лошади;</li>
     *        <li>5-0,11-14 лошадей.</li>
     *    </ol>
     *    <ul>
     *        <li>201 лошадь;</li>
     *        <li>54 лошади;</li>
     *        <li>7 лошадей;</li>
     *        <li>2 011 лошадей.</li>
     *    </ul>
     *
     * @param integer       $number          количество для которого будет подбираться правильная форма слова
     * @param string[]|null $words           массив с 3-мя вариантами слова словами
     * @param boolean       $printWithNumber если true, то в возвращаемая строка будет с переданным количеством и чрез неразрывный пробел форма слова
     * @param null|string   $mainPart        неизменяемая часть в форме слова
     *
     * @return int|string возвращает одно из значений переданного массива, если массив не передан, то вернёт нужный index 0,1,2
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    static public function countWordForm ( $number , $words = null , $printWithNumber = false , $mainPart = null )
    {
        if ( $words == null )
            $words = [ 0 , 1 , 2 ];

        $number = preg_replace('/\s+/u',"", (string) $number);

        /**
         * Если не походить по маске под цифровое значение
         */
        if ( !is_numeric( $number ) )
        {
            if ( is_array( $number ) || @count( $number ) )
                $number = count( $number );

            else if ( is_string( $number ) )
                $number = strlen( $number );

            else throw new \InvalidArgumentException("Для получения слова в зависимости от количества, нужно предать положительное целое, передан" . self::getTypeRu( $number ) . "!");
        }

        $last = substr( $number , -1 );

        $beforeLast = 0;

        /**
         * Если более 1 цифры,
         * то взять предпоследнюю
         */
        if ( strlen($number) > 1 )
            $beforeLast = substr( $number , -2 , 1 );

        $text = "";
        /**
         * Если предан массив со значениями
         */
        if ( $words )
        {
            /**
             * Количество и неразрывный пробел
             */
            if ( $printWithNumber )
                $text = number_format( $number , 0 , "" , Text::SPACE_FOUR_PER_EM ) . " ";

            if ( $mainPart )
                $text = $text . $mainPart;
        }


        /**
         * 1, но не 11
         */
        if ( $beforeLast != 1 && $last == 1 )
            $word = $words[0];

        /**
         * 2,3,4, но не 1х
         */
        else if ( $beforeLast != 1 && in_array( $last , [ 2 , 3 , 4 ] ) )
            $word = $words[1];

        /**
         * Если не цело число
         */
        else if ( strpbrk($number, '.,') )
            $word = $words[1];

        /**
         * Всё остальное: 7, 11, 29, 0
         */
        else $word = $words[2];

        return $text ? ($text . $word) : $word;
    }


    /**
     * Принадлежит ли объект классу
     *
     * @param object         $object            Проверяемый объект
     * @param object|string  $objectOrClassName Объект для сравнения или имя класса
     * @param boolean|string $throwException
     *
     * @return false|object Возвращает переданный объект если он является сущностью указанного класса
     *
     * @throws \Exception
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    static public function instanceOfClass ($object ,$objectOrClassName ,$throwException = true)
    {
        if (!is_object($objectOrClassName) && !is_string($objectOrClassName))
            throw new \InvalidArgumentException("Второй аргумент должен быть объектом или именем класса, передан");

        $className = $objectOrClassName;
        if (is_object($className))
            $className = get_class($className);
        elseif (!class_exists($className) && !interface_exists($className))
            throw new \RuntimeException("Не найден указанный для сравнения класс «{$className}»!");

        if (!is_object($object) || !is_a($object, $className))
        {
            if ($throwException)
                throw new \RuntimeException("Проверяемый объект/значение не принадлежит классу «{$className}», передан");

            return false;
        }

        return $object;
    }


    /**
     * Аргумент является одним из проверяемых типов
     *
     * @param mixed           $variable    Проверяемый объект
     * @param string|string[] $typeOrTypes Тип или типы
     * @param boolean|string  $throwException
     *
     * @return false|mixed Возвращает переданную переменную если она принадлежит одному из указанных типов
     *
     * @throws \Exception
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    static public function typeOf ($variable ,$typeOrTypes ,$throwException = false)
    {
        $wrongArgumentText = "Второй аргумент должен быть строкой с названием или индексным массивом с названиями типов данных, передан" . static::getTypeRu($typeOrTypes) . "! Возможные варианты: " . static::getTypes(null ,true) . "!";

        $notOfTypeText = "Переданное значение не относится к %s и является: " . static::getTypeRu($variable ,false);

        if ($throwException && is_string($throwException))
            $notOfTypeText = $throwException . " " . $notOfTypeText;

        if (!is_array($typeOrTypes))
        {
            if (gettype($variable) != $typeOrTypes && !($typeOrTypes == static::TYPE_CALLABLE && is_callable($variable)))
            {
                if (!$throwException)
                    return false;

                $type = static::getTypes($typeOrTypes);

                throw new \RuntimeException(sprintf($notOfTypeText ,"типу «{$typeOrTypes}: {$type}»"));
            }
        }

        if (is_array($typeOrTypes))
        {
            $diff = array_diff($typeOrTypes ,array_keys(static::TYPES));

            if ($diff)
                throw new \InvalidArgumentException("Найдены значения не соответствующие возможным названиям типов данных: " . static::getTypeRu($diff) . "! " . $wrongArgumentText);

            if (!in_array(gettype($variable) ,$typeOrTypes) && !(in_array(static::TYPE_CALLABLE ,$typeOrTypes) && is_callable($variable)))
            {
                if ($throwException)
                    throw new \RuntimeException(sprintf($notOfTypeText ,"указанным типам: " . implode(", " ,$typeOrTypes)));

                return false;
            }

        }

        return $variable;
    }


    /**
     * @param int|string $value
     * @param int        $multi
     * @param int        $div
     * @param int        $length
     *
     * @return int
     *
     * @throws \Exception
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public
    static
    function getIntegerOrStringCheckSum ($value ,$multi = 99 ,$div = 255 ,$length = 2)
    {
        $value = static::typeOf($value ,[static::TYPE_INTEGER ,static::TYPE_STRING] ,"Неверное значение аргумента для получения проверочной суммы");

        $multi = static::getInteger($multi ,"Неверное значение аргумента умножения для получения проверочной суммы!" ,1);

        $div = static::getInteger($div ,"Неверное значение аргумента деления для получения проверочной суммы!" ,1);

        $length = static::getInteger($length ,"Неверное значение аргумента запрашиваемой длины проверочной суммы!" ,1);

        return str_pad((string) round(hexdec(substr(md5($value), 0, $length)) * $multi / $div), $length, (string) 0, STR_PAD_LEFT);
    }


    /**
     * @param int|string $value
     * @param int        $multi
     * @param int        $div
     * @param int        $length
     * @param null|int   $min
     * @param null|int   $max
     *
     * @return int
     *
     * @throws \Exception
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public
    static
    function getCheckSum ($value ,$multi = 9 ,$div = 255 ,$length = 2 ,$min = null ,$max = null)
    {
        $value = static::typeOf($value ,[static::TYPE_INTEGER ,static::TYPE_STRING] ,"Неверное значение аргумента для получения проверочной суммы");

        $multi = static::getInteger($multi ,"Неверное значение аргумента умножения для получения проверочной суммы!" ,1);

        $div = static::getInteger($div ,"Неверное значение аргумента деления для получения проверочной суммы!" ,1);

        $length = static::getInteger($length ,"Неверное значение аргумента запрашиваемой длины проверочной суммы!" ,1);

        if ($min !== null)
            $min = static::getInteger($min ,"Неверное значение аргумента минимального значения проверочной суммы!");

        if ($max !== null)
            $max = static::getInteger($max ,"Неверное значение аргумента максимального значения проверочной суммы!");

        $checkSum = md5($value);
        $checkSum = hexdec($checkSum) / pow($div ,2);
        if ($checkSum < 500)
            $checkSum = $checkSum * $multi;
        $power = log($checkSum);
        $checkSum = $checkSum / pow($div ,($power / 5));
        $checkSum = str_pad((string) $checkSum, $length, (string) 0, STR_PAD_LEFT);

        if (strlen($checkSum) > $length)
            $checkSum = (int) substr($checkSum ,-$length ,$length);

        $checkSum = round($checkSum);

        if ($min && $checkSum < $min)
            $checkSum = $min;

        if ($max && $checkSum > $max)
        {
            if ($max && $min > $max)
                throw new \InvalidArgumentException("Неверно казан допустимый интервал, минимально допустимое значение «{$min}», максимально допустимое «{$max}»!");

            $checkSum = $max;
        }

        return $checkSum;
    }


    /**
     * Получить строку на русском с описанием типа переменной
     *
     * <ul>
     *     <li> "boolean" – логическое верно/неверно </li>
     *     <li> "integer" - целое число </li>
     *     <li> "double" (по историческим причинам в случае типа float возвращается "double", а не просто "float") </li>
     *     <li> "string" - строка </li>
     *     <li> "array" - массив </li>
     *     <li> "object" - объект </li>
     *     <li> "resource" - ресурс</li>
     *     <li> "NULL" - пустое значение </li>
     *     <li> "unknown type" - неизвестный тип данных </li>
     * </ul>
     *
     * <ul>
     *     <li>
     *         передан<b>а</b> строка с 75 символами
     *     </li>
     *     <li>
     *         указан<b>о</b> логическое утверждение «true – верно»
     *     </li>
     *     <li>
     *         возвращён объект класса \Passport\Person\Person
     *     </li>
     * </ul>
     *
     * @param mixed $var          Переменная для проверки
     * @param bool  $withEnd      [опция] Указывать окончание к слову передан<b>а</b>,указан<b>о</b>,получен<b>ой</b>
     * @param int   $symbolsPrint Кол-во символов в строках которые будут выведены
     * @param int   $arrayLength  Кол-во элементов массива для выведения
     * @param int   $arrayDepth   Глубина просмотра массива
     *
     * @return string возвращает строку с текстом об типе переданной переменной
     *
     * @throws \Exception
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    static public function getTypeRu ($var ,$withEnd = true ,$symbolsPrint = 15 ,$arrayLength = 5 ,$arrayDepth = 3)
    {
        if ($symbolsPrint)
            $symbolsPrint = static::getInteger($symbolsPrint ,"Неверное значение аргумента длины выводимой строки!", 0);

        if ($arrayLength)
            $arrayLength = static::getInteger($arrayLength ,"Неверное значение аргумента количества элементов массива для выведения!", 0);

        if ($arrayDepth)
            $arrayDepth = static::getInteger($arrayDepth ,"Неверное значение аргумента глубины просмотра массива для выведения!", 0);

        $typeName = gettype($var);

        switch ( $typeName )
        {
            case static::TYPE_ARRAY:
                $end = "";
                $count = count($var ,COUNT_RECURSIVE);
                if ( $count == 0 )
                    $text = "пустой массив";
                else
                {
                    $text = "массив с " . self::countWordForm($count , ["ом","ами","ами"] , true , "элемент");

                    if ($arrayDepth && $arrayLength - 1)
                    {
                        if (count($var) > $arrayLength - 1)
                        {
                            $var = array_splice($var ,0 ,$arrayLength - 1);

                            $more = static::countWordForm(count($var) - ($arrayLength - 1) ,["элемент" ,"элемента" ,"элементов"] ,true);

                            $var[] = "[и ещё {$more}]";
                        }

                        $text = $text . ": [";
                        $values = [];
                        foreach ($var as $index => $value)
                        {
                            $values[] = $index . ": " . static::getTypeRu($value ,false ,$symbolsPrint ,$arrayLength ,$arrayDepth - 1);
                        }
                        $text = $text . implode("; " ,$values) . "]";
                    }
                }
                break;

            case static::TYPE_BOOLEAN:
                $end = "о";
                $text = "логическое утверждение ";
                $text = $text . ($var ? "«true – верно»" : "«false – неверно»");
                break;

            case static::TYPE_DOUBLE:
                $end = "о";
                $parts = preg_split("/\.|,/", (string) abs($var));
                $before = $parts[0];
                $after = 0;
                if ( isset($parts[1]) )
                    $after = $parts[1];

                $text = "дробное "
                        . ($var < 0 ? "отрицательное" : "положительное")
                        . " число с "
                        . self::countWordForm(strlen($before),["ой","ами","ами"],true,"цифр")
                        . " до точки и "
                        . self::countWordForm(strlen($after),["ой","ами","ами"],true,"цифр")
                        . " после точки";

                if ($symbolsPrint && $symbolsPrint - 1)
                {
                    if (strlen((string) abs($var)) > $symbolsPrint )
                        $var = mb_strcut($var ,0 ,($symbolsPrint - 1)) . Text::SYMBOL_ELLIPSIS;

                    $text = $text . ": «{$var}»";
                }
                break;

            case static::TYPE_INTEGER:
                if ( $var == 0 )
                {
                    $end = "а";
                    $text = "цифра 0";
                }
                else
                {
                    $end = "о";
                    $text = "целое "
                            . ($var < 0 ? "отрицательное" : "положительное")
                            . " число с "
                            . self::countWordForm(strlen((string) abs($var)),["ой","ами","ами"],true,"цифр");

                    if ($symbolsPrint && $symbolsPrint - 1)
                    {
                        if (strlen((string) abs($var)) > $symbolsPrint )
                            $var = mb_strcut($var,0,($symbolsPrint - 1)) . Text::SYMBOL_ELLIPSIS;

                        $text = $text . ": «{$var}»";
                    }
                }

                break;

            case static::TYPE_NULL:
            case static::TYPE_RESOURCE:
                $end = "а";
                $text = static::getTypes($typeName);
                break;

            case static::TYPE_OBJECT:
                $end = "";
                $className = get_class($var);
                $text = "объект класса «{$className}»";
                if ($var instanceof \Closure)
                    $text = $text . " [функция]";
                while ($className = get_parent_class($className))
                {$text = $text . " >> наследник «{$className}»";}
                break;

            case static::TYPE_STRING:
                $end = "а";
                $count = mb_strlen( $var );
                if ( $count == 0 )
                    $text = "пустая строка";
                else
                {
                    $text = "строка с " . self::countWordForm($count , ["ом","ами","ами"] , true , "символ" );
                    if ($symbolsPrint && $symbolsPrint - 1)
                    {
                        if ($count > $symbolsPrint )
                            $var = mb_strcut($var,0,($symbolsPrint - 1)) . Text::SYMBOL_ELLIPSIS;

                        $text = $text . ": «{$var}»";
                    }
                }
                break;

            case "unknown type":
                $end = "";
                $text = "неизвестный тип данных";
                break;

            default:
                $end = "ой";
                $text = "переменной тип данных не удалось проверить, было передано «{$typeName}»";
        }

        if (!$withEnd)
            $end = "";
        else
            $end = $end . " ";

        $text = $end . $text;

        return $text;
    }

    /**
     * Значения единиц измерения объёма цифровых данных
     *
     * @var array[] Ассоциативный массив где ключи являются языковыми группами, а их значения - массивы со значениями единиц измерения объёма цифровых данных
     */
    static $bitsSizeUnits = [
        'en' => [ 'B' , 'KB' , 'MB' , 'GB' , 'TB' , 'PB' , 'EB' , 'ZB' , 'YB' ] ,
        'ru' => [ 'Б' , 'КБ' , 'МБ' , 'ГБ' , 'ТБ' , 'ПБ' , 'ЭБ' , 'ЗБ' , 'ЙБ' ]
    ];



    /**
     * @deprecated Используйте File::humanSize()
     *
     * @param int    $size
     * @param string $for
     * @param int    $digitsAfter
     * @param null   $displayIn
     *
     * @return string
     */
    static function readableFileSize ( $size , $for = 'ru' , $digitsAfter = 1 , $displayIn = null )
    {
        return File::humanSize($size, 'binary-win', $for, $digitsAfter, $displayIn);
    }


    /**
     * Создать объект времени из преданного источника:
     * - из строки
     * - UNIX time
     * - массива
     *
     * @param integer|integer[]|string|string[] $date  - источник для создания объекта (год может быть отрицательным, но в БД минимальный 0-й год), для массива по умолчанию порядок значений: 5-й параметр - <b>$order = "dmy"</b> - день, месяц, год
     *
     * @param integer[]|string|null             $time  - источник для установки времени
     *
     * @param DateTime                          $min   - DateTime - минимально значение, если дата получиться меньше, то будет установлено это значение
     * @param DateTime                          $max   - DateTime - максимальное значение, если дата получиться больше, то будет установлено это значение
     * @param string                            $order - если переданный источник - индексный массив, то указать, в какой последовательности расположены значения "dmy"|"ymd"|"mdy"
     *
     * @return DateTimeInterface
     *
     * @throws \Exception
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public static function getDateTimeFrom ($date ,$time = [] ,DateTime $min = null ,DateTime $max = null ,$order = "dmy")
    {
        /**
         * Если UNIX timestamp
         */
        if ( is_numeric( $date ) ) {
            $date = ( new DateTime() )->setTimestamp( $date );

        } else if ( is_string( $date ) ) {
            /**
             * Если строка, то проверить на валидность и получить время
             */
            if ( self::dateCheck( $date ) === true )
                $date = new DateTime( $date );

            else {

                $ruMonths = [
                    "янв","фев","мар","апр","ма","июн","июл","авг","сен","окт","ноя","дек"
                ];

                $ruMonthsStr = implode( "|" , $ruMonths );

                $pattern = "/
                            (?'day'\\d{2})
                            \\s+
                            (?'month'{$ruMonthsStr})(?:\\S*)
                            \\s+
                            (?'year'\\d{1,4})
                            (?:
                                \\D*
                                (?'time'(?:\\d{1,2}:?){2,3})
                            )?
                        /uXx";

                preg_match( $pattern , $date , $matches );

                /**
                 * Если есть три найденные значения
                 */
                if ( count( $matches ) > 3 )
                {
                    if ( +$matches["day"] > 31 || $matches["day"] == "00" )
                    {
                        $day = $matches["year"];
                        $year = $matches["day"];
                    }
                    else
                    {
                        $day = $matches["day"];
                        $year = $matches["year"];
                    }


                    $month = 1 + array_search( mb_substr($matches["month"], 0, 3) , $ruMonths );


                    $date = [ $day , $month , $year ];

                    /**
                     * Если есть четвёртый параметр, время
                     */
                    if ( !empty( $matches["time"] ) )
                        $time = $matches["time"];

                    else $time = null;
                }
            }
        }

        /**
         * Если массив
         */
        if (is_array($date) && count($date) > 0) {
            if (count($date) != 3)
                throw new \InvalidArgumentException( 'Для взятия временной метки, нужно передать массив с тремя элементами, передан' . self::getTypeRu( $date ) . "!" );

            if ( !is_string( $order ) || strlen( $order ) != 3 )
                throw new \InvalidArgumentException( 'Ошибка при получении временной метки, порядок частей в массиве указан в неверном формате, возможные варианты "dmy"|"ymd"|"mdy", указан' . self::getTypeRu( $order ) . "!" );

            /**
             * В строку, если массив индексный или используются не такие ключи - dmy, то в последовательности $order будут взяты параметры массива
             */
            $d = array_flip( str_split ( strtolower( $order ) , 1 ) );
            $index = -1;
            array_walk( $d , function ( &$val , $key , $date ) use ( &$index )
            {
                /**
                 * Если нет такого ключа, то попробовать взять по индексу
                 */
                if ( !isset( $date[$key] ) || !is_numeric( $date[$key] ) )
                {
                    $index++;

                    /**
                     * Если нет такого индекса, то установить данное значение как 0
                     */
                    if ( !isset( $date[$index] ) || !is_numeric( $date[$index] ) || +$date[$index] == 0 )
                        $val = 0;

                    else $val = +$date[$index];
                }
                else $val = +$date[$key];

                /**
                 * Если день или месяц равны 0-ю,
                 * то установить как 1-ца
                 */
                if ( in_array( $key , ["d","m"]) && +$val == 0 )
                    $val = 1;

            } , $date );

            $date = new DateTime();

            $date->setDate ( $d["y"] , $d["m"] , $d["d"] );

        }
        elseif ( !$date instanceof DateTime )
            $date = new DateTime();



        /**
         * Если время null, то установить всё по нулям
         */
        if ( $time === false || $time === null )
            $date->setTime ( 0 , 0 , 0 );

        /**
         * Если указано время
         */
        else if ( !empty( $time ) )
        {
            /**
             * Если строка, то проверить на валидность и разбить на элементы массива
             */
            if ( is_string( $time ) && preg_match( "/^(-?(?>\\d+):?){1,3}$/" , $time ) )
                $time = explode(":",$time);


            if ( is_array( $time ) && count( $time ) )
                $date->setTime ( current($time) , next($time) , next($time) );

        }


        /**
         * Если указаны min и/или max, то сравнить получившееся значение с ними, если не проходит, то заменить на min/max
         */
        if ( $min && $min instanceof DateTime && $date < $min )
            $date = $min;

        else if ( $max && $max instanceof DateTime && $date < $max )
            $date = $max;

        return $date;
    }

    /**
     * Если пусто, NULL или более четырёх нулей:
     *
     * @param string $date
     *
     * @return bool|null|\string[]|true
     */
    public static function dateCheck ( $date )
    {

        // Если NULL то завершить вернув NULL:
        if ( static::dateIsNull( $date ) )
            return null;

        // Продолжаем проверку:
        else
        {
            // Убрать лишние пробелы:
            $date = trim( $date );

            // Проверка по RegExp:
            $regResult = static::dateTimeRegCheck( $date );

            // Ответ RegExp - массив совпадений, проверить их:
            if ( is_array( $regResult ) )
            {
                $checkResult = static::dateCheckRegResults( $regResult );

                // Есть Строка а не TRUE - Exp:
                if ( is_string( $checkResult ) )
                    return $regResult;
            }
            // Ответ RegExp - вернуть текст ошибки:
            else if ( is_string( $regResult ) )
                return $regResult;
        }

        // Всё верно, true - строка может быть преобразована в Date:
        return true;
    }

    /**
     * Проверить, передана ли строка обозначающая FALSE (0000-00-00|0000-00-00 00:00:00) или NULL, FALSE
     *
     * @param  string|boolean|null $date Проверяемое значение/строка даты
     *
     * @return boolean TRUE – переданное значение является пустым, не датой; FALSE – нет, передана не пустое значение
     */
    public static function dateIsNull ( $date )
    {
        // empty:
        if ( empty( $date ) )
            return true;
        // NULL:
        elseif ( preg_match( '/(null|false|^\s*$)/i' , $date ) )
            return true;

        elseif ( in_array($date ,[Date::MYSQL_FALSE,DateTime::MYSQL_FALSE]) )
            return true;

        elseif (strpos($date ,DateTime::MYSQL_FALSE . ".0") === 0 && preg_match("/\.0+\s*$/" ,$date))
            return true;

        // Дата НЕ null:
        return false;
    }

    /**
     * @param string $date
     *
     * @return true|string[]|string - если формат, то TRUE, если неверный формат, то массив с возможными шаблонами формата данных
     *
     * @see http://php.net/manual/ru/datetime.formats.relative.php
     */
    public static function dateTimeRegCheck ( $date )
    {
        // Не подходящие форматы даты:
        $matchMiss = [ ];

        // 2013.2.3/2013-01-13/2013/01/13:
        if ( !preg_match( '/\d{2,4}(?<sep1>[-.\/])(?<month>\d{1,2})(?<sep2>[-.\/])(?<day>\d{1,2})/' , $date , $match ) )
            $matchMiss[] = "YYYY-mm-dd || YYYY.m.d || YYYY/mm/d";
        else return $match;

        // 1.14.2005/11-17-2005:
        if ( !preg_match( '/(?<day>\d{1,2})(?<sep1>[-.])(?<month>\d{1,2})(?<sep2>[-.])\d{4}/' , $date , $match ) )
            $matchMiss[] = "dd-mm-YYYY || d.m.YYYY";
        else return $match;

        // Дни недели:
        $weekday = [
            "mon(?:" ,
            "tue(?:s" ,
            "wed(?:nes" ,
            "thu(?:rs" ,
            "fri(?:" ,
            "sun(?:" ,
            "sat(?:ur" ,
            "week(?:"
        ];
        $weekday = implode( "day)?|" , $weekday ) . "day)?";

        // Месяцы:
        $months = [
            "jan(?|uary)?" ,
            "feb(?|ruary)?" ,
            "mar(?|ch)?" ,
            "apr(?|il)?" ,
            "may" ,
            "jun(?|e)?" ,
            "jul(?|e)?" ,
            "aug(?|ust)?" ,
            "sep(?|tember)?" ,
            "oct(?|ober)?" ,
            "nov(?|ember)?" ,
            "dec(?|ember)?"
        ];
        $months = implode( "|" , $months );

        // 3 jan 45:
        if ( !preg_match( "/(({$weekday}\\s+)?(?<day>\\d{,2})\\s+)?({$months})(\\s+\\d{,4})?/i" , $date , $match ) )
            $matchMiss[] = "mon, 22 jan 13";
        else return $match;

        // +4 days/next week/ 5 years ago:
        if (!preg_match("/^
                    \\s* (?# отступы вначале)
                    (?| (?# day of)
                        (?<relatively>first | second | third | fourth | fifth | sixth | seventh | eighth | ninth | tenth | eleventh | twelfth | next | last | previous | this)
                        \\s+
                        (?|day|{$weekday})\\s+of
                        \\s+
                    )?
                    (?|
                        (?|
                            [+-]?\\d+
                            |
                            \k<relatively>
                        )
                        \\s+
                    )?
                    (?# указание единиц измерения)
                    (?|(?|{$weekday}|sec(?|ond)?|min(?|ute)?|hour|day|week|forth?night|month|year)s?)?

                    (?|\\s+ago)? (?# назад)

                    (?# Время)
                    (?|\\s+(?|[01]?[0-9]|2[0-4])(?|:(?|[0-5]?[0-9])){1,2})?
                    \\s* (?# отступы в конце)
                $/ixX" ,$date))
            $matchMiss[] = "last day|mon|wednesday of next year | next month | ";
        else return true;

        $reg = "now|tomorrow|yesterday|midnight|noon|(?|back|front)\\s+of\\s+(?|[01]?[0-9]|2[0-4])";
        // now|tomorrow|yesterday:
        if ( !preg_match( "/(?|{$reg})/i" , $date ) )
            $matchMiss[] = $reg;
        else return true;

        // Проверить другие форматы:
        // if ( !strtotime($date) )
        //    $matchMiss[] = "";
        // else return true;

        // 4 несовпадения - ошибка формате написания даты:
        // Ни один формат не подошёл - вернуть текст:
        return "Формат даты указан неверно, возможные варианты:\n" . implode( "\n" , $matchMiss ) . "\n";
    }

    /**
     * Проверка полученных данных поиска по паттернам, можно ли строку преобразовать в дату
     *
     * @param  string[]    $regResults Массив с найденными данными
     *
     * @return true|string Если без ошибок, то true, если есть ошибки, то текст ошибки
     */
    private static function dateCheckRegResults ( $regResults )
    {
        // Если есть разделители, Сравнить разделители:
        if ( isset( $regResults["sep1"] ) && $regResults["sep1"] != $regResults["sep2"] )
            return "В строке даты используются разные разделители:" . "\n {$regResults["sep1"]} != {$regResults["sep2"]}";

        // Число не может быть более 31:
        if ( $regResults["day"] > 31 )
            return "В месяце не более 31 дня";

        // Месяц не может быть более 12:
        else if ( $regResults["month"] > 12 )
            return "В году 12 месяцев";

        // Всё верно:
        return true;
    }


    /**
     * @return string Php PCRE regexp для выделения из установок колонки её допустимых значений
     */
    public static function getRegExpToGetDBTableTypes()
    {
        // @formatter:off
        /**
         * типы с указанием кол-ва символов
         */
        $regExpLength = [];
        $regExpLength[] = '(?:';
            $regExpLength[] = '(?<type>(?:tiny|big)?int|(?:var)?char|year)';
            $regExpLength[] = '\(';
                $regExpLength[] = '(?<length>\d+)';
            $regExpLength[] = '\(';
        $regExpLength[] = ')';
        $regExpLength = implode("",$regExpLength);

        /**
         * типы со списками
         */
        $regExpValues = [];
        $regExpValues[] = '(?:';
            $regExpValues[] = '(?<type>set|enum)';
            $regExpValues[] = '\(';
                $regExpValues[] = '(?<values>(?<quot>\'|").+)';
            $regExpValues[] = '\)';
        $regExpValues[] = ')';
        $regExpValues = implode("",$regExpValues);
        // @formatter:on

        /**
         * типы без дополнительных параметров
         */
        $regExpNoParams = '(?<type>date|datetime|text)';

        $regExp = implode("|",[$regExpLength,$regExpValues,$regExpNoParams]);

        /**
         * @see http://php.net/manual/ru/reference.pcre.pattern.modifiers.php
         * (?J) разрешить повторения ключей в выражении (как модификатор в конце, пока не поддерживается)
         * u - unicode
         * U - unGreedy - брать минимально подходящие совпадения
         * X - любой обратный слэш в шаблоне, за которым следует символ, не имеющий специального значения, приводят к ошибке
         * x - неЭкранированные пробелы, символы табуляции и пустой строки будут проигнорированы в шаблоне, если они не являются частью символьного класса
         */
        return "/(?J){$regExp}/uUXx";
    }

    /**
     * Обработать данные о таблице из SQL базы
     *
     * field,type,collation,null,key,default,extra,privileges,comment
     * p_id,"int(10) unsigned",NULL,NO,PRI,NULL,auto_increment,select,ID
     *
     * @param array[] $data массив с данными о всех колонках таблицы
     *
     * @return array[] ассоциативный массив, где ключи - названия колонок, а их значения - описания колонок
     */
    static function parseSqlTableInfoAboutFields ( $data )
    {
        $array = array_flip( array_column( $data , "field" ) );

        array_walk( $data ,function ( &$val ) use ( &$array ) {
            /**
             * Все ключи в нижний регистр
             */
            $val = array_change_key_case( $val );

            /**
             */
            $field = $val["field"];

            /**
             * Выбрать все указанные значения полей
             */
            preg_match( static::getRegExpToGetDBTableTypes() , $val["type"] , $types );

            if ( count( $types ) ) {

                $empty = "[не указано]";

                /**
                 * Убрать type, потов в конце добавить!
                 */
                unset( $val["type"] );

                $val["type"] = $types["type"];

                if ( in_array( $val["type"], ["set","enum"] ) ) {

                    $val["values"] = explode( "," , $types["values"] );

                    /**
                     * Если в списке есть кавычки, то убрать их
                     */
                    if ( !empty( $types["quot"] ) )
                        $val["values"] = str_replace( $types["quot"] , "" , $val["values"] );

                    //$val["values"] = array_map( create_function ( '$values' , 'return trim( $values );' ) , $val["values"] );
                    $val["values"] = array_map( function($values) { return trim( $values ); }, $val["values"] );

                } else if ( in_array( $val["type"], ["date","datetime","text"] ) ) {
                    if ( in_array( $val["type"], ["date","datetime"] ) )
                        $empty = "[дата не указана]";

                    else $empty = "[пустая строка]";

                } else if ( isset( $types["length"] ) ) {
                    $empty = "[пустая строка]";

                    $val["length"] = $types["length"];
                }

                $val["empty"] = $empty;
            }

            $array[$field] = $val;

        } );

        /**
         * Вернуть ассоциативный массив [ fieldName => sets [, ... ] ]
         */
        return $array;
    }

    /**
     * Преобразовать значение из цифр в формат для СНИЛС, с дефисами для групп по 3 цифры, и отделением пробелом контрольной суммы из двух цифр
     *
     * @param string $value
     * @param ?bool  $validate_snils
     * @param string &$error_message
     * @param string &$error_code
     *
     * @return string|null
     */
    static function convertValueToSnilsFormat (string $value, ?bool $validate_snils = false, &$error_message = "", &$error_code = "") : ?string
    {
        if (!$value)
            return null;

        $digits = $value;

        $digits = str_replace(Text::SPACES, "", $digits);
        $digits = str_replace(Text::DASHES_AND_HYPHENS, "", $digits);

        $result = DataValidation::validateSnils($digits, $error_message, $error_code);
        if (!$result)
        {
            if ($validate_snils)
                throw new \RuntimeException("Не верное значение СНИЛС «{$value}»: {$error_message}!");

            return null;
        }

        preg_match_all("/\d{2,3}/ux", $digits, $matches);
        $parts = $matches[0];

        return $parts[0] . "-" . $parts[1] . "-" .$parts [2] . " " . $parts[3];
    }

    /**
     * Преобразовать значение из цифр в формат серии и номера Паспорта РФ: XX XX XXXXXX
     *
     * @param string $value
     * @param ?bool  $validate_snils
     * @param string &$error_message
     * @param string &$error_code
     *
     * @return string|null
     */
    static function convertValueToPassportFormat (string $value, ?bool $validate_snils = false, &$error_message = "", &$error_code = "") : ?string
    {
        if (!$value)
            return null;

        $digits = preg_replace("/[^\d]/", "", $value);

        preg_match("/^(\d{2})(\d{2})(\d{6})$/ux", $digits, $matches);
        if (!$matches)
            return $value;

        return $matches[1] . " " . $matches[2] . " " .$matches [3];
    }
}
