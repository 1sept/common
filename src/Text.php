<?php

declare(strict_types=1);

namespace Sept\Common;

class Text
{
    /**
     * Пробелы и отступы
     *
     * @see https://ru.wikipedia.org/wiki/Пробел
     *
     * @var string[]
     */
    const SPACES = [
        " ",
        self::SPACE_NOBR,
        self::SPACE_EN,
        self::SPACE_EM,
        self::SPACE_THREE_PER_EM,
        self::SPACE_FOUR_PER_EM,
        self::SPACE_SIX_PER_EM,
        self::SPACE_FIGURE,
        self::SPACE_PUNCTUATION,
        self::SPACE_THIN,
        self::SPACE_HAIR,
        self::SPACE_WORD_SPLIT,
        self::SPACE_NARROW,
        self::SPACE_MATHEMATICAL,
        self::SPACE_WORD_JOIN,
        self::SPACE_IDEOGRAPHIC,
    ];

    /**
     * <b>Неразрывный</b> пробел // &amp;nbsp;
     * @var string
     */
    const SPACE_NOBR = " ";
    /**
     * Пробы для случаев всяких
     *
     * Равен половине кегля шрифта (исторически происходит от ширины заглавной буквы «N») // &ensp;
     * @var string
     */
    const SPACE_EN = " ";
    /**
     * Равен кеглю шрифта (исторически происходит от ширины заглавной буквы «M») // &emsp;
     * @var string
     */
    const SPACE_EM = " ";
    /**
     * Ближе всех к обычному пробелу, втрое меньше, чем EM-SPACE
     * @var string
     */
    const SPACE_THREE_PER_EM = " ";
    /**
     * В четыре раза меньше, чем EM-SPACE, красиво получается при использовании для разделения числа на группы по 1000
     * @var string
     */
    const SPACE_FOUR_PER_EM  = " ";
    /**
     * В шесть раз меньше, чем EM-SPACE
     * @var string
     */
    const SPACE_SIX_PER_EM  = " ";
    /**
     * Имеет такую же ширину, что и цифры в данном шрифте, и предназначен для набора таблиц.
     * <b>Неразрывный</b>
     * @var string
     */
    const SPACE_FIGURE = " ";
    /**
     * Ширина равна ширине точки
     * @var string
     */
    const SPACE_PUNCTUATION = " ";
    /**
     * Обычно имеет ширину в 1⁄5 (реже – в 1⁄6) кегля. По пропорциям соответствует двухпунктовой шпации при наборе кеглем в 10 пунктов // &thinsp;
     * @var string
     */
    const SPACE_THIN = " ";
    /**
     * Самый тонкий пробел, соответствует самой тонкой шпации в кассе наборщика, "с волосок"
     * @var string
     */
    const SPACE_HAIR = " ";
    /**
     * Показывает места, в которых можно разрывать строку, не добавляя знак переноса; ширина его нулевая. Применяется в языках, в которых пробелов нет. При выравнивании текста по ширине может расширяться, как и любой другой пробел // ZERO
     * @var string
     */
    const SPACE_WORD_SPLIT = "​";
    /**
     * Узкий <b>Неразрывный</b> пробел // NARROW – узкий
     * @var string
     */
    const SPACE_NARROW = " ";
    /**
     * Узкий пробел, применяемый в математических формулах
     * @var string
     */
    const SPACE_MATHEMATICAL = " ";
    /**
     * Аналогичен SPACE_WORD_SPLIT (ZERO-WIDTH SPACE), но <b>Неразрывный</b>
     * @var string
     */
    const SPACE_WORD_JOIN = "⁠";
    /**
     * Используется в восточных языках, равняется ширине одного иероглифа
     * @var string
     */
    const SPACE_IDEOGRAPHIC = "　";


    /**
     * Все Варианты дефисов и тире
     * @see https://ru.wikipedia.org/wiki/Тире
     * @var string[]
     */
    const DASHES_AND_HYPHENS = [
        self::DASH_LONG,
        self::DASH_MIDDLE,
        self::DASH_FIGURE,
        self::HYPHEN,
        self::HYPHEN_NON_BREAKING,
        self::HYPHEN_MINUS,
        self::HYPHEN_SOFT,
        self::SYMBOL_MINUS,
    ];
    /**
     * Длинное тире, &mdash; (черта длиной в заглавную букву М)
     * @var string
     */
    const DASH_LONG = "—";
    /**
     * Среднее тире, &ndash; (черта длиной в заглавную букву N)
     * @var string
     */
    const DASH_MIDDLE = "–";
    /**
     * Цифровое тире, &#8210; или &#x2012;
     * @var string
     */
    const DASH_FIGURE = "‒";

    /**
     * @see https://ru.wikipedia.org/wiki/Дефис/**
     */
    /**
     * Дефис
     * @var string
     */
    const HYPHEN = "‐";
    /**
     * Неразрывный дефис
     * @var string
     */
    const HYPHEN_NON_BREAKING = "‑";
    /**
     * Дефис-минус
     * @var string
     */
    const HYPHEN_MINUS = "-";
    /**
     * Для указания вручную места возможного переноса.
     * Дефис-перенос, отображается только если слово в конце строки и не помещается целиком,
     * тогда слово разбивается в этом месте и символ отображается
     * &shy; / &#173;
     * @var string
     */
    const HYPHEN_SOFT = "­";

    /**
     * Минус / &minus;
     * Не считается за символ разрыва, объединяет стоящие перед и после символы,
     * НО есть \Parus\Text::HYPHEN_NON_BREAKING
     * @var string
     * @see https://ru.wikipedia.org/wiki/Минус
     */
    const SYMBOL_MINUS = "−";
    /**
     * Рубль / &#8381;
     * @var string
     * @see https://ru.wikipedia.org/wiki/Символ_рубля
     */
    const SYMBOL_RUBLE = "₽";
    /**
     * Умножить / &times; / &#215;
     * @var string
     */
    const SYMBOL_TIMES = "×";
    /**
     * Троеточие / &hellip;
     * @var string
     */
    const SYMBOL_ELLIPSIS = "…";
    /**
     * Половина, 1/2
     * @var string
     * @see https://ru.wikipedia.org/wiki/Дроби_в_Юникоде
     */
    const SYMBOL_HALF = "½";
    /**
     * Четверть, 1/4
     * @var string
     */
    const SYMBOL_QUARTER = "¼";
    /**
     * Градус / &deg; / &#176;
     * @var string
     */
    const SYMBOL_DEGREE = "°";
    /**
     * Параграф / &sect; / &#167;
     * @var string
     */
    const SYMBOL_PARAGRAPH = "§";
    /**
     * Копирайт / &copy; / &#169;
     * @var string
     */
    const SYMBOL_COPYRIGHT = "©";
    /**
     * Знак зарегистрированной торговой марки / &reg; / &#174;
     * @var string
     */
    const SYMBOL_REGISTERED = "®";
    /**
     * Знак торговой марки / &trade; / &#8482;
     * @var string
     */
    const SYMBOL_TRADEMARK = "™";

    /**
     * Набор символов [0-9a-zA-Z]
     * @var string
     */
    const DEFAULT_ENG_CHARACTERS = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

