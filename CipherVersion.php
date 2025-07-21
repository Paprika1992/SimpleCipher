<?php

/**
 * Класс для работы с версией алгоритма
 */
class CipherVersion
{
	/**
	 * @var array фиксированный массив кирилических и латинских букв в верхнем и нижнем регистрах
	 */
	private static $lettersArr;


	/**
	 * Метод получает из зашифрованного текста версию алгоритма
	 *
	 * @param string $cipherText зашифрованный текст
	 * @return int
	 */
	public static function getVersion(string $cipherText): int
	{
		require("./config.php");
		self::$lettersArr = $CONF_lettersArr;
    	$versionString = mb_substr($cipherText, -5);
		//Массив кирилических и латинских букв и цифр, которые участвовали в формировании версии
		$lettersArr = self::$lettersArr;
		$numberArr = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
		$versionSymbArr = preg_split('//u', $versionString, -1, PREG_SPLIT_NO_EMPTY);
		//Вычленяем числа из строки с версией
		$versionNumberArr  = array_values(array_intersect($versionSymbArr, $numberArr));
		//Вычленяем буквы из строки с версией
		$letterArr  = array_values(array_diff($versionSymbArr, $numberArr));
		// var_dump($letterArr);
		//Определяем паттерн формирования
		$pattern = $versionNumberArr[0];
		//Получаем флаг реверса 
		$reverseLettersArr = (($versionNumberArr[1] % 2 === 0) ? 0 : 1);
		if (!($reverseLettersArr % 2 === 0)) {
			$lettersArr = array_combine(array_keys($lettersArr), array_reverse(array_values($lettersArr)));
		}
		//Получаем цифры указателя на версию, чтобы из них собрать итоговую версию
		$versionNumbers = str_split($lettersArr[$letterArr[0]] . $lettersArr[$letterArr[1]]);
		switch ($pattern) {
			case 1:
				$version = implode('', $versionNumbers);
				break;
			case 2:
				$version = $versionNumbers[0] . $versionNumbers[2] . $versionNumbers[1];
				break;
			case 3:
				$version = $versionNumbers[1] . $versionNumbers[2] . $versionNumbers[0];
				break;
			case 4:
				$version = $versionNumbers[2] . $versionNumbers[1] . $versionNumbers[0];
				break;
			case 5:
				$version = $versionNumbers[1] . $versionNumbers[0] . $versionNumbers[2];
				break;
			case 6:
				$version = $versionNumbers[2] . $versionNumbers[0] . $versionNumbers[1];
				break;
		}
		return (int)$version;
	}
}
