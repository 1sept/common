<?php

declare(strict_types=1);

namespace Sept\Common;

/**
 * Класс функций, для работы с массивами
 *
 * @author Александр Васильев <a.vasilyev@1sept.ru>
 */
class Arrays
{
    /**
     * Индексирует матрицу значений (например, результат работы DBS::fetchAll) по указанным столбцам (именам ключей)
     *
     * @param  mixed  $keys          Массив имён ключей (или строка — один ключ)
     * @param  array  $source        Матрица значений
     * @param  bool   $removeLastKey Удалять ключ, если он остался один (по умолчанию: не удалять)
     * @param  string $mergeStrategy Стратегия объединения совпадающих значений ключей (см. self::merge())
     *
     * @return array Многомерный массив (проиндексированная матрица)
     *
     * @example:
     * $source = [
     *     0 => [
     *               'apple' => 3,
     *               'orange' => 7,
     *               'tomato' => 5
     *          ],
     *     1 => [
     *               'apple' => 2,
     *               'orange' => 14,
     *               'tomato' => 2
     *          ],
     *     2 => [
     *               'apple' => 100,
     *               'orange' => 4,
     *               'tomato' => 65
     *          ]
     * ];
     * $keys = 'apple'; // or $keys = [ 'apple' ];
     * $result = Arrays::index($keys, $source);
     *
     * $source = [
     *     2 => [
     *               'orange' => 14,
     *               'tomato' => 2
     *          ],
     *     3 => [
     *               'orange' => 7,
     *               'tomato' => 5
     *          ],
     *   100 => [
     *               'orange' => 4,
     *               'tomato' => 65
     *          ]
     * ];
     */
    public static function index ($keys, array $source, $removeLastKey = false, $mergeStrategy = 'exception')
    {
        if (!$keys or !$source) {
            return $source;
        }

        if (is_array($keys)) {
            $keys = array_values($keys);
        } else {
            $keys = [ $keys ];
        }

        $result = [];
        foreach ($source as $rowNum => $row) {
            $keysValues = [];
            foreach ($keys as $key) {
                if (!isset($row[$key])) {
                    throw new \UnexpectedValueException('В '. $rowNum .'-й строке отсутствует ключ «'.$key .'»');
                }
                $keysValues[] = $row[$key];
                unset($row[$key]);
            }

            if ($removeLastKey and count($row) == 1) {
                $row = array_pop($row);
            }

            $result = self::merge($result, self::makeNode($keysValues, $row), $mergeStrategy);
        }
        return $result;
    }


    /**
     * Объединяет два многомерных массива согласно выбранной стратегии
     *
     * @param array  $a             Массив A
     * @param array  $b             Массив B
     * @param string $mergeStrategy Стратегия объединения совпадающих значений ключей:
     *                              - exception — кинуть исключение (по умолчанию)
     *                              - leave_original — оставить оригинальное значение
     *                              - replace — заменить новым значанием
     *
     * @return array
     */
    public static function merge (array $a, array $b, $mergeStrategy = 'exception')
    {
        $mergeStrategyAvailable = ['exception', 'leave_original', 'replace'];

        if (!in_array($mergeStrategy, $mergeStrategyAvailable)) {
            throw new \InvalidArgumentException("Указана неизвестная стратегия объединения массивов, возможны такие варианты:" . implode(", ",$mergeStrategyAvailable) . "; передан" . Data::getTypeRu($mergeStrategy) . "!");
        }

        $result = $a;
        foreach ($b as $key => $rowB) {
            if (isset($a[$key])) {
                if (is_array($a[$key]) and is_array($b[$key])) {
                    try {
                        $result[$key] = self::merge($a[$key], $b[$key], $mergeStrategy);
                    } catch (\UnexpectedValueException $e) {
                        throw new \UnexpectedValueException('['. $key .']'. $e->getMessage());
                    }
                } else {
                    if ($mergeStrategy == 'leave_original') {
                        $result[$key] = $a[$key];
                    } else if ($mergeStrategy == 'replace') {
                        $result[$key] = $b[$key];
                    } else if ($mergeStrategy == 'exception') {
                        throw new \UnexpectedValueException('['. $key .'] ключи массивов не уникальны');
                    }
                }
            } else {
                $result[$key] = $rowB;
            }
        }
        return $result;
    }


