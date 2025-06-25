<?php

class CipherVersion
{
  private static $lettersArr = ['а'=>0, 'б'=>1, 'в'=>2, 'г'=>3, 'д'=>4, 'е'=>5, 'ё'=>6, 'ж'=>7, 'з'=>8, 'и'=>9, 'й'=>10, 'к'=>11, 'л'=>12, 'м'=>13, 'н'=>14, 'о'=>15, 'п'=>16, 'р'=>17, 'с'=>18, 'т'=>19, 'у'=>20, 'ф'=>21, 'х'=>22, 'ц'=>23, 'ч'=>24, 'ш'=>25, 'щ'=>26, 'ъ'=>27, 'ы'=>28, 'ь'=>29, 'э'=>30, 'ю'=>31, 'я'=>32, 'z'=>58, 'y'=>57, 'x'=>56, 'w'=>55, 'v'=>54, 'u'=>53, 't'=>52, 's'=>51, 'r'=>50, 'p'=>49, 'q'=>48, 'o'=>47, 'n'=>46, 'm'=>45, 'l'=>44, 'k'=>43, 'j'=>42, 'i'=>41,'h'=>40, 'g'=>39, 'f'=>38, 'e'=>37, 'd'=>36, 'c'=>35, 'b'=>34, 'a'=>33];
  /**
   * Метод получает из зашифрованного текста версию алгоритма
   *
   * @param string $cipherText зашифрованный текст
   * @return array
   */
  public static function getVersion(string $cipherText): array
	{
		$versionArr = [
			'cipherVersion' => null,
			'cipherKey' => null,
		];
    $versionString = mb_substr($cipherText, -6);
		//Массив кирилических и латинских букв и цифр, которые участвовали в формировании версии
		$lettersArr = self::$lettersArr;
		$numberArr = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
		$versionSymbArr = preg_split('//u', $versionString, -1, PREG_SPLIT_NO_EMPTY);
		//Вычленяем числа из строки с шифром
		$numberArr  = array_values(array_intersect($versionSymbArr, $numberArr));
		//Вычленяем буквы из строки с шифром
		$letterArr  = array_values(array_diff($versionSymbArr, $numberArr));
		//Определяем паттерн размещения
		$pattern = $numberArr[0];
		//Получаем флаг реверса 
		$reverseLettersArr = (($numberArr[1] % 2 === 0) ? 0 : 1);
		$cipherKeyNum = $numberArr[2];
		//var_dump($cipherKeyNum);
		if (!($reverseLettersArr % 2 === 0)) {
			$lettersArr = array_combine(array_keys($lettersArr), array_reverse(array_values($lettersArr)));
		}
		switch ($pattern) {
			case 1:
				$version = implode('', array_map(function($el) use($lettersArr) {return $lettersArr[$el];}, $letterArr));
				break;
			case 2:
			case 3:
				$version = (string)$lettersArr[$letterArr[0]] . (string)$lettersArr[$letterArr[1]];
				break;
			case 4:
			case 5:
				$version = (string)$lettersArr[$letterArr[1]] . (string)$lettersArr[$letterArr[2]];
				break;
		}
		//Определяем ключ конкретного шифра, беря последнюю цифру из версии алгоритма
		// $this->cipherKey = $this->cipherKeyStorage[substr($version, -1)];
		// $this->cipherKey_second = $this->cipherKeyStorage[substr($version, -1) == (count($this->cipherKeyStorage) - 1) ? 0 : substr($version, -1) + 1];


		$versionArr['cipherVersion'] = (int)$version;
		$versionArr['cipherKey'] = $cipherKeyNum;

		return $versionArr;

		//return (int)$version;
	}

}