    /**
     * Мета символы регулярных выражений
     * @var string
     */
    const REGEXP_META_SYMBOLS = ".?+*|\\/[](){}";

    /**
     * MySQL мета символы для оператора LIKE:
     * «_» - любой символ (UTF-8);
     * «%» – любое кол-во любых символов
     *
     * @var string
     */
    const MYSQL_LIKE_META_SYMBOLS = "_%";

    /**
     * Русский алфавит
     * @var string[]
     */
    const ALPHABET_RU = ["А","Б","В","Г","Д","Е","Ё","Ж","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х","Ц","Ч","Ш","Щ","Ъ","Ы","Ь","Э","Ю","Я"];

    /**
     * Массив соответствия Уникодов кириллическим символам
     * @var string[]
     */
    const ARRAY_UNICODE_CYRILLIC = [
        '\u0430' => 'а', '\u0410' => 'А',
        '\u0431' => 'б', '\u0411' => 'Б',
        '\u0432' => 'в', '\u0412' => 'В',
        '\u0433' => 'г', '\u0413' => 'Г',
        '\u0434' => 'д', '\u0414' => 'Д',
        '\u0435' => 'е', '\u0415' => 'Е',
        '\u0451' => 'ё', '\u0401' => 'Ё',
        '\u0436' => 'ж', '\u0416' => 'Ж',
        '\u0437' => 'з', '\u0417' => 'З',
        '\u0438' => 'и', '\u0418' => 'И',
        '\u0439' => 'й', '\u0419' => 'Й',
        '\u043a' => 'к', '\u041a' => 'К',
        '\u043b' => 'л', '\u041b' => 'Л',
        '\u043c' => 'м', '\u041c' => 'М',
        '\u043d' => 'н', '\u041d' => 'Н',
        '\u043e' => 'о', '\u041e' => 'О',
        '\u043f' => 'п', '\u041f' => 'П',
        '\u0440' => 'р', '\u0420' => 'Р',
        '\u0441' => 'с', '\u0421' => 'С',
        '\u0442' => 'т', '\u0422' => 'Т',
        '\u0443' => 'у', '\u0423' => 'У',
        '\u0444' => 'ф', '\u0424' => 'Ф',
        '\u0445' => 'х', '\u0425' => 'Х',
        '\u0446' => 'ц', '\u0426' => 'Ц',
        '\u0447' => 'ч', '\u0427' => 'Ч',
        '\u0448' => 'ш', '\u0428' => 'Ш',
        '\u0449' => 'щ', '\u0429' => 'Щ',
        '\u044a' => 'ъ', '\u042a' => 'Ъ',
        '\u044b' => 'ы', '\u042b' => 'Ы',
        '\u044c' => 'ь', '\u042c' => 'Ь',
        '\u044d' => 'э', '\u042d' => 'Э',
        '\u044e' => 'ю', '\u042e' => 'Ю',
        '\u044f' => 'я', '\u042f' => 'Я',
    ];

    /**
     * Указатель метода конвертации строки в ASCII
     * @see base64_encode()
     * @var string
     */
    const TO_ASCII_BY_BASE64 = "B";

    /**
     * Указатель метода конвертации строки в ASCII
     * @see quoted_printable_encode()
     * @var string
     */
    const TO_ASCII_BY_QUOTED_PRINTABLE = "Q";

    /**
     * Преобразовать в строке кириллические символы из UNICODE кодов в символы
     *
     * @param string $string Строка с текстом для преобразования
     *
     * @return string
     */
    public static function unicodeToCyrillic ($string)
    {
        // Должна быть строка
        Data::typeOf($string ,Data::TYPE_STRING ,true);

        return strtr($string ,static::ARRAY_UNICODE_CYRILLIC);
    }

    /**
     * Преобразовать в строке кириллические символы в UNICODE код
     *
     * @param string $string Строка с текстом для преобразования
     *
     * @return string
     */
    public static function cyrillicToUnicode ($string)
    {
        // Должна быть строка
        Data::typeOf($string ,Data::TYPE_STRING ,true);

        return strtr($string ,array_flip(static::ARRAY_UNICODE_CYRILLIC));
    }

    /**
     * Получить массив с символами дефисов, переносов, тире и минуса
     *
     * @deprecated
     * @SEE Text::DASHES_AND_HYPHENS
     * @return string[]
     */
    static public function getDashesAndHyphens ()
    {
        return self::DASHES_AND_HYPHENS;
    }

    /**
     * Получить массив с символами пробелов/отступов
     *
     * @deprecated
     * @SEE Text::SPACES
     * @return string[]
     */
    static public function getAllSpaces ()
    {
        return self::SPACES;
    }

    /**
     * Экранировать обратными слэшами («\») символы в строке
     *
     * @param string          $string Строка в которой нужно экранировать символы
     * @param string|string[] $chars  Символы, которые должны быть экранированы, строка с символами или массив с символами
     *
     * @return string
     *
     * @throws \Parus\Exception\InvalidArgumentException
     */
    public static function escapeBySlashes ($string, $chars)
    {
        return static::escapeBy($string, $chars, "\\");
    }

    /**
     * Экранировать символы в строке указанными символами
     *
     * @param string          $string Строка в которой нужно экранировать символы
     * @param string|string[] $chars  Символы, которые должны быть экранированы, строка с символами или массив с символами
     * @param string          $escape Экранирующий символ
     *
     * @return string
     *
     * @throws \Parus\Exception\InvalidArgumentException
     */
    public static function escapeBy ($string, $chars, $escape = "\\")
    {
        if (!is_string($string) || $string === "") {
            throw new InvalidArgumentException("Ожидается строка, передан" . Data::getTypeRu( $string ) . '!');
        }

        if (!is_string($chars) && !is_array($chars)) {
            throw new InvalidArgumentException("Второй аргумент должен быть символом или массивом символов, передан" . Data::getTypeRu( $chars ) . '!');
        }

        if (!is_string($escape)) {
            throw new InvalidArgumentException("Третий аргумент должен быть строкой, передан" . Data::getTypeRu( $escape ) . '!');
        }

        if (is_array($chars)) {
            foreach($chars as $index=>&$char) {
                if (!is_string($char))
                    throw new InvalidArgumentException("В массиве символов на экранирование {$index} элемент не является символом, передан" . Data::getTypeRu( $char ) . '!');
            }

            $chars = implode("" ,$chars);
        }

        $chars = preg_split('/(?<!^)(?!$)/u' ,$chars);

        foreach ($chars as $index => &$char) {
            if (strpos(static::REGEXP_META_SYMBOLS ,$char) !== FALSE)
                $char = "\\" . $char;
        }

        $patternSymbolsToEscape = implode("|", $chars);

        if (!strpos(static::REGEXP_META_SYMBOLS, $escape) !== FALSE)
            $escapedEscape = $escape;
        else {
            $escapeEscape = preg_split('/(?<!^)(?!$)/u' ,$escape);

            foreach ($escapeEscape as $index => &$char) {
                if (strpos(static::REGEXP_META_SYMBOLS ,$char) !== FALSE)
                    $char = "\\" . $char;
            }

            $escapeEscape = implode("|", $escapeEscape);
            $pattern = "#(?'escape'(?>\\*))?(?'char'{$escapeEscape})#uX";

            $escapedEscape = preg_replace_callback($pattern, function($matches) {
                /** Если чётное кол-во, то добавить слэш */
                if (empty($matches["escape"]) || strlen($matches["escape"]) % 2 == 0)
                    return $matches["escape"] . '\\' . $matches["char"];
                else
                    return $matches[0];
            }, $escape, -1, $count);
        }

        $pattern = "#(?'escape'(?>{$escapedEscape}*))?(?'char'{$patternSymbolsToEscape})#uX";

        $stringEscapedSlashes = preg_replace_callback($pattern, function($matches) USE ($escape) {
            /** Если чётное кол-во, то добавить слэш */
            if (empty($matches["escape"]) || strlen($matches["escape"]) % 2 == 0)
                return $matches["escape"] . $escape . $matches["char"];
            else
                return $matches[0];
        }, $string, -1, $count);

        return $stringEscapedSlashes;
    }

