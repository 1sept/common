<?php

declare(strict_types=1);

namespace Sept\Common;

use Parus\Exception\FileErrorException;
use Parus\Exception\FileNotFoundException;
use Parus\Exception\LengthException;
use Parus\Exception\OutOfBoundsException;
use Parus\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Расширение базового класса работы с файлами
 * @see \SplFileObject, \SplFileInfo
 *
 * @author Александр Васильев <a.vasilyev@1sept.ru>
 */
class File extends \SplFileObject
{
    /**
     * Максимальная длина пути до файла
     * @var int
     */
    const PATH_MAX_LENGTH = 1024;

    /**
     * Относительный путь (без имени файла)
     * @var string
     */
    private $relativePath;
    /**
     * Относительный путь (с именем файла)
     * @var string
     */
    private $relativePathname;

    /**
     * File constructor.
     *
     * @param string $filename
     * @param string $open_mode
     * @param bool   $use_include_path
     * @param null   $context
     *
     * @see https://www.php.net/manual/ru/function.fopen.php
     */
    public function __construct ($filename, $open_mode = "r", $use_include_path = false, $context = null)
    {
        parent::__construct($filename, $open_mode, $use_include_path, $context);

        if ($filename instanceOf \Symfony\Component\Finder\SplFileInfo) {
            $this->setRelativePath($filename->getRelativePath());
            $this->setRelativePathname($filename->getRelativePathname());
        }
    }

    public function getRelativePath ()
    {
        return $this->relativePath;
    }

    private function setRelativePath ($relativePath)
    {
        $this->relativePath = $relativePath;
    }

    public function getRelativePathname ()
    {
        return $this->relativePathname;
    }

    private function setRelativePathname ($relativePathname)
    {
        $this->relativePathname = $relativePathname;
    }

    /**
     * Возвращает содержимое файла
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getContents ()
    {
        $level = error_reporting(0);
        $content = file_get_contents($this->getPathname());
        error_reporting($level);
        if (false === $content) {
            $error = error_get_last();
            throw new \RuntimeException($error['message']);
        }

        return $content;
    }


    /**
     * Проверить длину пути до файла
     *
     * @param string $filePath Путь в системе к объекту
     * @param bool   $throwException
     *
     * @return string возвращает переданное значение
     *
     * @throws \Parus\Exception\LengthException
     */
    public static function checkPathLength ($filePath ,$throwException = true)
    {
        $errText = "";
        if (is_string($throwException))
            $errText = Text::prepareSingleLine($throwException) . " ";

        $filePath = Text::prepareSingleLine($filePath ,true ,$errText . "Неверно указано значения аргумента пути до файла!");

        if (strlen($filePath) > File::PATH_MAX_LENGTH)
        {
            if ($throwException)
                throw new LengthException(
                      $errText
                    . "Передано значение с длиной пути: "
                    . Data::countWordForm(strlen($filePath) ,["символ","символа","символов"] ,true) . "!"
                    . " "
                    . "Максимальная длина пути " . Data::countWordForm(File::PATH_MAX_LENGTH ,["символ","символа","символов"] ,true) . "!");

            return false;
        }

        return $filePath;
    }

    /**
     * Размер папки или файла
     * @param string $path Путь в системе к объекту
     * @return int Размер в байтах
     */
    public static function getSummarySize ($path)
    {
        if (!is_dir($path)) {
            return filesize($path);
        }

        $size = 0;
        foreach (scandir($path) as $file) {
            if ($file == '.' or $file == '..') {
                continue;
            }
            $size += filesize($path.'/'.$file);
        }
        return $size;
    }

    /**
     * Размер файла в человеко-понятном виде
     * @see static::$sizeUnits и static::humanSize()
     *
     * @param string $numeralSystem Система счисления (по умолчанию: decimal)
     * @param string $lang Язык (по умолчанию: ru)
     * @param int    $precision Кол-во знаков после запятой (по умолчанию: 1)
     * @param string $forceUnit Принудить использовать определённую единицу измерения в выбранной системе счисления (порядковый номер начиная с 1 или название единицы на любом языке)
     *
     * @return string
     *
     * @throws \Parus\Exception\FileErrorException
     */
    public function getHumanSize ($numeralSystem = 'decimal', $lang = 'ru', $precision = 1, $forceUnit = null)
    {
        return self::humanSize($this, $numeralSystem, $lang, $precision, $forceUnit);
    }