    /**
     * Создаёт иерархический элемент массива
     * Пример: $keys = [2,4,5] и $value = 'cat' вернёт $result[2][4][5] = 'cat'
     *
     * @param array $keys  Массив значений ключей
     * @param mixed $value Значение нода
     * @param array $node  Опция, можно передать массив, который будет дополнен полученными значениями
     *
     * @return array|array[] Иерархический элемент массива
     */
    private static function makeNode (array $keys, $value, array $node = [])
    {
        $key = array_shift($keys);
        if (!$keys) {
            $node[$key] = $value;
        } else {
            $node[$key] = self::makeNode($keys, $value, $node);
        }
        return $node;
    }

    /**
     * Ищет долю (%) каждого из чисел массива и округляет их так, чтобы в сумме было ровно {$target}%
     * @param array $values Массив значений
     * @param integer $precision Количество знаков после запятой для округления (по умолчанию = 0, может принимать отрицательные значения)
     * @param integer $target Целевое значение в процентах (по умолчанию = 100%)
     * @return array Массив округлённых долей
     */
    public static function calculateRoundedPercentages (array $values, $precision = 0, $target = 100)
    {
        $summary = array_sum($values);
        if (!$summary) { // Если все значения равны 0
            return $values;
        }

        $base = $residue = [];
        $step = pow(10, $precision);

        foreach ($values as $key => $value) {
            $percent       = $value * $target * $step / $summary;
            $base[$key]    = floor($percent);         // Округляем до целого в меньшую сторону
            $residue[$key] = $percent - $base[$key];  // Находим разницу между {$target}% и суммой целых чисел
        }
        $notEnough = $target * $step - array_sum($base);

        if ($notEnough) {
            arsort($residue);
            $priority = array_keys($residue);

            for ($i=0; $i<$notEnough; $i++) {
                // Последовательно добавляем по 1 к каждому целому числу в порядке уменьшения их дробной части,
                // пока сумма целых чисел не бедет равна {$target}%
                $base[$priority[$i]]++;
            }
        }

        if ($precision) {
            foreach ($base as &$value) {
                $value /= $step;
            }
        }
        return $base;
    }

    /**
     * Возвращает массив результатов выдачи метода, вызванного у каждого объекта массива
     * @param object[]|string[] $pieces       Массив объектами или названиями классов
     * @param string            $methodName   Имя метода объекта или класса
     * @param array             $methodParams Список параметров вызываемого метода
     *
     * @return mixed[]|object[]|string[]|int[]
     */
    public static function getMethodsOutputs (array $pieces, $methodName, $methodParams = [])
    {
        $array = [];
        if (!empty($pieces)) {
            foreach ($pieces as $piece) {
                $array[] = call_user_func_array([ $piece, $methodName ], $methodParams);
            }
        }
        return $array;
    }

    /**
     * Склеивает в строку результат выдачи метода, вызванного у каждого объекта массива
     * @param object[]|string[] $pieces       Массив объектами или названиями классов
     * @param string            $methodName   Имя метода объекта или класса
     * @param string            $glue         Склеивающая строка
     * @param array             $methodParams Список параметров вызываемого метода
     *
     * @return string
     */
    public static function implodeMethodsOutputs (array $pieces, $methodName, $glue = '', $methodParams = [])
    {
        $strings = [];
        if (!empty($pieces)) {
            $strings = static::getMethodsOutputs($pieces, $methodName, $methodParams);
        }
        return implode($glue, $strings);
    }

    /**
     * Склеивает массив в одну строку
     *
     * @param array  $array Массив для склейки
     * @param string $start Начальная строка
     * @param string $glue Склеивающая строка
     * @param string $end Строка завершения
     *
     * @return string
     */
    public static function implodeToString (array $array, string $start = "«", string $glue = "», «", string $end = "»")
    {
        return $start . implode($glue, $array) . $end;
    }


    private static $arrayLevel = 0;
    private static $arrayNextLevel = null;
    private static $arrayException = true;