    /**
     * Удаляет лишние символы
     *
     * @param string $string
     *
     * @return string
     */
    public static function removeSpecAndControlSymbols ($string)
    {
        /**
         * Убрать управляющие символы
         */
        $string = str_replace(static::getControlSymbols() ," " ,$string);
        $string = str_replace(static::getControlSymbols(true) ," " ,$string);
        /**
         * Убрать управляющие символы латиницы
         */
        $string = str_replace(static::getLatinControlSymbols() ," " ,$string);
        $string = str_replace(static::getLatinControlSymbols(true) ," " ,$string);

        $string = str_replace(static::getLatinControlSymbols1() ," " ,$string);
        $string = str_replace(static::getLatinControlSymbols1(true) ," " ,$string);

        $string = str_replace(static::getSpecialSymbols() ," " ,$string);
        $string = str_replace(static::getSpecialSymbols(true) ," " ,$string);

        return $string;
    }

    /**
     * Удаляет двойные пробелы, табуляции, переводы строк, а также пробелы и отступы в начале и конце строки
     * и все лишние символы, возвращает одну строку
     *
     * @param string                 $string
     * @param bool                   $noTabs             [опция] Убрать табы (\t)
     * @param bool|string|\Exception $throwException     [опция] Не возвращать null, boolean или пустую строку, в данном аргументе можно передать текст исключения
     * @param null|int               $minChars           [опция] Можно указать минимальное кол-во символов
     * @param null|int               $maxChars           [опция] Можно указать максимальное кол-во символов
     * @param bool                   $removeDoubleSpaces [опция] Убирать двойные пробелы
     *
     * @return false|string
     *
     * @throws \Parus\Exception\Exception
     * @throws \Parus\Exception\InvalidArgumentException
     */
    public static function prepareSingleLine ($string ,$noTabs = true ,$throwException = false ,$minChars = null ,$maxChars = null ,$removeDoubleSpaces = true)
    {
        if (!$throwException) {
            if (is_null($string) || is_bool($string) || $string === "") {
                return $string;
            }
        }

        // Если не строка или пустая строка:
        if (!is_string($string) || $string === "")
        {
            if ($throwException)
                throw new RuntimeException(...ParusException::getArguments("Ожидается строка, передан" . Data::getTypeRu($string ) . "!" ,$throwException));

            return false;
        }

        $string = trim($string);

        if ( $noTabs )
            $string = preg_replace('/\t/mu', ' ', $string);

        $string = static::removeSpecAndControlSymbols($string);

        $string = preg_replace('/\r|\n|\f|\v/mu', ' ', $string);

        if ($removeDoubleSpaces)
            $string = static::removeDoubleSpaces($string);

        if ($minChars) {
            self::isInteger($minChars ,"Неверно указан четвёртый аргумент «Минимальное кол-во символов»!" ,1);

            if (mb_strlen($string) < $minChars)
            {
                if ($throwException)
                    throw new RangeException(...ParusException::getArguments(
                        "Минимальное указанное кол-во – "
                            . Data::countWordForm($minChars ,["","а","ов"] ,true ,"символ")
                            . ", передан" . Data::getTypeRu($string)
                            . "!"
                        ,$throwException)
                    );

                return false;
            }
        }

        if ($maxChars) {
            self::isInteger($maxChars ,"Неверно указан пятый аргумент «Максимальное кол-во символов»!" ,1);

            if (mb_strlen($string) > $maxChars)
            {
                if ($throwException)
                    throw new RangeException(...ParusException::getArguments(
                        "Максимальное указанное кол-во – "
                            . Data::countWordForm($maxChars ,["","а","ов"] ,true ,"символ")
                            . ", передан" . Data::getTypeRu($string)
                            . "!"
                        ,$throwException
                    ));

                return false;
            }
        }

        return $string;
    }

    /**
     * @deprecated Использовать static::prepare
     *
     * @param string   $string           Текст который нужно обработать
     * @param bool     $noTabs           [опция] Убирать tab-ы и отступы в начале строк
     * @param bool|int $removeEmptyLines [опция] Убирать пустые строки или разрешить кол-во пустых строк
     * @param bool     $trim             [опция] Убирать пустые строки, отступы и пробелы в конце и начале текста
     *
     * @return string
     *
     * @throws \Parus\Exception\InvalidArgumentException
     */
    static public function prepareText ( $string, $noTabs = true, $removeEmptyLines = false, $trim = true )
    {
        return static::prepare($string, $noTabs, $removeEmptyLines, $trim);
    }

    /**
     * Подготовить текст убрав всё лишнее
     *
     * @param string   $string           Текст который нужно обработать
     * @param bool     $noTabs           [опция] Убирать tab-ы и отступы в начале строк
     * @param bool|int $removeEmptyLines [опция] Убирать пустые строки или разрешить кол-во пустых строк
     * @param bool     $trim             [опция] Убирать пустые строки, отступы и пробелы в конце и начале текста
     *
     * @return string
     *
     * @throws \Parus\Exception\Exception
     * @throws \Parus\Exception\InvalidArgumentException
     */
    static public function prepare ( $string, $noTabs = true, $removeEmptyLines = false, $trim = true )
    {
        if ( is_null($string) || is_bool($string) )
            return $string;

        if (!is_string($string)) {
            throw new InvalidArgumentException('Ожидается строка, передан' . Data::getTypeRu( $string ) . '!');
        }

        $string = static::removeWinAndMacEOLs( $string );

        $string = static::removeSpecAndControlSymbols($string);

        /**
         * Убрать пробелы и отступы в конце строк
         */
        $string = self::removeSpacesAtLinesEnds( $string );

        if ( $noTabs === true )
        {
            $string = self::removeTabs( $string );
            $string = self::removeSpacesAtLinesBegins( $string );
        }

        /**
         * Убрать отступы, где более одного пробела подряд не в начале строки и не перед табами
         */
        $string = static::removeDoubleSpacesNotAtBeginningOfLines( $string );

        if ( $removeEmptyLines !== false )
            $string = self::removeEmptyLines( $string, $removeEmptyLines );

        if ( $trim === true )
            $string = trim( $string );

        return $string;
    }

