<?php

// use function Ramsey\Uuid\v1;

mb_internal_encoding("UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//TODO
//проверь на уникальность все символы и подумай какие спецсимволы можно добавить
//добавь (проверь как с слешами отработает) _-/\<>&`~

class SimpleCipher
{
	/**
	 * @var array массив ключей шифра, которые будут использоваться в зависимости от 3ей цифры в версии алогоритма
	 */
	private $cipherKeyStorage = [
		0 => 'sТVцПzTjceoёжaЧB"е8LQчэЕЛ+ Аh№ДiщDPсMы6ЙЫ%pюН:мqSKWw5уHUХЩNпZX3РдIлйГnБJИ1я}uМ(ШО]бvъEн9ydшЭ{kзmgоЗ|ФGрьКаЁЖ!Уг?b7ЦxкC0)вЪlЮтЯtOA;В[2хYиRfЬrF4Сф',
		1 => 'kde}7xЙV:ЬЕ]XГ(fyh1KтSТH щzсЪ6ел|мцTgЛр4бY5Aгзиv)r9ёНI!ЮнСЫйuCУЧЗМо%ФЯхР3tф{"Да8+ЦЭGjUАnчШpПХИ?;шюaдOБQжп[0ЁcJLуякlPqWыэDEZsК2ВMoЩNiBвьО№mbъFЖRw',
		2 => '7|жJАК3d5цz8ЭуШЖXкЙpчъu:2дВbиЩZЁб4гsWIСFУ}БЗьAmLUэйYяoщёOjvЬrCSN!xвPiюV"{qИЪEа]Kл96о0enЕ;tфQ(HГ№lрОмyзМkDhwхGMa%ЛыД1)Tgc+ФЯРн?НЫеХRтПЧпfЮш[ТсЦB ',
		3 => 'а"УЙKВn2юЬЗт[уЮJм№NЛX]Ъ+tЁ;1MHьыХ(ЖDл)aБVuКДЦ4нсgZ3LowбIsUРФ9Od7ёeвш6AжЕПиYrГc5SЧyТхbз?Rщ{рчАЭпijг8:Шxэq%цИЯkМйС !FъTfфЫQЩе}PоEmдCОvWя|кphG0zНBl',
		4 => 'J;oeчэиЖ]"ЁцTwз7{ыфК3хГ+SюПzbИIЮОмkt!тUpвдРЫAViH?З4ХБvЛaЦоOGж2ruR%[C6ьXcMъёKL|sh ФЪE0DZ9FаУd№А}QЬYЧqЩШy(fейnуPлс)mбТЭНBС8В1ДМlЙгрЯяjнЕW5Ngx:пшкщ',
		5 => 'Оvтh4фщЁM0л:аFёD)чЫИс(zRЭ№ВЧk3ЗЦДя Uпwъ6rю?mгXAt{HgБl5EФПa2ГjTдшУЛМхЯмsdэкЬGLJр!fZуKNp%Жй;[ЪqЙЮC]Х"ШвбЕIицuо8BnКOoы+Н9РYЩPьжАyxТ7С}нзiQcSb|е1WeV',
		6 => 'ЪнCfЧх]ЖNРиbУ0поsВ4шhФOgжч|рцьJСШ;YЭЩБмъвo(тЮi8йсЗПЯ1:GЦу+dwzxщt cЙ7eёАлSAДlZvyфjU%№}qяKЛ"КQЕИюkГMLыХpn2)mB?3Нк{зеu!FдHаЁ6RТЬгМTWPE5[raIЫОб9XVэD',
		7 => '3ФУzeЛАNЧ)A;rDС!дmеЬTылХRБh:яJyMьfЭUnЙdHkвYИэ 6{wОjкТcмBПКтЗнсР9+%EKЪ7ЕШ1pGЖCЮiЫцшВГНXоFLqзx5}ёvж2SЦчпбtаsуogЩйЁQъ0(х|84]юuгфМи"ЯlI№PZV?Wр[OДbaщ',
		8 => 'gUЙИ{XohjTЁя]хBШУцрc8ъеs3Y+ФSкДqC0ЧRп%DсЦVжСE(дmPыОAdЯимзвy5bёxL?М"}Г!ЪOгКpщ2aMншПNРфfk lоuХ№FЭ1IQZn|ЬтТ9eюэБiGуrЫьЛЖ4ВЗчЩW6wJАл7Kt;ЮЕа:йzбHН[v)',
		9 => 'йcЛuoэ№%hnqЕAж4afг!BJТv;шУ6HХпрsцXщл+ф3сLЪgQЗхкюtб)ШOПiKxyUФъчzPн}[2dm1ЦРЯ(WАз9НЫ7вGlот ЖО{иьb5ГIEа8ЭYZ:jFИуё0КДя]ЙweЧrЁ?ыВDNЮЩ|"МCдеTpБСЬkVSMRм',
	];


	/**
	 * @var string ключ шифра, который будет использоваться для построения первой матрицы
	 */
	private $cipherKey;
	/**
	 * @var string ключ шифра, который будет использоваться для построения второй матрицы (строится на основании $this->cipherKey)
	 */
	private $cipherKey_second;
	/**
	 * @var string передаваемый для шифровки/дешифровки текст
	 */
	private $text;
	/**
	 * @var string соль для шифра
	 */
	private $salt;
	/**
	 * @var int фейковая длина зашифрованной строки (по умолчанию 100)
	 */
	private $fakeLength;
	/**
	 * @var array массив различных символов, которые не являются буквами или цифрами. Массив нужен для обрамления частей указателя на реальную длину шифруемого текста
	 */
	private $encryptLengthDelimetr;
	/**
	 * @var int версия приложения. Перовая версия 12|случайная_версия_ключа_шифра|, чтобы не начинать с 001
	 */
	private $version = 12;
	/**
	 * @var string версия приложения в зашифрованном виде
	 */
	// private $encryptVersion;
	/**
	 * @var boolean шифруем/дешифруем текст
	 */
	private $encrypt;
	// /**
	//  * @var array массив с координатами символов исходного сообщения
	//  */
	// private $originalSymbCoordArr = [];
	/**
	 * @var int размерность матрицы (в случае, если матрица 10х10 равна 10)
	 */
	private $matrixDepth;
	/*Набор кириллических и латинских символов ниже, а также числа (далее по коду) будут участвовать в формировании вертикального и горизонтального векторов инициализации
  Значения в массиве не идут по порядку идут только лишь для дополнительной "путацницы"
  НЕ МЕНЯТЬ ПОСЛЕ РЕЛИЗА*/
	private $cyrilicLetters = ['а'=>0, 'б'=>1, 'в'=>2, 'г'=>3, 'д'=>4, 'е'=>5, 'ё'=>6, 'ж'=>7, 'з'=>8, 'и'=>9, 'й'=>10, 'к'=>11, 'л'=>12, 'м'=>13, 'н'=>14, 'о'=>15, 'п'=>16, 'р'=>17, 'с'=>18, 'т'=>19, 'у'=>20, 'ф'=>21, 'х'=>22, 'ц'=>23, 'ч'=>24, 'ш'=>25, 'щ'=>26, 'ъ'=>27, 'ы'=>28, 'ь'=>29, 'э'=>30, 'ю'=>31, 'я'=>32];
	private $latinLetters = ['z'=>58, 'y'=>57, 'x'=>56, 'w'=>55, 'v'=>54, 'u'=>53, 't'=>52, 's'=>51, 'r'=>50, 'p'=>49, 'q'=>48, 'o'=>47, 'n'=>46, 'm'=>45, 'l'=>44, 'k'=>43, 'j'=>42, 'i'=>41,'h'=>40, 'g'=>39, 'f'=>38, 'e'=>37, 'd'=>36, 'c'=>35, 'b'=>34, 'a'=>33];
	private $lettersArr = ['а'=>0, 'б'=>1, 'в'=>2, 'г'=>3, 'д'=>4, 'е'=>5, 'ё'=>6, 'ж'=>7, 'з'=>8, 'и'=>9, 'й'=>10, 'к'=>11, 'л'=>12, 'м'=>13, 'н'=>14, 'о'=>15, 'п'=>16, 'р'=>17, 'с'=>18, 'т'=>19, 'у'=>20, 'ф'=>21, 'х'=>22, 'ц'=>23, 'ч'=>24, 'ш'=>25, 'щ'=>26, 'ъ'=>27, 'ы'=>28, 'ь'=>29, 'э'=>30, 'ю'=>31, 'я'=>32, 'z'=>58, 'y'=>57, 'x'=>56, 'w'=>55, 'v'=>54, 'u'=>53, 't'=>52, 's'=>51, 'r'=>50, 'p'=>49, 'q'=>48, 'o'=>47, 'n'=>46, 'm'=>45, 'l'=>44, 'k'=>43, 'j'=>42, 'i'=>41,'h'=>40, 'g'=>39, 'f'=>38, 'e'=>37, 'd'=>36, 'c'=>35, 'b'=>34, 'a'=>33];
	// private $symbolsArr = ["!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "-", "_", "+", "=", "|", ">", "<", ",", ".", "?", "'", "`", "~", ";", ":", "[", "]", "{", "}"];
	/**
	 * TODO
	 * @var int окно захвата символов для первой матрицы, которое будет перемещаться НЕ ДОЛЖНО БЫТЬ БОЛЬШЕ ДЛИНЫ КЛЮЧА ШИФРА И НЕ МЕНЬШЕ 3Х. Протести варианты минимального и максимального значение тоже. Если указать значение 3, то шифры получаются одинаковые через какое-то время. Попробуй потести какое минимальное значение для перемешивания
	 */
	private $windowSizeFirst;
	/**
	 * @var int окно захвата символов для второй матрицы
	 */
	private $windowSizeSecond;
	/**
	 * @var int количество итераций смещения шифра для первой матрицы
	 */
	private $shiftCountFirst;
	/**
	 * @var int количество итераций смещения шифра для второй матрицы
	 */
	private $shiftCountSecond;
	/**
	 * @var bool флаг реверсивности ключа. Используем ли его в оригинальном виде, либо переворачиваем
	 */
	//private $reverseFlag;
	/**
   * @var int количество символов в векторе инициализации
   */
  private $vectorLength = 3;
	/**
   * @var string формируемый вектор вертикальной инициализации в виде биграмма
   */
  private $cipherVectorVert;
  /**
   * @var string формируемый вектор горизонтальной инициализации в виде биграмма
   */
  private $cipherVectorHor;
	/**
   * @var string вектор вертикальной инициализации в виде числа
   */
  private $initializationVectorVert;
  /**
   * @var string вектор горизонтальной инициализации в виде числа
   */
  private $initializationVectorHor;
	/**
   * @var array первая матрица на основе преобразованного ключа шифра
   */
	private $matrixOne;
	/**
   * @var array вторая матрица на основе первого преобразованного ключа шифра
   */
	private $matrixTwo;
	/**
   * @var array преобразованноая первая матрица после сдвига по векторам инициализации
   */
	private $transformedMatrixOne;
	/**
	 * @var array преобразованноая вторая матрица после сдвига по векторам инициализации
	 */
	private $transformedMatrixTwo;
	/**
	 * @var array объединяющий массив трансформированных матриц
	 */
	private $transformedMatrixArr; 
	/**
   * @var int максимальная фейковая длина шифруемой строки
   */
	private $maxFakeLength = 1000;
	/**
   * @var string фейковые символы, которые были вычленены из расшифровываемой строки после ее очистки для сравнения с фейковыми символами, используемыми при шифровании
   */
	private $fakeSymbolString = null;


	public function __construct(string $text, ?string $salt = null)
	{
		$this->text = $text;
		#Гаврилов
		//ПОПРОБУЙ В СОЛЬ ПЕРЕДАТЬ КИТАЙСКИЙ СИМВОЛ ИЛИ РУССКИЙ, ОНИ ДОЛЖНЫ УДАЛЯТЬСЯ ТУТ. В СОЛИ МОЖЕТ БЫТЬ ТОЛЬКО ЛАТИНСКИЕ СИМВОЛЫ И ЦИФРЫ
		$this->salt = preg_replace('/[^a-zA-Z0-9]+/', '', $salt);
		$this->matrixDepth = sqrt(mb_strlen($this->cipherKeyStorage[0]));
	}


	/**
	 * Метод формирует массив-указатель на хэш исходной строки, который кладется в шифр для сравнения такого же массива-указателя на хэш дешифруемой строки. Если они не совпадают - в шифре был подменен символ исходной строки. В этом случае возвращаем пользователю фейковую строку 
	 *
	 * Работает следующим образом: хэшируем текст, после чего вычленяем оттуда только цифры и отдельно только буквы. 
	 * Получаем сумму всех цифр и сумму букв (суммируя ключи массива с буквами).
	 * От сумм берем только первые 2 цифры. В этом случае к шифру в итоге добавляется 6 фейковых символов. Чем больше цифр будет будем брать, тем меньше вероятность наткнуться на колизию (когда сумма всех цифр или всех букв 2х разных хэшов совпадет), но тем больше фейковых символов будет добавлено к итоговому шифру.
	 * Добавляем к суммам по 1 букве из хэша букв для разнообразия. 
	 * Сформировавшиеся отрезки кладем вместо векторов инициализации. 
	 * При дешифровке шифра, получив итоговый (исходный) текст, повторяем эту операцию. Если элементы получившегося массива будут совпадаеть с векторами инициализации - считаем, что символы исходной строки не были подменены в шифре. В противном случае возвращаем фейковую строку
	 * 
	 * @param string $text текст на хэш которого будет указывать полученный массив
	 * @param string $matrixParamString строка с параметрами преобразования матриц и случайными разделителями между параметрами Нужны для уникальности результатов работы метода для каждого шифра
	 * @return array
	 */
	private function getTextHashPointer(string $text, string $matrixParamString): array
	{
		//ПЕРЕПИСАТЬ СЛЕДУЮЩИМ ОБРАЗОМ. ХЭШИРУЕМ СТРОКУ, КОДИРУЕМ В BASE64. ПЕРВЫЕ 3 СИМВОЛА И ПОСЛЕДНИЕ 3 СИМВОЛА ИСПОЛЬЗУЕМ КАК ВЕТОРЫ ИНИЦИАЛИЗАЦИИ. ИХ ЖЕ И СРАВНИВАЕМ ПРИ ДЕШИФРОВАНИИ
		//base32
		//Массив указатель
	  	$hashPointerArr = [
			'firstVector' => null,		//Элемент формируется на основе цифр хэша строки
			'secondVector' => null		//Элемент формируется на основе букв хэша строки
		];
		//Хэш строки. Подмешиваем строку с параметрами преобразования матриц для уникальности
		$hashText = hash('whirlpool', $text . $this->reverseString($matrixParamString));
		//Кодируем в base64 для разбавшения хэша более широким диапазоном используемых символов. В алгоритме хэша используются цифры 0-9 и буквы abcdef. При кодировании в base64 больше символов.
		$encodeText = preg_replace('/[^a-z0-9]/i', '', base64_encode($hashText));
		//var_dump($encodeText);
		$firstVector = substr($encodeText, 0, 3);
		$secondVector = substr($encodeText, -3);
		// var_dump($firstVector);
		// var_dump($lastVector);
		//Сумма чисел из хэша строки
		//$hashText_numbersSumm = array_sum(str_split(preg_replace('/[^0-9]+/', '', $hashText)));
		//Добавляем элементы массива случайных параметров преобразования матриц. Преобразовываем в строку, чтобы в результате забрать только первые 2 цифры из получившейся суммы
		//$hashText_numbersSumm = $hashText_numbersSumm + $matrixParamArr[4] + $matrixParamArr[3] . "";
		//$hashText_numbersSumm = substr($hashText_numbersSumm, 0, 2);
		#Гаврилов
		//ПРОВЕРИТЬ. МОЖЕТ ЛИ БЫТЬ МЕНЬШЕ 2Х ЦИФР В ПЕРВОМ И ВТОРОМ ОТРЕЗКЕ. еСЛИ МОЖЕТ - СУЩЕСТВУЕТ ОПАСНОСТЬ, ЧТО МЫ НЕ СМОЖЕМ ДОСТАТЬ ЭТИ ОТРЕЗКИ, ТАК КАК ПЛАНИРУЕТСЯ, ЧТО ОТРЕЗОК БУДЕТ СОСТОЯТЬ ИЗ БУКВЫ + 2 ЧИСЛА. еСЛИ ЧИСЛА МОЖЕТ БЫТЬ МЕНЬШЕ 2Х - ПРОБЛЕМА
		//Массив букв из хэша строки
		//$hashText_letters = str_split(preg_replace('/[^a-z]+/i', '', $hashText));
		//Случайным образом определяем букву, которая будет добавлена к первому элементу итогового массива (для разбавления, чтобы не было заметно, что элемент представляет собой только цифры)
		//$firstLetter = $hashText_letters[array_sum(str_split($hashText_numbersSumm))];
		//Сумма букв из хэша строки. Определяется путем сложений ключей соответствующих элементов массива $this->lettersArr 
		//$hashTextLettersSum = array_sum(array_map(function($el) {return array_key_exists($el, $this->lettersArr) !== false ? $this->lettersArr[$el] : null;}, $hashText_letters));
		//Добавляем элементы массива случайных параметров преобразования матриц. Преобразовываем в строку, чтобы в результате забрать только первые 2 цифры из получившейся суммы
		//$hashTextLettersSum = $hashTextLettersSum + $matrixParamArr[0] + $matrixParamArr[1] . "";
		//$hashTextLettersSum = substr($hashTextLettersSum, 0, 2);
		//Случайным образом определяем букву, которая будет добавлена ко второму элементу итогового массива (для разбавления, чтобы не было заметно, что элемент представляет собой только цифры)
		//$lastLetter = $hashText_letters[array_sum(str_split($hashTextLettersSum))];
		//В процессе формирования итогового массива случайным образом определяем где в полученном значении будет находиться буква - перед цифрами, либо после них. Нужно для разнообразия
		// $hashPointerArr['numbersSum'] = str_split($hashText_numbersSumm)[0] % 2 == 0 ? $firstLetter . $hashText_numbersSumm : $hashText_numbersSumm . $firstLetter;
		$hashPointerArr['firstVector'] = $firstVector;
		// $hashPointerArr['lettersSum'] = str_split($hashTextLettersSum)[0] % 2 == 0 ? $lastLetter . $hashTextLettersSum : $hashTextLettersSum . $lastLetter;
		$hashPointerArr['secondVector'] = $secondVector;

	  	return $hashPointerArr;
	}


	/**
	 * Метод преобразовывает ключ шифра, используя соль
	 *
	 * @param string $cipherKey - ключ для преобразования
	 * @return string
	 */
	private function useSaltToCipherKey($cipherKey)
	{
		// $cipherKey = 'sТVцПzTjceoёжaЧB"е8LQчэЕЛ+ Аh№ДiщDPсMы6ЙЫ%pюН:мqSKWw5уHUХЩNпZX3РдIлйГnБJИ1я}uМ(ШО]бvъEн9ydшЭ{kзmgоЗ|ФGрьКаЁЖ!Уг?b7ЦxкC0)вЪlЮтЯtOA;В[2хYиRfЬrF4Сф';
		// var_dump($cipherKey);
		//Новый ключ шифрования
		$newCipherKey = null;
		$cipherKeySymbArr = $this->getStrArr($cipherKey);
		//var_dump($cipherKeySymbArr);
		/*Перебираем все символы в соли и если этот символ из соли найден в ключе шифра - кладем его в начало (или конец в зависимости от четности/нечетности порядка итерации) ключа шифра (например на нулевую позицию), с прежней позиции элемент из массива удаляем. Например: 
		ключ - abcdef123
		соль - d2
		находим символ d, с изначальной позиции массива элемент удаляем (abcef123) и помещаем его на нулевую позицию в ключе -> dabcef123
		то же самое делаем с символом 2 - перемещаем его уже в конец массива (так как номер итерации теперь четный) ->dabcef132*/ 
		$keyCounter = 0;
		foreach ($this->getStrArr($this->salt) as $saltKey => $saltSymb){
			$сipherSymbKey = array_search($saltSymb, $cipherKeySymbArr);
			if ($сipherSymbKey !== false) {
				unset($cipherKeySymbArr[$сipherSymbKey]);
				if ($saltKey % 2 !== 0) {
					array_unshift($cipherKeySymbArr, $saltSymb);
				} else {
					$cipherKeySymbArr[] = $saltSymb;
				}
			}
		}
		$newCipherKey = implode('', $cipherKeySymbArr);

		return $newCipherKey;
	}


	/**
	 * Метод применяет соль к параметрам преобразования матриц, чтобы по разному формировались ключи к матрицам в зависимости от секретного ключа
	 *
	 * @param array $matrixParam
	 * @return array
	 */
	private function useSaltToMatrixParam($matrixParam)
	{
		$transformedMatrixParam = $matrixParam;
		$cipherSalatArr = $this->getStrArr($this->salt);
		//Массив с латинскими буквами в верхнем регистре, который мы ниже объединим с массивом латинских букв в нижнем регистре и на его основе [n => 2, H => 3, s => 4 ...] будем считать сумму ключей символов в соли
		
		$upperLatinLetters = array_map(function($el){ return strtoupper($el);}, array_flip($this->latinLetters));
		$cipherSalatArr = array_map(function($el) use($upperLatinLetters) {return preg_match('/[^0-9]/', $el) ? array_flip(array_flip($this->latinLetters) + array_merge($upperLatinLetters, []))[$el] : (int)$el;}, $cipherSalatArr);
		$saltSymbSum = array_sum($cipherSalatArr);
		//Первую цифру из суммы символов соли добавляем к размеру окна захвата символов для преобразования первого ключа (если их сумма не больше 55, в противном случае - вычитаем). Последнюю цифру из суммы, соответственно добавляем к размеру окна захвата для преобразования второго ключа
		$shiftWindowSize_first = substr($saltSymbSum, 0, 1);
		$shiftWindowSize_second = substr($saltSymbSum, -1);
		//Двузначное число из начала суммы символов добавляем к количеству итераций сдвига первого ключа, двузначное число из конца суммы, соответственно, к количеству итераций второго ключа
		$shiftIteration_first = substr($saltSymbSum, 0, 2);
		$shiftIteration_second = substr($saltSymbSum, -2);
		$transformedMatrixParam[0] = ($transformedMatrixParam[0] + $shiftWindowSize_first >= 55) ? $transformedMatrixParam[0] - $shiftWindowSize_first : $transformedMatrixParam[0] - $shiftWindowSize_first;
		$transformedMatrixParam[1] = $transformedMatrixParam[1] + $shiftIteration_first;
		$transformedMatrixParam[3] = ($transformedMatrixParam[3] + $shiftWindowSize_second >= 55) ? $transformedMatrixParam[3] - $shiftWindowSize_second : $transformedMatrixParam[3] - $shiftWindowSize_second;
		$transformedMatrixParam[4] = $transformedMatrixParam[4] + $shiftIteration_second;

		return $transformedMatrixParam;
	}


	/**
	 * Метод шифрования текста
	 *
	 * @param integer $fakeLength фейковая длина шифра
	 * @return string
	 */
	public function encryptText(int $fakeLength = 50): string
	{
		//Фейковая длина не может быть меньше 50 символов
		$fakeLength = $fakeLength < 50 ? 50 : $fakeLength;
		$this->encrypt = true;
		//Должно быть нечетным! Чтобы дойдя до конца, начиналось каждый раз с разной позиции в начале строки
		//Сделать так, чтобы при выпадении четного числа - к нему прибавлялась единица
		//Минимум 11
		//ВЫЯСНИТЬ МАКСИМАЛЬНО ЭФФЕКТИВНЫЙ РАЗМЕР
		$this->windowSizeFirst = $this->getRandNum(55, 12);
		//минимум 99
		//ВЫЯСНИТЬ МАКСИМАЛЬНО ЭФФЕКТИВНЫЙ РАЗМЕР
		$this->shiftCountFirst = $this->getRandNum(999, 99);
		/**
		 * @var string Флаг реверса ключа шифра, который используется для формирования первой матрицы. Ключ для второй матрицы всегда имеет противоположное значение
		 */
		$reverseCipherKey = ($this->getRandNum(3, 1) == 1 ? 0 : 1);
		/**
		 * @var string версия приложения в зашифрованном виде
		 */
		$encryptVersion = $this->setVersion();
		$this->windowSizeSecond = $this->getRandNum(55, 12);
		$this->shiftCountSecond = $this->shiftCountFirst + $this->getRandNum(1999, 99);
		//Заполняем массив с параметрами преобразования матриц (пока что данными для преобразования первой матрицы)
		$matrixParamArr = [
										0 => $this->windowSizeFirst, 
										1 => $this->shiftCountFirst, 
										2 => $reverseCipherKey,
										3 => $this->windowSizeSecond,
										4 => $this->shiftCountSecond,
									];
		$transformedMatrixParamArr = [];
		//Здесь дополнительно преобразуем ключ шифрования, так как только в методе setVersion происходит формирование ключа
		if ($this->salt) {
			$this->cipherKey = $this->useSaltToCipherKey($this->cipherKey);
			$this->cipherKey_second = $this->useSaltToCipherKey($this->cipherKey_second);
			$transformedMatrixParamArr = $this->useSaltToMatrixParam($matrixParamArr);
			$this->windowSizeFirst = $transformedMatrixParamArr[0];
			$this->shiftCountFirst = $transformedMatrixParamArr[1];
			$this->windowSizeSecond = $transformedMatrixParamArr[3];
			$this->shiftCountSecond = $transformedMatrixParamArr[4];
		}
		//Сдвигаем ключ шифра для первой матрицы
		$mixedCipher = $this->shiftCipherKey($this->cipherKey, $this->windowSizeFirst, $this->shiftCountFirst, $matrixParamArr[2]);
		//Для ключа второй матрицы флаг реверса обязательно меняется на противоположный
		$reverseCipherKey = ($reverseCipherKey ? 0 : 1);
		//Сдвигаем ключ шифра для второй матрицы
		$mixedCipherTwo = $this->shiftCipherKey($this->cipherKey_second, $this->windowSizeSecond, $this->shiftCountSecond, $reverseCipherKey);
		#Гаврилов
		//УБЕРИ ПОСЛЕ ТЕСТИРОВАНИЯ
		//$mixedCipher = '7|жJАК3d5цz8ЭуШЖXкЙpчъu:2дВbиЩZЁб4гsWIСFУ}БЗьAmLUэйYяoщёOjvЬrCSN!xвPiюV"{qИЪEа]Kл96о0enЕ;tфQ(HГ№lрОмyзМkDhwхGMa%ЛыД1)Tgc+ФЯРн?НЫеХRтПЧпfЮш[ТсЦB ';
		#Гаврилов
		//УБЕРИ ПОСЛЕ ТЕСТИРОВАНИЯ	
		//$mixedCipherTwo = 'kde}7xЙV:ЬЕ]XГ(fyh1KтSТH щzсЪ6ел|мцTgЛр4бY5Aгзиv)r9ёНI!ЮнСЫйuCУЧЗМо%ФЯхР3tф{"Да8+ЦЭGjUАnчШpПХИ?;шюaдOБQжп[0ЁcJLуякlPqWыэDEZsК2ВMoЩNiBвьО№mbъFЖRw';
		//Только буквы для рандомной вставки между параметрами полезной нагрузки для трансформации матриц
		$lettersArr = array_flip($this->lettersArr);
		$this->matrixOne = $this->fillMatrix($mixedCipher, (int)substr(array_sum($this->salt ? $transformedMatrixParamArr : $matrixParamArr), -1, 1));
		#Гаврилов
		//УБЕРИ ПОСЛЕ ТЕСТИРОВАНИЯ
		//ДЛЯ ТЕСТИРОВАНИЯ ГРАМОВ НА ОДИНАКОВОЙ ЛИНИИ СФОРМИРУЙ МАТРИЦУ НА ОСНОВЕ ПЕРВОГО КЛЮЧА ИЗ МАССИВОВ КЛЮЧЕЙ МАТРИЦЫ
		//$this->matrixOne = $this->fillMatrix($mixedCipher, 1);
		//Добавляем 1 к предыдущей сумме параметров матрицы, так как это дает 50% шанс, что паттерн заполнения изменится для второй матрицы (так как паттерны делятся по двойкам: 0,1 - 1й паттерн, 2,3 - 2й и так далее). На самом деле, нам не обязательно, чтобы паттерн менялся, так как сама последовательность символов для формирования матрицы разная, поэтому добавление 1 позволит с равной вероятностью получить как тот же паттерн заполнения матрицы, что был для 1й матрицы (0 превратится в 1 - 1й паттерн), так и следующий паттерн (1 превратится в 2 - 2й паттерн).
		$this->matrixTwo = $this->fillMatrix($mixedCipherTwo, (int)substr(array_sum($this->salt ? $transformedMatrixParamArr : $matrixParamArr) + 1, -1, 1));
		#Гаврилов
		//УБЕРИ ПОСЛЕ ТЕСТИРОВАНИЯ
		//$this->matrixTwo = $this->fillMatrix($mixedCipherTwo, 2);
		//Формируем итоговую строку с параметрами формирования матриц. В качестве разделителя между параметрами формирования матриц использовать только случайные БУКВЫ, без знаков препинаний и различных спецсимволов (@, ^ и т.д.), потому что эти символы, в свою очередь, будут использоваться для обособления в параметрах преобразований матрицы первой части указателя на реальную длину шифруемого текста
		$transformMatrixParam = implode('', array_map(function($el) use($lettersArr) {return $el . $lettersArr[array_rand($lettersArr)];}, $matrixParamArr));
		$hashTextParams = $this->getTextHashPointer($this->text, $transformMatrixParam);
		//Числа от 1 до 9 используются, так как два вектора идут подряд и если векторы будут содержать 2 числа непонятно будет где заканчивается первый вектор и начинается второй.
		//TODO
		//ПЕРЕИМЕНУЙ ВЕКТОРЫ ИНИЦИАЛИЗАЦИИ "ВЕРТИКАЛЬНЫЙ/ГОРИЗОНТАЛЬНЫЙ" НА "ПЕРВЫЙ/ВТОРОЙ"
		// $this->cipherVectorVert = $this->createVector(array_merge(array_flip($this->cyrilicLetters), array_flip($this->latinLetters), ['0','1','2','3','4','5','6','7','8','9']));
		$this->cipherVectorVert = $hashTextParams['firstVector'];
		// $this->cipherVectorHor = $this->createVector(array_merge(array_flip($this->cyrilicLetters), array_flip($this->latinLetters), ['0','1','2','3','4','5','6','7','8','9']));
		$this->cipherVectorHor = $hashTextParams['secondVector'];
		$this->initializationVectorVert = $this->getVector($this->cipherVectorVert, 'vert');
		$this->initializationVectorHor = $this->getVector($this->cipherVectorHor, 'hor');
		$this->transformedMatrixOne = $this->shiftMatrix($this->matrixOne, 1, $this->initializationVectorVert, $this->initializationVectorHor);
		$this->transformedMatrixTwo = $this->shiftMatrix($this->matrixTwo, 0, $this->initializationVectorVert, $this->initializationVectorHor);
		$this->transformedMatrixArr[1] = $this->transformedMatrixOne;
		$this->transformedMatrixArr[2] = $this->transformedMatrixTwo;
		#Гаврилов
		//УБЕРИ ПОСЛЕ ТЕСТИРОВАНИЯ
		// $this->transformedMatrixArr[1] = $this->matrixOne;
		// $this->transformedMatrixArr[2] = $this->matrixTwo;
		// var_dump('ШИФРОВАНИЕ');
		// $this->drawMatrix($this->transformedMatrixArr[1]);
		// $this->drawMatrix($this->transformedMatrixArr[2]);
		// die();
		//$this->originalSymbCoordArr = $this->createSymbCoords($this->text);
		//$coordCiphrSymbArr_interim = $this->createCiphrCoords();
		$ecnryptText_interim = $this->createCiphrCoords($this->text);
		//var_dump($coordCiphrSymbArr_interim);
		// var_dump('после трансформации');
		// $this->drawMatrix($this->transformedMatrixArr[1]);
		// $this->drawMatrix($this->transformedMatrixArr[2]);
		//Промежуточный зашифрованный текст (без внедренных фейковых символов)
		//$ecnryptText_interim = $this->createCiphr($coordCiphrSymbArr_interim);
		var_dump($ecnryptText_interim);
		$this->encryptLengthDelimetr = array_filter($this->getStrArr($this->cipherKey), function($el){return preg_match('/[^a-zа-ё0-9]/ui', $el);});
		//Итоговая длина шифруемого текста
		$encryptTextLength = $this->encryptLengthPointer($transformMatrixParam);
		//Первая часть указателя на длину исходного сообщения (3546) без разграничителей - 35
		$encryptLengthWithoutDelimetr_first = mb_substr((string)$encryptTextLength, 0, $this->getRandNum(mb_strlen((string)$encryptTextLength) + 1));
		//Вторая часть указателя на длину исходного сообщения (3546) с разграничителями - {46"
		$encryptLength_second = $this->encryptLengthDelimetr[array_rand($this->encryptLengthDelimetr)] . mb_substr((string)$encryptTextLength, mb_strlen($encryptLengthWithoutDelimetr_first)) . $this->encryptLengthDelimetr[array_rand($this->encryptLengthDelimetr)];
		//Первая часть указателя на длину исходного сообщения (3546) с разграничителями - *35$
		$encryptLength_first = $this->encryptLengthDelimetr[array_rand($this->encryptLengthDelimetr)] . $encryptLengthWithoutDelimetr_first . $this->encryptLengthDelimetr[array_rand($this->encryptLengthDelimetr)];
		$fakeLength = $this->calcLenFakeSymb($fakeLength, $ecnryptText_interim, $this->cipherVectorVert . $this->cipherVectorHor . $transformMatrixParam . $encryptVersion . $encryptLength_first . $encryptLength_second);
		//Итоговый зашифрованный текст исходного сообщения (заполненный фейковыми символами)
		$resultCipherText = $this->fillFakeLength($ecnryptText_interim, $fakeLength, $this->createFakeLengthHash($ecnryptText_interim, $transformMatrixParam));                  
		//Итоговый шифр, включающий в себя зашифрованный текст исходного сообщений + полезная нагрузка шифра
		$resutCipher = $this->constructCipherText($this->cipherVectorVert, $this->cipherVectorHor, $resultCipherText, $transformMatrixParam, $encryptVersion, $encryptLength_first, $encryptLength_second);

		return $resutCipher;
	}


	/**
	 * Метод возвращает указатель на реальную длину исходного сообщения
	 * Метод создает значение, содержащее информацию о количестве символов исходного сообщения, чтобы поместить его в шифр и при этом не подсвечивать реальное количество фейковых символов или символов исходного сообщения.
	 *
	 * @param string $transformMatrixParam параметры преобразования матриц
	 * @return int
	 */
	private function encryptLengthPointer($transformMatrixParam)
	{
		//Берем максимально допустимое количество символов для шифра ($this->maxFakeLength)(чтобы в том числе нормально обрабатывались строки с максимально допустимым количеством символов), добавляем сумму значений, взятую из параметров формирования матриц и добавляем длину исходной строки
		$resultEncryptLengthPointer = $this->maxFakeLength + array_sum(preg_split('/[a-zа-ё]/iu', $transformMatrixParam)) + mb_strlen($this->text);

		return $resultEncryptLengthPointer;
	}


	/**
	 * Метод получает реальную длину исходной строки из указателя + параметры преобразования матрицы
	 *
	 * @param string $transformMatrixParam параметры преобразования матриц
	 * @param int $transformMatrixParam указатель на реальную длину исходной строки
	 * @return int
	 */
	private function getRealStringLength($transformMatrixParam, $realLengthPointer)
	{
		$realStringLenght = $realLengthPointer - $this->maxFakeLength - array_sum(preg_split('/[a-zа-ё]/iu', $transformMatrixParam));

		return $realStringLenght;
	}


	#Гаврилов
	//ПЕРЕД РЕЛИЗОМ ПОМЕНЯЙ ФУНКЦИИ ХЭШИРОВАНИЯ НА АКТУАЛЬНЫЕ ДЛЯ АКТУАЛЬНОЙ ВЕРСИИ PHP
	/**
	 * Метод формирует хэш для заполнения отрезками из него пространство между символами исходного сообщения для достижения желаемой фейковой длины
	 *
	 * @param string $clearCipherText чистый зашифрованный исходный текст (без фейковых символов и полезной нагрузки)
	 * @param string $salt соль для формирования уникального хэша для каждого вариант шифра исходной строки даже если исходная строка одна и та же. Соль будет уникальна, так как зависит от совокупности полезной нагрузки, используемой для формирования матриц (она генерится случайным образом для каждого шифра)
	 * @return void
	 */
	private function createFakeLengthHash($clearCipherText, $salt)
	{
		$firstHash = hash('sha512', $clearCipherText . $salt);
		$secondHash = hash('whirlpool', $clearCipherText . $salt);
		$thirdHash = hash('sha512', $this->reverseString($firstHash) . $salt);
		$fourthHash = hash('sha512', $this->reverseString($secondHash) . $salt);

		$finalHash = $firstHash . $secondHash . $thirdHash . $fourthHash . $this->reverseString($firstHash) . $this->reverseString($secondHash) . $this->reverseString($thirdHash) . $this->reverseString($fourthHash);
		
		return $finalHash;
	}

	#Гаврилов
	//НЕ БУДЕТ ЛИ СТРАННО ВЫГЛЯДЕТЬ ХЭШ (где в основном буквы и цифры) С НЕБОЛЬШИМ КОЛИЧЕСТВОМ ЗНАКОВ ПРЕПИНАНИЯ и КИРИЛИЧЕСКИХ СИМВОЛОВ ВНУТРИ. МОЖЕТ ВМЕСТО ФУНКЦИЙ ХЭШИРОВАНИЯ ИСПОЛЬЗОВАТЬ САМ ТЕКСТ ШИФРА?

	/**
	 * Метод переворачивает строку задом наперед
	 *
	 * @param string $string
	 * @return string
	 */
	private function reverseString($string)
	{
		$reverseStr = implode('', array_reverse($this->getStrArr($string)));

		return $reverseStr; 
	}

	//ДЛЯ ЭКОНОМИИ МЕСТА В ПОЛЕЗНОЙ НАГРУЗКЕ ШИФРА ИСПОЛЬЗУЙ ОПРЕДЕЛЕННЫЙ АЛГОРИТМ ДЛЯ ПРЕОБРАЗОВАНИЯ ВТОРОЙ МАТРИЦЫ. НЕ РАНДОМНЫЕ ЧИСЛА, КАК ДЛЯ ПЕРВОЙ, А ОСНОВЫВАЯСЬ НА ЧИСЛАХ ДЛЯ ПРЕОБРАЗОВАНИЯ ПЕРВОЙ МАТРИЦЫ
	//НАПРИМЕР В НЕКОТОРЫХ СЛУЧАЯХ ИСПОЛЬЗОВАТЬ ВЕКТОР ИНИЦИАЛИЗАЦИИ ДЛЯ ПРЕОБРАЗОВАНИЯ МАССИВА, А В НЕ КОТОРЫХ НЕТ. НАПРИМЕР КОГДА ОДНО ИЗ ЗНАЧЕНИЙ ИЗ ПОЛЕЗНОЙ НАГРУЗКИ (НАПРИМЕР КОЛИЧЕСТВО ИТЕРАЦИЙ) ДЕЛИТСЯ НА 2 БЕЗ ОСТАТКА, А В ОСТАЛЬНЫХ СЛУЧАЯХ НЕ ИСПОЛЬЗОВАТЬ ВЕКТОРА ИНИЦИАЛИАЦИИ


	/**
	 * Метод дешифровки текста
	 *
	 * @return string
	 */
	public function decryptText()
	{
		//var_dump('##__РАСШИФРОВКА__##');
		$this->encrypt = false;
		//Начинаем очищать шифр от полезной нагрузки, чтобы получить зашифрованную строку
		//Сначала удаляем начальный вектор иницилазиации
		//$clearCipherText = mb_substr($this->text, 2);
		$clearCipherText = mb_substr($this->text, 3);
		//Получаем 2 вектора инициализации с начала и конца строки
		//$vectorVert = mb_substr($this->text, 0, 2);
		$vectorVert = mb_substr($this->text, 0, 3);
		//var_dump("вертикальный вектор $vectorVert");
		//$vectorHor = mb_substr($this->text, -2);
		$vectorHor = mb_substr($this->text, -3);
		//var_dump("горизонтальный вектор $vectorHor");
		$this->initializationVectorVert = $this->getVector($vectorVert, 'vert');
		$this->initializationVectorHor = $this->getVector($vectorHor, 'hor');
		//Получаем строку, содержащую информацию с параметрами преобразования матриц + первая часть версии алгоритма
		//Отрезок с параметрами преобразования матриц изначально состоит из 5 сегментов ([цифра]+[буква]{1}, например, 123d). Однако, в этот отрезок на рандомную позицию вставляется сегмент с первой частью вверсии алгоритма ([какой-то символ]{1}[цифра]+[какой-то символ]{1}), например, (|23!). Итоговый вариант отрезка с параметрами преобразования матрицы - 20e89№19%0о0e22г1169w.
		//Получаем отрезок с параметрами преобразования матриц + первая часть указателя на длину исходной строки - 20e89№19%0о0e22г1169w
		//preg_match('/([^a-zа-ё]+[a-zа-ё]{1}){5}/ui', mb_substr($this->text, 2), $lengthFirstMatches);
		#Гаврилов
		//ЗДЕСЬ И ДАЛЕЕ ЗАМЕНЯЙ ЦИФРУ 3 НА ДЛИНУ ВЕКТОРА ИНИЦИАЛИЗАЦИИ (ЛЮБОГО), А НЕ ХАРДКОРЬ
		preg_match('/([^a-zа-ё]+[a-zа-ё]{1}){5}/ui', mb_substr($this->text, 3), $lengthFirstMatches);
		//var_dump($lengthFirstMatches);
		//Теперь очищаем от полезной нагрузки с параметрами формирования матриц
		$clearCipherText = mb_substr($clearCipherText, mb_strlen($lengthFirstMatches[0]));
		//Здесь вычленяем сегмент с 1й частью указателя на длину исходной строки - №19%
		preg_match('/[^a-zа-ё0-9]{1}[0-9]+[^a-zа-ё0-9]{1}/ui', $lengthFirstMatches[0], $versionMatch);
		//var_dump($versionMatch);
		//Получаем массив параметров преобразования матриц (уже без 1й части фейковой длины) - 20e890о0e22г1169w => [20],[890],[0],[22],[1169]
		//Сначала в виде строки, затем преобразовываем в массив чистых параметров преобразования матрицы
		$transformMatrixString = str_replace($versionMatch[0], '', $lengthFirstMatches[0]);
		//var_dump($transformMatrixString);
		$matrixParamArr = preg_split('/[^0-9]{1}/', str_replace($versionMatch[0], '', $lengthFirstMatches[0]), 0, PREG_SPLIT_NO_EMPTY);
		$matrixParamArr = array_map(function($el){return (int)$el;}, $matrixParamArr);
		//Очищаем сегмент с 1й частью указателя на длину исходной строки от спецсимволов - №19% => 19
		//var_dump("параметры матрицы " . implode('', $transformMatrixArr));
		$fakeLengthFirst = mb_substr($versionMatch[0], 1, mb_strlen($versionMatch[0]) - 2);
		//Получаем сегмент со 2й частью указателя на длину исходной строки
		// preg_match('/([^0-9a-zа-ё]{1}[0-9]*[^0-9a-zа-ё]{1})([0-9a-zа-ё]{0,6}[0-9a-zа-ё]{2})$/ui', $this->text, $lengthSecondMatches);
		//var_dump("первая часть длины $fakeLengthFirst");
		preg_match('/([^0-9a-zа-ё]{1}[0-9]*[^0-9a-zа-ё]{1})([0-9a-zа-ё]{0,6}[0-9a-zа-ё]{3})$/ui', $this->text, $lengthSecondMatches);
		//var_dump($lengthSecondMatches);
		//var_dump($lengthSecondMatches);
		//Очищаем сегмент со 2й частью указателя на длину исходной строки
		//$fakeLengthSecond = mb_substr($lengthSecondMatches[1], 1, mb_strlen($lengthSecondMatches[1]) - 2);
		$fakeLengthSecond = mb_substr($lengthSecondMatches[1], 1, mb_strlen($lengthSecondMatches[1]) - 2);
		//var_dump("вторая часть длины $fakeLengthSecond");
		//Получаем отрезок, содержаший: версию алгоритма (6 символов), 2ю часть указателя на длину исходной строки (длина $lengthSecondMatches[1]), вектор горизонтальной инициализации (2 символа)
		#Гаврилов
		//ПОЧЕМУ ЗДЕСЬ 9? РАНЬШЕ БЫЛО 8 И ЭТО ВСЕ ЛОМАЛО. ИСПОЛЬЗУЙ ОПРЕДЕЛИТЕЛЬ ДЛИНЫ ПЕРЕМЕННЫХ, А НЕ КОНКРЕТНЫЕ ЦИФРЫ
		$cipherVersion = mb_substr($this->text, mb_strlen($this->text) - (9 + mb_strlen($lengthSecondMatches[1])));
		//var_dump($this->text);
		// var_dump($lengthSecondMatches);
		// var_dump($cipherVersion);
		//Теперь очищаем от полезной нагрузки, связанной с версией алгоритма, 2й частью указателя на длину исходной строки и вторым вектором инициализации
		$clearCipherText = mb_substr($clearCipherText, 0, (0 - mb_strlen($cipherVersion)));
		//Получаем чистую версию алгоритма из зашифрованного отрезка, удаляя в строке два последних символа (вектор горизонтальной инициализации) и заменяя на пустоту сегмент, содержащий 2ю часть фейковой длины шифра ($lengthSecondMatches[1])
		//$cipherVersion = $this->getVersion(mb_substr(str_replace($lengthSecondMatches[1], '', $cipherVersion), 0, -2));
		$cipherVersion = $this->getVersion(mb_substr(str_replace($lengthSecondMatches[1], '', $cipherVersion), 0, -3));
		//Здесь дополнительно преобразуем ключ шифрования, так как только в методе setVersion происходит формирование ключа
		$this->windowSizeFirst = $matrixParamArr[0];
		$this->shiftCountFirst = $matrixParamArr[1];
		$this->windowSizeSecond = $matrixParamArr[3];
		$this->shiftCountSecond = $matrixParamArr[4];
		$transformedMatrixParamArr = [];
		if ($this->salt) {
			$this->cipherKey = $this->useSaltToCipherKey($this->cipherKey);
			$this->cipherKey_second = $this->useSaltToCipherKey($this->cipherKey_second);
			$transformedMatrixParamArr = $this->useSaltToMatrixParam($matrixParamArr);
			$this->windowSizeFirst = $transformedMatrixParamArr[0];
			$this->shiftCountFirst = $transformedMatrixParamArr[1];
			$this->windowSizeSecond = $transformedMatrixParamArr[3];
			$this->shiftCountSecond = $transformedMatrixParamArr[4];
		}
		//В матрицу (?)
		$reverseCipherKey = ($matrixParamArr['2'] % 2 === 0) ? 0 : 1;
		//Сдвигаем шифр только после определения версии, так как только на этом этапе происходит определение ключа шифра 
		$mixedCipher = $this->shiftCipherKey($this->cipherKey, $this->windowSizeFirst, $this->shiftCountFirst, $reverseCipherKey);
		#Гаврилов
		//УБЕРИ ПОСЛЕ ТЕСТИРОВАНИЯ
		//$mixedCipher = '7|жJАК3d5цz8ЭуШЖXкЙpчъu:2дВbиЩZЁб4гsWIСFУ}БЗьAmLUэйYяoщёOjvЬrCSN!xвPiюV"{qИЪEа]Kл96о0enЕ;tфQ(HГ№lрОмyзМkDhwхGMa%ЛыД1)Tgc+ФЯРн?НЫеХRтПЧпfЮш[ТсЦB ';
		$reverseCipherKey = ($reverseCipherKey ? 0 : 1);
		$mixedCipherTwo = $this->shiftCipherKey($this->cipherKey_second, $this->windowSizeSecond, $this->shiftCountSecond, $reverseCipherKey);
		#Гаврилов
		//УБЕРИ ПОСЛЕ ТЕСТИРОВАНИЯ
		//$mixedCipherTwo = 'kde}7xЙV:ЬЕ]XГ(fyh1KтSТH щzсЪ6ел|мцTgЛр4бY5Aгзиv)r9ёНI!ЮнСЫйuCУЧЗМо%ФЯхР3tф{"Да8+ЦЭGjUАnчШpПХИ?;шюaдOБQжп[0ЁcJLуякlPqWыэDEZsК2ВMoЩNiBвьО№mbъFЖRw';
		$this->matrixOne = $this->fillMatrix($mixedCipher, (int)substr(array_sum($this->salt ? $transformedMatrixParamArr : $matrixParamArr), -1, 1));
		$this->matrixTwo = $this->fillMatrix($mixedCipherTwo, (int)substr(array_sum($this->salt ? $transformedMatrixParamArr : $matrixParamArr) + 1, -1, 1));
		#Гаврилов
		//УБЕРИ ПОСЛЕ ТЕСТИРОВАНИЯ
		// $this->matrixOne = $this->fillMatrix($mixedCipher, 1);
		// $this->matrixTwo = $this->fillMatrix($mixedCipherTwo, 2);
		$this->transformedMatrixArr[1] = $this->shiftMatrix($this->matrixOne, 1, $this->initializationVectorVert, $this->initializationVectorHor);
		$this->transformedMatrixArr[2] = $this->shiftMatrix($this->matrixTwo, 0, $this->initializationVectorVert, $this->initializationVectorHor);
		#Гаврилов
		//УБЕРИ ПОСЛЕ ТЕСТИРОВАНИЯ
		// $this->transformedMatrixArr[1] = $this->matrixOne;
		// $this->transformedMatrixArr[2] = $this->matrixTwo;
		$realStringLength = $this->getRealStringLength(str_replace($versionMatch[0], '', $lengthFirstMatches[0]), $fakeLengthFirst . $fakeLengthSecond);
		#Гаврилов
		//ИСПОЛЬЗОВАТЬ ДЛЯ ВТОРОЙ МАТРИЦЫ ПРЕОБРАЗОВАННУЮ ОТРЕВЕРШЕННУЮ ПАРУ ИЗ 10 ВЕРСИЙ КЛЮЧЕЙ ШИФРА, ВМЕСТО ПОПЫТОК ПРЕОБРАЗОВАТЬ ПЕРВЫЙ КЛЮЧ ШИФРА

			#Гаврилов
			//ДОБАВЬ СОЛЬ ДЛЯ ТОГО, ЧТОБЫ КАЖДЫЙ ПОЛЬЗОВАТЕЛЬ ПЕРЕДАВАЯ ДАННЫЕ ДЛЯ ЗАШИФРОВКИ ИЛИ РАСШИФРОВКИ ДОПОЛНИТЕЛЬНО ТРАНСФОРМИРОВАЛ ПРОЦЕСС, ИЗМЕНЯЯ КЛЮЧ ДЛЯ МАТРИЦЫ, НО ПРИ ЭТОМ НЕ КЛАДЯ ЭТИ ИЗМЕНЕНИЯ ШИФР. ЭТИ ИЗМЕНЕНИЯ ПРИМЕНЯЮТСЯ ТОЛЬКО ПРИ НАЛИЧИИ СОЛИ ОТ ПОЛЬЗОВАТЕЛЯ
		//Очищенный от фейковых символов текст для расшифровки
		$clearDecryptText = $this->cleanFakeSymb($clearCipherText, $realStringLength);
		$checkFakeSymbols = $this->checkFakeSymbols($this->fakeSymbolString, $this->createFakeLengthHash($clearDecryptText, $transformMatrixString));

		//Если проверка на наполнение фейковыми символами не пройдена возвращаем фейковую строку
		if (!$checkFakeSymbols) {
			return $this->getFakeText();
		}
		#Гаврилов
		//ПРИ СОЗДАНИИ ШИФРА С КООРДИНАТАМИ СИМВОЛОВ МЫ ДОЛЖНЫ ПРИ КАЖДОЙ ИТЕРАЦИИ И ПОИСКА БИГРАММА ПЕРЕСТРАИВАТЬ МАТРИЦУ ЧЕРЕЗ $THIS->transformMatrix()
		// var_dump('ДЕШИФРОВКА');
		// $this->drawMatrix($this->transformedMatrixArr[1]);
		// $this->drawMatrix($this->transformedMatrixArr[2]);
		// var_dump($clearDecryptText);
		//$this->originalSymbCoordArr = $this->createSymbCoords($clearDecryptText);
		//var_dump($coordSymbArr_interim);
		//$coordCiphrSymbArr_interim = $this->createCiphrCoords($clearDecryptText);
		$ecnryptText_interim = $this->createCiphrCoords($clearDecryptText);
		// var_dump('после трансформации');
		// $this->drawMatrix($this->transformedMatrixArr[1]);
		// $this->drawMatrix($this->transformedMatrixArr[2]);
		// //$ecnryptText_interim = $this->createCiphr($coordCiphrSymbArr_interim);
		// var_dump($ecnryptText_interim);
		// die();
		$compareTextHash = $this->getTextHashPointer($ecnryptText_interim, $transformMatrixString);
		//Проверяем совпадает ли указатель на хэш расшифрованной строки с указателем на хэш исходной строки, который содержится в первом и втором векторах инициализации
		if ($compareTextHash['firstVector'] !== $vectorVert || $compareTextHash['secondVector'] !== $vectorHor) {;
			return $this->getFakeText();
		}

		return $ecnryptText_interim; 
	}


	/**
	 * Метод возвращает фейковую строку, преобразуя поступивший для дешфирования текст. Метод вызывается в случае, если пользователю нужно вернуть фейковое сочетание, а не реально получившийся результат. Например, если пользователь заменил один фейковый символ на другой без этого метода ему бы вернулся исходный текст, так как фейковый символ не участвует в шифровании. Этот же метод вернет ему фейковый текст, чтобы он понял, что подменяя символы в шифре нормальный результат не получить
	 *
	 * @return string
	 */
	private function getFakeText(): string
	{
	  	$fakeDecryptText = $this->getStrArr($this->text);
		shuffle($fakeDecryptText);

		return implode('', $fakeDecryptText);
	}


	/**
   * Метод возвращает количество фейковых символов, которое нужно добавить к шифруемому сообщению для достижения желаемой длины
   *
   * @param int $cipherLen желаемая длина шифра (вместе с фейковыми символами)
   * @param string $textCipher шифруемый текст
   * @param string $cipherParamsStr строка со всей полезной нагрузкой, использующейся для шифрования
   * @return int
   */
  private function calcLenFakeSymb($cipherLen, $textCipher, $cipherParamsStr)
  {
   	$fakeSymbCount = $cipherLen - mb_strlen($textCipher) - mb_strlen($cipherParamsStr);

    return ($fakeSymbCount > 0 ? $fakeSymbCount : 0);
  }


	/**
   * Метод очищает зашифрованный текст от фейковых символов
   *
   * @param string $decryptText текст для расшифровки
   * @param int $realStringLenght реальная длина исходного текста
   * @return string
   */
  private function cleanFakeSymb($decryptText, $realStringLenght)
  {
		//На примере строки Zceb4Т0ea002f2fП8c6eХab3fЦ15a0Шe185т2271O61766752108601йb1c4}5af1t99bfЗ325fKb60e541d093b
		//Количество символов в одном сегменте в расшифровываемой строке. Каждый сегмент состоит из одного реального символа исходной строки + фейковые символы из заранее подготовленного хэша. Например, если сегмент равен 5, то в строке из примера первый сегмент будет Zceb4, где Z - реальный символ зашифрованной исходной строки, а ceb4 - отрезок с фейковыми символами
		$symbSegments = floor(mb_strlen($decryptText) / $realStringLenght);
		/**
		 * @var array массив символов расшифровываемой строки
		 */
		$cleanDecryptTextArr = [];
		/**
		 * @var array массив сегментов фейковых символов, которые вставляется между символами шифра изначальной строки
		 */
		$fakeSegmentsArr = [];
		$i = 0;
		/*Перебираем строку с фейковыми символами, разбивая ее на сегменты:
		[Zceb4][Т0ea0][02f2f][П8c6e]...
		из каждого сегмента берем 1й символ и кладем в массив, который является зашифрованной исходной строкой
		[Z][T][0][П]...
		остальные символы кладем в массив с фейковыми символами, которые нужно сравнить с заранее подготовленным хэшем, на основе которого и формировался массив фейковых символов для заполнения шифра исходной строки при шифровании
		[ceb4][0ea0][2f2f][8c6e]...
		*/
		while ($i < $realStringLenght) {
			$cleanDecryptTextArr[] = mb_substr($decryptText, $i * $symbSegments, 1);
			$fakeSegmentsArr[] = mb_substr($decryptText, $i * $symbSegments + 1, $symbSegments - 1);
			$i++;
		}
		/*
		Количество фейковых символов в сегменте округляется. Если среднее количество фейковых символов в одном сегменте = 3.2, в каждый сегмент будет помещаться 3 фейковых символа. Весь остаток фейковых символов после формирования всех сегментов будет помещен в конец строки. Например:
		В исходной строке 14 символов, желаемая длина шифра 123 символа, за вычетом полезной нагрузки шифра, итоговое количество фейковых символов для заполнения - 74. Таким образом, в одном сегменте после каждого символа зашифрованной исходной строки находится 5.28 фейковых символов (74/14). 14 сегментов по 5 фейковых символа = 70 фейковых символов. Нужно еще разместить оставшиеся 4 фейковых символа - они кладутся в конец строки. 
		Ниже мы получаем разницу между длиной переданной в метод расшифровываемой строки и длиной уже сформированных строк: символов исходной строки и фейковых символов. Эта разница - остаток фейковых символов, которые размещены в конце строки
		*/
		$fakeSymbRemainder = mb_strlen($decryptText) - mb_strlen(implode('', array_merge($cleanDecryptTextArr, $fakeSegmentsArr)));
		//Добавляем остаток фейковых символов в конец массива с отрезками фейковых символов только если остаток больше 0. Если он строго равен 0, то функция mb_substr($decryptText, -0) вернет полную строку будто это и есть последний отрезок фейковых символов
		if ($fakeSymbRemainder > 0) {
			$fakeSegmentsArr[] = mb_substr($decryptText, -$fakeSymbRemainder);
		}
		$cleanDecryptText = implode('', $cleanDecryptTextArr);

		$this->fakeSymbolString = implode('', $fakeSegmentsArr);

		return $cleanDecryptText;
  }


	/**
	 * Undocumented function
	 *
	 * @param string $fakeSymbolString фейковые символы, полученные в результате очистки расшифровываемой строки
	 * @param string $cipherTextHash хэш для заполнения исходной строки фейковыми символами
	 * @return bool
	 */
	private function checkFakeSymbols($fakeSymbolString, $cipherTextHash)
	{

		//var_dump($fakeSymbolString);
		$hashComparePart = substr($cipherTextHash, 0, strlen($fakeSymbolString));

		// var_dump($hashComparePart);

		return $hashComparePart === $fakeSymbolString;
		// var_dump($fakeSymbolString);
		// //var_dump($cipherTextHash);
		// // $this->createFakeLengthHash($clearDecryptText, $transformMatrixString);
		// var_dump(substr($cipherTextHash, 0, strlen($fakeSymbolString)));

	}


	#Гаврилов
	//УДАЛИ ИЗ МЭДЖИКА СКРИПТ, КОГДА ПЕРЕДАЕШЬ ЕГО СЕБЕ НА КОМП

	#Гаврилов
	//ПОПРОБУЙ УКАЗАТЬ МАКСИМАЛЬНУЮ ДЛИНУ ТЕКСТА 1000
	//ПОПРОБУЙ УКАЗАТЬ РЕАЛЬНЫЙ ТЕКСТ РОВНО 1000 СИМВОЛОВ И ФЕЙКОВУЮ ДЛИНУ 1000 / 0

	#Гаврилов
	//ПОПРОБОВАТЬ ВСЕ ТАКИ РЕАЛИЗОВАТЬ ПОСЛЕДОВАТЕЛЬНОЕ ЗАПОЛНЕНИЕ ВСЕМИ ФЕЙКОВЫМИ СИМВОЛАМИ РАВНОМЕРНО ПО ВСЕМУ ШИФРУ, А НЕ ПО 1 ФЕЙКОВОМУ СИМВОЛУ ПОСЛЕ КАЖДОГО РЕАЛЬНОГО СИМВОЛА, А ОСТАТОК ТУПО В КОНЦЕ. МОЖНО ПОПРОБОВАТЬ СОБРАТЬ МАССИВ БУКВ ИЗНАЧАЛЬНОГО ТЕКСТА, ЗАТЕМ СОБРАТЬ МАССИВ ФЕЙКОВЫХ СИМВОЛОВ И ПОТОМ ПРОЙДЯСЬ FOREACH ПО ИЗНАЧАЛЬНОМУ ЦИКЛУ С ОРИГИНАЛЬНЫМИ СИМВОЛАМИ ВСТРАИВАТЬ ПОСЛЕ КАЖДОГО СИМВОЛА СИМВОЛ/Ы ИЗ МАССИВА С ФЕЙКОВЫМИ СИМВОЛАМИ
  /**
   * Метод заполняет фейковыми значениями шифруемый текст до достижения желаемой длины
   *
   * @param string $interimCipherText промежуточный зашифрованный текст (без заполненных фейковых символов) 
   * @param string $fakeLengthHash хэш для заполнения исходной строки фейковыми значениями
   * @return string
   */
  private function fillFakeLength($interimCipherText, $fakeLength, $fakeLengthHash)
  {
		// $fakeLengthHash_arr = $this->getStrArr($fakeLengthHash);


    // die();

		//Количество символов в каждом сегменте, который будет проставляться после каждой буквы исходного сообщения. Например если значение = 3, то в строке "test" после каждого символа будет 3 фейковых символа: "t[fr2]e[sdn]s[12f]t[sdz]"
		$fullSegments = floor($fakeLength / mb_strlen($interimCipherText));
		$fullSegmentsArr = [];
		$n = 0;
		while ($n < mb_strlen($interimCipherText))
		{
			$fullSegmentsArr[$n] = mb_substr($fakeLengthHash, $fullSegments * $n, $fullSegments);
			$n++;
		}

		//echo '<pre>'; var_dump($fullSegmentsArr); echo'</pre>';

    // die();

		$splitStr = $this->getStrArr($interimCipherText);
		//echo '<pre>'; var_dump($splitStr); echo'</pre>';

		// $newResultCipher = array_map(function($el) use($splitStr) {return $el . ;}, array_keys($fullSegmentsArr));

		// echo '<pre>'; var_dump($fullSegmentsArr); echo'</pre>';

		$newResultCipher = [];
		foreach ($fullSegmentsArr as $key => $value) {
			$newResultCipher[$key] = $splitStr[$key] . $value;
		}
		//echo '<pre>'; var_dump($newResultCipher); echo'</pre>';
		$newResultCipher = implode('', $newResultCipher);

		//echo '<pre>'; var_dump($newResultCipher); echo'</pre>';
		// echo '<pre>'; var_dump(mb_strlen($newResultCipher)); echo'</pre>';

		$fakeSymbRemainder = $fakeLength + mb_strlen($interimCipherText) - mb_strlen($newResultCipher);
		//echo '<pre>'; var_dump($fakeSymbRemainder); echo'</pre>';

		$fakeSymbRemainderStr = mb_substr($fakeLengthHash, $fullSegments * mb_strlen($interimCipherText), $fakeSymbRemainder);

    // var_dump($fakeLength);
    // var_dump(mb_strlen($interimCipherText));
    // var_dump(mb_strlen($newResultCipher));

    // var_dump($fakeSymbRemainder);
    // var_dump($fakeSymbRemainderStr);

		// echo '<pre>'; var_dump($fakeSymbRemainderStr); echo'</pre>';

		//echo '<pre>'; var_dump($fakeSymbRemainderStr); echo'</pre>';

		$newResultCipher = $newResultCipher . $fakeSymbRemainderStr;
		
		// echo '<pre>'; var_dump($newResultCipher); echo'</pre>';

		// echo '<pre>'; var_dump($newResultCipher); echo'</pre>';
		// echo '<pre>'; var_dump(mb_strlen($newResultCipher)); echo'</pre>';

		return $newResultCipher;

    // $encryptTextArr = $this->getStrArr($encryptText);
    // $i = 0;
    // //Каждая итерация вставляет фейковый символ после каждого символа в шифруемом тексте
    // foreach ($encryptTextArr as &$symbol) {
    //   if ($i < $fakeSymbCount) {
    //     //Вставляем рандомный символ из матрицы-ключа после каждого символа
    //     $symbol .= $this->squares[0][$this->getRandNum(count($this->squares[0]))][$this->getRandNum(count($this->squares[0]))];

    //     $i++;
    //   }
    // }
    // //Как только заполнили фейковым символами шифруемый текст, оставшиеся фейковые символы кладем в конец строки
    // while ($i < $fakeSymbCount) {
    //   $encryptTextArr[] = $this->squares[0][$this->getRandNum(count($this->squares[0]))][$this->getRandNum(count($this->squares[0]))];

    //   $i++;
    // }

    // return implode('', $encryptTextArr);
  }


	/**
	 * Метод возвращает массив с параметрами для формирования матриц
	 *
	 * @param string $transformMatrixInfo строка с параметрами формирования матриц
	 * @return void
	 */
	// private function getTransformMatrixInfo($transformMatrixInfo)
	// {
	// 	#Гаврилов
	// 	//ПРОВЕРЬ КОРРЕКТНО ЛИ РАЗОБЬЕТ СТРОКУ, ЕСЛИ БУДЕТ БУКВА В ВЕРХНЕМ РЕГИСТРЕ
	// 	echo '<pre>'; var_dump($transformMatrixInfo); echo'</pre>';
	// 	$transformInfoArr = preg_split('/[0-9]+[^0-9]{1}/ui', $transformMatrixInfo);

	// 	return $transformInfoArr;
	// }


	/**
	 * Метод сдвига ключа шифра
	 * 
	 * @param string $cipherKey ключ, который нужно преобразовать
	 * @param int $windowSize окно захвата символов, которое сдвигается каждую итерацию. В шифре abj36fh5k окно захвата символов будет (в случае значения 4) abj3 - [abj3]6fh5k
	 * @param int $shiftCount количество итераций сдвига в шифре
	 * @param bool reverseCipherKey флаг реверсивности ключа шифра
	 * @return string
	 */
	private function shiftCipherKey(string $cipherKey, int $windowSize, int $shiftCount, $reverseCipherKey): string
	{
		$transformedCipherKey = $cipherKey;
		//generalIteration - счетчик общего количества итераций
		//stringIteration - счетчик итераций в рамках одного прохода по строке ключу шифра. Сбрасывается как только перебор проходит ключ полностью до конца и должен вернуться в начало
		$generalIteration = $stringIteration = 0;
		//Шаг сдвига окна (в большую или меньшую сторону) после каждой итерации.
		$shiftSize = 1;
		//Флаг увеличения окна захвата. При каждой итерации окно захвата уменьшается. Дойдя до минимума, увеличивается
		$increaseVector = false;
		while ($generalIteration < $shiftCount) {
			$leftPart = ($stringIteration ? $stringIteration * ($windowSize + $shiftSize) : null);	//Левая часть строки, не участвующая в перемешивании
			$rightPart = mb_strlen($transformedCipherKey) - $leftPart - $windowSize - $shiftSize;	//Права часть строки, не участвующая в перемешивании
			//Если мы дошли до конца строки и правая часть строки содержит меньше 0 символов - строка закончилась, возвращаемся в начало и повторяем перемешиваниеs
			if ($rightPart < 0) {
				if ($reverseCipherKey) {
					$transformedCipherKey = implode('', array_reverse(preg_split('//u', $transformedCipherKey, -1, PREG_SPLIT_NO_EMPTY)));
				}
				$leftPart = $windowSize + $shiftSize + $rightPart;
				//Перерасчитываем правую часть после пересчета остальных частей
				$rightPart = mb_strlen($transformedCipherKey) - $leftPart - $windowSize - $shiftSize;
				//При достижении конца строки сбрасываем количество итераций пробега по строке
				$stringIteration = 0;
			}
			$pattern = "/" . ($leftPart ? "(.{" . $leftPart . "})" : null) . "(.{" . $windowSize . "})(.{" . $shiftSize . "})(.{" . $rightPart . "})/u";
			$replacement = ($leftPart ? '${1}${3}${2}${4}' : '${2}${1}${3}');
			//Само действие перестановки кусков шифра
			$transformedCipherKey = preg_replace($pattern, $replacement, $transformedCipherKey);
			//Если вектор увеличения отключен, окно захвата символов уменьшается, промежуток пропуска символов увеличивается.
			if ($increaseVector == false) {
				$windowSize--;
				$shiftSize++;
				//Как только окнок захвата уменьшается до нуля, вектор увеличения включается - окно захвата увеличивается, промежуток пропуска символов уменьшается
				if ($windowSize == 0) {
					//Присваиваем двойку, а не единицу, так как единица была в последней итерации (перед уменьшением до нуля)
					$windowSize = 2;
					$shiftSize = $windowSize - 1;
					$increaseVector = true;
				}
			} else {
				$windowSize++;
				$shiftSize--;
				//Как только окнок захвата достигает исходного размера окна захвата ($this->windowSizeStart), вектор увеличения выключается - окно захвата уменьшается, промежуток пропуска символов увеличивается
				if ($windowSize > $windowSize) {
					$windowSize = $windowSize - 1;
					$shiftSize = 1;	//Изначальные условия - промежуток пропуска равняется 1му (окно захвата может быть равно изначальному размеру окна захвата, эту величину можем не менять)
					$increaseVector = false;
				}
			}
			$generalIteration++;
			$stringIteration++;
		}
		return $transformedCipherKey;
	}


	/**
	 * Набор итогового зашифрованного текста
	 *
	 * @param string $cipherText зашифрованный текст
	 * @return string
	 */
	private function constructCipherText($vectorVert, $vectorHor, $cipherText, $matrixParams, $cipherVersion, $fakeLenFirst, $fakeLenSecond)
	{
		// echo '<pre>'; var_dump("вертикальный вектор $vectorVert"); echo'</pre>';
		// echo '<pre>'; var_dump("горизонтальный вектор $vectorHor"); echo'</pre>';
		// echo '<pre>'; var_dump("параметры матрицы $matrixParams"); echo'</pre>';
		// echo '<pre>'; var_dump("фейковая длина " . $fakeLenFirst . $fakeLenSecond); echo'</pre>';
		//Позиция разделения строки с параметрами матриц после которогой будет вставлена первая часть фейковой длины. Например, параметры преобразования матрицы - 20e890о0e22г1169w, первая часть версии - №1%, позиция разделения - 5. Итоговый сегмент с параметрами преобразования матрицы + первой частью версии алгоритма - 20e89№1%0о0e22г1169w
		//По аналогии работает шифрование второй части фейковой длины с версией алгоритма ниже в этом методе
		//Максимальная позиция  куда вставляется отрезок с первой частью указателя на длину исходной строки ( №1% ) должна быть равна длине сегмента с параметрами матрицы ( 20e890о0e22г1169w ) минус 1, так как если позиция будет равна длине сегмента, отрезок вставится в самый конец сегмента ( 20e890о0e22г1169w№1% ) и при расшифровке будет проблема
		$matrixParamsDelimetr = $this->getRandNum(mb_strlen($matrixParams));
		$transformMatrixParams = mb_substr($matrixParams, 0, $matrixParamsDelimetr) . $fakeLenFirst . mb_substr($matrixParams, $matrixParamsDelimetr);
		//echo '<pre>'; var_dump($cipherVersion); echo'</pre>';
		//Место разделения строки с параметрами матриц после которогой будет вставлена первая часть фейковой длины
		$cipherVerDelimetr = $this->getRandNum(mb_strlen($cipherVersion) + 1);
		//echo '<pre>'; var_dump($cipherVerDelimetr); echo'</pre>';
		$transformСipherVer = mb_substr($cipherVersion, 0, $cipherVerDelimetr) . $fakeLenSecond . mb_substr($cipherVersion, $cipherVerDelimetr);
		//echo '<pre>'; var_dump($transformСipherVer); echo'</pre>';
		$resultCipherText = $vectorVert . $transformMatrixParams . $cipherText . $transformСipherVer . $vectorHor;

		return $resultCipherText;
	}


	//ДЛЯ ЗАПОЛНЕНИЯ ВТОРОЙ МАТРИЦЫ ИСПОЛЬЗОВАТЬ ДРУГОЙ РАНДОМНЫЙ МЕТОД ФОРМИРОВАНИЯ 
	//Заполнение матриц
	/**
	 * Метод заполнения матрицы
	 *
	 * @param string $mixedCipherKey преобразованный ключ шифра
	 * @param int $pattern ключ паттерна заполнения матрицы
	 * @return void
	 */
	private function fillMatrix($mixedCipherKey, $pattern)
	{
		$matrixOne = [];
		$cipherKeyArr = preg_split('//u', $mixedCipherKey, -1, PREG_SPLIT_NO_EMPTY);
		$x = $y = 0;

		switch ($pattern) {
			/*Заполнение по строкам сверху внизу слева направо на примере массива 4х4 [1,2...16]
				[1,   2,   3,   4]
				[5,   6,   7,   8],
				[9,   10,  11,  12],
				[13,  14,  15,  16]
			*/
			case 0:		
			case 1:
				while ($x < $this->matrixDepth)
				{
					while ($y < $this->matrixDepth)
					{
						$matrixOne[$x][$y] = $cipherKeyArr[$x * $this->matrixDepth + $y];
						$y++;
					}
					$y = 0;
					$x++;
				}
			break;
			//сверху вниз справа налево
			/*Заполнение по строкам сверху внизу справа налево на примере массива 4х4 [1,2...16]
				[4,   3,   2,   1]
				[8,   7,   6,   5],
				[12,  11,  10,  9],
				[16,  15,  14,  13]
			*/
			case 2:
			case 3:
				$y = $this->matrixDepth - 1;
				while ($x < $this->matrixDepth)
				{
					while ($y >= 0)
					{
						$matrixOne[$x][$y] = $cipherKeyArr[$x * $this->matrixDepth + $y];
						$y--;
					}
					ksort($matrixOne[$x]);
					$y = $this->matrixDepth - 1;
					$x++;
				}
			break;
			/*Заполнение по строкам снизу вверх слева направо на примере массива 4х4 [1,2...16]
				[13, 14,  15,  16]
				[9,  10,  11,  12],
				[5,  6,   7,   8],
				[1,  2,   3,   4]
			*/
			case 4:
			case 5:
				$x = $this->matrixDepth - 1;
				while ($x >= 0)
				{
					while ($y < $this->matrixDepth)
					{
						$matrixOne[$x][$y] = $cipherKeyArr[$x * $this->matrixDepth + $y];
						$y++;
					}
					$y = 0;
					$x--;
				}
				break;
			/*Заполнение по строкам снизу вверх справа налево на примере массива 4х4 [1,2...16]
				[13, 15,  14,  13]
				[12, 11,  10,  9],
				[8,  7,   6,   5],
				[4,  3,   2,   1]
			*/
			case 6:
			case 7:
				$x = $this->matrixDepth - 1;
				$y = $this->matrixDepth - 1;
				while ($x >= 0)
				{
					while ($y >= 0)
					{
						$matrixOne[$x][$y] = $cipherKeyArr[$x * $this->matrixDepth + $y];
						$y--;
					}
					ksort($matrixOne[$x]);
					$y = $this->matrixDepth - 1;
					$x--;
				}
			break;
			/*Заполнение "уголком" на примере массива 4х4 [1,1...16]
				[1,  2,  3,   4]
				[8,  9,  10,  5],
				[13, 14, 11,  6],
				[16, 15, 12,  7]
			*/
			case 8:
			case 9:
				$xRow = $t = 0;
				$yCol = 0;
				while ($xRow < $this->matrixDepth) 
				{
					$yRow = 0;
					while ($yRow < $this->matrixDepth - $xRow) {
						$matrixOne[$xRow][$yRow] = $cipherKeyArr[$t];
						$yRow++;
						$t++;
					}
					$yCol = $this->matrixDepth - 1 - $xRow;
					$xCol = $xRow + 1;
					while ($xCol < $this->matrixDepth) {
						$matrixOne[$xCol][$yCol] = $cipherKeyArr[$t];
						$t++;
						$xCol++;
					}
					//Без этой сортировки ключи массивов начинаются не по порядку в плане чисел НАПРИМЕР! 0,1,2,3,4,5,6,7,8,9,10,11 а по порядку в порядке заполнения 0,1,11,3,4,10,5,6,9,7,8
					ksort($matrixOne[$xRow]);
					$xRow++;
				}
				break;

		}	

		return $matrixOne;
	}


	#Гаврилов
	//ВЫДЕЛИТЬ СОВПАДАЮЩИЙ ЧАСТИ КОДА ИЗ ШИФРОВАНИЯ И ДЕШИФРОВАНИЯ ВЕРСИИ И ОФОРМИТЬ ОТДЕЛЬНЫМИ МЕТОДАМИ
	/**
	 * Метод получения версии шифра
	 *
	 * @param string $versionString зашифрованное представление версии шифра
	 * @return int
	 */
	private function getVersion(string $versionString): int
	{
		//Массив кирилических и латинских букв и цифр, которые участвовали в формировании версии
		$lettersArr = $this->cyrilicLetters + $this->latinLetters;
		$numberArr = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
		$versionSymbArr = $this->getStrArr($versionString);
		//Вычленяем числа из строки с шифром
		$numberArr  = array_values(array_intersect($versionSymbArr, $numberArr));
		//Вычленяем буквы из строки с шифром
		$letterArr  = array_values(array_diff($versionSymbArr, $numberArr));
		//Определяем паттерн размещения
		$pattern = $numberArr[0];
		//Получаем флаг реверса 
		$reverseLettersArr = (($numberArr[1] % 2 === 0) ? 0 : 1);
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
		$this->cipherKey = $this->cipherKeyStorage[substr($version, -1)];
		$this->cipherKey_second = $this->cipherKeyStorage[substr($version, -1) == (count($this->cipherKeyStorage) - 1) ? 0 : substr($version, -1) + 1];
		#Гаврилов
		//УБЕРИ ПОСЛЕ ТЕСТИРОВАНИЯ
		// $this->cipherKey = 'йcЛuoэ№%hnqЕAж4afг!BJТv;шУ6HХпрsцXщл+ф3сLЪgQЗхкюtб)ШOПiKxyUФъчzPн}[2dm1ЦРЯ(WАз9НЫ7вGlот ЖО{иьb5ГIEа8ЭYZ:jFИуё0КДя]ЙweЧrЁ?ыВDNЮЩ|"МCдеTpБСЬkVSMRм';
		// $this->cipherKey_second = 'gUЙИ{XohjTЁя]хBШУцрc8ъеs3Y+ФSкДqC0ЧRп%DсЦVжСE(дmPыОAdЯимзвy5bёxL?М"}Г!ЪOгКpщ2aMншПNРфfk lоuХ№FЭ1IQZn|ЬтТ9eюэБiGуrЫьЛЖ4ВЗчЩW6wJАл7Kt;ЮЕа:йzбHН[v)';

		return (int)$version;
	}


	/**
	 * Метод формирования версии конкретного шифра. Версия самого алгоритма/скрипта фиксирована и прописывается в свойстве класса $this->version, метод ниже определяет каким образом версия конкретного шифра будет сформирована
	 * Версия состоит из 6ти значений: 3 значения - чистая версия шифра + 3 значения - параметры формирования итоговой версии шифра версии, на основании которых все элементы будут перемешаны
	 * Например. Версия алгоритма - 12, используемый индекс ключа для матриц - 3. Итоговая версия шифра - 123. Паттерн преобразования цифр шифра в буквы - 4 (версия шифра 123 преобразуется в 'bsg'), флаг реверсивности - 0, рандомное число - 8. Дальше эти значения перемешиваются, полная зашифрованная версия шифра - b40sg8
	 *
	 * @return string
	 */
	private function setVersion(): string
	{
		$lettersArr = $this->cyrilicLetters + $this->latinLetters;
		/*Паттерн преобразования цифр версии в буквы. Цифры версии будут преобразованы в буквы, ориентируясь на массив [a=>0, b=>1...]. Версия всегда состоит из 3х символов: 2 первых - версия, 3 - индекс используемого ключа из массива $this->cipherKeyStorage. 
		Ниже результаты применения паттерна на примере версии 123 (в скобках буквы, которые будут возвращены вместо цифр в случае не реверсивного массива букв). Например, версия 123 в случае паттерна №1 будет преобразована в бвг:
		№1 - 1(б)_2(в)_3(г)
		№2 - 12(л)_3(г)_rand
		№3 - 1(б)_23(ц)_rand
		№4 - rand_12(л)_3(г)
		№5 - rand_1(б)_23(ц)
		*/
		$pattern = $this->getRandNum(6);
		//Индекс ключа шифрования на основании которого будет формироваться 1 матрица
		$cipherKeyIndex = $this->getRandNum(10);
		//Версия алгоритма + индекс рандомного ключа
		$cipherVersion = $this->version . $cipherKeyIndex;
		#Гаврилов
		//ВЫНЕСИ ФОРМИРОВАНИЕ КЛЮЧА ШИФРОВАНИЯ ЗА ПРЕДЕЛЫ ДАННОГО МЕТОДА
		$this->cipherKey = $this->cipherKeyStorage[$cipherKeyIndex];
		#Гаврилов
		//УБЕРИ ПОСЛЕ ТЕСТИРОВАНИЯ
		//$this->cipherKey = 'йcЛuoэ№%hnqЕAж4afг!BJТv;шУ6HХпрsцXщл+ф3сLЪgQЗхкюtб)ШOПiKxyUФъчzPн}[2dm1ЦРЯ(WАз9НЫ7вGlот ЖО{иьb5ГIEа8ЭYZ:jFИуё0КДя]ЙweЧrЁ?ыВDNЮЩ|"МCдеTpБСЬkVSMRм';
		//Ключ второго шифра для формирования второй матрицы строится на основании другого ключа из массива $this->cipherKeyStorage (следующего ключ после ключа первой матрицы, либо первый ключ массива, если ключ для первый матрицы оказался последним в массиве)
		$this->cipherKey_second = $this->cipherKeyStorage[$cipherKeyIndex == (count($this->cipherKeyStorage) - 1) ? 0 : $cipherKeyIndex + 1];
		#Гаврилов
		//УБЕРИ ПОСЛЕ ТЕСТИРОВАНИЯ
		//$this->cipherKey_second = 'gUЙИ{XohjTЁя]хBШУцрc8ъеs3Y+ФSкДqC0ЧRп%DсЦVжСE(дmPыОAdЯимзвy5bёxL?М"}Г!ЪOгКpщ2aMншПNРфfk lоuХ№FЭ1IQZn|ЬтТ9eюэБiGуrЫьЛЖ4ВЗчЩW6wJАл7Kt;ЮЕа:йzбHН[v)';
		//Флаг реверсивности (относится только к формированию версии, к реверсивности других параметров шифра отношения не имеет). Второе число в массиве параметров формирования версии. Если число четное - массив с буквами/цифрами, на основании которого цифры версии преобразуются в буквы, не реверсим, иначе реверсим. Дополнительный фактор запутывания
		$reverseLettersArr = $this->getRandNum(10);
		if (!($reverseLettersArr % 2 === 0)) {
			$lettersArr = array_combine(array_keys($lettersArr), array_reverse(array_values($lettersArr)));
		}
		//Бьем версию на массив цифр
		$versionSymbArr = str_split((string)$cipherVersion);
		//Массив цифр, участвующих в шифровании версии алгоритма
		//1 цифра - паттерн набора версии
		//2 цифра - флаг реверсивности массива букв, на основании которого цифры версии будут преобразовываться в буквы. Например версия 123 [1,2,3] должна преобразоваться в буквы на основании массива [a,b,c]. Если флаг реверсивности 0 - версия будет abc, если 1 - cba
		//3 цифра - рандомная (ВОЗМОЖНОСТЬ РАЗМЕСТИТЬ ПОЛЕЗНУЮ НАГРУЗКУ)
		$cipherNumArr = [$pattern, $reverseLettersArr, $this->getRandNum(10)];
		//Массив всех символов версии, на основании которого будет сформирована итоговая строка с версией
		$cipherSymbArr = [];
		//Ниже мы определеяем порядок размещения букв и цифр, которые будут участвовать в шифровании версии алгоритма (в зависимости от паттерна, определенного выше). Буквы означают цифры шифра. Например, версия алгоритма 123 в буквенном выражении "fpa" 
		switch ($pattern) {
			case 1:
				$cipherSymbArr = array_map(function($el) use($lettersArr) {return array_search((int)$el, $lettersArr);}, $versionSymbArr);
				break;
			case 2:
				$cipherSymbArr[] = array_search((int)$versionSymbArr[0], $lettersArr);
				$cipherSymbArr[] = array_search((int)($versionSymbArr[1] . $versionSymbArr[2]), $lettersArr);
				$cipherSymbArr[] = array_rand($lettersArr);
				break;
			case 3:
				$cipherSymbArr[] = array_search((int)($versionSymbArr[0] . $versionSymbArr[1]), $lettersArr);
				$cipherSymbArr[] = array_search((int)$versionSymbArr[2], $lettersArr);
				$cipherSymbArr[] = array_rand($lettersArr);
				break;
			case 4:
				$cipherSymbArr[] = array_rand($lettersArr);
				$cipherSymbArr[] = array_search((int)$versionSymbArr[0], $lettersArr);
				$cipherSymbArr[] = array_search((int)($versionSymbArr[1] . $versionSymbArr[2]), $lettersArr);
				break;
			case 5:
				$cipherSymbArr[] = array_rand($lettersArr);
				$cipherSymbArr[] = array_search((int)($versionSymbArr[0] . $versionSymbArr[1]), $lettersArr);
				$cipherSymbArr[] = array_search((int)$versionSymbArr[2], $lettersArr);
				break;
		}
		$resultVersionArr = [];
		//Цикл ниже должен объединить массив цифр и массив букв таким образом, чтобы относительная последовательность символов в итоговом массиве совпадала с последовательностью символов входящих в него подмасивов. Например, в случае объединения двух массивов [1, 2, 3] и ['a', 'b', 'c']
		//Подходящие результаты [1, 2, 3, 'a', 'b', 'c'] или [1, 'a', 'b', 'c', 2, 3] или ['a', 'b', 1, 2, 'c', 3]
		//Неподходящие результаты: [3, 2, 'a', 'b', 'c', 1] или [1, 'a', 2, 'c', 3, 'b']
		//Это позволит, получив 6 символов, вычленить из них отдельно буквы и отдельно цифры в том же порядке, в каком они были объединены
		$n = true;
		while($n)
		{
			if (!count($cipherNumArr) && !count($cipherSymbArr)) {
				$n = false;
			}
			if ($this->getRandNum(3) === 1) {
				if (count($cipherNumArr) > 0) {
					$symb = array_shift($cipherNumArr);
					$resultVersionArr[] = $symb;
				}
			} else {
				if (count($cipherSymbArr) > 0) {
					$symb = array_shift($cipherSymbArr);
					$resultVersionArr[] = $symb;
				}
			}
		}
		$resultVersion = implode('', $resultVersionArr); 

		return $resultVersion;
	}


	/**
	 	* Сдвигаем матрицы в соответствии с векторами инициализации
	 	*
		* @param array $matrix матрица для преобразования
		* @param boolean $pattern паттерн преобразования: 1 - столбцы/строки, 0 - строки/столбцы
		* @param int $vertical вертикальный вектор инициализации (сдвиг столбцов)
		* @param int $horizon горизонтальный вектор инициализации (сдвиг строк)
		* @return void
	*/
	private function shiftMatrix($matrix, $pattern, $vertical, $horizon)
	{
			//Преобразованная матрица
			$transformedMatrix = $matrix;

			if ($pattern == 0) {
				$vectorRow = $vertical;
				$vectorCol = $horizon;
			} else {
				$vectorCol = $vertical;
			$vectorRow = $horizon;
			}

		//Сдвигаем строки матрицы
		//foreach ($this->squares as $squareNum => &$squareArr) {
		//Наоборот сдвигаем строки по вертикальному вектору
		
		$row = 1;
		while ($row <= $vectorRow) {
			//Строки сдвигаем в конец матрицы
			$shiftedRow = array_shift($transformedMatrix);
			$transformedMatrix[] = $shiftedRow;
			$row++;
		}
		//}
		//Сдвигаем столбцы матрицы
		// foreach ($this->squares as $squareNum => &$squareArr) {
		foreach ($transformedMatrix as &$rowArr) {
			$col = 1;
			while ($col <= $vectorCol) {
			//Столбцы с конца матрицы двигаем в начало
			$shiftedCol = array_pop($rowArr);
			array_unshift($rowArr, $shiftedCol);
			$col++;
			}
		}
		// }

			return $transformedMatrix;

		//$this->shiftCol($offsetVert + $offsetHor);
	}



	/**
	 * Метод должен трансформировать матрицу после каждого найденного символа, чтобы следующий найденный символ (если он на той же позиции) имел координаты, так как будет уже на другой позиции
	 *
	 * @param int $matrixNum порядковый номер матрицы (1й квадрат Полибия или 2й)
	 * @param int $iterationCount порядковый номер итерации шифрования
	 * @param array $symbCoord массив с координатами символа в матрице (строка/столбец)
	 * @return void
	 */
	private function transformMatrix(int $matrixNum, int $iterationCount, array $symbCoord): void
	{
		$transformedMatrix = ($matrixNum == 1 ? $this->transformedMatrixArr[1] : $this->transformedMatrixArr[2]);
		$symbol = $transformedMatrix[$symbCoord[0]][$symbCoord[1]];
		// var_dump($matrixNum);
		// var_dump($iterationCount);
		// var_dump($symbCoord);
		// var_dump($symbol);
		#Гаврилов
		//ПОПРОБУЙ ЧТО БУДЕТ ЕСЛИ В ТЕКСТЕ БУДЕТ НЕШИФРУЕМЫЙ СИМВОЛ
		//В зависимости от того четный или нечетный символ (если это цифра), сдвигаем его либо по столбцам, либо по рядам матрицы
		//В зависимости от того какой порядковый номер у ширфуемого элемента в ключе шифра (четный или нечетный) определяем меняем матрицу по столбцам или по строкам. Номер итерации прибавляем, чтобы гарантировать, что в 50% процентах будет смена четности на нечетность (четный ключ + четная итерация = четное число, нечетный ключ + нечетная итерация = четное чисо, нечетный ключ + четная итерация = нечетное число, четный ключ + нечетная итераия = нечетное число)
		$transformedVector = ((array_search($symbol, $this->getStrArr(($matrixNum == 1 ? $this->cipherKey : $this->cipherKey_second))) + $iterationCount) % 2 !== 0) ? 'row' : 'col';
		//Номер итерации шифрования прибавляем для того, чтобы гарантировать чередовать нечетность и нечетность даже если шифруется один тот же символ с одинаковыми координатами (описал подробнее выше по коду)
		$coordSumm = (int)$symbCoord[0] + (int)$symbCoord[1] + $iterationCount;
		#Гаврилов
		//ЧТО ЕСЛИ ВЫПАДЕТ 0? НЕ БУДЕТ МЕНЯТЬСЯ СИМВОЛ?
		$newCoordOne = (int)substr((string)$coordSumm, -1);
		//Новые координаты символа при сдвиге должны отличаться от предыдущих как по столбцу, так и по строке
		$newCoordOne = ($newCoordOne == $symbCoord[$transformedVector === 'row' ? 1 : 0] ? ($newCoordOne + 1 == $this->matrixDepth ? $newCoordOne - 1 : $newCoordOne + 1) : $newCoordOne);
		$newCoordTwo = (int)substr((string)$coordSumm, 0, 1);
		//Новые координаты символа при сдвиге должны отличаться от предыдущих как по столбцу, так и по строке
		$newCoordTwo = ($newCoordTwo == $symbCoord[$transformedVector === 'row' ? 0 : 1] ? ($newCoordTwo + 1 == $this->matrixDepth ? $newCoordTwo - 1 : $newCoordTwo + 1) : $newCoordTwo);
		if ($transformedVector === 'row') {
			$transformedSymb = ($transformedMatrix[$newCoordTwo][$newCoordOne]);
			$transformedMatrix[$symbCoord[0]][$symbCoord[1]] = $transformedSymb;
			$transformedMatrix[$newCoordTwo][$newCoordOne] = $symbol;
 		} else {
			#Гаврилов
			//МОЖЕТ ЛИ ТАКОЕ БЫТЬ, ЧТО ПО СТРОКЕ СИМВОЛ БУДЕТ ЗАМЕНЕН НА СИМВОЛ ИСХОДНЫЙ? НАПРИМЕР, МА => БД . СИМВОЛ Д СМЕЩАЕМ ПО СТРОКЕ ГДЕ НАХОДИТСЯ А. ЧТО ТОГДА БУДЕТ? ЗАТЕСТИ
			$transformedSymb = ($transformedMatrix[$newCoordOne][$newCoordTwo]);
			$transformedMatrix[$symbCoord[0]][$symbCoord[1]] = $transformedSymb;
			$transformedMatrix[$newCoordOne][$newCoordTwo] = $symbol;
		}
		if ($matrixNum == 1) {
			$this->transformedMatrixArr[1] = $transformedMatrix;
		} else {
			$this->transformedMatrixArr[2] = $transformedMatrix;
		}
	}



	/**
	 * Метод формирует массив с координатами символов исходного сообщения
	 * 
	 * @param string $text текст требующий преобразования
	 * @return array массив символов исходного текста с координатами из матриц
	 */
	private function createSymbCoords($text)
	{
		$symbCoordArr = [];
		$transformedMatrixNum = 0;
		//Бьем фразу для шифрования по символам на массив и ищем каждый символ в матрицах
		foreach ($this->getStrArr($text) as $symbKey => $symbol) {
			$transformedMatrixNum = 0;
			//Определяем в какой матрице ищем символ. При шифровке если элемент четный - в 1й, если нечетный - во 2й. При дешифровке обратная ситуация
			if ($this->encrypt) {
				if ($symbKey % 2 !== 0) {
					$matrix = $this->transformedMatrixArr[1];
					$transformedMatrixNum = 1;
				} else {
					$transformedMatrixNum = 2;
					$matrix = $this->transformedMatrixArr[2];
				}
				//$matrix = ($symbKey % 2 !== 0 ? $this->transformedMatrixArr[2] : $this->transformedMatrixArr[1]);
			} else {
				if ($symbKey % 2 !== 0) {
					$matrix = $this->transformedMatrixArr[2];
					$transformedMatrixNum = 2;
				} else {
					$transformedMatrixNum = 1;
					$matrix = $this->transformedMatrixArr[1];
				}
				//$matrix = ($symbKey % 2 !== 0 ? $this->transformedMatrixArr[1] : $this->transformedMatrixArr[2]);
			}

			//Флаг - был ли найден символ в матрицах. Если нет - символ не шифруется
			$findSymbol = false;
			foreach ($matrix as $matrixRow => $rowData) {
				if (($symbolCol = array_search($symbol, $rowData, true)) !== false) {
					var_dump($rowData);
					var_dump($symbol);
					// var_dump($matrixRow);
					// var_dump($symbolCol);
					$symbCoordArr[] = [$matrixRow, $symbolCol];
					$findSymbol = true;
					//При дешифровке мы трансформируем матрицу при формировании массива с координатами символов, так как в случае дешифровки эти символы - символы шифра, а они должны сдвигаться, так как символы исходного сообщения двигаться не должны в матрице
					if (!$this->encrypt) {
						//Если символ не имеет пары (последний символ в строке, притом четный, либо если символ не найден в матрицах)
						// if (($grammKey = $this->returnNearbyGramm($symbKey)) === false || !$findSymbol) {
						// 	var_dump($grammKey);
						// } else {
						// 	var_dump($grammKey);
						// }
						$this->transformMatrix($transformedMatrixNum, $symbKey, [$matrixRow, $symbolCol]);
						// var_dump('итерация дешифровки - ' . $symbKey);
						// var_dump($symbol);
						// var_dump($transformedMatrixNum);
						// var_dump([$matrixRow, $symbolCol]);
						
						// $this->drawMatrix($this->transformedMatrixArr[1]);
						// $this->drawMatrix($this->transformedMatrixArr[2]);	
					}
					
					

					break;
				}
			}
			//Если символ не найден в матрицах - не шифруем его
			if (!$findSymbol) {
				$symbCoordArr[] = $symbol;
			}
		}
		return $symbCoordArr;
	}


	/**
	 * Метод формирует массив с координатами символов зашифрованного сообщения
	 *
	 * @param array $coordSymbArr массив с координатами символов исходного текста из матриц
	 * @return array массив символов преобразованного текста с координатами из матриц
	 */
	private function createCiphrCoords(string $text)
	{
		$bigram = [];
		$bigramCoords = [];
		// var_dump($this->originalSymbCoordArr);
		//var_dump($coordSymbArr);
		$interimCoordArr = [];
		//Промежуточный массив символов шифра без координат
		$interimSymbArr = [];
		$ciphrCoordArr = [];
		$transformedMatrixNum = 0;
		//$matrix = null;
		#Гаврилов
		//ПРОБУЙ НЕРАСПОЗНАННЫЙ СИМВОЛЫ
		foreach ($this->getStrArr($text) as $symbKey => $symbol) {
			// var_dump('итерация шифрования - ' . $symbKey);
			// var_dump("символ - $symbol");
			/**
				 * При дешифровании мы делим строку шифра на биграммы и работаем с координатами символов биграммы. Так как при дешифровке при определении координат каждого символа исходной строки символ шифра сдвигается и использовать его координаты в следующей итерации не получится
				 */
				
			if ($symbKey % 2 !== 0) {
				//$bigram = $this->transformedMatrixArr[1][$bigramCoords[0][0]][$bigramCoords[0][1]] . mb_substr($text, 1 * $symbKey, 1);
				// $bigram = mb_substr($text, 1 * ($symbKey - 1), 2);
				$transformedMatrixNum = ($this->encrypt ? 1 : 2);
				#Гаврилов
				//ПРОВЕРЬ КАК ОТРАБОТАЕТ КОГДА В СТРОКЕ ОКАЖУТСЯ НЕШИФРУЕМЫЕ СИМВОЛЫ
				//Пока подразумевается, что если симолы нешифруемы - они вместо координат размещаются в массиве биграммы в виде самих символов. Чтобы скрипт ниже мог понять, что символ не шифруем (если всместо координат сами символы)
				// $firstBigramSymbCoord = $this->getSymbCoords($this->transformedMatrixArr[$this->encrypt ? 2 : 1], mb_substr($bigram, 0, 1));
				// $secondBigramSymbCoord = $this->getSymbCoords($this->transformedMatrixArr[$this->encrypt ? 1 : 2], mb_substr($bigram, 1, 1));
				// //$bigramCoords[0] = $firstBigramSymbCoord ? $firstBigramSymbCoord : mb_substr($bigram, 0, 1);
				// $bigramCoords[1] = $secondBigramSymbCoord ? $secondBigramSymbCoord : mb_substr($bigram, 1, 1);

				// $bigrammSum_CORRECT = array_sum($bigramCoords[0]) + $bigramCoords[1][0] + $bigramCoords[0][0];
				// $bigrammSum_CURRENT = array_sum($bigramCoords[0]) + array_sum($bigramCoords[1]);
				// var_dump("ВЕРНАЯ СУММА - $bigrammSum_CORRECT");
				// var_dump("ТЕКУЩАЯ СУММА - $bigrammSum_CURRENT");

			} else {
				$bigram = mb_substr($text, 1 * $symbKey, 2);
				$transformedMatrixNum = ($this->encrypt ? 2 : 1);
				
				#Гаврилов
				//ПРОВЕРЬ КАК ОТРАБОТАЕТ КОГДА В СТРОКЕ ОКАЖУТСЯ НЕШИФРУЕМЫЕ СИМВОЛЫ
				//Пока подразумевается, что если симолы нешифруемы - они вместо координат размещаются в массиве биграммы в виде самих символов. Чтобы скрипт ниже мог понять, что символ не шифруем (если всместо координат сами символы)
				$firstBigramSymbCoord = $this->getSymbCoords($this->transformedMatrixArr[$this->encrypt ? 2 : 1], mb_substr($bigram, 0, 1));
				$secondBigramSymbCoord = $this->getSymbCoords($this->transformedMatrixArr[$this->encrypt ? 1 : 2], mb_substr($bigram, 1, 1));
				$bigramCoords[0] = $firstBigramSymbCoord ? $firstBigramSymbCoord : mb_substr($bigram, 0, 1);
				$bigramCoords[1] = $secondBigramSymbCoord ? $secondBigramSymbCoord : mb_substr($bigram, 1, 1);
				// var_dump($bigram);
				// var_dump($bigramCoords);
			}	
			// var_dump($bigram);
			// var_dump($bigramCoords);
			// var_dump("номер матрицы - $transformedMatrixNum");
			$symbCoord = $this->getSymbCoords($this->transformedMatrixArr[$transformedMatrixNum], $symbol);
			//var_dump("координаты символа - [" . implode(', ', $symbCoord) . "]");
			//Если грамм не является массивом с координатами - это нераспознанный символ, не шифруем его
			if (!$symbCoord) {
				$ciphrCoordArr[] = $symbol;
				$interimCoordArr[] = $symbol;

				continue;
			}
			$grammKey = $this->returnNearbyGramm($symbKey, $text);
			//var_dump("символ соседнего грама - " . ($grammKey !== false ? $this->getStrArr($text)[$grammKey] : 'false'));
			/**
			 * @var array массив координат соседнего грамма в биграме, координаты которого нужно использовать для шифрования/дешифрования текста.
			 * Матрицы используем противоположные, так как соседний грамм ищется по определению в другой матрице
			 */
			$nearGrammCoords = ($grammKey !== false) ? $this->getSymbCoords(($this->transformedMatrixArr[($transformedMatrixNum == 1 ? 2 : 1)]), $this->getStrArr($text)[$grammKey]) : false;
			
			if ($bigramCoords[1] && $bigramCoords[0][1] == $bigramCoords[1][1]) {
				if ($this->encrypt) {
					if ($symbKey % 2 !== 0) {
						$interimSymbArr[] = $this->transformedMatrixArr[2][$bigramCoords[1][0]][$bigramCoords[1][1]];
						// var_dump("Добавляемый символ (2 матрица) - " . $this->transformedMatrixArr[2][$bigramCoords[1][0]][$bigramCoords[1][1]]);
						// var_dump("координаты символа (2 матрица) - [" . $bigramCoords[1][0] . "," . $bigramCoords[1][1] . "]");
						#Гаврилов
						//ЕСЛИ МЫ БУДЕМ ПЕРЕСТРАИВАТЬ МАТРИЦЫ ПЕРЕД ТЕМ КАК СОБИРАТЬ ШИФРОВАННЫЕ СИМВОЛЫ, ТО ПРИ ДЕШИФРОВКЕ ЭТИ ЖЕ СИМВОЛЫ БУДУТ НЕ В ОДНОМ СТОЛБЦЕ, А ЗНАЧИТ, МЫ НЕ СМОЖЕМ С ИХ ПОМОЩЬЮ НАЙТИ СИМВОЛЫ ИСХОДНОЙ СТРОКИ
						//Матрицы перестраиваются один раз при обработке полного биграмма, у которого совпадают столбцы. Если их перестраивать каждую итерацию при шифровании/дешифровании биграммы - могут возникнуть проблемы (если координаты символов биграммы полностью совпадают между матрицами М[1,1], А[1,1])
						$this->transformMatrix(1, $symbKey, $bigramCoords[1]);
						$this->transformMatrix(2, $symbKey, $bigramCoords[0]);
					} else {
						$interimSymbArr[] = $this->transformedMatrixArr[1][$bigramCoords[0][0]][$bigramCoords[0][1]];
						// var_dump("Добавляемый символ (1 матрица) - " . $this->transformedMatrixArr[1][$bigramCoords[0][0]][$bigramCoords[0][1]]);
						// var_dump("координаты символа (1 матрица) - [" . $bigramCoords[0][0] . "," . $bigramCoords[0][1] . "]");
						#Гаврилов
						//ЕСЛИ МЫ БУДЕМ ПЕРЕСТРАИВАТЬ МАТРИЦЫ ПЕРЕД ТЕМ КАК СОБИРАТЬ ШИФРОВАННЫЕ СИМВОЛЫ, ТО ПРИ ДЕШИФРОВКЕ ЭТИ ЖЕ СИМВОЛЫ БУДУТ НЕ В ОДНОМ СТОЛБЦЕ, А ЗНАЧИТ, МЫ НЕ СМОЖЕМ С ИХ ПОМОЩЬЮ НАЙТИ СИМВОЛЫ ИСХОДНОЙ СТРОКИ
						
					}
				} else {
					if ($symbKey % 2 !== 0) {
						$interimSymbArr[] = $this->transformedMatrixArr[1][$bigramCoords[1][0]][$bigramCoords[1][1]];
						// var_dump("Добавляемый символ (1 матрица) - " . $this->transformedMatrixArr[1][$bigramCoords[1][0]][$bigramCoords[1][1]]);
						// var_dump("координаты символа (1 матрица) - [" . $bigramCoords[1][0] . "," . $bigramCoords[1][1] . "]");
						#Гаврилов
						//ЕСЛИ МЫ БУДЕМ ПЕРЕСТРАИВАТЬ МАТРИЦЫ ПЕРЕД ТЕМ КАК СОБИРАТЬ ШИФРОВАННЫЕ СИМВОЛЫ, ТО ПРИ ДЕШИФРОВКЕ ЭТИ ЖЕ СИМВОЛЫ БУДУТ НЕ В ОДНОМ СТОЛБЦЕ, А ЗНАЧИТ, МЫ НЕ СМОЖЕМ С ИХ ПОМОЩЬЮ НАЙТИ СИМВОЛЫ ИСХОДНОЙ СТРОКИ
						$this->transformMatrix(2, $symbKey, $bigramCoords[0]);
						$this->transformMatrix(1, $symbKey, $bigramCoords[1]);
					} else {
						$interimSymbArr[] = $this->transformedMatrixArr[2][$bigramCoords[0][0]][$bigramCoords[0][1]];
						// var_dump("Добавляемый символ (2 матрица) - " . $this->transformedMatrixArr[2][$bigramCoords[0][0]][$bigramCoords[0][1]]);
						// var_dump("координаты символа (2 матрица) - [" . $bigramCoords[0][0] . "," . $bigramCoords[0][1] . "]");
						#Гаврилов
						//ЕСЛИ МЫ БУДЕМ ПЕРЕСТРАИВАТЬ МАТРИЦЫ ПЕРЕД ТЕМ КАК СОБИРАТЬ ШИФРОВАННЫЕ СИМВОЛЫ, ТО ПРИ ДЕШИФРОВКЕ ЭТИ ЖЕ СИМВОЛЫ БУДУТ НЕ В ОДНОМ СТОЛБЦЕ, А ЗНАЧИТ, МЫ НЕ СМОЖЕМ С ИХ ПОМОЩЬЮ НАЙТИ СИМВОЛЫ ИСХОДНОЙ СТРОКИ
						
					}
				}

				
				// $this->drawMatrix($this->transformedMatrixArr[1]);
				// $this->drawMatrix($this->transformedMatrixArr[2]);

				// var_dump($interimSymbArr);

				// die();

				continue;
			}
			#Гаврилов
			//ЗАТЕСТИ ЭТОТ ВАРИАНТ КАК ИЗМЕНИТСЯ МАТРИЦА
			//Если биграмм неполный (грамм не имеет "пары"), либо соседний грамм является нераспознанным символом - просто меняем координаты исходного символа местами
			if ($grammKey === false || $nearGrammCoords === false) {
				//var_dump('there');
				if ($this->encrypt) {
					$interimSymbArr[] = $this->transformedMatrixArr[($transformedMatrixNum == 1 ? 2 : 1)][$symbCoord[1]][$symbCoord[0]];
					$this->transformMatrix(($transformedMatrixNum == 1 ? 2 : 1), $symbKey, [$symbCoord[1], $symbCoord[0]]);
				} else {
					$interimSymbArr[] = $this->transformedMatrixArr[($transformedMatrixNum == 1 ? 2 : 1)][$symbCoord[1]][$symbCoord[0]];
					// var_dump("Заменяемый символ " . $this->transformedMatrixArr[($transformedMatrixNum == 1 ? 2 : 1)][$symbCoord[1]][$symbCoord[0]]);
					// var_dump("Координаты заменяемого символа - [" . implode(', ', [$symbCoord[1], $symbCoord[0]]) . "]");
					$this->transformMatrix(($transformedMatrixNum == 1 ? 2 : 1), $symbKey, [$symbCoord[1], $symbCoord[0]]);
				}

				continue;
			} else {
				$ciphrCoordArr[] = [$nearGrammCoords[0], $symbCoord[1]];
				//$interimSymbArr[] = $this->transformedMatrixArr[($transformedMatrixNum == 1 ? 2 : 1)][$nearGrammCoords[0]][$symbCoord[1]];
				if ($this->encrypt) {
					//var_dump("координаты соседнего грамма - [" . implode(', ', $nearGrammCoords) . "]");
					$interimSymbArr[] = $this->transformedMatrixArr[($transformedMatrixNum == 1 ? 2 : 1)][$nearGrammCoords[0]][$symbCoord[1]];
					//var_dump("заменяемый симв	ол - " . $this->transformedMatrixArr[($transformedMatrixNum == 1 ? 2 : 1)][$nearGrammCoords[0]][$symbCoord[1]]);
					//var_dump("координаты заменяемого символа - [" . implode(', ', [$nearGrammCoords[0], $symbCoord[1]]) . "]");
					$this->transformMatrix(($transformedMatrixNum == 1 ? 2 : 1), $symbKey, [$nearGrammCoords[0], $symbCoord[1]]);
				} else {
					$matrixIndex = ($transformedMatrixNum == 1 ? 2 : 1);
					//$bigrammIndex = ($transformedMatrixNum == 1 ? 1 : 0);
					$interimSymbArr[] = $this->transformedMatrixArr[$matrixIndex][$bigramCoords[$transformedMatrixNum == 1 ? 1 : 0][0]][$bigramCoords[$transformedMatrixNum == 1 ? 0 : 1][1]];
					//var_dump("заменяемый символ - " . $this->transformedMatrixArr[($transformedMatrixNum == 1 ? 2 : 1)][$bigramCoords[$transformedMatrixNum == 1 ? 1 : 0][0]][$bigramCoords[$transformedMatrixNum == 1 ? 0 : 1][1]]);
					//var_dump("координаты заменяемого символа - [" . implode(', ', [$bigramCoords[$transformedMatrixNum == 1 ? 1 : 0][0], $bigramCoords[$transformedMatrixNum == 1 ? 0 : 1][1]]) . "]");
					$this->transformMatrix($transformedMatrixNum, $symbKey, $symbCoord);				
				}

				// var_dump($interimSymbArr);

				// $this->drawMatrix($this->transformedMatrixArr[1]);
				// $this->drawMatrix($this->transformedMatrixArr[2]);
			}
		}
		// var_dump("массив символов - " . implode('', $interimSymbArr));
		// var_dump($interimCoordArr);


		
		return implode('', $interimSymbArr);
	}


	/**
	 * Метод возвращает координаты определенного символа из определенной матрицы
	 *
	 * @param array $matrix матрица, где находится символ
	 * @param $symbol символ, чьи координаты необходимо вернуть
	 * @return array|false
	 */
	private function getSymbCoords(array $matrix, $symbol)
	{
		//var_dump($symbol);
		//$this->drawMatrix($matrix);
		$symbCoord = false;
		foreach ($matrix as $rowNum => $rowData){
			if (($symbCol = array_search($symbol, $matrix[$rowNum])) !== false) {
				//var_dump([$rowNum, $symbCol]);
				$symbCoord = [$rowNum, $symbCol];
			} 
		}
		return $symbCoord;
	}



	/**
	 * Метод возвращает ключ грамма, который нужно использовать для определения координат зашифрованного символа
	 *
	 * @param int $symbKey ключ символа, координаты которого нужно заменить на координаты соседнего (предыдущего, либо следующего) символа в биграмме, из которых соитоит шифруемая строка
	 * @param array $coordArr массив со всеми символами сообщения
	 * @return int|false false возвращается в случае неполного биграмма (грамм не имеет соседа), в остальных случаях возвращается ключ грамма, чьи координаты надо взять
	 */
	private function returnNearbyGramm($symbKey, $text)
	{
		$resultSymbKey = null;
		if ($symbKey % 2 === 0) {
			//Если у последнего символа нет "пары" (биграмм не полный), то меняем местами координаты строки и столбца. 
			//Эта ситуация может возникнуть только для четных символов, так как нечетные берут координаты у предыдущих символов
			#Гаврилов
			//ПРОВЕРЬ УСЛОВИЕ НИЖЕ ВЕРНО ЛИ ОТРАБАТЫВАЕТ
			if ($symbKey + 1 == mb_strlen($text)) {
				return false;
			}
			$resultSymbKey = $symbKey + 1;
		} else {
			$resultSymbKey = $symbKey - 1;
		}

    	return $resultSymbKey;
  	}

	
	/**
	 * Метод возвращает преобразованное (зашифрованное/расшифрованное) сообщение
	 *
	 * @param array $symbCoordArr массив с координатами символов преобразованного сообщения
	 * @return string
	 */
  	private function createCiphr($symbCoordArr)
	{
		$ciphrArr = [];
		foreach ($symbCoordArr as $symbKey => $symbCoord) {
			//Если вместо координат символа указан сам символ - он не был найден в матрицах. Пропускаем и не подбираем для него значение
			if (!is_array($symbCoord)) {
				$ciphrArr[] = $symbCoord;

				//return $symbCoord;
				continue;
			}
			$resultSymbCoord = $symbCoord;
			//Если грамм имеет "пару" в биграмме
			// if (($nearbyGramm = $this->returnNearbyGramm($symbKey)) !== false) {
			// 	//Если один из граммов - нераспознанный символ - не проверяем находятся ли граммы в одном столбце
			// 	if (is_array($symbCoordArr[$nearbyGramm])) {
			// 		//Если столбцы граммов совпадают - меняем граммы местами в исходом биграмме. Таким образом, мы меняем местами и координаты символов в прямоугольниках и избегаем простой смены символов в преобразованном биграмме в случае, когда они находятся в одном столбце (чтобы не было ситуации DG -> GD). Подробнее https://ru.wikipedia.org/wiki/Шифр_Уитстона в пункте "В случае, если буквы исходной биграммы сообщения находятся в одной строке (в горизонтальном шифровании)..."
			// 		if ($symbCoord[1] == $symbCoordArr[$nearbyGramm][1]) {
			// 			var_dump('da');
			// 			$resultSymbCoord = $symbCoordArr[$nearbyGramm];
			// 		}
			// 	}
			// }
			if ($this->encrypt) {
				// var_dump($symbKey);
				// var_dump($symbCoord);
				// var_dump($resultSymbCoord);
				
				$matrix = ($symbKey % 2 !== 0 ? $this->transformedMatrixArr[2] : $this->transformedMatrixArr[1]);
				// $this->drawMatrix($this->transformedMatrixArr[1]);
				// $this->drawMatrix($this->transformedMatrixArr[2]);
				$ciphrArr[] = $matrix[$resultSymbCoord[0]][$resultSymbCoord[1]];
			} else {
				$matrix = ($symbKey % 2 !== 0 ? $this->transformedMatrixArr[1] : $this->transformedMatrixArr[2]);
				$ciphrArr[] = $matrix[$resultSymbCoord[0]][$resultSymbCoord[1]];
			}
			//Если шифруем - возвращаем символ из одной матрицы, если расшифровываем - из другой
		}

		//die();

    return implode('', $ciphrArr);
  }


	/**
   * Метод формирует вектор инициализации в виде биграмма
   * @param array $array массив значений на основе которых будет строиться биграмм вектора
   *
   * @return string образованный биграмм из рандомных элементов массива $vectorArr
   */
//   private function createVector($array)
//   {
//     $vectorArr = $array;
//     $step = 1;
//     while ($step <= $this->vectorLength){
//       //Получаем рандомное число, которое является ключом элемента массива с символами векторов
//       $cipherVector[] = $vectorArr[$this->getRandNum(count($vectorArr))];
//       $step++;
//     }
    
//     return implode('', $cipherVector);
//   }


	/**
   * Получаем векторы инициализации в виде числа на который будет происходить сдвиг
   *
   * @param string $string входной текст (уже с векторами в начале и конце)
   * @param string $direction направление вектора (вертикальный/горизонтальный)
   * @return string
   */
  private function getVector($string, $direction)
  {
    //Если получаем вертикальный вектор - ищем в начале строке, если горизонтальный - в конце
    $offset = ($direction == 'vert' ? 0 : -1);
    //Разбиваем строку на массив символов
    $arr = $this->getStrArr(mb_strtolower(mb_substr($string, $offset, $this->vectorLength)));
    //Результирующий массив, куда будут добавлены цифры, сумма которых и является вектором инициализации, на который сдвигается матрица
    $reslutArr = [];
    foreach ($arr as $symbol) {
      if (preg_match('/[0-9]/', $symbol)) {
        $reslutArr[] = $symbol;
      } else if (preg_match('/[a-z]/i', $symbol)) {
        $reslutArr[] = $this->latinLetters[$symbol];
      } 
	//   else if (preg_match('/[а-ё]/', $symbol)) {
    //     $reslutArr[] = $this->cyrilicLetters[$symbol];
    //   }
    }

    //Получаем только первую цифру из получившегося числа (0-9)
    return (int)mb_substr(array_sum($reslutArr), -1, 1);
  }


	/**
	 * Визуализация матрицы
	 *
	 * @param array $matrix
	 * @return void
	 */
	private function drawMatrix($matrix)
	{
		$drawMatrix = '<div style="margin-bottom: 10px";>';
			foreach ($matrix as $rowArr) {
				$drawMatrix .= implode(',', $rowArr) . "<br>";
			}
		$drawMatrix .= '</div>';
		echo "<pre><div>" . $drawMatrix . "</div></pre>";
	}


	/**
   * Метод возвращает рандомное число из промежутка >= $min и <= $max
   * 
   * @param int $max максимально возможное значение минус 1. То есть, если нужно максимальное значение 3 - ставим 4
   * @param int $min минимально возможное значение
   * @return int
   */
  private function getRandNum($max, $min = 1)
  {
    return unpack("N", openssl_random_pseudo_bytes(4))[1] % ($max - $min) + $min;
  }


	/**
   * Метод возвращает массив символов из строки
   * Обычный str_split не обрабатывает кодировку utf-8, функция mb_str_split() появилась только в 7 версии php
   *
   * @param string $str строка для разбивки на массив
   * @return array
   */
  private function getStrArr($str)
  {
    return preg_split('//u', $str, -1, PREG_SPLIT_NO_EMPTY);
  }

}

//TODO
//ПЕРЕНЕСИ ВЕРСИЮ В КОНЕЦ ШИФРА, ЧТОБЫ ВО ВСЕХ ВЕРСИЯХ ОНА ГАРАНТИРОВАННО БРАЛАСЬ ИЗ ОДНОГО И ТОГО ЖЕ МЕСТА

#Гаврилов
//НЕ СРАВНИВАЮТСЯ ОТРЕЗКИ ХЭША ФЕЙКОВОЙ ДЛИНЫ

//TODO
//ЕСЛИ ДЛИННАЯ СТРОКА (БОЛЬШЕ ФЕЙКОВОГО КОЛИЧЕСТВА) - ШИФР ЛОМАЕТСЯ
//ЕСЛИ ПЕРЕДАТЬ КУЧУ ПОВТОРЯЮЩИХСЯ СИМВОЛОВ "МАМАМАМАМ" - ШИФР ЛОМАЕТСЯ


#Гаврилов
//ЕСЛИ ИСПОЛЬЗОВАТЬ СОЛЬ - ЛОМАЕТСЯ ПРИ ДЕШИФРОВКЕ
$n = 1;
while ($n <= 50) {
	$testCipher = (new SimpleCipher('1'))->encryptText(50);
	echo '<pre>'; var_dump($testCipher); echo'</pre>';
	// #Гаврилов
	// //если заменить первую букву в соли - ничего не поменяется, хотя должно
	// //ДОБАВЬ УЧЕТ БУКВ К ПЕРЕСЧЕТУ ПАРАМЕТРОВ ТРАНСФОРМАЦИИ МАТРИЦЫ. МАССИВ БУКВ ТРАНСФОРМИРУЕТСЯ В МАССИВ ЧИСЕЛ ПО КЛЮЧАМ ИЗ КЛЮЧА ШИФРА (НЕ ИЗ МАССИВА LETTERSARR) ДОБАВЛЯЕМ К КОЛИЧЕСТВУ ИТЕРАЦИЙ, ТАК КАК СУММА БУДЕТ БОЛЬШАЯ
	$decryptText = (new SimpleCipher($testCipher))->decryptText();
	echo '<pre>'; var_dump($decryptText); echo'</pre>';
	if ($decryptText !== '1') {
		var_dump('ОШИПКА!');
	}
 	$n++;
}


//ПЕРЕПИСАТЬ BASE64 на base32 (где нет заглавных букв, но нужно, чтобы там набор букв все равно был большой) СПРОСИ У НЕЙРОСЕТИ КАКОВА ВЕРОЯТНОСТЬ СТОЛКНУТЬСЯС КОЛИЗИЯМИ ЕСЛИ ИСПОЛЬЗОВАТЬ BASE 32 А НЕ BASE 64


//оригинальный текст чk25ю3 22|22ь1t13n847пoлЬЧШГЛуЬпЛбЬЧ?QQnгТвj 0ЗСUРоoъcъcMDdц$#б1вв4?48|1ъц - расшифровывается как надо
//тестовый текст для шифрования mama 157$#
//но текст чk2hю3 22|22ь1t13n847пoлЬЧШГЛуЬпЛбЬЧ?QQnгТвj 0ЗСUРоoъcъcMDdц$#б1вв4?48|1ъц тоже расшифровывается как надо ПОЧЕМУ?
//ОН не расшифровывается, но возвращает ошибку, пока не хватает обработки ошибок . Удаляй при шифровании и расшифровке предыдущие результаты работы шифра не после возворащения шифра, а сразу при нажатии на кнопкуч

// $decryptText = (new SimpleCipher('им14щ151ш1я17a1)2903?710ж{3"T1|K;BlЦTcgcв5лб88 "ри'))->decryptText();
// echo '<pre>'; var_dump($decryptText); echo'</pre>';

//Если расшифровывать текст 
//5x51ь580ф1}31!ж32н1427иб5ё5б7ё0ydу87cT1о8k9б5Z9ьa}987a7e5a120ff2bюс}05(57уr - выводит корректное значение
//Но если попытаться заменить в шифре часть текста - 
//5x51ь580ф1}31!ж32н1427иб5ё5б7ё0ydу87cT1о8k9б5Z9ьa}98зa7e5a120ff2bюс}05(57уr - все равно выводится верное значение. Выяснить почему, это фейковый символ?
//Даже если уалить целую часть текста - 
//5x51ь580ф1}31!ж32н1427иб5ё5б7ё0ydу87cT1о8k9б5Z9ьa}87a7e5a120ff2bюс}05(57уr