    /**
     * Склеить многомерный массив в строку
     *
     * @param string[]|string $glues      Символ или массив с символами для каждого уровня склеиваемых значений многомерных массивов
     * @param array|array[]   $array      Массив значения которого нужно объединить в строку
     * @param string[]        $levelsGlue Массив с двумя значениями, для обозначения начала и конца вложенного уровня значений массива, третьим значением можно передать массив для обозначений последующих вложенных уровней массивов
     * @param null|boolean    $exception  Метка для указания, выкидывать ли при ошибках
     * @param boolean         $withKeys   И добавлять неиндексные ключи
     *
     * @return string
     */
    public static function multiImplode($glues, array $array, array $levelsGlue = ["",""], $exception = null, $withKeys = false){

        if ( $exception !== null )
            self::$arrayException = $exception;

        /**
         * Кол-во элементов данного уровня массива для проверки,
         * добавлять ли склеивающие символы
         */
        $c = count($array);

        /**
         * Подсчёт уровней массива,
         * если массив не сохранён в $arrayNextLevel,
         * то обнулить считалку уровней
         */
        if ( $array !== self::$arrayNextLevel )
            self::$arrayLevel = 1;
        else
            self::$arrayLevel++;

        $g = $glues;
        /**
         * Проверить символы для склеивания элементов
         */
        if ( is_array( $glues ) )
        {
            $g = array_shift($glues);

            /**
             * Если в массиве уже нет значений
             */
            if ( is_null($g) && self::$arrayException )
                throw new \UnexpectedValueException("Не указаны символы для склеивания элементов " . self::$arrayLevel . " уровня массива!");
        }
        /**
         * Если не строка и не NULL и не было указана отмена предупреждений,
         * то выкинуть предупреждение
         */
        else if ( !is_string($glues) && !is_null($glues) && self::$arrayException )
            throw new \UnexpectedValueException("Первым аргументов метода должны быть символы склеивания элементов, строка или массив со строками для каждого уровня многомерного массива, передан" . Data::getTypeRu($glues) . "!");

        /**
         * Если нет значения для склеивания
         */
        if ( is_null($g) )
            $g = ",";

        $out = "";
        $i = 0;
        foreach ($array as $indexOrKey => $val){
            if (is_array($val)){
                if (is_string($indexOrKey) && $withKeys)
                    $out .= $indexOrKey;
                $out .= $levelsGlue[0];

                /**
                 * Если указано третье значение,
                 * то передать его для обозначения следующих подуровней
                 */
                if ( !empty($levelsGlue[2]) )
                    $nextLvlGlue = $levelsGlue[2];
                else
                    $nextLvlGlue = $levelsGlue;

                $out .= self::multiImplode($glues,$val,$nextLvlGlue,$exception,$withKeys);
                $out .= $levelsGlue[1];
            } else {
                $out .= (string)$val;
            }

            /**
             * Если данный проход не последний,
             * то добавить склеивающие символы
             */
            if (++$i<$c){
                $out .= $g;
            }
        }

        /**
         * Вернуть полученную строку
         */
        return $out;
    }

    /**
     * Перебрать массив, проверяя, все ли элементы являются целыми
     *
     * @param array|int[] $numbers           Массив с номерами
     * @param boolean     $convertToIntegers Преобразовывать все в целые
     * @param boolean     $withOutDuplicates Выкидывать ошибку, если номер уже был в перебираемых значениях
     * @param int         $sort              Значение -1 – в обратном порядке, значение 0 – не сортировать, значение 1 – отсортировать по порядку
     *
     * @return int[]
     */
    public static function prepareElementsAsIntegers (array $numbers, bool $convertToIntegers = true, bool $withOutDuplicates = false, int $sort = 0) : array
    {
        $allNumbers = [];
        // Перебрать массив, чтобы были одни целые, чтоб потом отсортировать, если указано:
        foreach ($numbers as $indexOrKey => $number)
        {
            Text::isInteger($number, "Неверное значение при проходе по массиву чисел для преобразования в массивы интервалов у элемента «{$indexOrKey}»!");

            if ($withOutDuplicates && in_array($number, $allNumbers))
                throw new \RuntimeException("В массиве перебираемых значений уже есть значение «{$number}»!");

            // Преобразовать в целое:
            if ($convertToIntegers)
                $numbers[$indexOrKey] = (int) $number;

            // Запомнить целое:
            $allNumbers[] = (int) $number;
        }

        // Если указано, что нужно отсортировать:
        if ($sort == 1)
            sort($numbers);
        elseif ($sort == -1)
            rsort($numbers);

        return $numbers;
    }

    /**
     * Подготовить массив номеров и вернуть в виде строки с интервалами
     *
     * @param array|int[] $numbers           Массив с номерами
     * @param int         $sort              Значение -1 – в обратном порядке, значение 0 – не сортировать, значение 1 – отсортировать по порядку
     * @param boolean     $withOutDuplicates Выкидывать ошибку, если номер уже был в перебираемых значениях
     * @param string  $intervalSymbol    Символ для установки между первым и последним значением интервала
     * @param string  $intervalsSplitter Символ для установки между интервалами
     *
     * @return string
     */
    public static function prepareNumbersAndGetAsIntervalsString (array $numbers, int $sort = 0, bool $withOutDuplicates = false, $intervalSymbol = "-", $intervalsSplitter = ", ") : string
    {
        return self::getNumbersIntervalsArrayAsString( self::getNumbersAsArraysOfIntervals($numbers, $sort, $withOutDuplicates), $intervalSymbol, $intervalsSplitter);
    }

