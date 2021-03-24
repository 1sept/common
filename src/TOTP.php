<?php

declare(strict_types=1);

namespace Sept\Common;

/**
 * Time-based One-time Password Algorithm
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, {@see http://www.gnu.org/licenses/}.
 *
 * PHP Google two-factor authentication module.
 *
 * See https://www.idontplaydarts.com/2011/07/google-totp-two-factor-authentication-for-php/
 * for more details
 *
 * @author Phil
 * @author Тимофей Соловейчик <timofey@1sept.ru>
 *
 * See http://jacob.jkrall.net/totp/
 */
class TOTP
{

	/**
	 * Interval between key regeneration
	 * @var int
	 */
	const KEY_REGENERATION = 30;

	/**
	 * Interval between key regeneration
	 * @var int
	 */
	const KEY_PLUS_TIME = 4;

	/**
	 * Length of secret key
	 * @var int
	 */
	const KEY_LENGTH = 16;

	/**
	 * Length of the Token generated
	 * @var int
	 */
	const OTP_LENGTH = 6;

//	/**
//	 * Lookup needed for Base32 encoding
//	 * @see https://en.wikipedia.org/wiki/Base32
//	 * @var int[]
//	 */
//	const BASE_32_ALPHABET = [
//		  "A" => 0  , "a" => 26 // , "0" => 52
//		, "B" => 1  , "b" => 27 // , "1" => 53
//		, "C" => 2  , "c" => 28 , "2" => 54
//		, "D" => 3  , "d" => 29 , "3" => 55
//		, "E" => 4  , "e" => 30 , "4" => 56
//		, "F" => 5  , "f" => 31 , "5" => 57
//		, "G" => 6  , "g" => 32 , "6" => 58
//		, "H" => 7  , "h" => 33 , "7" => 59
//		, "I" => 8  , "i" => 34 , "8" => 60
//		, "J" => 9  , "j" => 35 , "9" => 61
//		, "K" => 10 , "k" => 36 // , "+" => 62
//		, "L" => 11 , "l" => 37 // , "/" => 63
//		, "M" => 12 , "m" => 38 // , "=" => " "
//		, "N" => 13 , "n" => 39
//		, "O" => 14 , "o" => 40
//		, "P" => 15 , "p" => 41
//		, "Q" => 16 , "q" => 42
//		, "R" => 17 , "r" => 43
//		, "S" => 18 , "s" => 44
//		, "T" => 19 , "t" => 45
//		, "U" => 20 , "u" => 46
//		, "V" => 21 , "v" => 47
//		, "W" => 22 , "w" => 48
//		, "X" => 23 , "x" => 49
//		, "Y" => 24 , "y" => 50
//		, "Z" => 25 , "z" => 51
//	];

	/**
	 * Lookup needed for Base32 encoding
	 * @see https://en.wikipedia.org/wiki/Base32
	 * @var int[]
	 */
	const BASE_32_ALPHABET = [
		  "A" => 0  // , "0" => 26
		, "B" => 1  // , "1" => 26
		, "C" => 2  , "2" => 26
		, "D" => 3  , "3" => 27
		, "E" => 4  , "4" => 28
		, "F" => 5  , "5" => 29
		, "G" => 6  , "6" => 30
		, "H" => 7  , "7" => 31
		, "I" => 8  // , "8" => 32
		, "J" => 9  // , "9" => 33
		, "K" => 10 // , "+" => 35
		, "L" => 11 // , "/" => 36
		, "M" => 12 // , "=" => " "
		, "N" => 13
		, "O" => 14
		, "P" => 15
		, "Q" => 16
		, "R" => 17
		, "S" => 18
		, "T" => 19
		, "U" => 20
		, "V" => 21
		, "W" => 22
		, "X" => 23
		, "Y" => 24
		, "Z" => 25
	];

	/**
	 * Generates a 16 digit secret key in base32 format
	 * @return string
	 **/
	public
	static
	function generateSecretKey ($length = self::KEY_LENGTH)
	{
		$b32Symbols = implode("" ,array_keys(self::BASE_32_ALPHABET));

		$s = "";
		for ($i = 0; $i < $length; $i++)
			$s .= $b32Symbols[rand(0,31)];

		return $s;
	}


