<?php

// use function Ramsey\Uuid\v1;

mb_internal_encoding("UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(300);

ini_set('xdebug.var_display_max_depth', -1); // без ограничения глубины
ini_set('xdebug.var_display_max_children', -1); // без ограничения количества элементов
ini_set('xdebug.var_display_max_data', -1); // без ограничения длины строк

//TODO
//проверь на уникальность все символы и подумай какие спецсимволы можно добавить

class SimpleCipher
{
	/**
	 * @var array массив ключей шифра, которые будут использоваться в зависимости от 3ей цифры в версии алогоритма
	 */
	private $cipherKeyStorage = [
		0 => 'Sт⇔цVяСН@KИЮВr[5"Z:t*П}÷OmoЕЯврЗ08()ы4§чЙ9ГДУWэ7ишЛvcщАEH~ЖЩ!Iдеaлb/%3ОFу1`М-пёYюШsqJ©;LC_xЫQ\P.zp=ьGdЦy2аз×жX+ nhЬ€NЪм>kХб,{#AфR^]Т6ФРuКЁъБг|efоi≠wMкUπTЭн$jg<&хйЧ№?BсDl',
		1 => '}tлз0sWyк1пЕ%N×\с3LеЯДXу:дTЙцw€мE?)о№G|]pPвщFπIг`rz6хkeЬOj8эяВuDZ>ШьSКМh$Э4аИУЁ2Щ*Рmx@Uрш©и"Т=vъ#(QФн[i≠l<Ъ;Г+П5Jч⇔Зф§coБYёХЫ~Лbы.йVОтq^C,СЦKd{ M_gHб&А7aRю/НЧf÷Ю-A9Ж!Bnж',
		2 => 'йЧо83з]МылlK≠©;d0eW1wэYЖh4v+xЪ>аГ9{ШЙн*bуюCИФЕoпёХвБzУТyЮЫ€ЛНiV÷⇔X?геgК~#Q"Ашa)AЁSт&J§75`%хСкjr(|ВFB:sRжLмTG@ф/t^П×яОMqЩ,ZH}ЯЭ2Nд._u[O<иъ=\Dpцч$πPEсЗ IРfЬUcnЦkбрД6щ!m-ь№',
		3 => 'T&π[03eшМэnВoqГДNX2в{П)о÷4д!Биs<€Ы]B`же}>9hарщKг#%k5Шaiь©PЁl(ЮmUHGQЗA*=№хФХЪЧЦRР+чLЭп"§О-~jwлrСgtO⇔АЬv8ЕxЙЯ\Dй×я/z≠SЖтЩ @ёНEF.Y$кZъИКV:?JбfзCc|Wuм1юц^ынуIpс7_yУЛb;,фd6MТ',
		4 => 'J;oeчэиЖ]"ЁцTwз7{ыфК3хГ+SюПzbИIЮОмkt!тUpвдРЫAViH?З4ХБvЛaЦоOGж2ruR%[C6ьXcMъёKL|sh ФЪE0DZ9FаУd№А}QЬYЧqЩШy(fейnуPлс)mбТЭНBС8В1ДМlЙгрЯяjнЕW5Ngx:пшкщ&*\^-=`~@#$_/,.<>©§π≠×÷€⇔',
		5 => '])2h8Т_гK:ИСoGсП4§щёЦSgЬГ&≠\wcЕrт€FJыyЮ©?aНlm@бндzшжЗх#.Ё~Вqef/$3ХеЧво№uЭь⇔vDБWQ7id|з=уФH`кj(юМTπ"k*0КЖяф×N[UЯVIRп1CиXр;%Z {}мчЛцpДЙ^O6лtЪYАL,ъEMPsОA!B÷эШЫx<а5-РУb+й>9nЩ',
		6 => ',пЙЩ@GRS]#YHМ$€цUчж5pхьl}©ЮъJgF94ф0шРНЖЗзЪ1мmto×юaЁны>uQ⇔ОэЯа3EВТ!БЬIу|XisBд"К8WD≠y/nтkС A<с{:оё`÷h-jлГfФqe.Ур~Е*ИTя2А+[x?P=бL%K6ЫrйO§№г;ЛcV)ДЦvwbZвк_^ПC&ХщMЧd(7\ШиNеzπЭ',
		7 => 'н)Ичph[:EзH"π&К71^фВLy©AGё]§ьRnbmэг!%NуЫO*М№3DYS#o{вЛI/ШЧ€ЁАwJ2Te5zПГ$VxЖl?-тQС `@б≠UX;цпО_ЕBлЭvж~÷и}БъdйсЙZ|рыЮа\оs8.uХю⇔FKP,WCЩхмcФfУЬqЦgщР×iТ<9шЪr0tяЯеЗДН(j6M+=кa>kд4',
		8 => 'B§)яе{Лf$,Ыщi№сВpМb.jlI*KудшСЯЦktzЙ>MЪкgnмx=47Wy^ehDФз÷;ё€ГЖъ(rZ×©~юХd%вэv08цc\л⇔й[_C#So`OЧчGОЕuп3m<т2FД гQEЩ&ы!ЁиA]оπЮЭУYPa+|Т"}L:@нVРЬН/ь-а?RБ6ЗUжШб1хрИJHs9XфП5NTw≠КqА',
		9 => 'ТS9NИz&оxMlзе!эпGL<yМБYRДf$v{КОч€akыЫq⇔=Лw/туJлHh#mё4~ЮOшб|CюTAШ0F?я*ъngЁГ(oXС5VЕн§Ьu[U@ b>Жф%,Пπ}8ЭЙФ2©PЩЪtХ:А_-хЗЧp÷I+и`6рщйrгс№sDмiьcкжУKBв≠E"eQН;d17^)ацдВ]\W3Z.Ц×ЯРj',
		// 1 => 'Sт⇔цVяСН@KИЮВr[5"Z:t*П}÷OmoЕЯврЗ08()ы4§чЙ9ГДУWэ7ишЛvcщАEH~ЖЩ!Iдеaлb/%3ОFу1`М-пёYюШsqJ©;LC_xЫQ\P.zp=ьGdЦy2аз×жX+ nhЬ€NЪм>kХб,{#AфR^]Т6ФРuКЁъБг|efоi≠wMкUπTЭн$jg<&хйЧ№?BсDl',
		// 2 => 'Sт⇔цVяСН@KИЮВr[5"Z:t*П}÷OmoЕЯврЗ08()ы4§чЙ9ГДУWэ7ишЛvcщАEH~ЖЩ!Iдеaлb/%3ОFу1`М-пёYюШsqJ©;LC_xЫQ\P.zp=ьGdЦy2аз×жX+ nhЬ€NЪм>kХб,{#AфR^]Т6ФРuКЁъБг|efоi≠wMкUπTЭн$jg<&хйЧ№?BсDl',
		// 3 => 'Sт⇔цVяСН@KИЮВr[5"Z:t*П}÷OmoЕЯврЗ08()ы4§чЙ9ГДУWэ7ишЛvcщАEH~ЖЩ!Iдеaлb/%3ОFу1`М-пёYюШsqJ©;LC_xЫQ\P.zp=ьGdЦy2аз×жX+ nhЬ€NЪм>kХб,{#AфR^]Т6ФРuКЁъБг|efоi≠wMкUπTЭн$jg<&хйЧ№?BсDl',
		// 4 => 'Sт⇔цVяСН@KИЮВr[5"Z:t*П}÷OmoЕЯврЗ08()ы4§чЙ9ГДУWэ7ишЛvcщАEH~ЖЩ!Iдеaлb/%3ОFу1`М-пёYюШsqJ©;LC_xЫQ\P.zp=ьGdЦy2аз×жX+ nhЬ€NЪм>kХб,{#AфR^]Т6ФРuКЁъБг|efоi≠wMкUπTЭн$jg<&хйЧ№?BсDl',
		// 5 => 'Sт⇔цVяСН@KИЮВr[5"Z:t*П}÷OmoЕЯврЗ08()ы4§чЙ9ГДУWэ7ишЛvcщАEH~ЖЩ!Iдеaлb/%3ОFу1`М-пёYюШsqJ©;LC_xЫQ\P.zp=ьGdЦy2аз×жX+ nhЬ€NЪм>kХб,{#AфR^]Т6ФРuКЁъБг|efоi≠wMкUπTЭн$jg<&хйЧ№?BсDl',
		// 6 => 'Sт⇔цVяСН@KИЮВr[5"Z:t*П}÷OmoЕЯврЗ08()ы4§чЙ9ГДУWэ7ишЛvcщАEH~ЖЩ!Iдеaлb/%3ОFу1`М-пёYюШsqJ©;LC_xЫQ\P.zp=ьGdЦy2аз×жX+ nhЬ€NЪм>kХб,{#AфR^]Т6ФРuКЁъБг|efоi≠wMкUπTЭн$jg<&хйЧ№?BсDl',
		// 7 => 'Sт⇔цVяСН@KИЮВr[5"Z:t*П}÷OmoЕЯврЗ08()ы4§чЙ9ГДУWэ7ишЛvcщАEH~ЖЩ!Iдеaлb/%3ОFу1`М-пёYюШsqJ©;LC_xЫQ\P.zp=ьGdЦy2аз×жX+ nhЬ€NЪм>kХб,{#AфR^]Т6ФРuКЁъБг|efоi≠wMкUπTЭн$jg<&хйЧ№?BсDl',
		// 8 => 'Sт⇔цVяСН@KИЮВr[5"Z:t*П}÷OmoЕЯврЗ08()ы4§чЙ9ГДУWэ7ишЛvcщАEH~ЖЩ!Iдеaлb/%3ОFу1`М-пёYюШsqJ©;LC_xЫQ\P.zp=ьGdЦy2аз×жX+ nhЬ€NЪм>kХб,{#AфR^]Т6ФРuКЁъБг|efоi≠wMкUπTЭн$jg<&хйЧ№?BсDl',
		// 9 => 'Sт⇔цVяСН@KИЮВr[5"Z:t*П}÷OmoЕЯврЗ08()ы4§чЙ9ГДУWэ7ишЛvcщАEH~ЖЩ!Iдеaлb/%3ОFу1`М-пёYюШsqJ©;LC_xЫQ\P.zp=ьGdЦy2аз×жX+ nhЬ€NЪм>kХб,{#AфR^]Т6ФРuКЁъБг|efоi≠wMкUπTЭн$jg<&хйЧ№?BсDl'
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
	// /**
	//  * @var int фейковая длина зашифрованной строки (по умолчанию 100)
	//  */
	// private $fakeLength;
	/**
	 * @var array массив различных символов из ключа шифра, которые не являются буквами или цифрами. Массив нужен для выделения частей указателя на реальную длину исходного текста
	 */
	private $encryptLengthDelimeter;
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
	// /**
	//  * @var bool флаг реверсивности ключа. Используем ли его в оригинальном виде, либо переворачиваем
	//  */
	//private $reverseFlag;
	/**
   * @var int количество символов в векторе инициализации
   */
  private $vectorLength = 3;
	/**
   * @var string первый вектор инициализации в виде биграммы
   */
  private $cipherVectorFirst;
  /**
   * @var string второй вектор инициализации в виде биграммы
   */
  private $cipherVectorSecond;
	/**
   * @var string первый вектор инициализации в виде числа
   */
  private $initializationVectorFirst;
  /**
   * @var string второй вектор инициализации в виде числа
   */
  private $initializationVectorSecond;
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
	private $shiftedMatrixOne;
	/**
	 * @var array преобразованноая вторая матрица после сдвига по векторам инициализации
	 */
	private $shiftedMatrixTwo;
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
	/**
	 * @var array массив с позициями символов соли в массиве [0=>a, 1=>b, 2=>c ...], на основе которого будет происходит запутывание алгоритма при передаче соли
	 */
	private $saltNumberSegments;
	//Хэш суммы всех символов соли
	private $saltHashSum = null;

	public function __construct(string $text, ?string $salt = null)
	{
		$this->text = $text;
		#Гаврилов
		//ПОПРОБУЙ В СОЛЬ ПЕРЕДАТЬ КИТАЙСКИЙ СИМВОЛ ИЛИ РУССКИЙ, ОНИ ДОЛЖНЫ УДАЛЯТЬСЯ ТУТ. В СОЛИ МОЖЕТ БЫТЬ ТОЛЬКО ЛАТИНСКИЕ СИМВОЛЫ И ЦИФРЫ
		//ОНИ НИ В КОЕМ СЛУЧАЕ НЕ ДОЛЖНЫ УДАЛЯТЬСЯ ТУТ. СОЛЬ ДОЛЖНА ВАЛИДИРОВАТЬСЯ НА КАКОМ-ТО ЭТАПЕ. ЕСЛИ ВАЛИДАЦИЯ НЕ ПРОШЛА - ВОЗВРАЩАЕМ ОШИБКУ И СООБЩАЕМ ПОЛЬЗОВАТЕЛЮ О КРИВОЙ СОЛИ
		$this->salt = preg_replace('/[^a-zA-Z0-9]+/', '', $salt);
		$this->matrixDepth = sqrt(mb_strlen($this->cipherKeyStorage[0]));
		$this->saltNumberSegments = $this->getSaltNumbersArr();

		// var_dump($this->saltNumberSegments);
		// die();

		// $this->setSaltHash();
		// die();
	}


	/**
	 * Метод формирует хэш на сумму всех символов соли
	 *
	 * @param [type] $args
	 * @return void
	 */
	private function getHashSaltSum()
	{
		$cipherKeyNumbers = array_merge([], array_map(function($el){return (int)$el;}, array_filter($this->getStrArr($this->cipherKey), function($el){return preg_match('/[0-9]/', $el);})));
		//Определенным образом дополняем массив числами 10, 11, 12. Так как итоговый массив должен содержать 13 элементов. Столько же, сколько в одном сегменте массива $this->saltNumberSegments. Если элементов будет меньше - мы не будем задействовать один из элементов в сегментах $this->saltNumberSegments. Если элементов будет больше - мы не найдем нужный элемент в сегменте $this->saltNumberSegment, будет ошибка
		foreach (array_reverse($cipherKeyNumbers) as $key => $value){
			if (in_array($value, [0, 1, 2]) !== false){
				$newPos = ($key == 9 ? 0 : $key);
				$transformNumb = $cipherKeyNumbers[$newPos];
				$cipherKeyNumbers[$newPos] = 10 + $value;
				if ($key % 2 !== 0) {
					$cipherKeyNumbers[] = $transformNumb;
				} else {
					array_unshift($cipherKeyNumbers, $transformNumb);
				}
			}
		}
		//Этот метод позволяет суммировать вложенные массивы
		$getSaltSum = function (array $arr) use (&$getSaltSum) : float {
			$sum = array_sum($arr);
			foreach($arr as $child) {
				$sum += is_array($child) ? $getSaltSum($child) : 0;
			}
			return $sum;
		};
		//Общая сумма всех индексов всех символов соли
		$generalSaltSum = $getSaltSum($this->saltNumberSegments);
		//Умножаем, чтобы не уйти в отрицательные значения при дальнейших операциях
		$generalSaltSum = $generalSaltSum * 3.14;
		foreach ($cipherKeyNumbers as $cipherNumKey => $cipherKeySymb){
			//Складываем и умножаем (либо вычитаем и делим в зависимости от четной или нечетной позиции) индексы элементов соли между собой.
			if ($cipherNumKey % 2 !== 0) {
				$generalSaltSum += array_sum($this->saltNumberSegments[$cipherKeySymb]);
				$generalSaltSum = $generalSaltSum * ($cipherKeySymb == 0 ? 1 : $cipherKeySymb);
			} else {
				$generalSaltSum -= array_sum($this->saltNumberSegments[$cipherKeySymb]);
				//умножаем на 0.123, чтобы при делении гарантированно избавиться от периода (например, 0.11111111)
				$generalSaltSum = $generalSaltSum / ($cipherKeySymb == 0 ? 1 : $cipherKeySymb) * 0.123;
			}
		}
		$generalSaltSum = str_replace('.', '', (string)$generalSaltSum);

		return $generalSaltSum;
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
		//Максимальное значение - примерно половина от длины ключа шифра
		$this->windowSizeFirst = $this->getRandNum(floor(count($this->getStrArr($this->cipherKeyStorage[0])) / 2), 12);
		$this->shiftCountFirst = $this->getRandNum(999, 99);
		/**
		 * @var string Флаг реверса ключа шифра, который используется для формирования первой матрицы. Ключ для второй матрицы всегда имеет противоположное значение
		 */
		$reverseCipherKey = ($this->getRandNum(3, 1) == 1 ? 0 : 1);
		//Индекс ключа шифрования на основании которого будет формироваться 1я матрица
		//cipherKeyIndex_fake - фейковый ключ шифра, который помещается в полезную нагрузку шифра. При передаче соли реальный ключ шифра преобразуется в соответствии с солью. При шифровании используется реальный ключ шифра, но в полезную нагрузку кладется фейковый ключ шифра. Таким образом, расшифровка БЕЗ передачи соли не сформирует действительный ключ шифра, использующийся при шифровании и расшифровка не будет успешной
		$cipherKeyIndex = $cipherKeyIndex_fake = $this->getRandNum(10);
		//Если передается соль, используем ее для определения ключа шифра
		if ($this->salt) {
			$cipherKeyIndex = $this->getRealCipherKey($cipherKeyIndex);
		}
		$this->cipherKey = $this->cipherKeyStorage[$cipherKeyIndex];
		//Ключ второго шифра для формирования второй матрицы строится на основании другого ключа из массива $this->cipherKeyStorage (следующего ключ после ключа первой матрицы, либо первый ключ массива, если ключ для первый матрицы оказался последним в массиве)
		$this->cipherKey_second = $this->cipherKeyStorage[$cipherKeyIndex == (count($this->cipherKeyStorage) - 1) ? 0 : $cipherKeyIndex + 1];
		//Если передается соль, формируем из нее хэш на сумму всех символов соли, которая будет использоваться для запутывания ключей шифра и для определения паттерном формирования матриц. 
		//Формируем ПОСЛЕ определения ключа шифра, так как он используется при формировании хэша 
		if ($this->salt) {
			$this->saltHashSum = $this->getHashSaltSum();
		}
		// die();
		/**
		 * @var string версия приложения в зашифрованном виде
		 */
		$encryptVersion = $this->setVersion($this->salt ? $cipherKeyIndex_fake : $cipherKeyIndex);
		$this->windowSizeSecond = $this->getRandNum(floor(count($this->getStrArr($this->cipherKeyStorage[0])) / 2), 12);
		$this->shiftCountSecond = $this->shiftCountFirst + $this->getRandNum(1999, 99);
		//Заполняем массив с параметрами преобразования матриц (пока что данными для преобразования первой матрицы)
		$matrixParamArr = [
							0 => $this->windowSizeFirst, 
							1 => $this->shiftCountFirst, 
							2 => $reverseCipherKey,
							3 => $this->windowSizeSecond,
							4 => $this->shiftCountSecond,
						];
		/**
		 * @var array массив, который был изменен с помощью переданной соли
		 */
		$transformedMatrixParamArr = [];
		//Ниже преобразуем ключевые параметры шифра, если была передана соль
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
		$this->matrixOne = $this->fillMatrix($mixedCipher, (int)substr(array_sum($this->salt ? $transformedMatrixParamArr : $matrixParamArr), -1, 1));
		//Добавляем 1 к предыдущей сумме параметров матрицы, так как это дает 50% шанс, что паттерн заполнения изменится для второй матрицы (так как паттерны делятся по двойкам: 0,1 - 1й паттерн, 2,3 - 2й и так далее). На самом деле, нам не обязательно, чтобы паттерн менялся, так как сама последовательность символов для формирования матрицы разная, поэтому добавление 1 позволит с равной вероятностью получить как тот же паттерн заполнения матрицы, что был для 1й матрицы (0 превратится в 1 - и то и то 1й паттерн), так и следующий паттерн (1 превратится в 2 - это уже 2й паттерн).
		$this->matrixTwo = $this->fillMatrix($mixedCipherTwo, (int)substr(array_sum($this->salt ? $transformedMatrixParamArr : $matrixParamArr) + 1, -1, 1));
		//Только буквы для рандомной вставки между параметрами полезной нагрузки для трансформации матриц
		$lettersArr = array_flip($this->lettersArr);
		//Формируем итоговую строку с параметрами формирования матриц. В качестве разделителя между параметрами формирования матриц использовать только случайные БУКВЫ, без знаков препинаний и различных спецсимволов (@, ^ и т.д.), потому что эти символы, в свою очередь, будут использоваться для обособления в параметрах преобразований матрицы первой части указателя на реальную длину шифруемого текста
		$transformMatrixParam = implode('', array_map(function($el) use($lettersArr) {return $el . $lettersArr[array_rand($lettersArr)];}, $matrixParamArr));
		$hashTextParams = $this->getTextHashPointer($this->text, $transformMatrixParam);
		$this->cipherVectorFirst = $hashTextParams['firstVector'];
		$this->cipherVectorSecond = $hashTextParams['secondVector'];
		$this->initializationVectorFirst = $this->getVector($this->cipherVectorFirst, 'vert');
		$this->initializationVectorSecond = $this->getVector($this->cipherVectorSecond, 'hor');
		$this->shiftedMatrixOne = $this->shiftMatrix($this->matrixOne, 1, $this->initializationVectorFirst, $this->initializationVectorSecond);
		$this->shiftedMatrixTwo = $this->shiftMatrix($this->matrixTwo, 0, $this->initializationVectorFirst, $this->initializationVectorSecond);
		$this->transformedMatrixArr[1] = $this->shiftedMatrixOne;
		$this->transformedMatrixArr[2] = $this->shiftedMatrixTwo;
		$ecnryptText_interim = $this->transformSourceText($this->text);
		//var_dump($ecnryptText_interim);
		//Промежуточный зашифрованный текст (без внедренных фейковых символов)
		$this->encryptLengthDelimeter = array_filter($this->getStrArr($this->cipherKey), function($el){return preg_match('/[^a-zа-ё0-9]/ui', $el);});
		//Итоговая длина шифруемого текста
		$encryptTextLength = $this->encryptLengthPointer($transformMatrixParam);
		//Первая часть указателя на длину исходного сообщения (3546) без разграничителей - 35
		$encryptLengthWithoutDelimeter_first = mb_substr((string)$encryptTextLength, 0, $this->getRandNum(mb_strlen((string)$encryptTextLength) + 1));
		//Вторая часть указателя на длину исходного сообщения (3546) с разграничителями - {46"
		$encryptLength_second = $this->encryptLengthDelimeter[array_rand($this->encryptLengthDelimeter)] . mb_substr((string)$encryptTextLength, mb_strlen($encryptLengthWithoutDelimeter_first)) . $this->encryptLengthDelimeter[array_rand($this->encryptLengthDelimeter)];
		//Первая часть указателя на длину исходного сообщения (3546) с разграничителями - *35$
		$encryptLength_first = $this->encryptLengthDelimeter[array_rand($this->encryptLengthDelimeter)] . $encryptLengthWithoutDelimeter_first . $this->encryptLengthDelimeter[array_rand($this->encryptLengthDelimeter)];
		$fakeLength = $this->calcLenFakeSymb($fakeLength, $ecnryptText_interim, $this->cipherVectorFirst . $this->cipherVectorSecond . $transformMatrixParam . $encryptVersion . $encryptLength_first . $encryptLength_second);
		//Итоговый зашифрованный текст исходного сообщения (заполненный фейковыми символами)
		$resultCipherText = $this->fillFakeLength($ecnryptText_interim, $fakeLength, $this->createFakeLengthHash($ecnryptText_interim, $transformMatrixParam));                  
		//Итоговый шифр, включающий в себя зашифрованный текст исходного сообщений + полезная нагрузка шифра
		$resutCipher = $this->constructCipherText($this->cipherVectorFirst, $this->cipherVectorSecond, $resultCipherText, $transformMatrixParam, $encryptVersion, $encryptLength_first, $encryptLength_second);

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
	 * Метод преобразует ключ шифра, используя данные соли
	 *
	 * @param int $cipherKeyIndex текущий "фейковый" ключ шифра
	 * @return int
	 */
	private function getRealCipherKey(int $cipherKeyIndex): int
	{
		$cipherKeyIndex += $this->saltNumberSegments[$cipherKeyIndex][$cipherKeyIndex] ?? 1;
		//Так как ключей шифра 10, если значение больше 10, приводим его в допустимый диапазон
		$cipherKeyIndex = $cipherKeyIndex > 9 ? ($cipherKeyIndex == 10 ? 0 : floor($cipherKeyIndex/10)) : $cipherKeyIndex;

		return $cipherKeyIndex;
	}


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
		$this->initializationVectorFirst = $this->getVector($vectorVert, 'vert');
		$this->initializationVectorSecond = $this->getVector($vectorHor, 'hor');
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
		//Теперь очищаем от полезной нагрузки, связанной с версией алгоритма, 2й частью указателя на длину исходной строки и вторым вектором инициализации
		$clearCipherText = mb_substr($clearCipherText, 0, (0 - mb_strlen($cipherVersion)));
		//Получаем чистую версию алгоритма из зашифрованного отрезка, удаляя в строке два последних символа (вектор горизонтальной инициализации) и заменяя на пустоту сегмент, содержащий 2ю часть фейковой длины шифра ($lengthSecondMatches[1])
		//$cipherVersion = $this->getVersion(mb_substr(str_replace($lengthSecondMatches[1], '', $cipherVersion), 0, -2));
		$cipherVersion = $this->getVersion(mb_substr(str_replace($lengthSecondMatches[1], '', $cipherVersion), 0, -3));
		$cipherKeyIndex = substr($cipherVersion, -1);
		if ($this->salt) {
			$cipherKeyIndex = $this->getRealCipherKey($cipherKeyIndex);
		}
		$this->cipherKey = $this->cipherKeyStorage[$cipherKeyIndex];
		$this->cipherKey_second = $this->cipherKeyStorage[$cipherKeyIndex == (count($this->cipherKeyStorage) - 1) ? 0 : $cipherKeyIndex + 1];
		if ($this->salt) {
			$this->saltHashSum = $this->getHashSaltSum();
		}
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
		$reverseCipherKey = ($reverseCipherKey ? 0 : 1);
		$mixedCipherTwo = $this->shiftCipherKey($this->cipherKey_second, $this->windowSizeSecond, $this->shiftCountSecond, $reverseCipherKey);
		$this->matrixOne = $this->fillMatrix($mixedCipher, (int)substr(array_sum($this->salt ? $transformedMatrixParamArr : $matrixParamArr), -1, 1));
		$this->matrixTwo = $this->fillMatrix($mixedCipherTwo, (int)substr(array_sum($this->salt ? $transformedMatrixParamArr : $matrixParamArr) + 1, -1, 1));
		$this->transformedMatrixArr[1] = $this->shiftMatrix($this->matrixOne, 1, $this->initializationVectorFirst, $this->initializationVectorSecond);
		$this->transformedMatrixArr[2] = $this->shiftMatrix($this->matrixTwo, 0, $this->initializationVectorFirst, $this->initializationVectorSecond);
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
		//$coordCiphrSymbArr_interim = $this->transformSourceText($clearDecryptText);
		$ecnryptText_interim = $this->transformSourceText($clearDecryptText);
		// var_dump('после трансформации');
		// $this->drawMatrix($this->transformedMatrixArr[1]);
		// $this->drawMatrix($this->transformedMatrixArr[2]);
		// //$ecnryptText_interim = $this->transformSourceText($coordCiphrSymbArr_interim);
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
	 * Метод преобразовывает ключ шифра, используя соль
	 *
	 * @param string $cipherKey - ключ для преобразования
	 * @return string
	 */
	private function useSaltToCipherKey(string $cipherKey): string
	{
		//Новый ключ шифрования
		$newCipherKey = null;
		$cipherKeySymbArr = $this->getStrArr($cipherKey);
		preg_match_all('/[1-9]+/', $this->salt, $saltNumbers);
		$saltNumbers = array_map(function($el){return intval($el);}, $saltNumbers[0]);
		$saltNumbersCount = 0;
		//var_dump($saltNumbers);
		foreach ($this->getStrArr($this->salt) as $saltKey => $saltSymb){
			$сipherSymbKey = array_search($saltSymb, $cipherKeySymbArr);
			// var_dump($saltSymb);
			// var_dump($сipherSymbKey);
			// var_dump("итерация - $saltKey");
			// var_dump("число - $saltNumbers[$saltNumbersCount]");
			/**
			 * @var int $newPosition новая позиция в ключе шифра символа из соли
			 */
			$newPosition = $сipherSymbKey + $saltKey + $saltNumbers[$saltNumbersCount];
			//var_dump($newPosition);
			//Если число из перебираемого массива чисел соли нечетное, новая позиция символа берется с конца ключа шифра, а не с начала. Например, если новая позиция = 15, то она пересчитывается на 169 - 15 = 154 
			if ($saltNumbers[$saltNumbersCount] % 2 !== 0) {	
				$newPosition = count($cipherKeySymbArr) - $newPosition;
				//var_dump($newPosition);
			}
			/**
			 * @var $replaceSymbol заменяемый символ в ключе шифра
			 */
			$replaceSymbol = null;
			//Проверяем новую позицию символа из соли. Если новая позиция больше чем длина ключа шифра, пересчитываем ее, чтобы уменьшить. Если она в итоге становится меньше нуля, то либо кладем символ в начало ключа шифра, либо в самый конец, в зависимости от того текущая итерация перебора соли четная или нет
			if ($newPosition >= count($cipherKeySymbArr)) {
				$newPosition = $saltKey - $сipherSymbKey - $saltNumbers[$saltNumbersCount];
				//var_dump($newPosition);
			}
			if ($newPosition < 0) {
				$newPosition = ($saltKey % 2 !== 0 ? 0 : count($cipherKeySymbArr) - 1);
				//var_dump($newPosition);
			}
			$replaceSymbol = $cipherKeySymbArr[$newPosition];
			// var_dump("заменяемый символ - $replaceSymbol");
			// var_dump("новая позиция - $newPosition");
			$cipherKeySymbArr[$newPosition] = $saltSymb;
			$cipherKeySymbArr[$сipherSymbKey] = $replaceSymbol;
			//var_dump($cipherKeySymbArr);
			$saltNumbersCount++;
			if ($saltNumbersCount == count($saltNumbers)) {
				$saltNumbersCount = 0;
			}
		}
		$newCipherKey = implode('', $cipherKeySymbArr);

		// var_dump($cipherKey);
		// var_dump($newCipherKey);

		return $newCipherKey;
	}

	#Гаврилов
	//В ВЕРСИИ БЫЛ СВОБОДНЫЙ СЛОТ ПОД ПОЛЕЗНУЮ НАГРУЗКУ. ПОПРОБОВАТЬ ВСЕ ТАКИ ВЕРСИЮ СДЕЛАТЬ ТРЕХЗНАЧНОЙ А СВОБОДНЫЙ СЛОТ ПОТРАТИТЬ И ЗАШИТЬ ТУДА ПОРЯДКОВЫЙ НОМЕР ИСПОЛЬЗУЕМОГО КЛЮЧА ШИФРА?

	/**
	 * Метод преобразовывает массив символов соли в массив чисел, где каждое число - ключ элемента в массиве [0 => a, 1 => b, 2 => c ...], а затем делит массив на сегменты, равные квадратному корню из длины соли. Этот массив сегментов нужен для более случайного перемешивания параметров шифрования при использовании соли
	 *
	 * @return array
	 */
	private function getSaltNumbersArr(): array
	{
		$cipherSaltArr = str_split($this->salt);
		// var_dump($cipherSaltArr);
		//Массив с латинскими буквами в верхнем регистре, который мы ниже объединим с массивом латинских букв в нижнем регистре и на его основе [n => 2, H => 3, s => 4 ...] будем считать сумму ключей символов в соли
		$upperLatinLetters = array_map(function($el){return strtoupper($el);}, array_flip($this->latinLetters));
		$cipherSaltArr = array_map(function($el) use($upperLatinLetters) {return preg_match('/[^0-9]/', $el) ? array_flip(array_flip($this->latinLetters) + array_merge($upperLatinLetters, []))[$el] : (int)$el;}, $cipherSaltArr);
		// var_dump($cipherSaltArr);
		$saltNumberSegments = [];
		//Размер одного сегмента символов
		$numberSegmentSize = (int)floor(sqrt(count($cipherSaltArr)));
		$n = $i = 0;
		while ($n < $numberSegmentSize) {
			$numberSegment = [];
			while ($i < $numberSegmentSize) {
				$numberSegment[] = $cipherSaltArr[$n * $numberSegmentSize + $i];

				$i++;
			}
			$i = 0;
			$saltNumberSegments[] = $numberSegment;

			$n++;
		}
		return $saltNumberSegments;
	}


	#Гаврилов
	//КАК БУДЕТ ВРЕМЯ, ПОРАБОТАЙ ЕЩЕ НАД СОЛЬЮ, ЕЩЕ БОЛЕЕ ИСПОЛЬЗОВАВ ВСЕ СИМВОЛЫ СОЛИ В ЗАПУТЫВАНИИ. ТЕКУЩАЯ ВЕРОЯТНОСТЬ КОЛЛИЗИЙ 0.6% (одна коллизия на 15к попыток). Нужно довести хотя бы до 1 коллизии на 65к попыток
	/**
	 * Метод применяет соль к параметрам преобразования матриц, чтобы по разному формировались ключи к матрицам, в зависимости от секретного ключа
	 *
	 * @param array $matrixParam массив параметров преобразования матриц
	 * @return array
	 */
	private function useSaltToMatrixParam(array $matrixParam): array
	{
		$transformedMatrixParam = $matrixParam;
		//Получаем цифры из ключа шифра. Получится случайный массив, наполненный цифрами от 0 до 9
		// $cipherKeyNumbers = array_merge([], array_map(function($el){return (int)$el;}, array_filter($this->getStrArr($this->cipherKey), function($el){return preg_match('/[0-9]/', $el);})));
		// //Определенным образом дополняем массив числами 10, 11, 12. Так как итоговый массив должен содержать 13 элементов. Столько же, сколько в одном сегменте массива $this->saltNumberSegments. Если элементов будет меньше - мы не будем задействовать один из элементов в сегментах $this->saltNumberSegments. Если элементов будет больше - мы не найдем нужный элемент в сегменте $this->saltNumberSegment, будет ошибка
		// foreach (array_reverse($cipherKeyNumbers) as $key => $value){
		// 	if (in_array($value, [0, 1, 2]) !== false){
		// 		$newPos = ($key == 9 ? 0 : $key);
		// 		$transformNumb = $cipherKeyNumbers[$newPos];
		// 		$cipherKeyNumbers[$newPos] = 10 + $value;
		// 		if ($key % 2 !== 0) {
		// 			$cipherKeyNumbers[] = $transformNumb;
		// 		} else {
		// 			array_unshift($cipherKeyNumbers, $transformNumb);
		// 		}
		// 	}
		// }
		// //Этот метод позволяет суммировать вложенные массивы
		// $getSaltSum = function (array $arr) use (&$getSaltSum) : float {
		// 	$sum = array_sum($arr);
		// 	foreach($arr as $child) {
		// 		$sum += is_array($child) ? $getSaltSum($child) : 0;
		// 	}
		// 	return $sum;
		// };
		// //Общая сумма всех индексов всех символов соли
		// $generalSaltSum = $getSaltSum($this->saltNumberSegments);
		// //Умножаем, чтобы не уйти в отрицательные значения при дальнейших операциях
		// $generalSaltSum = $generalSaltSum * 3.14;
		// foreach ($cipherKeyNumbers as $cipherNumKey => $cipherKeySymb){
		// 	//Складываем и умножаем (либо вычитаем и делим в зависимости от четной или нечетной позиции) индексы элементов соли между собой.
		// 	if ($cipherNumKey % 2 !== 0) {
		// 		$generalSaltSum += array_sum($this->saltNumberSegments[$cipherKeySymb]);
		// 		$generalSaltSum = $generalSaltSum * ($cipherKeySymb == 0 ? 1 : $cipherKeySymb);
		// 	} else {
		// 		$generalSaltSum -= array_sum($this->saltNumberSegments[$cipherKeySymb]);
		// 		//умножаем на 0.123, чтобы при делении гарантированно избавиться от периода (например, 0.11111111)
		// 		$generalSaltSum = $generalSaltSum / ($cipherKeySymb == 0 ? 1 : $cipherKeySymb) * 0.123;
		// 	}
		// }
		// $generalSaltSum = str_replace('.', '', (string)$generalSaltSum);
		// var_dump($this->saltHashSum);
		$transformedMatrixParam = $matrixParam;
		//Для формирования новых параметров преобразования матриц используем цифры суммы позиций символов соли (начиная с конца, так как в начале числа могут быть более близкими от соли к соли)
		$shiftWindowSize_first = (int)substr($this->saltHashSum, -1);
		$shiftWindowSize_second = (int)substr($this->saltHashSum, -2, 1);
		//Двузначное число из начала суммы символов добавляем к количеству итераций сдвига первого ключа, двузначное число из конца суммы, соответственно, к количеству итераций второго ключа
		$shiftIteration_first = (int)substr($this->saltHashSum, -4, 2);
		$shiftIteration_second = (int)substr($this->saltHashSum, -6, 2);
		//Трансформируем массив с параметрами сдвига ключа шифра. Если новые значений окна захвата символов больше примерно половины от ключа шифра - через вычитание делаем окно захвата меньше исходного, а не больше
		$transformedMatrixParam[0] = ($transformedMatrixParam[0] + $shiftWindowSize_first >= floor(count($this->getStrArr($this->cipherKeyStorage[0])) / 2)) ? $transformedMatrixParam[0] - $shiftWindowSize_first : $transformedMatrixParam[0] - $shiftWindowSize_first;
		$transformedMatrixParam[1] = $transformedMatrixParam[1] + $shiftIteration_first;
		$transformedMatrixParam[3] = ($transformedMatrixParam[3] + $shiftWindowSize_second >= floor(count($this->getStrArr($this->cipherKeyStorage[0])) / 2)) ? $transformedMatrixParam[3] - $shiftWindowSize_second : $transformedMatrixParam[3] - $shiftWindowSize_second;
		$transformedMatrixParam[4] = $transformedMatrixParam[4] + $shiftIteration_second;

		return $transformedMatrixParam;

		// $saltSymbSum = array_sum($cipherSaltArr);
		//Первую цифру из суммы символов соли добавляем к размеру окна захвата символов для преобразования первого ключа (если их сумма не больше примерно половины от величины ключа шифра, в противном случае - вычитаем). Последнюю цифру из суммы, соответственно, добавляем к размеру окна захвата для преобразования второго ключа
		//$shiftWindowSize_first = substr($saltSymbSum, 0, 1);

		//ПРОБЕГАЕМСЯ ПО ВСЕМ ЧИСЛАМ ПРЕВОГО И ВТОРОГО КЛЮЧЕЙ ШИФРА И В ЗАВИСИМОСТИ ОТ НИХ ОБРАЩАЕМСЯ К ОПРЕДЕЛЕННОМУ СЕГМЕНТУ СИМВОЛОВ СОЛИ ДЛЯ ПРЕОБРАЩОВАНИЯ ПАРАМЕТРОВ МАТРИЦЫ		

		// var_dump($this->saltNumberSegments);

		// die();

		// if ($cipherKeyNumbers[0] % 2 === 0) {
		// 	$shiftWindowSize_first = $this->windowSizeFirst + $this->saltNumberSegments[$cipherKeyNumbers[0]][0];
		// 	$shiftWindowSize_second = $this->windowSizeSecond - $this->saltNumberSegments[$cipherKeyNumbers[1]][1];
		// 	$shiftIteration_first = $this->shiftCountFirst + $this->saltNumberSegments[$cipherKeyNumbers[2]][2] * 5;
		// 	$shiftIteration_second = $this->shiftCountSecond + $this->saltNumberSegments[$cipherKeyNumbers[3]][3] * 5;
		// } else {
		// 	$shiftWindowSize_first = $this->windowSizeFirst - $this->saltNumberSegments[$cipherKeyNumbers[0]][0];
		// 	$shiftWindowSize_second = $this->windowSizeSecond + $this->saltNumberSegments[$cipherKeyNumbers[1]][1];
		// 	$shiftIteration_first = $this->shiftCountFirst + $this->saltNumberSegments[$cipherKeyNumbers[4]][4] * 5;
		// 	$shiftIteration_second = $this->shiftCountSecond + $this->saltNumberSegments[$cipherKeyNumbers[5]][5] * 5;
		// }

		// if ($shiftWindowSize_first >= floor(count($this->getStrArr($this->cipherKeyStorage[0])) / 2)) {
		// 	$shiftWindowSize_first = $this->windowSizeFirst - $this->saltNumberSegments[$cipherKeyNumbers[0]][0];
		// } else if ($shiftWindowSize_first < 10) {
		// 	$shiftWindowSize_first = $this->windowSizeFirst + $this->saltNumberSegments[$cipherKeyNumbers[0]][0];
		// }
		// if ($shiftWindowSize_second >= floor(count($this->getStrArr($this->cipherKeyStorage[0])) / 2)) {
		// 	$shiftWindowSize_second = $this->windowSizeSecond - $this->saltNumberSegments[$cipherKeyNumbers[1]][1];
		// } else if ($shiftWindowSize_second < 10) {
		// 	$shiftWindowSize_second = $this->windowSizeSecond + $this->saltNumberSegments[$cipherKeyNumbers[1]][1];
		// }
		// $transformedMatrixParam[0] = $shiftWindowSize_first;
		// $transformedMatrixParam[1] = $shiftIteration_first;
		// $transformedMatrixParam[3] = $shiftWindowSize_second;
		// $transformedMatrixParam[4] = $shiftIteration_second;

		// // var_dump([$this->windowSizeFirst, $this->shiftCountFirst, $this->windowSizeSecond, $this->shiftCountSecond]);

		// // var_dump($transformedMatrixParam);

		//return $transformedMatrixParam;
	}


	/**
	 * Метод формирует массив-указатель на хэш исходной строки, который кладется в шифр для сравнения такого же массива-указателя на хэш дешифруемой строки. Если они не совпадают - в шифре был подменен символ исходной строки. В этом случае возвращаем пользователю фейковую строку 
	 * 
	 * @param string $text текст на хэш которого будет указывать полученный массив
	 * @param string $matrixParamString строка с параметрами преобразования матриц и случайными разделителями между параметрами Нужны для уникальности результатов работы метода для каждого шифра
	 * @return array
	 */
	private function getTextHashPointer(string $text, string $matrixParamString): array
	{
		//Массив указатель
	  	$hashPointerArr = [
			'firstVector' => null,		//Элемент формируется на основе цифр хэша строки
			'secondVector' => null		//Элемент формируется на основе букв хэша строки
		];
		//Хэш строки. Подмешиваем строку с параметрами преобразования матриц для уникальности
		$hashText = hash('whirlpool', $text . $this->reverseString($matrixParamString));
		//Кодируем в base64 для разбавшения хэша более широким диапазоном используемых символов. В алгоритме хэша используются цифры 0-9 и буквы abcdef. При кодировании в base64 больше символов.
		$encodeText = preg_replace('/[^a-z0-9]/i', '', base64_encode($hashText));
		//Первые 3 символа с начала хэша - первый вектор
		$firstVector = substr($encodeText, 0, 3);
		//Последние 3 символа с конца строки - второй вектор
		$secondVector = substr($encodeText, -3);
		$hashPointerArr['firstVector'] = $firstVector;
		$hashPointerArr['secondVector'] = $secondVector;

	  	return $hashPointerArr;
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
	 * @param int $windowSize окно захвата символов, которое сдвигается каждую итерацию. В шифре abj36fh5k окно захвата символов будет (в случае величины 4 - abj3 ([abj3]6fh5k)
	 * @param int $shiftCount количество итераций сдвига в шифре
	 * @param bool reverseCipherKey флаг реверсивности ключа шифра
	 * @return string
	 */
	private function shiftCipherKey(string $cipherKey, int $windowSize, int $shiftCount, $reverseCipherKey): string
	{
		$transformedCipherKey = $cipherKey;
		//generalIteration - счетчик общего количества итераций
		//stringIteration - счетчик итераций в рамках одного прохода по строке ключу шифра. Сбрасывается как только цикл проходит ключ полностью до конца и должен вернуться в начало
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
				//При достижении конца строки сбрасываем количество итераций цикла
				$stringIteration = 0;
			}
			//Регулярка, с помощью которой бьем строку на сегменты, которые будем переставлять
			$pattern = "/" . ($leftPart ? "(.{" . $leftPart . "})" : null) . "(.{" . $windowSize . "})(.{" . $shiftSize . "})(.{" . $rightPart . "})/u";
			$replacement = ($leftPart ? '${1}${3}${2}${4}' : '${2}${1}${3}');
			//Само действие перестановки сгементов шифра
			$transformedCipherKey = preg_replace($pattern, $replacement, $transformedCipherKey);
			//Если вектор увеличения отключен, окно захвата символов уменьшается, промежуток пропуска символов увеличивается.
			if ($increaseVector == false) {
				$windowSize--;
				$shiftSize++;
				//Как только окнок захвата уменьшается до нуля, вектор увеличения включается, окно захвата увеличивается, промежуток пропуска символов уменьшается
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
		//Позиция разделения строки с параметрами матриц после которогой будет вставлена первая часть фейковой длины. Например, параметры преобразования матрицы - 20e890о0e22г1169w, первая часть версии - №1%, позиция разделения - 5. Итоговый сегмент с параметрами преобразования матрицы + первой частью версии алгоритма - 20e89№1%0о0e22г1169w
		//По аналогии работает шифрование второй части фейковой длины с версией алгоритма ниже в этом методе
		//Максимальная позиция  куда вставляется отрезок с первой частью указателя на длину исходной строки ( №1% ) должна быть равна длине сегмента с параметрами матрицы ( 20e890о0e22г1169w ) минус 1, так как если позиция будет равна длине сегмента, отрезок вставится в самый конец сегмента ( 20e890о0e22г1169w№1% ) и при расшифровке будет проблема
		$matrixParamsDelimeter = $this->getRandNum(mb_strlen($matrixParams));
		$transformMatrixParams = mb_substr($matrixParams, 0, $matrixParamsDelimeter) . $fakeLenFirst . mb_substr($matrixParams, $matrixParamsDelimeter);
		//Место разделения строки с параметрами матриц после которогой будет вставлена первая часть фейковой длины
		$cipherVerDelimeter = $this->getRandNum(mb_strlen($cipherVersion) + 1);
		$transformСipherVer = mb_substr($cipherVersion, 0, $cipherVerDelimeter) . $fakeLenSecond . mb_substr($cipherVersion, $cipherVerDelimeter);
		$resultCipherText = $vectorVert . $transformMatrixParams . $cipherText . $transformСipherVer . $vectorHor;

		return $resultCipherText;
	}


	/**
	 * Метод заполнения матрицы
	 *
	 * @param string $mixedCipherKey преобразованный ключ шифра, на основе которого будет формироваться матрица
	 * @param int $pattern ключ паттерна заполнения матрицы
	 * @return array
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
					//Без этой сортировки ключи массивов начинаются не по порядку в плане чисел НАПРИМЕР, не 0,1,2,3,4,5,6,7,8,9,10,11 а по порядку в порядке заполнения их в этом методе - 0,1,11,3,4,10,5,6,9,7,8
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
		// $this->cipherKey = $this->cipherKeyStorage[substr($version, -1)];
		// $this->cipherKey_second = $this->cipherKeyStorage[substr($version, -1) == (count($this->cipherKeyStorage) - 1) ? 0 : substr($version, -1) + 1];

		return (int)$version;
	}


	/**
	 * Метод формирования версии конкретного шифра. Версия самого алгоритма/скрипта фиксирована и прописывается в свойстве класса $this->version, метод ниже определяет каким образом версия конкретного шифра будет сформирована
	 * Версия состоит из 6ти символов: 3 символа (чистая версия шифра) + 3 символа (ПАРАМЕТРЫ формирования итоговой версии шифра) версии, на основании которых все элементы версии будут перемешаны
	 * Например. Версия алгоритма - 12, используемый индекс ключа шифра ($this->cipherKeyStorage) - 3. Итоговая версия шифра - 123. Паттерн преобразования цифр шифра в буквы - 4 (версия шифра 123 преобразуется в 'bsg'), флаг реверсивности - 0, рандомное число - 8. Дальше эти значения перемешиваются, полная зашифрованная версия шифра - b40sg8
	 *
	 * @return string
	 */
	private function setVersion(int $cipherKeyIndex): string
	{
		$lettersArr = $this->cyrilicLetters + $this->latinLetters;
		/*Паттерн преобразования цифр версии в буквы. Цифры версии будут преобразованы в буквы, ориентируясь на массив [a=>0, b=>1...]. Версия всегда состоит из 3х символов: 2 первых - версия, 3 - индекс используемого ключа из массива $this->cipherKeyStorage. 
		Ниже результаты применения паттерна на примере версии 123 (в скобках буквы, которые будут возвращены вместо цифр в случае не реверсивного массива букв). Например, версия 123 в случае паттерна №1 будет преобразована в бвг:
		№1 - 1(б)_2(в)_3(г)
		№2 - 12(л)_3(г)_rand
		№3 - 1(б)_23(ц)_rand
		№4 - rand_12(л)_3(г)
		№5 - rand_1(б)_23(ц)*/
		$pattern = $this->getRandNum(6);
		//Индекс ключа шифрования на основании которого будет формироваться 1я матрица
		//$cipherKeyIndex = $this->getRandNum(10);
		//Версия алгоритма + индекс рандомного ключа
		$cipherVersion = $this->version . $cipherKeyIndex;
		#Гаврилов
		//ВЫНЕСИ ФОРМИРОВАНИЕ КЛЮЧА ШИФРОВАНИЯ ЗА ПРЕДЕЛЫ ДАННОГО МЕТОДА
		// $this->cipherKey = $this->cipherKeyStorage[$cipherKeyIndex];
		// //Ключ второго шифра для формирования второй матрицы строится на основании другого ключа из массива $this->cipherKeyStorage (следующего ключ после ключа первой матрицы, либо первый ключ массива, если ключ для первый матрицы оказался последним в массиве)
		// $this->cipherKey_second = $this->cipherKeyStorage[$cipherKeyIndex == (count($this->cipherKeyStorage) - 1) ? 0 : $cipherKeyIndex + 1];
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
		/*Цикл ниже должен объединить массив цифр и массив букв таким образом, чтобы относительная последовательность символов в итоговом массиве совпадала с последовательностью символов входящих в него подмасивов. Например, в случае объединения двух массивов [1, 2, 3] и ['a', 'b', 'c']
		Подходящие результаты [1, 2, 3, 'a', 'b', 'c'] или [1, 'a', 'b', 'c', 2, 3] или ['a', 'b', 1, 2, 'c', 3]
		Неподходящие результаты: [3, 2, 'a', 'b', 'c', 1] или [1, 'a', 2, 'c', 3, 'b']
		Это позволит, получив 6 символов, вычленить из них отдельно буквы и отдельно цифры в ТОМ же порядке, в каком они были объединены*/
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
		//Номер итерации шифрования прибавляем для того, чтобы гарантировать чередовать нечетность и нечетность даже если шифруется один тот же символ с одинаковыми координатами (описал подробнее выше по коду). Номер матрицы добавляется, чтобы гарантировать, что если один и тот же символ в разных матрицах находится на одной и той же позиции, после трансформации они поменяют координаты относительно друг друга
		$coordSumm = (int)$symbCoord[0] + (int)$symbCoord[1] + $iterationCount + $matrixNum;
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



	// /**
	//  * Метод формирует массив с координатами символов исходного сообщения
	//  * 
	//  * @param string $text текст требующий преобразования
	//  * @return array массив символов исходного текста с координатами из матриц
	//  */
	// private function createSymbCoords($text)
	// {
	// 	$symbCoordArr = [];
	// 	$transformedMatrixNum = 0;
	// 	//Бьем фразу для шифрования по символам на массив и ищем каждый символ в матрицах
	// 	foreach ($this->getStrArr($text) as $symbKey => $symbol) {
	// 		$transformedMatrixNum = 0;
	// 		//Определяем в какой матрице ищем символ. При шифровке если элемент четный - в 1й, если нечетный - во 2й. При дешифровке обратная ситуация
	// 		if ($this->encrypt) {
	// 			if ($symbKey % 2 !== 0) {
	// 				$matrix = $this->transformedMatrixArr[1];
	// 				$transformedMatrixNum = 1;
	// 			} else {
	// 				$transformedMatrixNum = 2;
	// 				$matrix = $this->transformedMatrixArr[2];
	// 			}
	// 		} else {
	// 			if ($symbKey % 2 !== 0) {
	// 				$matrix = $this->transformedMatrixArr[2];
	// 				$transformedMatrixNum = 2;
	// 			} else {
	// 				$transformedMatrixNum = 1;
	// 				$matrix = $this->transformedMatrixArr[1];
	// 			}
	// 		}

	// 		//Флаг - был ли найден символ в матрицах. Если нет - символ не шифруется
	// 		$findSymbol = false;
	// 		foreach ($matrix as $matrixRow => $rowData) {
	// 			if (($symbolCol = array_search($symbol, $rowData, true)) !== false) {
	// 				$symbCoordArr[] = [$matrixRow, $symbolCol];
	// 				$findSymbol = true;
	// 				//При дешифровке мы трансформируем матрицу при формировании массива с координатами символов, так как в случае дешифровки эти символы - символы шифра, а они должны сдвигаться, так как символы исходного сообщения двигаться не должны в матрице
	// 				if (!$this->encrypt) {
	// 					$this->transformMatrix($transformedMatrixNum, $symbKey, [$matrixRow, $symbolCol]);
	// 				}
	// 				break;
	// 			}
	// 		}
	// 		//Если символ не найден в матрицах - не шифруем его
	// 		if (!$findSymbol) {
	// 			$symbCoordArr[] = $symbol;
	// 		}
	// 	}
	// 	return $symbCoordArr;
	// }


	/**
	 * Метод преобразует исходный текст. Создает шифр из исходного сообщения, либо возвраащет исходное сообщение из шифра
	 *
	 * Каждую итерацию шифрования/дешифровки биграммы матрицы должны трансформироваться, передвигая обрабатываемые символы, чтобы даже последовательности одинаковых символов шифровались в разные символы, а не повторяли очередность шифруемогой строки
	 * 
	 * @param string $text текст для преобразования
	 * @return string
	 */
	private function transformSourceText(string $text): string
	{
		/**
		 * @var string Биграмма текста, которая преобразуется в итерации цикла
		 */
		$bigramma = [];
		/**
		 * @var array Массив координат символов биграммы
		 */
		$bigrammaCoords = [];
		/**
		 * @var array Массив символо преобразованного текста
		 */
		$transfomedTextSymbArr = [];
		$transformedMatrixNum = 0;
		//var_dump('start');
		foreach ($this->getStrArr($text) as $symbKey => $symbol) {
			// $this->drawMatrix($this->transformedMatrixArr[1]);
	 		// $this->drawMatrix($this->transformedMatrixArr[2]);
			//В зависимости от того четный или нечетный символ преобразовываемой строки определяем с какой матрицей работаем для его преобразования. При шифровании первый символ биграммы ищется в 1й матрице, но шифруется символом 2й матрицы. 2й символ биграммы ищется во 2й матрице и шифруется символом из 1й. При дешифровке, соответственно, ситуация противоположная
			if ($symbKey % 2 !== 0) {
				$transformedMatrixNum = ($this->encrypt ? 1 : 2);
			} else {
				$bigramma = mb_substr($text, 1 * $symbKey, 2);
				$transformedMatrixNum = ($this->encrypt ? 2 : 1);
				/**
				 *При дешифровании мы делим строку шифра на биграммы и работаем с координатами символов биграммы. Так как при дешифровке при определении координат каждого символа исходной строки каждый символ шифра постепенно сдвигается и использовать его координаты в следующей итерации не получится. Например, одна из биграмм шифра Fh. Символ F после дешифровки сдвинется в матрице так же как он сдвигался и при шифровании. В при дешифровке второго символа биграммы - h - координаты символа F нельязя использовать, так как он сдвинулся. Поэтому определяем координаты символов биграммы на этапе вычленения из строки биграммы и не пересчитываем их пока не дешифруем полностью биграмму
				*/
				$firstBigrammaSymbCoord = $this->getSymbCoords($this->transformedMatrixArr[$this->encrypt ? 2 : 1], mb_substr($bigramma, 0, 1));
				$secondBigrammaSymbCoord = $this->getSymbCoords($this->transformedMatrixArr[$this->encrypt ? 1 : 2], mb_substr($bigramma, 1, 1));
				//Если координаты биграммы не определились - в массив с координатами положится сам символ, а не масссив координат. Это позволит скрипту в дальнейшем определять символ биграммы нашелся или нет. Учитывая, что мы будем возвращать ошибку, если в шифруемой строке пользователя будут нераспознаваемые символы, смысла в этом нет вроде бы. Но пока рабоатет лучше не трогать...
				$bigrammaCoords[0] = $firstBigrammaSymbCoord ? $firstBigrammaSymbCoord : mb_substr($bigramma, 0, 1);
				$bigrammaCoords[1] = $secondBigrammaSymbCoord ? $secondBigrammaSymbCoord : mb_substr($bigramma, 1, 1);
			}	
			$symbCoord = $this->getSymbCoords($this->transformedMatrixArr[$transformedMatrixNum], $symbol);
			//Если грамм не является массивом с координатами - это нераспознанный символ, не шифруем его
			if (!$symbCoord) {
				$transfomedTextSymbArr[] = $symbol;

				continue;
			}
			/**
			 * @var int Ключ пары символа биграммы. В биграмме Fh, если работаем с символом F, его сосед - h и наоборот  
			 */
			$nearBigrammaSymbKey = $this->returnNearbyGramm($symbKey, $text);
			/**
			 * @var array массив координат соседнего грамма в биграме, координаты которого нужно использовать для преобразования текста
			 * Матрицы используем противоположные, так как соседний символ биграммы ищется в соседней же матрице
			 */
			$nearBigrammaSymbCoords = ($nearBigrammaSymbKey !== false) ? $this->getSymbCoords(($this->transformedMatrixArr[($transformedMatrixNum == 1 ? 2 : 1)]), $this->getStrArr($text)[$nearBigrammaSymbKey]) : false;
			//Если столбцы символов биграммы в соседних матрицах совпадают - мы преобразуем символы биграммы, используя в качестве номера строки координаты символов противоположной матрицы. Например. Биграмма Fh, ее координаты F[1 матрица][5,6], h[2][2,6]. Шифруемая биграмма будет с координатами [1][2,6],[2][5,6]. 
			if (is_array($bigrammaCoords[0]) && is_array($bigrammaCoords[1]) && $bigrammaCoords[0][1] == $bigrammaCoords[1][1]) {
				if ($this->encrypt) {
					if ($symbKey % 2 !== 0) {
						$transfomedTextSymbArr[] = $this->transformedMatrixArr[2][$bigrammaCoords[1][0]][$bigrammaCoords[1][1]];
						//Матрицы перестраиваются один раз при обработке полной биграммы, у которой совпадают столбцы. Если их перестраивать каждую итерацию при шифровании/дешифровании биграммы - могут возникнуть проблемы (если координаты символов биграммы полностью совпадают между матрицами М[1,1], А[1,1])
						$this->transformMatrix(1, $symbKey, $bigrammaCoords[1]);
						$this->transformMatrix(2, $symbKey, $bigrammaCoords[0]);
					} else {
						$transfomedTextSymbArr[] = $this->transformedMatrixArr[1][$bigrammaCoords[0][0]][$bigrammaCoords[0][1]];
					}
				} else {
					if ($symbKey % 2 !== 0) {
						$transfomedTextSymbArr[] = $this->transformedMatrixArr[1][$bigrammaCoords[1][0]][$bigrammaCoords[1][1]];
						$this->transformMatrix(1, $symbKey, $bigrammaCoords[1]);
						$this->transformMatrix(2, $symbKey, $bigrammaCoords[0]);
					} else {
						$transfomedTextSymbArr[] = $this->transformedMatrixArr[2][$bigrammaCoords[0][0]][$bigrammaCoords[0][1]];
					}
				}

				continue;
			}
			//Если биграмма неполная (символ не имеет "пары"), либо соседний символ является нераспознанным, просто меняем координаты строки и столбца символа местами
			if ($nearBigrammaSymbKey === false || $nearBigrammaSymbCoords === false) {
				if ($this->encrypt) {
					$transfomedTextSymbArr[] = $this->transformedMatrixArr[($transformedMatrixNum == 1 ? 2 : 1)][$symbCoord[1]][$symbCoord[0]];
					$this->transformMatrix(($transformedMatrixNum == 1 ? 2 : 1), $symbKey, [$symbCoord[1], $symbCoord[0]]);
				} else {
					$transfomedTextSymbArr[] = $this->transformedMatrixArr[($transformedMatrixNum == 1 ? 2 : 1)][$symbCoord[1]][$symbCoord[0]];
					$this->transformMatrix(($transformedMatrixNum == 1 ? 2 : 1), $symbKey, [$symbCoord[1], $symbCoord[0]]);
				}
				continue;
			//Стандартная обработки биграммы согласно алгоритму двойного квадрата Полибия - координаты символов биграммы передаваемой строки меняеются местами (строка/столбец и их порядок в массиве координат), таким образом формируется биграмма преобразованной строки
			} else {
				if ($this->encrypt) {
					$transfomedTextSymbArr[] = $this->transformedMatrixArr[($transformedMatrixNum == 1 ? 2 : 1)][$nearBigrammaSymbCoords[0]][$symbCoord[1]];
					$this->transformMatrix(($transformedMatrixNum == 1 ? 2 : 1), $symbKey, [$nearBigrammaSymbCoords[0], $symbCoord[1]]);
				} else {
					$matrixIndex = ($transformedMatrixNum == 1 ? 2 : 1);
					$transfomedTextSymbArr[] = $this->transformedMatrixArr[$matrixIndex][$bigrammaCoords[$transformedMatrixNum == 1 ? 1 : 0][0]][$bigrammaCoords[$transformedMatrixNum == 1 ? 0 : 1][1]];
					$this->transformMatrix($transformedMatrixNum, $symbKey, $symbCoord);				
				}
			}
		}
		
		return implode('', $transfomedTextSymbArr);
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
		$symbCoord = false;
		foreach ($matrix as $rowNum => $rowData){
			if (($symbCol = array_search($symbol, $matrix[$rowNum])) !== false) {
				$symbCoord = [$rowNum, $symbCol];
			} 
		}
		return $symbCoord;
	}


	/**
	 * Метод возвращает ключ соседнего символа биграммы. Он требуется для определения координат зашифрованного символа
	 *
	 * @param int $symbKey ключ символа, координаты которого нужно заменить на координаты соседнего (предыдущего, либо следующего) символа в биграмме, из которых соитоит преобразуемая строка
	 * @param string преобразуемый текст
	 * @return int|false false возвращается в случае неполной биграммы (символ не имеет соседа), в остальных случаях возвращается ключ символа
	 */
	private function returnNearbyGramm(int $symbKey, string $text)
	{
		$resultSymbKey = null;
		if ($symbKey % 2 === 0) {
			//Если у последнего символа биграммы нет "пары" (биграмма не полная, находится в конце текста), то не возвращаем соседний символ. В итоге просто меняем местами координаты строки и столбца для этого символа 
			//Эта ситуация может возникнуть только для четных символов, так как нечетные берут координаты у предыдущих символов биграммы
			if ($symbKey + 1 == mb_strlen($text)) {
				return false;
			}
			$resultSymbKey = $symbKey + 1;
		} else {
			$resultSymbKey = $symbKey - 1;
		}
    	return $resultSymbKey;
  	}

	
	// /**
	//  * Метод возвращает преобразованное (зашифрованное/расшифрованное) сообщение
	//  *
	//  * @param array $symbCoordArr массив с координатами символов преобразованного сообщения
	//  * @return string
	//  */
  	// private function createCiphr($symbCoordArr)
	// {
	// 	$ciphrArr = [];
	// 	foreach ($symbCoordArr as $symbKey => $symbCoord) {
	// 		//Если вместо координат символа указан сам символ - он не был найден в матрицах. Пропускаем и не подбираем для него значение
	// 		if (!is_array($symbCoord)) {
	// 			$ciphrArr[] = $symbCoord;

	// 			//return $symbCoord;
	// 			continue;
	// 		}
	// 		$resultSymbCoord = $symbCoord;
	// 		//Если грамм имеет "пару" в биграмме
	// 		// if (($nearbyGramm = $this->returnNearbyGramm($symbKey)) !== false) {
	// 		// 	//Если один из граммов - нераспознанный символ - не проверяем находятся ли граммы в одном столбце
	// 		// 	if (is_array($symbCoordArr[$nearbyGramm])) {
	// 		// 		//Если столбцы граммов совпадают - меняем граммы местами в исходом биграмме. Таким образом, мы меняем местами и координаты символов в прямоугольниках и избегаем простой смены символов в преобразованном биграмме в случае, когда они находятся в одном столбце (чтобы не было ситуации DG -> GD). Подробнее https://ru.wikipedia.org/wiki/Шифр_Уитстона в пункте "В случае, если буквы исходной биграммы сообщения находятся в одной строке (в горизонтальном шифровании)..."
	// 		// 		if ($symbCoord[1] == $symbCoordArr[$nearbyGramm][1]) {
	// 		// 			var_dump('da');
	// 		// 			$resultSymbCoord = $symbCoordArr[$nearbyGramm];
	// 		// 		}
	// 		// 	}
	// 		// }
	// 		if ($this->encrypt) {
	// 			// var_dump($symbKey);
	// 			// var_dump($symbCoord);
	// 			// var_dump($resultSymbCoord);
				
	// 			$matrix = ($symbKey % 2 !== 0 ? $this->transformedMatrixArr[2] : $this->transformedMatrixArr[1]);
	// 			// $this->drawMatrix($this->transformedMatrixArr[1]);
	// 			// $this->drawMatrix($this->transformedMatrixArr[2]);
	// 			$ciphrArr[] = $matrix[$resultSymbCoord[0]][$resultSymbCoord[1]];
	// 		} else {
	// 			$matrix = ($symbKey % 2 !== 0 ? $this->transformedMatrixArr[1] : $this->transformedMatrixArr[2]);
	// 			$ciphrArr[] = $matrix[$resultSymbCoord[0]][$resultSymbCoord[1]];
	// 		}
	// 		//Если шифруем - возвращаем символ из одной матрицы, если расшифровываем - из другой
	// 	}

	// 	//die();

	// 	return implode('', $ciphrArr);
	// }


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
  #Гаврилов
  //ПОМЕНЯЙ МЕТОД НИЖЕ НА PRIVATE
  public function getStrArr($str)
  {
    return preg_split('//u', $str, -1, PREG_SPLIT_NO_EMPTY);
  }

}

//TODO
//ПЕРЕНЕСИ ВЕРСИЮ В КОНЕЦ ШИФРА, ЧТОБЫ ВО ВСЕХ ВЕРСИЯХ ОНА ГАРАНТИРОВАННО БРАЛАСЬ ИЗ ОДНОГО И ТОГО ЖЕ МЕСТА



$symbArr = ['z'=>58, 'y'=>57, 'x'=>56, 'w'=>55, 'v'=>54, 'u'=>53, 't'=>52, 's'=>51, 'r'=>50, 'p'=>49, 'q'=>48, 'o'=>47, 'n'=>46, 'm'=>45, 'l'=>44, 'k'=>43, 'j'=>42, 'i'=>41,'h'=>40, 'g'=>39, 'f'=>38, 'e'=>37, 'd'=>36, 'c'=>35, 'b'=>34, 'a'=>33];
	$symbArr = array_flip($symbArr);
	foreach ($symbArr as $key => $value){
		$symbArr[] = mb_strtoupper($value);
	}
	$symbArr[] = 0;
	$symbArr[] = 1;
	$symbArr[] = 2;
	$symbArr[] = 3;
	$symbArr[] = 4;
	$symbArr[] = 5;
	$symbArr[] = 6;
	$symbArr[] = 7;
	$symbArr[] = 8;
	$symbArr[] = 9;

	$symbArr = array_merge($symbArr, []);

#Гаврилов
//ИСПОЛЬЗУЯ КЛЮЧ 
//NTI0M2FmNWEwOGU3NDY2YTc5MАFiMTEyOTdlNmY1NTQzY2Q4MzYzMmJkMTNiODRjOGI2YjY4NjEwYjNmM2NjZGJhOWY1NjRiYmU3OTEzZjdhZmIzNDExM2QwZTgwMjhkZDE1OTIwMDlhY2YxZjIxMDljNDA4MTllZjc3MmEzOTI
//И КЛЮЧ
//NTI0M2FmNWEwOGU3NDY2YTc5MфFiMTEyOTdlNmY1NTQzY2Q4MzYzMmJkMTNiODRjOGI2YjY4NjEwYjNmM2NjZGJhOWY1NjRiYmU3OTEzZjdhZmIzNDExM2QwZTgwMjhkZDE1OTIwMDlhY2YxZjIxMDljNDA4MTllZjc3MmEzOTI
//ДАЮТ один и тот же вариант

#Гаврилов
//СОЛЬ ДОЛЖНА состоять только из латинских символов нижнего и верхнего регистра плюс цифры от 0 до 9. если соль не при передаче не соответствует формату - возвращаем ошибку

$cipherText = 'мама мыла раму';
// $cipherText = '1111111111111111111111111';
$salt = null;
$salt = 'NTI0M2FmNWEwOGU3NDY2YTc5MAFiMTEyOTdlNmY1NTQzY2Q4MzYzMmJkMTNiODRjOGI2YjY4NjEwYjNmM2NjZGJhOWY1NjRiYmU3OTEzZjdhZmIzNDExM2QwZTgwMjhkZDE1OTIwMDlhY2YxZjIxMDljNDA4MTllZjc3MmEzOTI';
$testCipher = (new SimpleCipher($cipherText, $salt))->encryptText(67);
$n = 1;
$saltNew = $salt;
while ($n <= 10000) {
	$randomNumb_pos = unpack("N", openssl_random_pseudo_bytes(4))[1] % (160 - 1) + 1;
	$randimNumb_symb = unpack("N", openssl_random_pseudo_bytes(4))[1] % (59 - 1) + 1;
	$saltArr = str_split($saltNew);
	shuffle($saltArr);
	$saltNew = implode('', $saltArr);
	//if ($salt !== $saltNew) {
		// var_dump('here');
		$decryptText = (new SimpleCipher($testCipher, $saltNew))->decryptText();
		if ($decryptText === $cipherText) {
			var_dump($saltNew);
			var_dump('Жаль!');
		}
	//}
	var_dump($n);

	// die();
	// $testCipher = (new SimpleCipher($cipherText, $salt))->encryptText(67);
	// echo '<pre>'; var_dump($testCipher); echo'</pre>';
	// $decryptText = (new SimpleCipher($testCipher, $salt))->decryptText();

	// // #Гаврилов
	// // //если заменить первую букву в соли - ничего не поменяется, хотя должно
	// // //ДОБАВЬ УЧЕТ БУКВ К ПЕРЕСЧЕТУ ПАРАМЕТРОВ ТРАНСФОРМАЦИИ МАТРИЦЫ. МАССИВ БУКВ ТРАНСФОРМИРУЕТСЯ В МАССИВ ЧИСЕЛ ПО КЛЮЧАМ ИЗ КЛЮЧА ШИФРА (НЕ ИЗ МАССИВА LETTERSARR) ДОБАВЛЯЕМ К КОЛИЧЕСТВУ ИТЕРАЦИЙ, ТАК КАК СУММА БУДЕТ БОЛЬШАЯ
 	
	// $newSaltArr = preg_split('//u', $salt, -1, PREG_SPLIT_NO_EMPTY);
	// $s = 0;
	// while ($s < count($newSaltArr)) {
	// 	$newSaltArr_transform = $newSaltArr;
	// 	$a = 0;
	// 	while ($a < count($symbArr)) {
	// 		$newSaltStr = implode('', $newSaltArr);
	// 		$newSaltArr_transform[$s] = $symbArr[$a];
	// 		$newSaltStr = implode('', $newSaltArr_transform);
	// 		$newDecryptText = (new SimpleCipher($testCipher, $newSaltStr))->decryptText();
	// 		if ($newDecryptText == $cipherText && $newSaltStr !== $salt) {
	// 			var_dump('Жаль');
	// 			var_dump($newSaltStr);
	// 			var_dump(count($newSaltArr) * $s + $a);
	// 		}
	// 		$a++;
	// 	}
	// 	$s++;
	// }

	//echo '<pre>'; var_dump($decryptText); echo'</pre>';
	// if ($decryptText !== $cipherText) {
	// 	var_dump('ОШИПКА!');
	// }
 	$n++;
}

#Гаврилов
//ПЕРЕПИСАТЬ ИСПОЛЬЗОВАНИЯ КЛЮЧА СЛЕДУЮЩИМ ОБРАЗОМ. БРАТЬ НЕ СУММУ ВСЕХ ЧИСЕЛ И ИЗ НЕЕ ВЫЧЛЕНЯТЬ ЦИФРЫ, А ГЕНЕРИТЬ СУММЫ ПО ОТРЕЗКАМ: СУММА ПЕРВЫХ ПЯТИ СИМВОЛОВ, СУММА ВТОРЫХ ПЯТИ СИМВОЛОВ И ТАК СКОЛЬКО НАДО
//НА ВЫХОДЕ (ПЕРЕД ЗАПОЛНЕНИЕМ ФЕЙКОВЫМИ СИМВОЛАМИ) В промежуточном результате БРАТЬ ИЗ соли БУКВЫ И ЦИФРЫ И ИХ В ШИФРЕ/ДЕШИФРЕ МЕНЯТЬ МЕСТАМИ, ПЕРЕНОСИТЬ В КОНЕЦ? КАКИМ ТО ОБРАЗОМ ИХ ИСПОЛЬЗОВАТЬ ДЛЯ ПЕРЕМЕШИВАНИЯ

#Гаврилов
//НУЖНО ДОБИТЬСЯ КОЛИЧЕСТВА КОЛИЗИЙ ПРИ ИСПОЛЬЗОВАНИИ СОЛИ текущее состояние 0.002



#Гаврилов
//ОДИН РАЗ ПРИ НАЖАТИИ НА КНОПКУ ЗАШИФРОВАТЬ Я ПОЛУЧИЛ ШИФР НЕ ЖЕЛАЕМОЙ ДЛИНЫ, А МЕНЬШЕЙ. ХЗ ПОЧЕМУ
//ВЫБИРАЛ КОЛИЧЕСТВО ФЕЙКОВЫХ СИМВОЛОВ 67. ЛИБО СТАРЫЕ РЕЗУЛЬТАТ, ЛИБО ШИФР ОБРЕЗАЛСЯ ПО КАКОМУ-ТО СИМВОЛУ И НЕ СФОРМИРОВАЛСЯ ПОЛНОСТЬЮ? В ЭТОМ СЛУЧАЕ МОГУТ БЫТЬ ВИНОВАТЫ СПЕЦСИМВОЛЫ?

#Гаврилов
//ПРОВЕСТИ ТЕСТИРОВАНИЕ: КАЖДЫЙ СИМВОЛ СОЛИ ПРИ РАСШИФРОВКЕ ПЕРЕБИРАТЬ ПО АЛФАВИТУ (ЛАТИНСКОМУ И КИРИЛИЧЕСКОМУ), РЕГИСТРУ И ЦИФРАМ И ПРОВЕРЯТЬ ПОЛУЧИТСЯ ЛИ ПОЛУЧИТЬ С КРИВОЙ СОЛЬЮ ТУ ЖЕ СТРОКУ ПРИ РАСШИФРОВКЕ КАК И ПРИ ШИФРОВКЕ

//ПЕРЕПИСАТЬ BASE64 на base32 (где нет заглавных букв, но нужно, чтобы там набор букв все равно был большой) СПРОСИ У НЕЙРОСЕТИ КАКОВА ВЕРОЯТНОСТЬ СТОЛКНУТЬСЯС КОЛИЗИЯМИ ЕСЛИ ИСПОЛЬЗОВАТЬ BASE 32 А НЕ BASE 64


//оригинальный текст чk25ю3 22|22ь1t13n847пoлЬЧШГЛуЬпЛбЬЧ?QQnгТвj 0ЗСUРоoъcъcMDdц$#б1вв4?48|1ъц - расшифровывается как надо
//тестовый текст для шифрования mama 157$#
//но текст чk2hю3 22|22ь1t13n847пoлЬЧШГЛуЬпЛбЬЧ?QQnгТвj 0ЗСUРоoъcъcMDdц$#б1вв4?48|1ъц тоже расшифровывается как надо ПОЧЕМУ?
//ОН не расшифровывается, но возвращает ошибку, пока не хватает обработки ошибок . Удаляй при шифровании и расшифровке предыдущие результаты работы шифра не после возворащения шифра, а сразу при нажатии на кнопкуч