    /**
     * Получить номера виде массива с массивами интервалов
     * @see Arrays::getNumbersIntervalsArrayAsString()
     *
     * @param array|int[] $numbers           Массив с номерами
     * @param int         $sort              Значение -1 – в обратном порядке, значение 0 – не сортировать, значение 1 – отсортировать по порядку
     * @param boolean     $withOutDuplicates Выкидывать ошибку, если номер уже был в перебираемых значениях
     *
     * @return int[][]
     */
    public static function getNumbersAsArraysOfIntervals (array $numbers, int $sort = 0, bool $withOutDuplicates = false) : array
    {
        $numbers = self::prepareElementsAsIntegers($numbers, true, $withOutDuplicates, $sort);

        $intervals = [];
        $currentInterval = [];
        $previousNumber = 0;
        $toGetNext = null;
        $next = null;
        foreach ($numbers as $indexOrKey => $number)
        {
            // Если есть предыдущее значение:
            // Если ещё не указано, как получить следующее значение:
            if ($previousNumber && !$toGetNext)
            {
                $toGetNext = 1;
                $next = $previousNumber + $toGetNext;
                // Если +1 один не сработал, то проверить -1:
                if ($number !== $next)
                {
                    $toGetNext = -1;
                    $next = $previousNumber + $toGetNext;
                    if ($number !== $next)
                        $next = null;
                }
            }

            // Если предыдущее значение не равно данному при прибавлении или вычитании единицы:
            if ($toGetNext && $number !== ($previousNumber + $toGetNext))
            {
                // Текущий массив номеров сохранить в массив интервалов:
                $intervals[] = $currentInterval;
                // Очистить переменную для нового интервала:
                $currentInterval = [];
                // Значение для получения следующего номера:
                $toGetNext = null;
            }

            // Добавить в интервал значение и сохранить его для следующего прохода:
            $currentInterval[] = $previousNumber = $number;
        }

        // Записать последний интервал:
        if ($currentInterval)
            $intervals[] = $currentInterval;

        // Если нет значений, то вернуть пустой массив:
        if (!$intervals)
            return [];

        // Пересортировать интервалы:
        if ($sort)
            usort($intervals ,function ($a ,$b) use ($sort) {
                if ($a[0] == $b[0])
                    return 0;

                // В обратном порядке:
                if ($sort == -1)
                    return ($a[0] < $b[0]) ? 1 : -1;

                return ($a[0] < $b[0]) ? -1 : 1;
            });

        return $intervals;
    }

    /**
     * Получить в виде строки интервалы номеров
     * @see Arrays::getNumbersAsArraysOfIntervals()
     *
     * @param int[][] $numbersIntervals  Массив с массивами цифр
     * @param string  $intervalSymbol    Символ для установки между первым и последним значением интервала
     * @param string  $intervalsSplitter Символ для установки между интервалами
     *
     * @return string
     */
    public static function getNumbersIntervalsArrayAsString (array $numbersIntervals, $intervalSymbol = "-", $intervalsSplitter = ", ") : string
    {
        if (!$numbersIntervals)
            return $intervalString = "[не указаны]";

        $intervalsString = "";
        foreach ($numbersIntervals as $indexOrKey => $interval)
        {
            if (!$interval || !is_array($interval))
                throw new \RuntimeException("Неверное значение при проходе по массиву интервалов у интервала «{$indexOrKey}»! Ожидался массив с массивами! Передан" . Data::getTypeRu($interval) . "!");

            // Первый элемент в интервале добавить в строку:
            $intervalsString = $intervalsString . $interval[0];

            // Если два элемента, то добавить через запятую:
            if (count($interval) == 2)
                $intervalsString = $intervalsString . $intervalsSplitter. $interval[1];
            // Если более 2-х, то вывести с символом интервала:
            elseif(count($interval) >= 3)
            {
                $last = array_values(array_slice($interval, -1))[0];
                $intervalsString = $intervalsString . $intervalSymbol . $last;
            }

            // Закрыть интервал символом разделения интервалов:
            $intervalsString = $intervalsString . $intervalsSplitter;
        }

        // Убрать лишний символом разделения интервалов в конце строки:
        $intervalsString = trim($intervalsString, $intervalsSplitter);

        return $intervalsString;
    }

    /**
     * `Преобразовать массив в объект
     * @param array $array
     * @return \stdClass
     */
    static function convertToObject (array $array)
    {
        $object = new \stdClass();
        foreach($array as $k => $v)
        {
            $object->{$k} = is_array($v) ? self::convertToObject($v) : $v;
        }
        return $object;
    }

}