	/**
	 * Returns the current Unix Timestamp devided by the keyRegeneration
	 * period.
	 * @return integer
	 **/
	public
	static
	function getTimestamp()
	{
		return floor(microtime(true)/self::KEY_REGENERATION);
	}


	/**
	 * Extracts the OTP from the SHA1 hash.
	 *
	 * @param string $hash
	 *
	 * @return int
	 **/
	public static function truncate ($hash)
	{
		$offset = ord($hash[19]) & 0xf;

		return (
			       ((ord($hash[$offset+0]) & 0x7f) << 24 ) |
			       ((ord($hash[$offset+1]) & 0xff) << 16 ) |
			       ((ord($hash[$offset+2]) & 0xff) << 8 ) |
			       (ord($hash[$offset+3]) & 0xff)
		       ) % pow(10, self::OTP_LENGTH);
	}


	/**
	 * Decodes a base32 string into a binary string.
	 *
	 * @param $b32
	 *
	 * @return int
	 *
	 * @throws \Parus\Exception\Exception
	 */
	public
	static
	function base32Decode ($b32)
	{
		$b32 = strtoupper($b32);

		$b32Symbols = implode("" ,array_keys(self::BASE_32_ALPHABET));

		if (preg_match_all("/[^{$b32Symbols}]+/u", $b32 ,$matches))
			throw new Exception("Есть недопустимые символы: «" . implode("» ,«" ,$matches[0]) . "». Разрешены такие символы: «{$b32Symbols}»!");

		$l = strlen($b32);
		$n = 0;
		$j = 0;
		/** @var int $binary */
		$binary = "";

		for ($i = 0; $i < $l; $i++) {

			// Move buffer left by 5 to make room
			$n = $n << 5;
			// Add value into buffer
			$n = $n + self::BASE_32_ALPHABET[$b32[$i]];
			// Keep track of number of bits in buffer
			$j = $j + 5;

			if ($j >= 8) {
				$j = $j - 8;
				$binary .= chr(($n & (0xFF << $j)) >> $j);
			}
		}

		return $binary;
	}


	/**
	 * Takes the secret key and the timestamp and returns the one time
	 * password.
	 *
	 * @param int     $key     Secret key in binary form.
	 * @param integer $counter Timestamp as returned by get_timestamp.
	 *
	 * @return string
	 *
	 * @throws \Parus\Exception\Exception
	 */
	public
	static
	function getOneTomePassword($key ,$counter)
	{
		if (strlen($key) < 8)
			throw new Exception('Secret key is too short. Must be at least 16 base 32 characters');

		$counter = Data::isPositiveInteger($counter ,"Неверно указан ключ TOTP!");

		// Counter must be 64-bit int
		$bin_counter = pack('N*', 0) . pack('N*', $counter);
		$hash        = hash_hmac ('sha1', $bin_counter, $key, true);

		return str_pad(self::truncate($hash) ,self::OTP_LENGTH ,'0' ,STR_PAD_LEFT);
	}


	/**
	 * Verifys a user inputted key against the current timestamp. Checks $window
	 * keys either side of the timestamp.
	 *
	 * @param string  $b32seed
	 * @param string  $key User specified key
	 * @param integer $window
	 * @param boolean $useTimeStamp
	 *
	 * @return boolean
	 **/
	public
	static
	function verifyKey($b32seed ,$key ,$window = self::KEY_PLUS_TIME ,$useTimeStamp = true)
	{
		$timeStamp = self::getTimestamp();

		if ($useTimeStamp !== true)
			$timeStamp = (int) $useTimeStamp;

		$binarySeed = self::base32Decode($b32seed);

		for ($ts = $timeStamp - $window; $ts <= $timeStamp + $window; $ts++)
			if (self::getOneTomePassword($binarySeed,$ts) == $key)
				return true;

		return false;
	}

}
