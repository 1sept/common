<?php

declare(strict_types=1);

namespace App\Common;

use Parus\Exception\Error;

/**
 * Шифрование
 *
 * @author Тимофей Соловейчик <timofey@1sept.ru>
 */
class Crypt
{
    /**
     * @see https://www.php.net/manual/ru/function.openssl-get-cipher-methods.php
     * @see openssl_get_cipher_methods
     * @var string Используемый шифр
     */
    const DEFAULT_CIPHER = "AES-256-XTS";

    /**
     * 16/24/32 бита / 32
     * @var string Строка соли/ключа
     */
    const STRING_GOT_ASCII = "adgmkwrкыпыкпmgskkjmomhawr]po,gvcmbnfk5432598ыпаhkaiphj";

    /**
     * Получить длину какого-то вектора для шифровки/расшифровки
     * @var integer Initialization Vector Size
     */
    private static $ivSize;

    /**
     * Получить какой-то вектор для шифровки/расшифровки
     * @var string Initialization Vector
     */
    private static $iv;

    /**
     * @var string
     */
    private static $cipher = self::DEFAULT_CIPHER;

    /**
     * Получить названия сильных шифров
     *
     * @param bool $aliases вернуть список псевдонимов
     *
     * @return string
     */
    public static function getStrongCipher ($aliases = false)
    {
        $ciphers = openssl_get_cipher_methods();

        $ciphers_and_aliases = openssl_get_cipher_methods(true);
        $cipher_aliases      = array_diff($ciphers_and_aliases, $ciphers);

        $remove = [
            //Режим ECB следует избегать
             "ecb"

            //По крайней мере, в начале августа 2016 года следующие методы объявлены слабыми:
            ,"des"
            ,"rc2"
            ,"rc4"
            ,"md5"
        ];

        // Убрать слабые:
        foreach ($remove as $string)
        {
            $ciphers = array_filter($ciphers, function($cipher) use ($string) {return stripos($cipher, $string) === FALSE;});
            $ciphers_and_aliases = array_filter($ciphers_and_aliases, function($cipher) use ($string) {return stripos($cipher, $string) === FALSE;});
        }

        // Убрать повторения:
        foreach ($ciphers_and_aliases as $cipher)
        {
            if ($index = array_search(Text::lowercaseAllCharacters($cipher), $ciphers))
                unset($ciphers[$index]);

            if ($index = array_search(Text::lowercaseAllCharacters($cipher), $cipher_aliases))
                unset($cipher_aliases[$index]);
        }

        if (!$aliases)
            return $ciphers;
        else
            return $cipher_aliases;
    }

    /**
     * Получить установленный шифр
     *
     * @return string
     */
    public static function getCipher ()
    {
        return self::$cipher;
    }

    /**
     * Получить ключ для шифровки/расшифровки
     *
     * @param string $cipher
     *
     * @return string
     */
    public static function setCipher (string $cipher)
    {
        $ciphers = openssl_get_cipher_methods();

        if (!in_array($cipher, $ciphers))
            throw new Error("Не поддерживаемый шрифт «{$cipher}»! Есть так: «" . join("», «", $ciphers) . "»!");

        self::$cipher = $cipher;
    }

    /**
     * Получить ключ для шифровки/расшифровки
     *
     * @param null $key
     *
     * @return string
     */
    private static function getKey ($key = null)
    {
        if (!$key) {
            $key = self::STRING_GOT_ASCII;
        }

        $binData = "";
        $len     = strlen($key);
        for ($i = 0; $i < $len; $i += 2) {
            $binData .= chr(hexdec(substr($key, $i, 2)));
        }

        return $binData;
    }

    /**
     * Initialization Vector Size
     *
     * Получить длину какого-то вектора для шифровки/расшифровки
     *
     * @param string|null $cipher
     *
     * @return int
     */
    private static function getIvSize ($cipher = null)
    {
        if (empty($cipher))
            $cipher = self::$cipher;

        if ($cipher || empty(self::$ivSize))
            self::$ivSize = openssl_cipher_iv_length($cipher);

        return self::$ivSize;
    }


    /**
     * Initialization Vector
     * Получить какой-то вектор для шифровки/расшифровки
     *
     * @param string|null $cipher
     *
     * @return string
     */
    private static function getIV ($cipher = null)
    {
        if (empty(self::$iv))
            self::$iv = openssl_random_pseudo_bytes(self::getIvSize($cipher));

        return self::$iv;
    }


    /**
     * Получить зашифрованное значение
     *
     * @param string|int|float      $value
     * @param null|string|int|float $key
     * @param null|string           $cipher
     *
     * @return string Зашифрованное значение
     */
    public static function encrypt ($value, $key = null, $cipher = null)
    {
        if (empty($cipher))
            $cipher = self::$cipher;

        $cipherText = openssl_encrypt($value, $cipher, self::getKey($key), 0, self::getIV($cipher));

        return $cipherText;
    }


    /**
     * Получить зашифрованное значение
     *
     * @param string|int|float      $value
     * @param null|string|int|float $key
     * @param null|string           $cipher
     *
     * @return string Зашифрованное значение
     */
    public static function encryptWithBase64 ($value, $key = null, $cipher = null)
    {
        $cipherText = self::encrypt($value, $key, $cipher);
        $cipherText = self::getIV() . $cipherText;
        $cipherTextBase64 = base64_encode($cipherText);

        return $cipherTextBase64;
    }


    /**
     * Расшифровать переданное значение
     *
     * @param string                $cipherText
     * @param null|string|int|float $key
     * @param null|string           $iv
     * @param null|string           $cipher
     *
     * @return false|string Расшифрованное значение
     */
    public static function decrypt ($cipherText, $key = null, $iv = null, $cipher = null)
    {
        if (empty($cipher))
            $cipher = self::$cipher;

        if (!$iv)
            $iv = self::getIV();

        $value = openssl_decrypt($cipherText, $cipher, self::getKey($key), 0, $iv);

        return $value;
    }


    /**
     * Расшифровать переданное значение
     *
     * @param string                $encryptedValueBase64
     * @param null|string|int|float $key
     * @param null|string           $cipher
     *
     * @return false|string Расшифрованное значение
     */
    public static function decryptBase64 ($encryptedValueBase64, $key = null, $cipher = null)
    {
        $encryptedValue = base64_decode($encryptedValueBase64);
        // Вектор:
        $iv_dec = substr($encryptedValue, 0, self::getIvSize());
        // Остальная часть, зашифрованный текст:
        $cipherText = substr($encryptedValue, self::getIvSize());

        $value = self::decrypt($cipherText, $key, $iv_dec, $cipher);

        return $value;
    }
}