    /**
     * Удаляет двойные пробелы, а также пробелы и отступы в начале и конце текста (trim)
     *
     * @param string $string
     *
     * @return string
     * @throws \Parus\Exception\Exception
     */
    public static function removeDoubleSpaces ($string)
    {
        if (empty($string) or is_bool($string)) {
            return $string;
        }

        if (!is_string($string)) {
            throw new ParusException('Ожидается строка, передан' . Data::getTypeRu( $string ) . '!');
        }

        $string = str_replace("  " ," " ,$string);
        $string = trim($string);

        return $string;
    }

    /**
     * Убрать пробелы и отступы в конце строк
     * @param string $string Текст который нужно обработать
     * @return string
     */
    static public function removeSpacesAtLinesEnds ( $string )
    {
        return preg_replace( '/[[:blank:]]+$/um' , "" , $string );
    }

    /**
     * Убрать пробелы и отступы в начале всех строк
     * @param string $string Текст который нужно обработать
     * @return string
     */
    static public function removeSpacesAtLinesBegins ( $string )
    {
        return preg_replace( '/^[[:blank:]]+/um' , "" , $string );
    }

    /**
     * Убрать отступы, где более одного пробела подряд не в начале строки и не перед табами
     * @param string $string Текст который нужно обработать
     * @return string
     */
    static public function removeDoubleSpacesNotAtBeginningOfLines ( $string )
    {
        return preg_replace( '/(?<!^|\t)\ +/um' , " " , $string );
    }

    /**
     * Убрать пустые строки
     *
     * @param string  $string                      Текст который нужно обработать
     * @param integer $allowableNumberOfEmptyLines Опция, оставлять подряди идущие пустые строки
     *
     * @return string
     * @throws \Parus\Exception\Exception
     *
     * @see \Parus\Text::removeSpacesAtLinesEnds() Нужно сперва очистить строки от отступов в конце строк
     */
    static public function removeEmptyLines ( $string, $allowableNumberOfEmptyLines = 1 )
    {
        if ( !ctype_digit( trim($allowableNumberOfEmptyLines) ) || +$allowableNumberOfEmptyLines < 1 )
            throw new ParusException( "Второй аргумент указан неверно, должно быть передано целое положительное, передан" . Data::getTypeRu($allowableNumberOfEmptyLines) . "!" );

        return preg_replace( "/\n\n{{$allowableNumberOfEmptyLines},}/um" , str_repeat("\n",++$allowableNumberOfEmptyLines) , $string );
    }

    /**
     * Убрать табы (\t) который находятся в тексте,
     * но не в начале строк
     *
     * @param string $string Текст который нужно обработать
     *
     * @return string
     */
    static public function removeTabsNotInBeginningOfLines ( $string )
    {
        return preg_replace( '/(?<!^\t*)\t+/um' , " " , $string );
    }

    /**
     * Убрать табы \t
     * @param string $string Текст который нужно обработать
     * @return string
     */
    static public function removeTabs ( $string )
    {
        return preg_replace( '/\t+/um' , " " , $string );
    }

    /**
     * Все окончания строк в UNIX формат \n
     * @param string $string Текст который нужно обработать
     * @return string
     */
    static public function removeWinAndMacEOLs ( $string )
    {
        return preg_replace( '/\r\n|\r/um' , "\n" , $string );
    }

    /**
     * Получить массив с Управляющими символами (0000—001F)
     * @see http://unicode-table.com/ru/blocks/control-character/
     * @param bool $keys Опция, вернуть только ключи
     * @return \string[] Ассоциативный массив, ключи – Юникоде, значения – сами символы
     */
    static public function getControlSymbols($keys = false)
    {
        $symbols = [
            "\u0000" => " ", // \0           Пустой символ
            "\u0001" => "", //     Ctrl+A   Начало заголовка
            "\u0002" => "", //     Ctrl+B   Начало текста
            "\u0003" => "", //     Ctrl+C   Конец текста
            "\u0004" => "", //     Ctrl+D   Конец передачи
            "\u0005" => "", //     Ctrl+E   Запрос
            "\u0006" => "", //     Ctrl+F   Подтверждение
            "\u0007" => "", // \a  Ctrl+G   Звуковой сигнал
            "\u0008" => "", // \b  Ctrl+H   Возврат на шаг

            "\u000B" => "", // \v  Ctrl+K   Вертикальная табуляция
            "\u000C" => "", // \f  Ctrl+L   Прогон страницы

            "\u000E" => "", //     Ctrl+N   Режим национальных символов
            "\u000F" => "", //     Ctrl+O   Режим обычного ASCII
            "\u0010" => "", //     Ctrl+P   Освобождение канала данных
            "\u0011" => "", //     Ctrl+Q   1-й код управления
            "\u0012" => "", //     Ctrl+R   2-й код управления
            "\u0013" => "", //     Ctrl+S   3-й код управления
            "\u0014" => "", //     Ctrl+T   4-й код управления
            "\u0015" => "", //     Ctrl+U   Отрицательное подтверждение
            "\u0016" => "", //     Ctrl+V   Пустой символ для синхронного режима
            "\u0017" => "", //     Ctrl+W   Конец блока передаваемых данных
            "\u0018" => "", //     Ctrl+X   Отмена
            "\u0019" => "", //     Ctrl+Y   Конец носителя
            "\u001A" => "", //     Ctrl+Z   Замена
            "\u001B" => "", //     Ctrl+[   Альтернативный регистр #2
            "\u001C" => "", //     Ctrl+\   Разделитель файлов
            "\u001D" => "", //     Ctrl+]   Разделитель групп
            "\u001E" => "", //     Ctrl+^   Разделитель записей
            "\u001F" => "", //     Ctrl+_   Разделитель полей

            // "\u0009" => "\t", //    Ctrl+I   Горизонтальная табуляция
            // "\u000A" => "\n", //    Ctrl+J   Перевод строки

            // "\u000D" => "\r", //    Ctrl+M   Возврат каретки
        ];

        return $keys ? array_keys($symbols) : $symbols;
    }

