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
	 * @var int фейковая длина зашифрованной строки (по умолчанию 100)
	 */
	private $fakeLength;
	/**
	 * @var array массив различных символов, которые не являются буквами, цифрами. Массив нужен для обрамления фейковой длины шифра
	 */
	private $fakeLengthDelimetr;
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
   * @var int максимальная фейковая длина шифруемой строки
   */
	private $maxFakeLength = 1000;
	/**
   * @var string фейковые символы, которые были вычленены из расшифровываемой строки после ее очистки для сравнения с фейковыми символами, используемыми при шифровании
   */
	private $fakeSymbolString = null;


	public function __construct($text)
	{
		$this->text = $text;
		$this->matrixDepth = sqrt(mb_strlen($this->cipherKeyStorage[0]));
	}


	private function getTextHashSumm(string $text, array $matrixParamArr): array
	{
	  	$resultHashSumm = [
			'numbersSum' => 0,
			'lettersSum' => 0
		];

		//var_dump($matrixParamArr);

		//Хэш исходного сообщения
		$hashText = hash('whirlpool', $text);
		//Сумма чисел из хэша исходного сообщения
		$hashText_numbersSumm = array_sum(str_split(preg_replace('/[^0-9]+/', '', $hashText)));
		//Массив букв из хэша исходного сообщения (добавляем элементы массива случайных параметров преобразования матрицы). Преобразовываем в строку, чтобы забрать только первые 2 цифры из числа
		$hashText_numbersSumm = $hashText_numbersSumm + $matrixParamArr[4] + $matrixParamArr[3] . "";
		$hashText_numbersSumm = substr($hashText_numbersSumm, 0, 2);
		#Гаврилов
		//ПРОВЕРИТЬ. МОЖЕТ ЛИ БЫТЬ МЕНЬШЕ 2Х ЦИФР В ПЕРВОМ И ВТОРОМ ОТРЕЗКЕ. еСЛИ МОЖЕТ - СУЩЕСТВУЕТ ОПАСНОСТЬ, ЧТО МЫ НЕ СМОЖЕМ ДОСТАТЬ ЭТИ ОТРЕЗКИ, ТАК КАК ПЛАНИРУЕТСЯ, ЧТО ОТРЕЗОК БУДЕТ СОСТОЯТЬ ИЗ БУКВЫ + 2 ЧИСЛА. еСЛИ ЧИСЛА МОЖЕТ БЫТЬ МЕНЬШЕ 2Х - ПРОБЛЕМА
		$hashText_letters = str_split(preg_replace('/[^a-z]+/i', '', $hashText));
		$firstLetter = $hashText_letters[array_sum(str_split($hashText_numbersSumm))];
		//Первая и последние буквы из массива букв хэша исходного сообщения для добавление в первый и второй отрезок соответственно
		//$firstLetter = array_shift($hashText_letters);
		//Массив цифр из хэша, каждый элемент которого представляет собой ключ соответствующего элемента из массива $this->lettersArr
		$hashTextLettersSum = array_sum(array_map(function($el) {return array_key_exists($el, $this->lettersArr) !== false ? $this->lettersArr[$el] : null;}, $hashText_letters));
		$hashTextLettersSum = $hashTextLettersSum + $matrixParamArr[0] + $matrixParamArr[1] . "";
		$hashTextLettersSum = substr($hashTextLettersSum, 0, 2);
		$lastLetter = $hashText_letters[array_sum(str_split($hashTextLettersSum))];
		$resultHashSumm['numbersSum'] = str_split($hashText_numbersSumm)[0] % 2 == 0 ? $firstLetter . $hashText_numbersSumm : $hashText_numbersSumm . $firstLetter;
		$resultHashSumm['lettersSum'] = str_split($hashTextLettersSum)[0] % 2 == 0 ? $lastLetter . $hashTextLettersSum : $hashTextLettersSum . $lastLetter;
		//$resultHashSumm['numbersSum'] = $hashText_numbersSumm;
		//$resultHashSumm['lettersSum'] = $hashTextLettersSum;

	  	return $resultHashSumm;
	}


	/**
	 * Метод шифрования текста
	 *
	 * @param integer $fakeLength фейковая длина шифра
	 * @return string
	 */
	public function encryptText($fakeLength = 50): string
	{
		//var_dump("##__ШИФРОВАНИЕ__##");
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
		//var_dump("версия $encryptVersion");
		//Сдвигаем ключ шифра для первой матрицы
		$mixedCipher = $this->shiftCipherKey($this->cipherKey, $this->windowSizeFirst, $this->shiftCountFirst, $reverseCipherKey);
		//Заполняем массив с параметрами преобразования матриц (пока что данными для первой матрицы)
		$transformMatrixParamArr = [
																0 => $this->windowSizeFirst, 
																1 => $this->shiftCountFirst, 
																2 => $reverseCipherKey,
															];
		$this->windowSizeSecond = $this->getRandNum(55, 12);
		$this->shiftCountSecond = $this->shiftCountFirst + $this->getRandNum(1999, 99);
		//Для ключа второй матрицы флаг реверса обязательно меняется на противоположный
		$reverseCipherKey = ($reverseCipherKey ? 0 : 1);
		//Сдвигаем ключ шифра для второй матрицы
		$mixedCipherTwo = $this->shiftCipherKey($this->cipherKey_second, $this->windowSizeSecond, $this->shiftCountSecond, $reverseCipherKey);
		//Только буквы для рандомной вставки между параметрами полезной нагрузки для трансформации матриц
		$lettersArr = array_flip($this->lettersArr);
		//Добавляем в массив с параметрами формирования матриц оставшиеся параметры, на основании которых формировалась вторая матрица
		$transformMatrixParamArr[3] = $this->windowSizeSecond;
		$transformMatrixParamArr[4] = $this->shiftCountSecond;
		#Гаврилов
		//ФОРМИРОВАНИЯ АЛГОРИТМА ПОСТРОЕНИЯ МАТРИЦЫ ДОЛЖНЫ КУДА-ТО ЗАПИСЫВАТЬСЯ В ШИФР, ЛИБО РАССЧИТЫВАТЬСЯ НА ОСНОВАНИИ ПАРАМЕТРОВ ФОРМИРОВАНИЯ САМОЙ МАТРИЦЫ ПЛЮС ВЕКТОРА ИНИЦИАЛИЗАЦИИ?
		$hashTextParams = $this->getTextHashSumm($this->text, $transformMatrixParamArr);
		//var_dump($hashTextParams);
		//var_dump("параметры матрицы " . implode('', $transformMatrixParamArr));
		$this->matrixOne = $this->fillMatrix($mixedCipher, (int)substr(array_sum($transformMatrixParamArr), -1, 1));
		//Добавляем 1 к предыдущей сумме параметров матрицы, так как это дает 50% шанс, что паттерн заполнения изменится для второй матрицы (так как паттерны делятся по двойкам: 0,1 - 1й паттерн, 2,3 - 2й и так далее). На самом деле, нам не обязательно, чтобы паттерн менялся, так как сама последовательность символов для формирования матрицы разная, поэтому добавление 1 позволит с равной вероятностью получить как тот же паттерн заполнения матрицы, что был для 1й матрицы (0 превратится в 1 - 1й паттерн), так и следующий паттерн (1 превратится в 2 - 2й паттерн).
		$this->matrixTwo = $this->fillMatrix($mixedCipherTwo, (int)substr(array_sum($transformMatrixParamArr) + 1, -1, 1));

		// var_dump($transformMatrixParamArr);

		//Формируем итоговую строку с параметрами формирования матриц
		//В качестве разделителя между параметрами формирования матриц использовать только случайные БУКВЫ, без знаков препинаний и различных спецсимволов (@, ^ и т.д.), потому что эти символы, в свою очередь, будут использоваться для выделения в параметрах преобразований матрицы первую часть версии алгоритма
		$transformMatrixParam = implode('', array_map(function($el) use($lettersArr) {return $el . $lettersArr[array_rand($lettersArr)];}, $transformMatrixParamArr));
		// var_dump($transformMatrixParam);
		// var_dump(array_sum(array_filter($this->getStrArr($transformMatrixParam), function($el) {
		// 	return is_numeric($el);
		// })));
		//Числа от 1 до 9 используются, так как два вектора идут подряд и если векторы будут содержать 2 числа непонятно будет где заканчивается первый вектор и начинается второй.
		//TODO
		//ПЕРЕИМЕНУЙ ВЕКТОРЫ ИНИЦИАЛИЗАЦИИ "ВЕРТИКАЛЬНЫЙ/ГОРИЗОНТАЛЬНЫЙ" НА "ПЕРВЫЙ/ВТОРОЙ"
		// $this->cipherVectorVert = $this->createVector(array_merge(array_flip($this->cyrilicLetters), array_flip($this->latinLetters), ['0','1','2','3','4','5','6','7','8','9']));
		$this->cipherVectorVert = $hashTextParams['numbersSum'];
		//var_dump("вертикальный вектор $this->cipherVectorVert");
		// $this->cipherVectorHor = $this->createVector(array_merge(array_flip($this->cyrilicLetters), array_flip($this->latinLetters), ['0','1','2','3','4','5','6','7','8','9']));
		$this->cipherVectorHor = $hashTextParams['lettersSum'];
		//var_dump("горизонтальный вектор $this->cipherVectorHor");
		$this->initializationVectorVert = $this->getVector($this->cipherVectorVert, 'vert');
		// var_dump($this->cipherVectorVert);
		// var_dump($this->initializationVectorVert);
		$this->initializationVectorHor = $this->getVector($this->cipherVectorHor, 'hor');
		$this->transformedMatrixOne = $this->transformMatrix($this->matrixOne, 1, $this->initializationVectorVert, $this->initializationVectorHor);
		$this->transformedMatrixTwo = $this->transformMatrix($this->matrixTwo, 0, $this->initializationVectorVert, $this->initializationVectorHor);
		$coordSymbArr_interim = $this->createSymbCoords($this->text);
		$coordCiphrSymbArr_interim = $this->createCiphrCoords($coordSymbArr_interim);
		//Промежуточный зашифрованный текст (без внедренных фейковых символов)
		$ecnryptText_interim = $this->createCiphr($coordCiphrSymbArr_interim);
		$this->fakeLengthDelimetr = array_filter($this->getStrArr($this->cipherKey), function($el){return preg_match('/[^a-zа-ё0-9]/ui', $el);});
		$fakeTrueLength = $this->realLengthPointer($transformMatrixParam);
		//Первая часть указателя на длину исходного сообщения (3546) без разграничителей - 35
		$firstPartFakeLengthWithoutDelimetr = mb_substr((string)$fakeTrueLength, 0, $this->getRandNum(mb_strlen((string)$fakeTrueLength) + 1));
		//Вторая часть указателя на длину исходного сообщения (3546) с разграничителями - {46"
		$secondPartFakeLength = $this->fakeLengthDelimetr[array_rand($this->fakeLengthDelimetr)] . mb_substr((string)$fakeTrueLength, mb_strlen($firstPartFakeLengthWithoutDelimetr)) . $this->fakeLengthDelimetr[array_rand($this->fakeLengthDelimetr)];
		//Первая часть указателя на длину исходного сообщения (3546) с разграничителями - *35$
		$firstPartFakeLength = $this->fakeLengthDelimetr[array_rand($this->fakeLengthDelimetr)] . $firstPartFakeLengthWithoutDelimetr . $this->fakeLengthDelimetr[array_rand($this->fakeLengthDelimetr)];
		// var_dump("первая часть длины $firstPartFakeLength");
		// var_dump("вторая часть длины $secondPartFakeLength");
		$fakeLength = $this->calcLenFakeSymb($fakeLength, $ecnryptText_interim, $this->cipherVectorVert . $this->cipherVectorHor . $transformMatrixParam . $encryptVersion . $firstPartFakeLength . $secondPartFakeLength);
		//var_dump($fakeLength);

		//Итоговый зашифрованный текст исходного сообщения (заполненный фейковыми символами)
		$resultCipherText = $this->fillFakeLength($ecnryptText_interim, $fakeLength, $this->createFakeLengthHash($ecnryptText_interim, $transformMatrixParam));
		
		
    //var_dump($resultCipherText);
                              
		//Итоговый шифр, включающий в себя зашифрованный текст исходного сообщений + полезная нагрузка шифра
		$resutCipher = $this->constructCipherText($this->cipherVectorVert, $this->cipherVectorHor, $resultCipherText, $transformMatrixParam, $encryptVersion, $firstPartFakeLength, $secondPartFakeLength);

		return $resutCipher;
	}


	/**
	 * Указатель для расчета реальной длины исходного сообщения
	 * Метод создает значение, содержащее информацию о количестве символов исходного сообщения, чтобы поместить его в шифр и при этом не подсвечивать реальное количество фейковых символов или символов исходного сообщения.
	 *
	 * @param string $transformMatrixParam параметры преобразования матриц
	 * @return int
	 */
	private function realLengthPointer($transformMatrixParam)
	{
		//Берем максимально допустимое количество символов для шифра ($this->maxFakeLength)(чтобы в том числе нормально обрабатывались строки с максимально допустимым количеством символов), добавляем сумму значений, взятую из параметров формирования матриц и добавляем длину исходной строки
		$resultRealLengthPointer = $this->maxFakeLength + array_sum(preg_split('/[a-zа-ё]/iu', $transformMatrixParam)) + mb_strlen($this->text);

		return $resultRealLengthPointer;
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
		$transformMatrixArr = preg_split('/[^0-9]{1}/', str_replace($versionMatch[0], '', $lengthFirstMatches[0]), 0, PREG_SPLIT_NO_EMPTY);
		$transformMatrixArr = array_map(function($el){return (int)$el;}, $transformMatrixArr);
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
		// var_dump($lengthSecondMatches);
		// var_dump($cipherVersion);
		//Теперь очищаем от полезной нагрузки, связанной с версией алгоритма, 2й частью указателя на длину исходной строки и вторым вектором инициализации
		$clearCipherText = mb_substr($clearCipherText, 0, (0 - mb_strlen($cipherVersion)));
		//Получаем чистую версию алгоритма из зашифрованного отрезка, удаляя в строке два последних символа (вектор горизонтальной инициализации) и заменяя на пустоту сегмент, содержащий 2ю часть фейковой длины шифра ($lengthSecondMatches[1])
		//$cipherVersion = $this->getVersion(mb_substr(str_replace($lengthSecondMatches[1], '', $cipherVersion), 0, -2));
		$cipherVersion = $this->getVersion(mb_substr(str_replace($lengthSecondMatches[1], '', $cipherVersion), 0, -3));
		//var_dump("версия $cipherVersion");
		//В матрицу (?)
		$reverseCipherKey = ($transformMatrixArr['2'] % 2 === 0) ? 0 : 1;
		//Сдвигаем шифр только после определения версии, так как только на этом этапе происходит определение ключа шифра 
		$mixedCipher = $this->shiftCipherKey($this->cipherKey, $transformMatrixArr[0], $transformMatrixArr[1], $reverseCipherKey);
		$reverseCipherKey = ($reverseCipherKey ? 0 : 1);
		$mixedCipherTwo = $this->shiftCipherKey($this->cipherKey_second, $transformMatrixArr[3], $transformMatrixArr[4], $reverseCipherKey);
		// substr(array_sum($transformMatrixArr) + 1, -1, 1));
		$this->matrixOne = $this->fillMatrix($mixedCipher, (int)substr(array_sum($transformMatrixArr), -1, 1));
		$this->matrixTwo = $this->fillMatrix($mixedCipherTwo, (int)substr(array_sum($transformMatrixArr) + 1, -1, 1));
		// $this->drawMatrix($this->matrixOne);
		// $this->drawMatrix($this->matrixTwo);
		$this->transformedMatrixOne = $this->transformMatrix($this->matrixOne, 1, $this->initializationVectorVert, $this->initializationVectorHor);
		$this->transformedMatrixTwo = $this->transformMatrix($this->matrixTwo, 0, $this->initializationVectorVert, $this->initializationVectorHor);
		// $this->drawMatrix($this->transformedMatrixOne);
		// $this->drawMatrix($this->transformedMatrixTwo);
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

		$coordSymbArr_interim = $this->createSymbCoords($clearDecryptText);
		$coordCiphrSymbArr_interim = $this->createCiphrCoords($coordSymbArr_interim);
		$ecnryptText_interim = $this->createCiphr($coordCiphrSymbArr_interim);
		$compareTextHash = $this->getTextHashSumm($ecnryptText_interim, $transformMatrixArr);
		//Проверяем совпадает ли указатель на хэш расшифрованной строки с указателем на хэш исходной строки, который содержится в первом и втором векторах инициализации
		if ($compareTextHash['numbersSum'] !== $vectorVert || $compareTextHash['lettersSum'] !== $vectorHor) {;
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
	 * @param array $keyStr ключ, который нужно преобразовать
	 * @return void
	 */
	private function shiftCipherKey($keyStr, $windowSize, $shiftCount, $reverseCipherKey)
	{
		$cipherStr = $keyStr;
		// echo '<pre>'; var_dump("Размер окна - $windowSize"); echo'</pre>';
		// echo '<pre>'; var_dump("Кол-во итераций - $shiftCount"); echo'</pre>';
		// echo '<pre>'; var_dump("флаг реверса - $reverseCipherKey"); echo'</pre>';
		// echo '<pre>'; var_dump("преобразовываем $cipherStr"); echo'</pre>';
		
		//generalIteration счетчик общего количества итераций
		//stringIteration счетчик итераций в рамках одного прохода по ключу. Сбрасывается как только перебор проходит ключ полностью до конца и должен вернуться в начало
		$generalIteration = $stringIteration = 0;
		// $windowSize = $this->windowSizeStart;
		//Сдвиг окна (в большую или меньшую сторону) после каждой итерации.
		//TODO
		//Оставить 1 или сделать изменяемой величиной?
		$shiftSize = 1;
		//Флаг увеличения окна захвата. При каждой итерации окно захвата уменьшается. Дойдя до минимума, увеличивается
		//TODO
		//сделать этот флаг изменяемым? Например, в некоторых случаях не увеличивать и не уменьшать его?
		$increaseVector = false;
		while ($generalIteration < $shiftCount) {
			$leftPart = ($stringIteration ? $stringIteration * ($windowSize + $shiftSize) : null);	//Левая часть строки, не участвующая в перемешивании
			$rightPart = mb_strlen($cipherStr) - $leftPart - $windowSize - $shiftSize;	//Права часть строки, не участвующая в перемешивании
			//Если мы дошли до конца строки и права строка содержит меньше 0 символов - строка закончилась, возвращаемся в начало и повторяем перемешивание, но не со стартовой позиции (0), а с небольшим смещением
			if ($rightPart < 0) {
				if ($reverseCipherKey) {
					$cipherStr = implode('', array_reverse(preg_split('//u', $cipherStr, -1, PREG_SPLIT_NO_EMPTY)));
				}
				// echo '<pre>'; var_dump($windowSize); echo'</pre>';
				// echo '<pre>'; var_dump($shiftSize); echo'</pre>';
				// echo '<pre>'; var_dump($rightPart); echo'</pre>';
				$leftPart = $windowSize + $shiftSize + $rightPart;
				//Перерасчитываем правую часть после пересчета остальных частей
				$rightPart = mb_strlen($cipherStr) - $leftPart - $windowSize - $shiftSize;
				$stringIteration = 0;
			}
			$pattern = "/" . ($leftPart ? "(.{" . $leftPart . "})" : null) . "(.{" . $windowSize . "})(.{" . $shiftSize . "})(.{" . $rightPart . "})/u";
			$replacement = ($leftPart ? '${1}${3}${2}${4}' : '${2}${1}${3}');
			// echo '<pre>'; var_dump("---итерация $generalIteration---"); echo'</pre>';
			// echo '<pre>'; var_dump("левая часть - " . mb_substr($cipherStr, 0, ($leftPart ? $leftPart : 0))); echo'</pre>';
			// echo '<pre>'; var_dump("окно захвата - " . mb_substr($cipherStr, $leftPart, $windowSize)); echo'</pre>';
			// echo '<pre>'; var_dump("переносим - " . mb_substr($cipherStr, $leftPart + $windowSize, $shiftSize)); echo'</pre>';
			// echo '<pre>'; var_dump("правая часть - " . mb_substr($cipherStr, $leftPart + $windowSize + $shiftSize)); echo'</pre>';
			
			// echo '<pre>'; var_dump("итог - " . mb_substr($cipherStr, 0, ($leftPart ? $leftPart : 0)) . " + " . mb_substr($cipherStr, $leftPart + $windowSize, $shiftSize) . " + " . mb_substr($cipherStr, $leftPart, $windowSize) . " + " . mb_substr($cipherStr, $leftPart + $windowSize + $shiftSize)); echo'</pre>';
			$cipherStr = preg_replace($pattern, $replacement, $cipherStr);
			// echo '<pre>'; var_dump($cipherStr); echo'</pre>';
			// echo '<pre>'; var_dump($this->cipherKey); echo'</pre>';
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

		return $cipherStr;
	}

	#Гаврилов
	//СЕЙЧАС ВЕКТОРЫ ИНИЦИАЛИЗАЦИИ ПРЕДСТАВЛЯЮТ СОБОЙ ОДНО ЧИСЛО, ДОЛЖНЫ ПРЕДСТАВЛЯТЬ СОБОЙ БИГРАМ. проверь корректно ли работает?

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
	 * @param integer $direction направление заполнения (используется в некоторых паттернах)
	 * @return void
	 */
	private function fillMatrix($mixedCipherKey, $pattern, $direction = 1)
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

						// $matrixOne = array_map(function($el){ksort($el);}, $matrixOne);

						// var_dump($matrixOne);

						// $matrixOne_test1 = $matrixOne_test2 = [];
						// $t = 0;
						// while ($x < $this->matrixDepth)
						// {
						// 	while ($y < $this->matrixDepth - $x)
						// 	{
						// 		$matrixOne[$x][$y] = $cipherKeyArr[$t];
						// 		//$matrixOne_test1 = $cipherKeyArr[$t];
						// 		$t++;
						// 		$y++;
						// 	}
						// 	$y = 0;
						// 	$x++;
						// }
						// $x = $y = $this->matrixDepth - 1;
						
						// while ($x >= 0)
						// {
						// 	//$y = $this->matrixDepth - $x;
						// 	while ($y >= $this->matrixDepth - $x)
						// 	{
						// 		$matrixOne[$x][$y] = $cipherKeyArr[$t];
						// 		//$matrixOne_test2 = $cipherKeyArr[$t];
						// 		$t++;
						// 		$y--;
						// 	}
						// 	$x--;
						// }
						break;

				

		}

		//echo '<pre>'; var_dump($matrixOne); echo'</pre>';
		// $this->drawMatrix($matrixOne);

		return $matrixOne;

		// switch ($direction)
		// {
		// 	//Сначала полностью заполняется одна строка/столбец, затем полностью вторая и тд
		// 	case 1:
		// 		//Если заполняется по строкам
		// 		if ($pattern) {
		// 			while ($x < $matrixDepth)
		// 			{
		// 				while ($y < $matrixDepth)
		// 				{
		// 					$matrixOne[$x][$y] = $cipherArrOne[$x * $matrixDepth + $y];
		// 					$y++;
		// 				}
		// 				$y = 0;
		// 				$x++;
		// 			}
		// 		//Если заполняется по столбцам
		// 		} else {
		// 			while ($y < $matrixDepth)
		// 			{
		// 				while ($x < $matrixDepth)
		// 				{
		// 					$matrixOne[$x][$y] = $cipherArrOne[$y * $matrixDepth + $x];
		// 					$x++;
		// 				}
		// 				$x = 0;
		// 				$y++;
		// 			}
		// 		}
		// 	break;
		// 	case 2:
		// 		//Движемся в обратном направлении
		// 		$reverse = false;
		// 		//Если заполняется по строкам
		// 		if ($pattern) {
		// 			while ($x < $matrixDepth)
		// 			{
		// 				//Если движемся в обратном направлении, заполняя строки
		// 				if ($reverse) {
		// 					while ($y >= 0)
		// 					{
		// 						$matrixOne[$x][$y] = $cipherArrOne[$x * $matrixDepth + $y];
		// 						$y--;
		// 					}
		// 					$reverse = false;
		// 					$y = 0;
		// 				} else {
		// 					while ($y < $matrixDepth)
		// 					{
		// 						$matrixOne[$x][$y] = $cipherArrOne[$x * $matrixDepth + $y];
		// 						$y++;
		// 					}
		// 					$reverse = true;
		// 					$y = $matrixDepth - 1;
		// 				}
		// 				$x++;
		// 			}
		// 		//Если заполняется по столбцам	
		// 		} else {
		// 			while ($x < $matrixDepth)
		// 			{
		// 				//Если движемся в обратном направлении, заполняя строки
		// 				if ($reverse) {
		// 					while ($y >= 0)
		// 					{
		// 						$matrixOne[$x][$y] = $cipherArrOne[$x * $matrixDepth + $y];
		// 						$y--;
		// 					}
		// 					$reverse = false;
		// 					$y = 0;
		// 				} else {
		// 					while ($y < $matrixDepth)
		// 					{
		// 						$matrixOne[$x][$y] = $cipherArrOne[$x * $matrixDepth + $y];
		// 						$y++;
		// 					}
		// 					$reverse = true;
		// 					$y = $matrixDepth - 1;
		// 				}
		// 				$x++;
		// 			}
		// 		}	
		// 	break;
		// 	//По спирали (по часовой)
		// 	case 3:
		// 		// $maxRange = $matrixDepth;	//Величина после которой меняем вектор заполнения строки->столбцы и наборот
		// 		// $fillVector = 'row';	//Вектор заполнения (по умолчанию начинаем со строки)
		// 		// $startRow = $startCol = 0;
		// 		// if ($fillVector == 'row') {
		// 		// 	while ($startRow < $maxRange) {
		// 		// 		$matrixOne[$x][$y] = $cipherArrOne[$x * $matrixDepth + $y];
		// 		// 		$y++;
		// 		// 	}
		// 		// } else {

		// 		// }

		// 		$maxRange = $matrixDepth;
		// 		while ($x < $matrixDepth) {
		// 			$row = $x;
		// 			while ($y < $maxRange) {
		// 				$matrixOne[$x][$y] = $cipherArrOne[$x * $matrixDepth + $y];
		// 				$y++;
		// 			}
		// 			while ($row < $matrixDepth) {
		// 				//Заполняем последний элемент в каждом массиве-строке
		// 				$matrixOne[$row][$maxRange - 1] = $cipherArrOne[$x * $matrixDepth + $y];
		// 				$row++;
		// 				$y++;
		// 			}
		// 			$maxRange--;
		// 			$y = 0;
		// 			$x++;
		// 		}

		// 	break;
		// }
		// $this->drawMatrix($matrixOne);
	}


	#Гаврилов
	//ВЫДЕЛИТЬ СОВПАДАЮЩИЙ ЧАСТИ КОДА ИЗ ШИФРОВАНИЯ И ДЕШИФРОВАНИЯ ВЕРСИИ И ОФОРМИТЬ ОТДЕЛЬНЫМИ МЕТОДАМИ
	/**
	 * Метод получения версии шифра
	 *
	 * @param string $versionString зашифрованное представление версии шифра
	 * @return int
	 */
	private function getVersion($versionString)
	{
		$lettersArr = $this->cyrilicLetters + $this->latinLetters;
		$numberArr = [1,2,3,4,5,6,7,8,9];
		$versionSymbArr = $this->getStrArr($versionString);
		$numberArr  = array_values(array_intersect($versionSymbArr, $numberArr));
		$letterArr  = array_values(array_diff($versionSymbArr, $numberArr));
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

		return (int)$version;
	}


	/**
	 * Метод формирования версии приложения. Сама версия прописывается в свойстве класса $version, метод ниже определяет каким образом версия будет зашифрована
	 *
	 * @return string
	 */
	private function setVersion()
	{
		$lettersArr = $this->cyrilicLetters + $this->latinLetters;
		/*Паттерн формирования версии. Версия всегда состоит из 3х чисел. Результаты применения паттерном на примере версии 123 и не реверсивного массива букв:
		№1 - 1(б)_2(в)_3(г)
		№2 - 12(л)_3(г)_rand
		№3 - 1(б)_23(ц)_rand
		№4 - rand_12(л)_3(г)
		№5 - rand_1(б)_23(ц)
		*/
		$pattern = $this->getRandNum(6);
		//Индекс ключа шифрования
		$cipherKeyIndex = $this->getRandNum(10);
		//Версия алгоритма + индекс рандомного ключа
		$cipherVersion = $this->version . $cipherKeyIndex;
		#Гаврилов
		//ВЫНЕСИ УСТАНОВКУ КЛЮЧА ШИФРОВАНИЯ ЗА ПРЕДЕЛЫ ДАННОГО МЕТОДА
		$this->cipherKey = $this->cipherKeyStorage[$cipherKeyIndex];
		//Ключ второго шифра для формирования второй матрицы строится на основании другого ключа из массива $this->cipherKeyStorage
		$this->cipherKey_second = $this->cipherKeyStorage[$cipherKeyIndex == (count($this->cipherKeyStorage) - 1) ? 0 : $cipherKeyIndex + 1];
		//Флаг реверсивности (относится только к формированию версии, к реверсивности других параметров шифра отношения не имеет). Второе число в массиве чисел формирования версии. Если число четное - массив с буквами/цифрами не реверсим, иначе реверсим. Дополнительный фактор запутывания
		$reverseLettersArr = $this->getRandNum(10);
		if (!($reverseLettersArr % 2 === 0)) {
			$lettersArr = array_combine(array_keys($lettersArr), array_reverse(array_values($lettersArr)));
		}
		//Бьем версию на массив цифр
		$verstionSymbArr = str_split((string)$cipherVersion);
		//Массив цифр, участвующих в шифровании версии алгоритма
		//1 цифра - паттерн формирования версии
		$cipherNumArr = [$pattern, $reverseLettersArr, $this->getRandNum(10)];
		//echo '<pre>'; var_dump("Устанавливаем версию - " . implode('', $cipherNumArr)); echo'</pre>';
		//Массив букв, участвующих в шифровании версии
		$cipherSymbArr = [];
		//Ниже мы определеяем массив букв, которые будут участвовать в шифровании версии алгоритма. Буквы олицетворяют цифры шифра. Например, версия алгоритма - 123, в буквенном выражении "fpa" 
		switch ($pattern) {
			case 1:
				$cipherSymbArr = array_map(function($el) use($lettersArr) {return array_search((int)$el, $lettersArr);}, $verstionSymbArr);
				break;
			case 2:
				$cipherSymbArr[] = array_search((int)$verstionSymbArr[0], $lettersArr);
				$cipherSymbArr[] = array_search((int)($verstionSymbArr[1] . $verstionSymbArr[2]), $lettersArr);
				$cipherSymbArr[] = array_rand($lettersArr);
				break;
			case 3:
				$cipherSymbArr[] = array_search((int)($verstionSymbArr[0] . $verstionSymbArr[1]), $lettersArr);
				$cipherSymbArr[] = array_search((int)$verstionSymbArr[2], $lettersArr);
				$cipherSymbArr[] = array_rand($lettersArr);
				break;
			case 4:
				$cipherSymbArr[] = array_rand($lettersArr);
				$cipherSymbArr[] = array_search((int)$verstionSymbArr[0], $lettersArr);
				$cipherSymbArr[] = array_search((int)($verstionSymbArr[1] . $verstionSymbArr[2]), $lettersArr);
				break;
			case 5:
				$cipherSymbArr[] = array_rand($lettersArr);
				$cipherSymbArr[] = array_search((int)($verstionSymbArr[0] . $verstionSymbArr[1]), $lettersArr);
				$cipherSymbArr[] = array_search((int)$verstionSymbArr[2], $lettersArr);
				break;
		}

		$resultVersionArr = [];

		//Цикл должен объединить массив цифр и массив букв таким образом, чтобы относительная последовательность символов в итоговом массиве совпадала с последовательность символов исходных подмасивов. Например, в случае объединения двух массивов [1, 2, 3] и ['a', 'b', 'c']
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
   * Перестраиваем матрицы в соответствии с векторами инициализации
   *
	 * @param array $matrix матрица для преобразования
	 * @param boolean $pattern паттерн преобразования: 1 - столбцы/строки, 0 - строки/столбцы
   * @param int $vertical вертикальный вектор инициализации (сдвиг столбцов)
   * @param int $horizon горизонтальный вектор инициализации (сдвиг строк)
   * @return void
   */
  private function transformMatrix($matrix, $pattern, $vertical, $horizon)
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
   * Метод формирует массив с координатами символов исходного сообщения
   * 
   * @param string $text текст требующий преобразования
   * @return array массив символов исходного текста с координатами из матриц
   */
  private function createSymbCoords($text)
  {
    $symbCoordArr = [];
    //Бьем фразу для шифрования по символам на массив и ищем каждый символ в матрицах
    foreach ($this->getStrArr($text) as $symbKey => $symbol) {
      //Определяем в какой матрице ищем символ. При шифровке если элемент четный - в 1й, если нечетный - во 2й. При дешифровке обратная ситуация
      if ($this->encrypt) {
        $matrix = ($symbKey % 2 !== 0 ? $this->transformedMatrixTwo : $this->transformedMatrixOne);
      } else {
        $matrix = ($symbKey % 2 !== 0 ? $this->transformedMatrixOne : $this->transformedMatrixTwo);
      }
      //Флаг был ли найден символ в матрицах. Если нет - символ не шифруется
      $findSymbol = false;
      foreach ($matrix as $matrixRow => $rowData) {
        if (($symbolCol = array_search($symbol, $rowData, true)) !== false) {
          $symbCoordArr[] = [$matrixRow, $symbolCol];
          $findSymbol = true;
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
  private function createCiphrCoords($coordSymbArr)
  {
    $ciphrCoordArr = [];
    foreach ($coordSymbArr as $symbKey => $simbCoord) {
      //Если грамм не является массивом с координатами - это нераспознанный символ, переходим к следующему грамму
      if (!is_array($simbCoord)) {
        $ciphrCoordArr[] = $simbCoord;

        continue;
      }
      //Если биграмм неполный (грамм не имеет "пары") - просто меняем координаты строки
      if (($grammKey = $this->returnNearbyGramm($symbKey, $coordSymbArr)) === false || !is_array($coordSymbArr[$this->returnNearbyGramm($symbKey, $coordSymbArr)])) {
        $ciphrCoordArr[] = [$simbCoord[1], $simbCoord[0]];

        continue;
      } else {
        $ciphrCoordArr[] = [$coordSymbArr[$grammKey][0], $simbCoord[1]];
      }
    }

    return $ciphrCoordArr;
  }


	/**
   * Метод возвращает ключ грамма, который нужно использовать для определения координат зашифрованного символа
   *
   * @param int $grammKey ключ грамма, координаты которого нужно заменить на координаты соседнего (предыдущего, либо следующего) грамма в биграмме, который берется из $coordArr
   * @param array $coordArr массив со всеми символами сообщения
   * @return int|false false возвращается в случае неполного биграмма (грамм не имеет соседа), в остальных случаях возвращается ключ грамма, чьи координаты надо взять
   */
  private function returnNearbyGramm($grammKey, $coordArr)
  {
    $resultGrammKey = null;
    if ($grammKey % 2 === 0) {
      //Если у последнего символа нет "пары" (биграмм не полный), то меняем местами координаты строки и столбца. 
      //Эта ситуация может возникнуть только для четных символов, так как нечетные берут координаты у предыдущих символов
      if (!array_key_exists($grammKey + 1, $coordArr)) {
        return false;
      }
      $resultGrammKey = $grammKey + 1;
    } else {
      $resultGrammKey = $grammKey - 1;
    }

    return $resultGrammKey;
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

        continue;
      }
      $resultSymbCoord = $symbCoord;
      //Если грамм имеет "пару" в биграмме
      if (($nearbyGramm = $this->returnNearbyGramm($symbKey, $symbCoordArr)) !== false) {
        //Если один из граммов - нераспознанный символ - не проверяем находятся ли граммы в одном столбце
        if (is_array($symbCoordArr[$nearbyGramm])) {
          //Если столбцы граммов совпадают - меняем граммы местами в исходом биграмме. Таким образом, мы меняем местами и координаты символов в прямоугольниках и избегаем простой смены символов в преобразованном биграмме в случае, когда они находятся в одном столбце (чтобы не было ситуации DG -> GD). Подробнее https://ru.wikipedia.org/wiki/Шифр_Уитстона в пункте "В случае, если буквы исходной биграммы сообщения находятся в одной строке (в горизонтальном шифровании)..."
          if ($symbCoord[1] == $symbCoordArr[$nearbyGramm][1]) {
            $resultSymbCoord = $symbCoordArr[$nearbyGramm];
          }
        }
      }
			if ($this->encrypt) {
        $matrix = ($symbKey % 2 !== 0 ? $this->transformedMatrixOne : $this->transformedMatrixTwo);
				$ciphrArr[] = $matrix[$resultSymbCoord[0]][$resultSymbCoord[1]];
      } else {
        $matrix = ($symbKey % 2 !== 0 ? $this->transformedMatrixTwo : $this->transformedMatrixOne);
				$ciphrArr[] = $matrix[$resultSymbCoord[0]][$resultSymbCoord[1]];
      }
      //Если шифруем - возвращаем символ из одной матрицы, если расшифровываем - из другой
    }

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
      } else if (preg_match('/[a-z]/', $symbol)) {
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

// $n = 1;
// while ($n <= 1) {
// 	$testCipher = (new SimpleCipher('мама мыла раму'))->encryptText(50);
// 	echo '<pre>'; var_dump($testCipher); echo'</pre>';
// 	$decryptText = (new SimpleCipher($testCipher))->decryptText();
// 	echo '<pre>'; var_dump($decryptText); echo'</pre>';
// 	$n++;
// }

//ИСХОДНУЮ СТРОКУ ХЭШИРОВАТЬ. БРАТЬ СУММУ ВСЕХ ЦИФР ИЗ ХЭША И ВСЕХ СИМВОЛОВ (в случае символов - считать за цифры индексы букв из массива. Неопределенные символы не считать). первый и последний символ в этом сегменте сохранять на месте и не брать в расчет. Это нужно для уникальности итогового значения. как будет работать проверка: исходная строка - "тестовый шифр", его хэш - 127df34hfdkdv2j. Берем сумму всех чисел - 1+2+7+3+4+2 = 19. берем сумму всех индексов символов (кроме превого и последнего) [d]+f+h+f+d+k+d+v[j] = 61(например). Берем первый символ добавляем его либо после числа (19d), либо до (d19). Для получения этого сегмента при расшифровке берем регулярку [a-z]{1}?[0-9]+/i|[0-9]+[a-z]{1}/i (удостовериться, что при "попадании" в первое условие регулярки второе условие не пройдет). Сформировавшийся отрезок кладет перед конечным шифром (вместо первого вектора инициализации). То же самое проделываем с буквами и цифрами из второго отрезка - j61 или 61j - кладем их в конец шифра вместо второго вектора инициализации. При расшифровке итоговое сообщение (если пройдена проверка на фейковые символы) так же хэшируем и так же вычленяем сумму чисел плюс первый символ хэша и сумму чисел плюс последний символ хэша. Это позволит избежать ситуации когда изменив один символ в шифре, мы можем получить искревленное, но все еще исходное сообщение (если он не фейковый), например "тесР?вый шифр". Вектора инициализации заменить и формировать исходя из этих значений 19d = 10d, 61j = 7j (подумай, что делать если число больше размерности матрицы)


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