    /**
     * Значения единиц измерения объёма цифровых данных
     * @var array[система_счисления][язык]
     */
    static $sizeUnits = [
        'decimal' => [ // ГОСТ 8.417-2002, IEC 60027-2, IEEE, EU, ISO, NIST
        //    Номер:     1,    2,    3,    4,     5,     6,     7,     8,     9  (для $forceUnit)
        //    Степени:  10^0, 10^3, 10^6, 10^9, 10^12, 10^15, 10^18, 10^21, 10^24
            'ru' => [  'Б', 'кБ', 'МБ', 'ГБ',  'ТБ',  'ПБ',  'ЭБ',  'ЗБ',  'ЙБ' ],
            'en' => [  'B', 'kB', 'MB', 'GB',  'TB',  'PB',  'EB',  'ZB',  'YB' ],
            'zh' => [ '千字节', '兆字节', '吉字节', '太字节', '拍字节', '艾字节', '泽字节', '尧字节' ] // Пример китайских единиц
        ],

        'binary' => [ // IEC 60027-2
        //    Номер:    1,     2,     3,     4,     5,     6,     7,     8,     9  (для $forceUnit)
        //    Степени:  2^0,  2^10,  2^20,  2^30,  2^40,  2^50,  2^60,  2^70,  2^80
            'ru' => [ 'Б', 'КиБ', 'МиБ', 'ГиБ', 'ТиБ', 'ПиБ', 'ЭиБ', 'ЗиБ', 'ЙиБ' ],
            'en' => [ 'B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB' ],
            'zh' => [ 'B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB' ]
        ],

        'binary-win' => [ // @deprecated: В Windows ошибочно пишется без «и» («i»)
        //    Номер:    1,    2,    3,    4,    5,    6,    7,    8,    9  (для $forceUnit)
        //    Степени:  2^0, 2^10, 2^20, 2^30, 2^40, 2^50, 2^60, 2^70, 2^80
            'ru' => [ 'Б', 'КБ', 'МБ', 'ГБ', 'ТБ', 'ПБ', 'ЭБ', 'ЗБ', 'ЙБ' ],
            'en' => [ 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' ],
            'zh' => [ 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' ]

        ]
    ];


    /**
     * Размер файла в удобном (человекопонятном) виде
     * @example 123,4 МБ, 102 Б, 502.72 GiB
     *
     * @param integer|\SplFileInfo $size          Размер файла в байтах или объект \SplFileInfo (или его потомок)
     * @param string               $numeralSystem Система счисления (по умолчанию: decimal)
     * @param string               $lang          Язык (по умолчанию: ru)
     * @param int|number           $precision     Кол-во знаков после запятой (по умолчанию: 1)
     * @param string               $forceUnit     Принудить использовать определённую единицу измерения в выбранной системе счисления (порядковый номер начиная с 1 или название единицы на любом языке)
     *
     * @return string
     * @throws \Parus\Exception\FileErrorException
     */
    public static function humanSize ($size, $numeralSystem = 'decimal', $lang = 'ru', $precision = 1, $forceUnit = null)
    {
        if ($size instanceOf \SplFileInfo) {
            $size = $size->getSize();
        }

        $forceUnitNum = null; // Порядковый номер единицы исчисления [1–9]
        if ($forceUnit !== null) {
            if (is_numeric($forceUnit) and isset(self::$sizeUnits[$numeralSystem][$lang][$forceUnit-1])) {
                $forceUnitNum = $forceUnit;

            } else if (is_string($forceUnit)) {
                foreach (self::$sizeUnits[$numeralSystem] as $langID => $units) {
                    $index = array_search($forceUnit, $units);
                    if ($index !== false) {
                        $forceUnitNum = $index + 1;
                        break;
                    }
                }
            }

            if (!$forceUnitNum) {
                throw new FileErrorException('Принудительная единица измерения не найдена');
            }
        }

        $unitNum = 1; // Начальная единица измерения перед делением
        $divider = ($numeralSystem == 'decimal')? 1000 : 1024; // Делитель

        while ($forceUnitNum === null ? $size >= $divider : --$forceUnitNum) {
            $size /= $divider;
            $unitNum++;
        }

        $decimalPoint = ',';
        $thousandsSeparator = ($size >= 10000)? ' ' : '';

        // Язык для чисел
        switch ($lang) {
            case 'ru':
                $decimalPoint = ',';
                $thousandsSeparator = ($size >= 10000)? ' ' : ''; // THIN SPACE, U+2009 (Unicode), E2 80 89 (UTF-8)
                                                                  // Разделитель не применяется для 4-х значных чисел
                break;

            case 'en':
            case 'zh': // Не нашёл подтверждения
                $decimalPoint = '.';
                $thousandsSeparator = ($size >= 10000)? ',' : '';
                break;
        }

        // Не выводить нули если округлённое число — целое
        $precision = strpos(round($size, $precision), $decimalPoint)? $precision : 0;

        return number_format($size, $precision, $decimalPoint, $thousandsSeparator) .' '. self::$sizeUnits[$numeralSystem][$lang][$unitNum-1];
    }


    /**
     * Сохранить контент в файл, если он изменился
     *
     * @param string          $filename Путь до файла
     * @param string          $data     Записываемые данные
     * @param int             $flags    Константы file_put_contents: FILE_USE_INCLUDE_PATH, FILE_APPEND, LOCK_EX, FILE_TEXT, FILE_BINARY
     * @param null|resource   $context  Контекст потока созданный через stream_context_create()
     *
     * @return bool Файл обновился или нет (+ не обновился из-за ошибки)
     *
     * @see http://php.net/manual/ru/function.file-put-contents.php — file_put_contents()
     * @see http://php.net/manual/ru/function.stream-context-create.php — stream_context_create()
     */
    public static function putContentsIfModified ($filename, $data, $flags = 0, $context = null)
    {
        // Создаём/обновляем файл только если он не существует или изменился
        if (!self::fileStringIdentical($filename, $data)) {
            $result = file_put_contents($filename, $data, $flags, $context);
            return (bool) ($result !== false);
        } else {
            return false; // Файл не изменился
        }
    }


    /**
     * Сжать файл с помощью GZip
     *
     * @param string      $fileName       Название/путь файла
     * @param null|string $targetFileName Название/путь для сохранения
     * @param int         $level          Уровень сжатия gzip, от 0 до 9
     *
     * @return bool Получилось ли сжать данные
     */
    public static function compressGZipFile ($fileName, $targetFileName = null, $level = 9)
    {
        $data = file_get_contents($fileName);
        if (!$targetFileName) {
            $fileName .= '.gz';
        }
        return self::compressGZipString($data, $fileName, $level);
    }

    /**
     * Сжать строку в файл с помощью GZip
     *
     * @param string      $data     Строка для сжатия
     * @param string      $fileName Название/путь файла
     * @param int         $level    Уровень сжатия gzip, от 0 до 9
     *
     * @return bool Получилось ли сжать данные
     */
    public static function compressGZipString ($data, $fileName, $level = 9)
    {
        return (bool) (file_put_contents($fileName, gzencode($data, $level), LOCK_EX));
    }

    /**
     * Сравнивает файлы на идентичность (побитовое сравнение — бинарно безопасно)
     * @param string $filenameA
     * @param string $filenameB
     * @return TRUE if files are the same, FALSE otherwise
     */
    public static function filesIdentical ($filenameA, $filenameB)
    {
        if (!file_exists($filenameA) or !file_exists($filenameB) or
            filetype($filenameA) !== filetype($filenameB) or
            filesize($filenameA) !== filesize($filenameB) or
            !$filePointerA = fopen($filenameA, 'rb')
        ) {
            return false;
        }

        if (!$filePointerB = fopen($filenameB, 'rb')) {
            fclose($filePointerA);
            return false;
        }

        $readLength = 4096; // Размер блока чтения файла (4 КБ)
        $same = true;
        while (!feof($filePointerA) and !feof($filePointerB)) {
            if (fread($filePointerA, $readLength) !== fread($filePointerB, $readLength)) {
                $same = false;
                break;
            }
        }

        if (feof($filePointerA) !== feof($filePointerB)) {
            $same = false;
        }

        fclose($filePointerA);
        fclose($filePointerB);

        return $same;
    }

    /**
     * Сравнивает файл и строку на идентичность (бинарно небезопасно)
     * @param string $filename
     * @param string $string
     * @return TRUE if files and string are the same, FALSE otherwise
     */
    public static function fileStringIdentical ($filename, $string)
    {
        // Попробовать SplTempFileObject

        return (bool) (file_exists($filename) and
            filetype($filename) == 'file' and
            filesize($filename) == strlen($string) and // strlen — бинарно небезопасно
            // md5_file($filename) == md5($string)
            strcmp(file_get_contents($filename), $string) == 0 // бинарно безопасно
        );
    }


    /**
     * Получить Временную метку последних изменений проверив все файлы директории
     *
     * @param string $dirName
     *
     * @return null|\Parus\DateTime
     */
    public
    static
    function getLastModifiedForFilesInDir ($dirName)
    {
        $dirName = static::checkPathLength($dirName ,"Неверное значение аргумента пути до директории для получения времени последних изменений!");

        if (!is_dir($dirName))
            throw new FileNotFoundException("Переданное значение не является путём до директории, передан" . Data::getTypeRu($dirName) . "!");

        $lastModified = null;

        // $filesPaths = glob($dirName . "*" ,GLOB_MARK);
        // foreach ($filesPaths as $filePath)
        // {
        //     if (is_dir($filePath))
        //         $fileLastModifiedDateTime = static::getLastModifiedForFilesInDir($filePath);
        //     else
        //         $fileLastModifiedDateTime = (new DateTime())->setTimestamp(filemtime($filePath));

        //     if (!$lastModified || $fileLastModifiedDateTime->compareWith($lastModified) > 0)
        //         $lastModified = $fileLastModifiedDateTime;
        // }

        /**
         * https://gist.github.com/tureki/9109489
         */
        $cls_rii =  new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator( $dirName ),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        $ary_files = [];
        foreach ($cls_rii as $str_fullfilename => $cls_spl)
        {
            if($cls_spl->isFile())
                $ary_files[] = $str_fullfilename;
        }

        $ary_files = array_combine(
            $ary_files,
            array_map("filemtime" ,$ary_files)
        );

        arsort($ary_files);
        $str_latest_file = key($ary_files);

        $lastModified = (new DateTime())->setTimestamp($ary_files[$str_latest_file]);

        return $lastModified;
    }


    /**
     * Получить Временную метку последних изменений из файла composer.lock
     *
     * @param null|string $path
     *
     * @return null|\Parus\DateTime
     */
    public
    static
    function getLastModifiedForComposerLock ($path = null)
    {
        if (!$path)
            $path = Settings::get("projectFolder") . "/composer.lock";

        if (!file_exists(static::checkPathLength($path ,"Неверное значение аргумента пути до директории для получения времени последних изменений!")))
            throw new FileNotFoundException("Не удалось найти файл composer.lock («{$path}»)!");

        $json = file_get_contents($path);
        if (!$json)
            throw new RuntimeException("Не удалось получить содержание файла composer.lock («{$path}»)!");

        $data = json_decode($json ,true);
        if (!$data)
            throw new RuntimeException("Не удалось преобразовать в массив JSON содержание файла composer.lock («{$path}»)!");

        $lastModified = null;
        foreach ($data["packages"] as $libData)
        {
            if (empty($libData["time"]))
                continue;

            $libLastModifiedDateTime = new DateTime($libData["time"]);

            if (!$lastModified || $libLastModifiedDateTime->compareWith($lastModified) > 0)
                $lastModified = $libLastModifiedDateTime;
        }

        return $lastModified;
    }


    /**
     * Получить MIME типа файла
     *
     * @return string
     */
    public
    function getMIME ()
    {
        return static::getMIMEForPath($this->getPathname());
    }


    /**
     * Получить MIME типа по пути файла
     *
     * @param null|string $filePath
     *
     * @return string
     */
    public static
    function getMIMEForPath ($filePath)
    {
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($fileInfo,$filePath);
        finfo_close($fileInfo);

        return $mime;
    }


    /**
     * Получить написание расширения для файла из его MIME типа
     *
     * @param null|string    $mime
     * @param boolean|string $throwException
     *
     * @return string|string[] Значение или ассоциативный массив со значениями
     *
     * @uses \Parus\File::loadMediaTypesFromApacheSVN()
     */
    public static
    function getExtensionByMIME ($mime = null ,$throwException = true)
    {
        $extensionsFilePath = __DIR__ . "/File/type/";

        $mime_ext = parse_ini_file($extensionsFilePath . "mime-extensions.ini");

        if ($mime === null)
            return $mime_ext;

        if (!array_key_exists($mime ,$mime_ext))
        {
            if (!$throwException)
                return false;

            $errText = "";
            if (is_string($throwException))
                $errText = Text::prepareSingleLine($throwException) . " ";

            throw new OutOfBoundsException($errText . "Не указано значение для расширения {$mime}!");
        }

        return $mime_ext[$mime][0];
    }


    /**
     * Получить написание MIME типов для файла по расширению
     *
     * @param null|string    $ext
     * @param int            $index Какую позицию если несколько значений
     * @param boolean|string $throwException
     *
     * @return string|\string[] Значение или ассоциативный массив со значениями
     *
     * @uses \Parus\File::loadMediaTypesFromApacheSVN()
     */
    public static
    function getMIMEByExtension ($ext = null ,$index = 0 ,$throwException = true)
    {
        $ext_mime = parse_ini_file(__DIR__ . "/File/type/extensions-mime.ini");

        if ($ext === null)
            return $ext_mime;

        if (!array_key_exists($ext ,$ext_mime))
        {
            if (!$throwException)
                return false;

            $errText = "";
            if (is_string($throwException))
                $errText = Text::prepareSingleLine($throwException) . " ";

            throw new OutOfBoundsException($errText . "Не указано значение для MIME типа {$ext}!");
        }

        if (is_array($ext_mime[$ext]))
            return $ext_mime[$ext][$index];

        return $ext_mime[$ext];
    }


    /**
     * Подгрузить данные из Apache SVN
     *
     * @uses cURL
     *
     * @see http://svn.apache.org/viewvc/httpd/httpd/trunk/docs/conf/mime.types?view=markup
     * @see SVN https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types
     */
    public static
    function loadMediaTypesFromApacheSVN ()
    {
        $uri = "https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types";

        // // Не всегда можно писать в основную директорию
        // $extensionsFilePath = __DIR__ . "/File/type/";
        $extensionsFilePath = Settings::get("temporaryFolder") . "/classes/files/";

        if (!is_dir($extensionsFilePath))
            mkdir($extensionsFilePath, 0750, true);

        $fileFullPath = $extensionsFilePath . "media-types.txt";

        if (!function_exists('curl_version'))
            throw new RuntimeException("Нет метода cURL, не удастся загрузить содержимое URI «{$uri}»!");

        $curl = curl_init($uri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        $text = curl_exec($curl);
        curl_close($curl);

        if (!$text)
            throw new FileNotFoundException("Не удалось загрузить содержание по URI «{$uri}»!");

        file_put_contents($fileFullPath ,$text);

        $file = new static($fileFullPath);

        /**
         * @see https://cweiske.de/tagebuch/php-mimetype.htm
         */
        $mimes = ["application/x-php" => "php"];
        $extensions = ["php" => "application/x-php"];

        $resFileMIME = new static($extensionsFilePath . "mime-extensions.ini" ,"w");
        $resFileExt = new static($extensionsFilePath . "extensions-mime.ini" ,"w");

        foreach($file as $line)
        {
            if (!$line)
                continue;

            // Пропустить комментарии
            if (strpos($line ,"#") === 0)
                continue;

            preg_match('/(?<mime>[^\s]+)\s+(?<extensions>[^\r\n]+)/' ,$line ,$matches);

            if (empty($matches["mime"]))
                throw new RuntimeException("Нет данных для строки «{$line}»!");

            if (empty($matches["extensions"]))
                throw new RuntimeException("Нет расширений файлов для «{$matches["mime"]}»!");

            $ext = explode(" " ,$matches["extensions"]);

            $mimes[$matches["mime"]] = $ext;

            foreach($ext as $extension)
            {
                $resFileMIME->fwrite("{$matches["mime"]}[] = \"{$extension}\"\n");
                $resFileExt->fwrite("{$extension} = \"{$matches["mime"]}\"\n");
                $extensions[$extension] = $matches["mime"];
            }
        }

        $resFileMIME->fwrite("application/x-php[] = \"php\"\n");
        $resFileExt->fwrite("php = \"application/x-php\"\n");

        return $extensionsFilePath;
    }

    /**
     * Для получения Symfony ответа с данным файлом
     * @param string|null $fileNameToResponse
     * @param string|null $fileNameASCIIToResponse Только символы ASCII, если не указано, то будет преобразован аргумент «$fileNameToResponse»
     *
     * @return BinaryFileResponse
     */
    public function getSymfonyResponse (?string $fileNameToResponse = null, ?string $fileNameASCIIToResponse = null) : BinaryFileResponse
    {
        if (!$fileNameToResponse)
            $fileNameToResponse = $this->getFilename();

        $response = BinaryFileResponse::create($this);
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $fileNameToResponse, strval($fileNameASCIIToResponse));
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', $this->getMIME());
        return $response;
    }
}