    /**
     * Управляющие символы расширенной латиницы (0080—00A0)
     * @see http://unicode-table.com/ru/blocks/latin-1-supplement/
     * @param bool $keys Опция, вернуть только ключи
     * @return string[] Ассоциативный массив, ключи – Юникоде, значения – сами символы
     */
    static public function getLatinControlSymbols($keys = false)
    {
        $symbols = [
            "\u0080" => "", // Символ-заполнитель / <Control>
            "\u0081" => "", // Символ управления / <Control>
            "\u0082" => "", // Здесь разрешён разрыв строки / Break Permitted Here
            "\u0083" => "", // Здесь не разрешён разрыв строки / No Break Here
            "\u0084" => "", // Символ управления / <Control>
            "\u0085" => "", // Следующая строка. Одновременно переводит строку и возвращает позицию печати к началу // Next Line
            "\u0086" => "", // Начало выделенной области / Start of Selected Area
            "\u0087" => "", // Конец выделенной области / End of Selected Area
            "\u0088" => "", // Установка позиций горизонтальной табуляции / Character Tabulation Set
            "\u0089" => "", // Установка позиций и выравнивания горизонтальной табуляции / Character Tabulation with Justification
            "\u008A" => "", // Установка позиций вертикальной табуляции /
            "\u008B" => "", // Частичный перевод строки вперёд / Partial Line Forward
            "\u008C" => "", // Частичный перевод строки назад / Partial Line Backward
            "\u008D" => "", // Обратный перевод строки / Reverse Line Feed
            "\u008E" => "", // 2-e значение для следующего символа / Single Shift Two
            "\u008F" => "", // 3-e значение для следующего символа / Single Shift Three
            "\u0090" => "", // Строка управления устройством / Device Control String
            "\u0091" => "", // Пользовательский символ № 1 / Private Use One
            "\u0092" => "", // Пользовательский символ № 2 / Private Use Two
            "\u0093" => "", // Установка режима передачи / Set Transmit State
            "\u0094" => "", // Символ отмены / Cancel Character
            "\u0095" => "", // Есть сообщение / Message Waiting
            "\u0096" => "", // Начало защищённой области / Start of Guarded Area
            "\u0097" => "", // Конец защищённой области / End of Guarded Area
            "\u0098" => "", // Начало строки / Start of String
            "\u0099" => "", // Следующий символ интерпретируется как специальный графический / <Control>
            "\u009A" => "", // Следующий символ интерпретируется как управляющий / Single Character Introducer
            "\u009B" => "", // Начало управляющей последовательности / Control Sequence Introducer
            "\u009C" => "", // Окончание строки / String Terminator
            "\u009D" => "", // Команда операционной системы / Operating System Command
            "\u009E" => "", // Секретное сообщение / Privacy Message
            "\u009F" => "", // Команда прикладной программы / Application Program Command

            // "\u00a0" => " ", // Неразрывный пробел / No-Break Space
        ];

        return $keys ? array_keys($symbols) : $symbols;
    }

    /**
     * Дополнительная латиница-1 0080—00FF
     * @see http://unicode-table.com/ru/blocks/latin-1-supplement/
     * @param bool $keys Опция, вернуть только ключи
     * @return string[] Ассоциативный массив, ключи – Юникоде, значения – сами символы
     */
    static public function getLatinControlSymbols1($keys = false)
    {
        $symbols = [
             "\u00AC"=>"¬", // Знак отрицания
        ];

        return $keys ? array_keys($symbols) : $symbols;
    }

    /**
     * Специальные символы FFF0—FFFF
     * @see http://unicode-table.com/ru/blocks/specials/
     * @param bool $keys Опция, вернуть только ключи
     * @return string[] Ассоциативный массив, ключи – Юникоде, значения – сами символы
     */
    static public function getSpecialSymbols($keys = false)
    {
        $symbols = [
            "\uFFF9"=>"￹￹",
            "\uFFFA"=>"￹￺",
            "\uFFFB"=>"￻￹￺",
            "\uFFFC"=>"￼￻￹￺￻",
            "\uFFFD"=>"�", // Заменяющий символ
            "\uFFFE"=>"�￻",
            "\uFFFF"=>'￿', // <Not a Character>
        ];

        return $keys ? array_keys($symbols) : $symbols;
    }

    /**
     * HTML экранирование
     * @param string $string
     * @return string
     */
    public static function htmlEscape ($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE | ENT_DISALLOWED, 'UTF-8');
    }


    /**
     * Возвращает строку закодированную с помощью base64_encode или quoted_printable_encode с указанием кодировки для ASCII формата
     *
     * @param string $string
     * @param string $encoder
     *
     * @return string
     *
     * @throws \Parus\Exception\BadMethodCallException
     */
    public static function encodeToASCII ($string ,$encoder = self::TO_ASCII_BY_BASE64)
    {
        $string = static::prepareSingleLine($string ,true ,"Неверное значение аргумента со строкой для кодирования в «{$encoder}»!");

        if (strtoupper($encoder) != self::TO_ASCII_BY_BASE64 && strtoupper($encoder) != self::TO_ASCII_BY_QUOTED_PRINTABLE)
        {
            $class = __CLASS__;
            throw new ObjectTraitCheckArgException("Неверное значение аргумента кодировки «{$encoder}»! Возможные варианты: \\{$class}::TO_ASCII_BY_BASE64 / \\{$class}::TO_ASCII_BY_QUOTED_PRINTABLE!");
        }

        if (strtoupper($encoder) == self::TO_ASCII_BY_BASE64)
            return "=?UTF-8?B?" . base64_encode($string) . "?=";
        else
            return "=?UTF-8?Q?" . quoted_printable_encode($string) . "?=";
    }

    /**
     * Возвращает транслитерированную строку (Cyr -> Lat)
     * @param string $string
     * @return string
     */
    public static function translit ($string)
    {
        return strtr($string, [
            'А'=>'A',    'Б'=>'B',    'В'=>'V',    'Г'=>'G',    'Д'=>'D',
            'Е'=>'E',    'Ё'=>'YO',   'Ж'=>'J',    'З'=>'Z',    'И'=>'I',
            'Й'=>'Y',    'К'=>'K',    'Л'=>'L',    'М'=>'M',    'Н'=>'N',
            'О'=>'O',    'П'=>'P',    'Р'=>'R',    'С'=>'S',    'Т'=>'T',
            'У'=>'U',    'Ф'=>'F',    'Х'=>'H',    'Ц'=>'TS',   'Ч'=>'CH',
            'Ш'=>'SH',   'Щ'=>'SCH',  'Ъ'=>'``',   'Ы'=>'YI',   'Ь'=>'`',
            'Э'=>'E',    'Ю'=>'YU',   'Я'=>'YA',

            'а'=>'a',    'б'=>'b',    'в'=>'v',    'г'=>'g',    'д'=>'d',
            'е'=>'e',    'ё'=>'yo',   'ж'=>'j',    'з'=>'z',    'и'=>'i',
            'й'=>'y',    'к'=>'k',    'л'=>'l',    'м'=>'m',    'н'=>'n',
            'о'=>'o',    'п'=>'p',    'р'=>'r',    'с'=>'s',    'т'=>'t',
            'у'=>'u',    'ф'=>'f',    'х'=>'h',    'ц'=>'ts',   'ч'=>'ch',
            'ш'=>'sh',   'щ'=>'sch',  'ъ'=>'``',   'ы'=>'yi',   'ь'=>'`',
            'э'=>'e',    'ю'=>'yu',   'я'=>'ya',
        ]);
    }

    /**
     * Преобразование строки в slug
     * @param string $string
     * @param int $max_length
     * @return string
     */
    public static function textToSlug (string $string, int $max_length = 0)
    {
        $string = preg_replace('~[^\p{L}\d]+~u', '-', $string);
        if ($max_length)
            $string = mb_substr($string, 0, $max_length);
        $string = trim($string, '-');
        $string = preg_replace('~-{2,}~', '-', $string);
        $string = self::lowercaseAllCharacters($string);
        return $string;
    }

    /**
     * Все символы в строке в верхний регистр
     * @param $string
     * @return string
     */
    public static function uppercaseAllCharacters ($string)
    {
        return mb_convert_case($string, MB_CASE_UPPER, 'UTF-8');
    }

    /**
     * Все символы в строке в нижний регистр
     * @param $string
     * @return string
     */
    public static function lowercaseAllCharacters ($string)
    {
        return mb_convert_case($string, MB_CASE_LOWER, 'UTF-8');
    }

    /**
     * Сделать первую букву Прописной
     * @param string $string
     * @return string
     */
    public static function uppercaseFirstCharacter ($string)
    {
        mb_internal_encoding('UTF-8');
        $stringLength = mb_strlen($string);
        if (!$stringLength) {
            return $string;
        }
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1, $stringLength - 1);
    }

    /**
     * @deprecated
     * @param $string
     * @return string
     */
    public static function capitalizeFirstCharacter ($string)
    {
        return self::uppercaseFirstCharacter($string);
    }

    /**
     * Первый символ каждой отдельной части в верхний регистр
     * @param $string
     * @return string
     */
    public static function uppercaseFirstCharacterOfEachWord ($string)
    {
        return mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
    }


    /**
     * Преобразовать строку в формате CamelCase в snake_case
     *
     * @param string $string
     *
     * @return string
     *
     * @throws \Parus\Exception\Exception
     * @throws \Parus\Exception\InvalidArgumentException
     */
    public static function convertCamelCaseToSnakeCase ($string)
    {
        $string = self::prepareSingleLine($string ,true ,"Преобразование строки в формате CamelCase в snake_case!");
        return mb_strtolower(preg_replace('/(?<!^)[A-Z]+/', '_$0', $string));
    }

    /**
     * Поляризатор слов.
     * Выбирает слово, основываясь на поле человека.
     * @param string $sex               Пол (варианты: 'male', 'female', null)
     * @param string $wordForMale       Слово мужского рода (пример: уважаемый)
     * @param string $wordForFemale     Слово женского рода (пример: уважаемая)
     * @param string $wordForUnknownSex Слово неопределенного рода (необязательное, при отсутвиии формируется автоматически) (пример: уважаемый(ая)')
     * @return string
     */
    public static function sexifyWord ($sex, $wordForMale, $wordForFemale, $wordForUnknownSex = null)
    {
        if (in_array($sex, ['male', 'female'])) {
            return ($sex == 'male') ? $wordForMale : $wordForFemale;
        }

        if ($wordForUnknownSex) {
            return $wordForUnknownSex;
        }

        if (mb_strlen($wordForMale) >= 4) {
            $wordForUnknownSex = null;
            for ($currentLength = mb_strlen($wordForMale); $currentLength >= 4; $currentLength--) {
                $croppedWordForMale = mb_substr($wordForMale, 0, $currentLength);
                if ($croppedWordForMale == mb_substr($wordForFemale, 0, $currentLength)) {
                    $wordForUnknownSex = $wordForMale .'('. mb_substr($wordForFemale, $currentLength, mb_strlen($wordForFemale)) .')';
                    break;
                }
            }
            return $wordForUnknownSex;
        }

        return $wordForMale .'('. $wordForFemale .')';
    }

    /**
     * Является ли срока целым положительным числом
     *
     * @param mixed                  $input Переменная для проверки
     * @param bool|string|\Exception $throwException
     * @param null|int               $min
     * @param null|int               $max
     *
     * @return bool Сравнение:
     *
     * Сравнение:
     *
     * is_int(23)    // true
     * is_int(-23)   // true
     * is_int("23")  // false
     * is_int("-23") // false
     * is_int(23.5)  // false
     * is_int(NULL)  // false
     * is_int("")    // false
     *
     * ctype_digit(23)    // false
     * ctype_digit(-23)   // false
     * ctype_digit("23")  // true
     * ctype_digit("-23") // false
     * ctype_digit(23.5)  // false
     * ctype_digit(NULL)  // false
     * ctype_digit("")    // false
     *
     * isInteger(23)    // true
     * isInteger(-23)   // true
     * isInteger("23")  // true
     * isInteger("-23") // true
     *
     * isInteger(23.5) // false
     * isInteger(NULL) // false
     * isInteger("")   // false
     *
     * @throws \Exception
     * @throws \Parus\Exception\InvalidArgumentException
     */
    public static function isInteger($input, $throwException = false, $min = null, $max = null): bool
    {
        /**
         * Объекты/ресурсы/массивы не преобразуются в строку,
         * целое положительное может быть указано как float
         */
        if (!is_integer($input) && !is_float($input) && !is_string($input)) {
            if ($throwException)
                throw new RuntimeException(...ParusException::getArguments("Переданное значение не является целым положительным, передан" . Data::getTypeRu($input) . "!", $throwException));

            return false;
        }

        preg_match("/^\s*(-?\d+)\s*$/m", (string) $input, $matches);

        if (!array_key_exists(1, $matches)) {
            if ($throwException)
                throw new InvalidArgumentException(...ParusException::getArguments("Переданное значение не является целым, передан" . Data::getTypeRu($input) . "!", $throwException));

            return false;
        }

        $integer = (int) $matches[1];

        if ($min || $min === 0) {
            $min = Data::getInteger($min, "Третий аргумент – минимальное значение, указан неверно!");

            if ($integer < $min) {
                if ($throwException)
                    throw new RangeException(...ParusException::getArguments("Переданное число меньше «{$min}», передан" . Data::getTypeRu($input) . "!", $throwException));

                return  false;
            }
        }

        if ($max || $max === 0) {
            $max = Data::getInteger($max, "Четвёртый аргумент – максимальное значение, указан неверно!");

            if (($min || $min === 0) && $min >= $max)
                throw new RangeException(...ParusException::getArguments("Третий аргумент – минимальное значение «{$min}» – должен быть меньше четвёртого аргумента – максимальное значение «{$max}»!", $throwException));

            if ($integer > $max) {
                if ($throwException)
                    throw new RangeException(...ParusException::getArguments("Переданное число больше «{$max}», передан" . Data::getTypeRu($input) . "!", $throwException));

                return  false;
            }
        }

        return true;
    }

    /**
     * Склонятор окончаний множественных целых чисел
     *
     * @param integer $number    Целое число
     * @param string  $variant_0 Вариант для 0, 5, 6, 7, 8, 9, 10… (например: рублей)
     * @param string  $variant_1 Вариант для 1, 21, 31… (например: рубль)
     * @param string  $variant_2 Вариант для 2, 3, 4, 22, 23, 24… (например: рубля)
     *
     * @return string
     */
    public static function declineDigital(int $number, string $variant_0, string $variant_1, string $variant_2): string
    {
        $div100 = abs($number % 100);
        if ($div100 == 0 or ($div100 >= 5 and $div100 <= 20)) {
            return $variant_0;
        }

        $div10 = abs($number % 10);
        return ($div10 == 1) ? $variant_1 : ((2 <= $div10 and $div10 <= 4) ? $variant_2 : $variant_0);
    }

    /**
     * Склонятор часов
     *
     * @param integer $number Число
     * @param bool $addNumber Добавлять само значение в строку
     * @return string
     */
    public static function declineDigitalHour(int $number, bool $addNumber = true): string
    {
        $decline = self::declineDigital($number, 'часов', 'час', 'часа');
        if ($addNumber) {
            $decline = $number . self::SPACE_NOBR . $decline;
        }
        return $decline;
    }

    /**
     * Склонятор рублей
     *
     * @param integer $number Число
     * @param bool $addNumber Добавлять само значение в строку
     * @return string
     */
    public static function declineDigitalRouble(int $number, bool $addNumber = true): string
    {
        $decline = self::declineDigital($number, 'рублей', 'рубль', 'рубля');
        if ($addNumber) {
            $decline = $number . self::SPACE_NOBR . $decline;
        }
        return $decline;
    }

    /**
     * Склонятор записей
     *
     * @param integer $number Число
     * @return string
     */
    public static function declineDigitalEntry(int $number): string
    {
        return $number . self::SPACE_NOBR . self::declineDigital($number, 'записей', 'запись', 'записи');
    }

    /**
     * Перевод чисел из десятичной системы счисления в XX-тичную (от 2 до 62)
     *
     * @access public
     * @static
     *
     * @param integer $number Число
     * @param integer $base   XX-тичность выходного числа
     *
     * @return string
     * @throws \Parus\Exception\Exception
     */
    public static function convertFromDecimalToBase ($number, $base = 62)
    {
        if (!is_int($number)) {
            throw new ParusException( "Первый аргумент должен быть целым числом, передан" . Data::getTypeRu($number) . "!");
        }

        if (!is_int($base) or $base < 2 or $base > 62) {
            throw new ParusException( "Второй аргумент должен быть целым числом в интервале от 2 до 62, передан" . Data::getTypeRu($number) . "!");
        }

        $pattern = self::DEFAULT_ENG_CHARACTERS;
        $remainder = $number % $base;
        $result = $pattern[$remainder];
        $q = floor($number / $base);
        while ($q) {
            $remainder = $q % $base;
            $q = floor($q / $base);
            $result = $pattern[$remainder] . $result;
        }
        return $result;
    }

    /**
     * Перевод чисел из XX-тичной (от 2 до 62) в десятичную систему счисления
     *
     * @access public
     * @static
     *
     * @param string  $number Число
     * @param integer $base   XX-тичность входного числа
     *
     * @return integer
     * @throws \Parus\Exception\Exception
     */
    public static function convertFromBaseToDecimal ($number, $base = 62)
    {
        $number = (string) $number;

        if (preg_match('/[^0-9a-zA-Z]/', $number)) {
            throw new ParusException( "Первый аргумент должен быть целым числом, передан" . Data::getTypeRu($number) . "!");
        }

        if (!is_int($base) or $base < 2 or $base > 62) {
            throw new ParusException( "Второй аргумент должен быть целым числом в интервале от 2 до 62, передан" . Data::getTypeRu($number) . "!");
        }

        $patern = self::DEFAULT_ENG_CHARACTERS;
        $limit = strlen($number);
        $result = strpos($patern, $number[0]);
        for ($i=1; $i<$limit; $i++) {
            $result = $base * $result + strpos($patern, $number[$i]);
        }
        return (integer) $result;
    }

    /**
     * Генерация случайной строки
     *
     * @access public
     * @static
     *
     * @param integer $length    Длина строки (по умолчанию = 10)
     * @param string $characters Набор символов (ASCII only, по умолчанию = [0-9a-zA-Z])
     *
     * @see \Parus\Text::DEFAULT_ENG_CHARACTERS
     * @return string
     */
     public static function generateRandomString ($length = 10, $characters = self::DEFAULT_ENG_CHARACTERS)
     {
         $charactersLenght = strlen($characters) - 1;
         $randomString = '';
         for ($i = 0; $i < $length; $i++) {
             $randomString .= $characters[rand(0, $charactersLenght)];
         }
         return $randomString;
     }

    /**
     * Конвертация раскладки клавиатуры, подготовка данных для баркода
     *
     * @access public
     * @static
     * @param string  $str  Строка для конвертации
     * @param boolean $toUp Переводить строку в верхний регистр , по умолчанию = true)
     * @return string
     */
    public static function prepare4Barcode ($str = '', $toUp = TRUE)
    {
        $rus = ['Й', 'Ц', 'У', 'К', 'Е', 'Н', 'Г', 'Ш', 'Щ', 'З', 'Х', 'Ъ', 'Ф', 'Ы', 'В', 'А', 'П', 'Р', 'О', 'Л', 'Д', 'Ж', 'Э', 'Я', 'Ч', 'С', 'М', 'И', 'Т', 'Ь', 'Б', 'Ю',
        'й', 'ц', 'у', 'к', 'е', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ъ', 'ф', 'ы', 'в', 'а', 'п', 'р', 'о', 'л', 'д', 'ж', 'э', 'я', 'ч', 'с', 'м', 'и', 'т', 'ь', 'б', 'ю',];
        $eng =  ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', '{', '}', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', ':', '~', 'Z', 'X', 'C', 'V', 'B', 'N', 'M', '<', '>',
        'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', '[', ']', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', ';', "'", 'z', 'x', 'c', 'v', 'b', 'n', 'm', ',', '.',];

        $phrase = ($toUp) ? mb_strtoupper($str, "UTF-8") : $str;
        return $newphrase = str_replace($rus, $eng, $phrase);
    }

    /**
     * Типографировать текст
     *
     * Evgeny Muravjev Typograph, http://mdash.ru
     *
     * @param string           $text    Текст, который нужно отредактировать
     * @param string[]         $options Массив с опциями для типографа: https://admin.1sept.ru/lib/Parus/EMT/tools-php/debug.php
     * @param boolean|string[] $replace Ассоциативный массив для замен в тексте, ключ: искомое, значение: текст замены. Если просто true, то будут заменены основные HTML сущности
     *
     * @return string
     * @throws \Parus\Exception\Exception
     */
    static function typograph ( $text, $options = [], $replace = true )
    {
        /**
         * Если предана НЕ строка
         */
        if ( !is_string( $text ) )
            throw new ParusException("Первый аргумент должен быть строкой, перед" . Data::getTypeRu($text) . "!");

        /**
         * Если предана строка без символов, то выйти
         */
        if ( trim( $text ) == "" )
            throw new ParusException("Передана пустая строка!");

        /*
         * "earthdweller/mdash": "dev-master"
         * {
         *   "type": "git",
         *   "url": "git@github.com:earthdweller/mdash.git"
         * }
         */
        $typograph = new \Emuravjev\Mdash\Typograph();

        if ( empty($options) )
            $options = [
                'Text.paragraphs'           => 'off', // Простановка параграфов
                'Text.breakline'            => 'off', // Простановка переносов строк
                'OptAlign.oa_oquote'        => 'off', // Оптическое выравнивание открывающей кавычки
                'OptAlign.oa_oquote_extra'  => 'off', // Оптическое выравнивание кавычки
                'OptAlign.oa_obracket_coma' => 'off', // Оптическое выравнивание для пунктуации (скобка и запятая)
                'Space.many_spaces_to_one'  => 'off', // Не трогать отступы в начале строки
            ];

        $typograph->setup( $options );

        $typograph->set_text( $text );

        $result = $typograph->apply();

        if ( !$result )
            throw new ParusException("Не удалось типографировать текст!");

        if ( $replace )
        {
            /**
             * Для читабельности заменить некоторые HTML сущности
             */
            if ( $replace === true )
                $replace = [
                    '&nbsp;'   => self::SPACE_NOBR,
                    '&thinsp;' => self::SPACE_PUNCTUATION,
                    '&mdash;'  => self::DASH_LONG,
                    '&minus;'  => self::SYMBOL_MINUS,
                    '&hellip;' => self::SYMBOL_ELLIPSIS,
                    '&laquo;'  => '«',
                    '&raquo;'  => '»',
                    '&#8470;'  => '№',
                    '&bdquo;'  => '„',
                    '&ldquo;'  => '“',
                    '&quot;'   => '"',
                    '&times;'  => self::SYMBOL_TIMES,
                ];

            $result = strtr( $result , $replace );
        }

        return $result;
    }

    /**
     * Преобразовать даты из цифрового формата в строки, вывести названия месяцев
     *
     * @param string $text
     *
     * @return string
     */
    static function dateToRu ( $text )
    {
        preg_match_all( '/\d{1,2}\.\d{1,2}\.\d{2,4}\s*(г\.?(од)?а?)?/ums' , $text , $matches );

        $space = self::SPACE_NOBR;

        if ( $matches )
            foreach ( $matches[0] as $index => $match )
            {
                if ( empty($match) )
                    continue;

                $date = new Date( $match );

                $yearText = $matches[1][$index];
                if ( $yearText )
                    $yearText = $space . $yearText;

                $text = str_replace( $match , $date->format("j {$space}F{$space} Y{$yearText}") , $text );
            }

        return $text;
    }

    /**
     * Временные интервалы вывести в правильном формате, точки на двоеточия, дефис
     *
     * @param string $text
     *
     * @param bool   $nobr
     *
     * @return string
     */
    public static function correctTimeInterval ( $text , $nobr = true )
    {
        preg_match_all( '/
                (?<=\s|^)          # Перед пробелом или началом строки
                (\d{1,2}[.:]\d{2}) # 1 - Начало интервала
                (?:\s+|)           # Может быть пробел
                (?:[' . self::HYPHEN_MINUS
                     . self::DASH_MIDDLE
                     . self::DASH_LONG
                     . self::SYMBOL_MINUS
                     . '])        # Варианты дефисов
                (?:\s+|)           # Может быть пробел
                (\d{1,2}[.:]\d{2}) # 2 - Конец интервала
                (?=\s+|$)          # После должен быть пробел или конец строки
                # x - не учитывать в паттерне пробелы и переносы
                # u - UTF-8
                # m - multiline - пробежаться по всем строкам
                # s - искать только по строкам
            /xums' , $text , $matches , PREG_SET_ORDER);

        if ( is_array( $matches ) )
        {
            foreach ( $matches as $match )
            {
                if ( empty($match) )
                    continue;

                $newTime = str_replace( "." , ":" , $match[1] ) . "–" . str_replace( "." , ":" , $match[2] );

                if ( $nobr )
                    $newTime = "<nobr>{$newTime}</nobr>";

                $text = str_replace( $match[0] , $newTime , $text );
            }
        }

        return $text;
    }

    /**
     * Строка начинается с искомой строки или по крайней мере с одной из строк массива?
     * @param string          $haystack Целевая строка (в которой производится поиск)
     * @param string|string[] $needle Искомая строка или массив строк
     * @return bool
     */
    public static function beginsWith ($haystack, $needle)
    {
        if (!is_array($needle)) {
            $needle = [ $needle ];
        }

        $haystack = (string) $haystack;

        foreach ($needle as $needleString) {
            if (mb_strpos($haystack, (string) $needleString, 0) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Строка заканчивается искомой строкой или по крайней мере одной из строк массива?
     * @param string          $haystack Целевая строка (в которой производится поиск)
     * @param string|string[] $needle Искомая строка или массив строк
     * @return bool
     */
    public static function endsWith ($haystack, $needle)
    {
        if (!is_array($needle)) {
            $needle = [ $needle ];
        }

        $haystack = (string) $haystack;
        $haystackLength = mb_strlen($haystack);

        foreach ($needle as $needleString) {
            if (mb_strrpos($haystack, (string) $needleString, 0) === $haystackLength - mb_strlen($needleString)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Строка содержит искомую(ые)?
     * @param string          $haystack Целевая строка (в которой производится поиск)
     * @param string|string[] $needle   Искомая строка или массив строк
     * @param bool            $forAll   Целевая строка содержит все строки из массива (значиние по умолчанию), иначе — целевая строка содержит хотя бы одну строку из массива
     * @return bool
     */
    public static function contains ($haystack, $needle, $forAll = true)
    {
        if (!is_array($needle)) {
            $needle = [ $needle ];
        }

        $haystack = (string) $haystack;

        foreach ($needle as $needleString) {
            $contains = (mb_strpos($haystack, (string) $needleString, 0) !== false);

            if ($forAll and !$contains) {
                return false;

            } else if (!$forAll and $contains) {
                return true;
            }
        }
        return $forAll;
    }

    /**
     * Строка не содержит искомую(ые)?
     * @param string          $haystack Целевая строка (в которой производится поиск)
     * @param string|string[] $needle   Искомая строка или массив строк
     * @param bool            $forAll   Целевая строка не содержит ни одной строки из массива (значиние по умолчанию), иначе — целевая строка не содержит хотя бы одной строки из массива
     * @return bool
     */
    public static function notContains ($haystack, $needle, $forAll = true)
    {
        return !static::contains($haystack, $needle, !$forAll);
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
     * @param string        $number            количество для которого будет подбираться правильная форма слова
     * @param string[]|null $words_array       массив с 3-мя вариантами слова словами
     * @param boolean       $print_with_number если true, то в возвращаемая строка будет с переданным количеством и чрез неразрывный пробел форма слова
     * @param null|string   $main_part         неизменяемая часть в форме слова
     *
     * @return int|string возвращает одно из значений переданного массива, если массив не передан, то вернёт нужный index 0,1,2
     */
    public static function getCountWordForm (string $number , $words_array = null , $print_with_number = false , $main_part = null ) : string
    {
        // Если лова не указаны, то вернуть индексы:
        if ($words_array !== null)
            $words = [0, 1, 2];

        $text = "";
        // Если предан массив со значениями:
        if ($words_array)
        {
            dump($number);
            // Количество и неразрывный пробел:
            if ($print_with_number)
                $text = number_format(+$number, 0, "", Text::SPACE_FOUR_PER_EM) . Text::SPACE_NOBR;

            if ($main_part)
                $text = $text . $main_part;
        }

        $last_digit = substr($number, -1, 1);

        $second_last_digit = 0;
        // Если более 1 цифры, то взять предпоследнюю:
        if (strlen($number) > 1)
            $second_last_digit = substr($number, -2, 1);

        // 1, но не 11:
        if ($last_digit == 1 && $second_last_digit != 1)
            $word = $words_array[0];

        // 2,3,4, но не 1х:
        else if (in_array($last_digit, [2, 3, 4]) && $second_last_digit != 1)
            $word = $words_array[1];

        // Если не цело число:
        else if ( strpbrk($number, '.,') )
            $word = $words_array[1];

        // Всё остальное: 7, 11, 29, 0:
        else $word = $words_array[2];


        return $text ? ($text . $word) : $word;
    }
}
