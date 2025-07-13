<?php

mb_internal_encoding("UTF-8");

#Гаврилов
//НА ПРОДОВСКОЙ ВЕРСИИ УБЕРИ НАСТРОЙКИ НИЖЕ 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(300);
ini_set('xdebug.var_display_max_depth', -1); // без ограничения глубины
ini_set('xdebug.var_display_max_children', -1); // без ограничения количества элементов
ini_set('xdebug.var_display_max_data', -1); // без ограничения длины строк


class SimpleCipher
{
	/**
	 * @var array массив ключей шифра, которые будут использоваться в зависимости от 3ей цифры в версии алогоритма
	 */
	// private $cipherKeyStorage = [
	// 	0 => 'Sт⇔цVяСН@KИЮВr[5"Z:t*П}÷OmoЕЯврЗ08()ы4§чЙ9ГДУWэ7ишЛvcщАEH~ЖЩ!Iдеaлb/%3ОFу1`М-пёYюШsqJ©;LC_xЫQ\P.zp=ьGdЦy2аз×жX+ nhЬ€NЪм>kХб,{#AфR^]Т6ФРuКЁъБг|efоi≠wMкUπTЭн$jg<&хйЧ№?BсDl',
	// 	1 => '}tлз0sWyк1пЕ%N×\с3LеЯДXу:дTЙцw€мE?)о№G|]pPвщFπIг`rz6хkeЬOj8эяВuDZ>ШьSКМh$Э4аИУЁ2Щ*Рmx@Uрш©и"Т=vъ#(QФн[i≠l<Ъ;Г+П5Jч⇔Зф§coБYёХЫ~Лbы.йVОтq^C,СЦKd{ M_gHб&А7aRю/НЧf÷Ю-A9Ж!Bnж',
	// 	2 => 'йЧо83з]МылlK≠©;d0eW1wэYЖh4v+xЪ>аГ9{ШЙн*bуюCИФЕoпёХвБzУТyЮЫ€ЛНiV÷⇔X?геgК~#Q"Ашa)AЁSт&J§75`%хСкjr(|ВFB:sRжLмTG@ф/t^П×яОMqЩ,ZH}ЯЭ2Nд._u[O<иъ=\Dpцч$πPEсЗ IРfЬUcnЦkбрД6щ!m-ь№',
	// 	3 => 'T&π[03eшМэnВoqГДNX2в{П)о÷4д!Биs<€Ы]B`же}>9hарщKг#%k5Шaiь©PЁl(ЮmUHGQЗA*=№хФХЪЧЦRР+чLЭп"§О-~jwлrСgtO⇔АЬv8ЕxЙЯ\Dй×я/z≠SЖтЩ @ёНEF.Y$кZъИКV:?JбfзCc|Wuм1юц^ынуIpс7_yУЛb;,фd6MТ',
	// 	4 => 'J;oeчэиЖ]"ЁцTwз7{ыфК3хГ+SюПzbИIЮОмkt!тUpвдРЫAViH?З4ХБvЛaЦоOGж2ruR%[C6ьXcMъёKL|sh ФЪE0DZ9FаУd№А}QЬYЧqЩШy(fейnуPлс)mбТЭНBС8В1ДМlЙгрЯяjнЕW5Ngx:пшкщ&*\^-=`~@#$_/,.<>©§π≠×÷€⇔',
	// 	5 => '])2h8Т_гK:ИСoGсП4§щёЦSgЬГ&≠\wcЕrт€FJыyЮ©?aНlm@бндzшжЗх#.Ё~Вqef/$3ХеЧво№uЭь⇔vDБWQ7id|з=уФH`кj(юМTπ"k*0КЖяф×N[UЯVIRп1CиXр;%Z {}мчЛцpДЙ^O6лtЪYАL,ъEMPsОA!B÷эШЫx<а5-РУb+й>9nЩ',
	// 	6 => ',пЙЩ@GRS]#YHМ$€цUчж5pхьl}©ЮъJgF94ф0шРНЖЗзЪ1мmto×юaЁны>uQ⇔ОэЯа3EВТ!БЬIу|XisBд"К8WD≠y/nтkС A<с{:оё`÷h-jлГfФqe.Ур~Е*ИTя2А+[x?P=бL%K6ЫrйO§№г;ЛcV)ДЦvwbZвк_^ПC&ХщMЧd(7\ШиNеzπЭ',
	// 	7 => 'н)Ичph[:EзH"π&К71^фВLy©AGё]§ьRnbmэг!%NуЫO*М№3DYS#o{вЛI/ШЧ€ЁАwJ2Te5zПГ$VxЖl?-тQС `@б≠UX;цпО_ЕBлЭvж~÷и}БъdйсЙZ|рыЮа\оs8.uХю⇔FKP,WCЩхмcФfУЬqЦgщР×iТ<9шЪr0tяЯеЗДН(j6M+=кa>kд4',
	// 	8 => 'B§)яе{Лf$,Ыщi№сВpМb.jlI*KудшСЯЦktzЙ>MЪкgnмx=47Wy^ehDФз÷;ё€ГЖъ(rZ×©~юХd%вэv08цc\л⇔й[_C#So`OЧчGОЕuп3m<т2FД гQEЩ&ы!ЁиA]оπЮЭУYPa+|Т"}L:@нVРЬН/ь-а?RБ6ЗUжШб1хрИJHs9XфП5NTw≠КqА',
	// 	9 => 'ТS9NИz&оxMlзе!эпGL<yМБYRДf$v{КОч€akыЫq⇔=Лw/туJлHh#mё4~ЮOшб|CюTAШ0F?я*ъngЁГ(oXС5VЕн§Ьu[U@ b>Жф%,Пπ}8ЭЙФ2©PЩЪtХ:А_-хЗЧp÷I+и`6рщйrгс№sDмiьcкжУKBв≠E"eQН;d17^)ацдВ]\W3Z.Ц×ЯРj',


	/**
	 * @var string ключ шифра, который будет использоваться для построения первой матрицы
	 */
	private $cipherKey;
	/**
	 * @var string ключ шифра, который будет использоваться для построения второй матрицы
	 */
	private $cipherKey_second;
	/**
	 * @var string перемешанный первый ключ шифра
	 */
	private $shiftedCipherKey_first;
	/**
	 * @var string перемешанный второй ключ шифра
	 */
	private $shiftedCipherKey_second;
	/**
	 * @var string передаваемый для шифрования/дешифрования текст
	 */
	private $text;
	/**
	 * @var string соль для шифра
	 */
	private $salt;
	/**
	 * @var array массив различных символов из ключа шифра, которые не являются буквами или цифрами. Массив нужен для обособления частей указателя на реальную длину исходного текста в полезной нагрузке шифра
	 */
	private $encryptLengthDelimeter;
	/**
	 * @var int версия приложения. Перовая версия 123, чтобы не начинать с 001
	 */
	private $version;
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
	private $latinLetters = ['z'=>58, 'y'=>57, 'x'=>56, 'w'=>55, 'v'=>54, 'u'=>53, 't'=>52, 's'=>51, 'r'=>50, 'p'=>49, 'q'=>48, 'o'=>47, 'n'=>46, 'm'=>45, 'l'=>44, 'k'=>43, 'j'=>42, 'i'=>41,'h'=>40, 'g'=>39, 'f'=>38, 'e'=>37, 'd'=>36, 'c'=>35, 'b'=>34, 'a'=>33];
	private $lettersArr = ['а'=>0, 'б'=>1, 'в'=>2, 'г'=>3, 'д'=>4, 'е'=>5, 'ё'=>6, 'ж'=>7, 'з'=>8, 'и'=>9, 'й'=>10, 'к'=>11, 'л'=>12, 'м'=>13, 'н'=>14, 'о'=>15, 'п'=>16, 'р'=>17, 'с'=>18, 'т'=>19, 'у'=>20, 'ф'=>21, 'х'=>22, 'ц'=>23, 'ч'=>24, 'ш'=>25, 'щ'=>26, 'ъ'=>27, 'ы'=>28, 'ь'=>29, 'э'=>30, 'ю'=>31, 'я'=>32, 'z'=>58, 'y'=>57, 'x'=>56, 'w'=>55, 'v'=>54, 'u'=>53, 't'=>52, 's'=>51, 'r'=>50, 'p'=>49, 'q'=>48, 'o'=>47, 'n'=>46, 'm'=>45, 'l'=>44, 'k'=>43, 'j'=>42, 'i'=>41,'h'=>40, 'g'=>39, 'f'=>38, 'e'=>37, 'd'=>36, 'c'=>35, 'b'=>34, 'a'=>33];
	/**
	 * @var int окно захвата символов для первой матрицы, которое будет перемещаться
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
   	 * @var int количество символов в векторе инициализации
	 */
	private $vectorLength = 3;
	/**
	 * @var array массив сдвинутых по векторам инициализации матриц
	 */
	private $shiftedMatrixArr;
	/**
	 * @var string фейковые символы, которые были вычленены из расшифровываемой строки после ее очистки для сравнения с фейковыми символами, используемыми при шифровании
	 */
	private $fakeSymbolString = null;
	/**
	 * @var array массив с позициями символов соли в массиве $this->latinLetters ([0=>a, 1=>b, 2=>c ...]), на основе которого будет происходит запутывание алгоритма при передаче соли
	 */
	private $saltNumberSegments;
	/**
	 * @var string хэш передаваемой соли, на основе которой будет запутываться шифр
	 */
	private $saltHashSum = null;
	/**
	 * @var string путь до файлов с ключами шифра
	 */
	private static $keyFilesPath = __DIR__ . "./cipherKeys/";


	public function __construct()
	{
		require_once (__DIR__ . "./../../CipherVersion.php");
		// $this->text = $text;
		// $this->salt = $salt;
		$this->version = array_reverse(explode('\\', __DIR__))[0];
		$this->matrixDepth = sqrt(mb_strlen(self::getFakeCipherKey()));
	}


	/**
	 * Метод возвращает фейковый ключ шифра, перемешивая случайным образом один из действующих ключей шифра
	 *
	 * @return string
	 */
	public static function getFakeCipherKey(): string
	{
		$fakeCipherKey = file_get_contents(self::$keyFilesPath . "cipherKey_0.txt");
		$fakeCipherKey = preg_split('//u', $fakeCipherKey, -1, PREG_SPLIT_NO_EMPTY);
		shuffle($fakeCipherKey);
		$fakeCipherKey = implode('', $fakeCipherKey);

		return $fakeCipherKey;
	}


	/**
	 * Метод формирует хэш соли, используя порядковые номера символов соли и числа из ключа шифрования
	 * $this->saltNumberSegments представляет собой массив вида
	 *  [0] => [1, 76, 80, 52...], 
	 *	[1] => [8, 16, 91, 11...]
 	 *	каждый подмассив состоит из 13 чисел, которые представляют собой порядковые номера элементов переданной соли. Далее эти сегменты будут между собой перемножаться, делиться, складываться и вычитаться В ЗАВИСИМОСТИ от другого массива, состоящего из чисел, взятых из ключа шифрования
	 *
	 * @return string
	 */
	private function getHashSalt(): string
	{
		/*Вычленяем только числа из ключа шифрования и фиксируем их как ключи будущего массива, где значениями будут выступать сумма элементов с сегментах соли ($this->saltNumberSegments).*/
		$cipherKeyNumbers = array_merge([], array_map(function($el){return (int)$el;}, array_filter($this->getStrArr($this->cipherKey), function($el){return preg_match('/[0-9]/', $el);})));
		//Определенным образом дополняем массив с числами ключа значениями: 10, 11, 12. Так как итоговый массив с сегментами соли должен содержать 13 элементов. Столько же, сколько в одном сегменте массива $this->saltNumberSegments. Если элементов будет меньше, мы при запутывании не будем задействовать один из элементов в сегментах $this->saltNumberSegments. Если элементов будет больше, мы не найдем нужный элемент в сегменте$this->saltNumberSegment, будет ошибка. 
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
		$func_getSaltSum = function (array $arr) use (&$func_getSaltSum) : float {
			$sum = array_sum($arr);
			foreach($arr as $child) {
				$sum += is_array($child) ? $func_getSaltSum($child) : 0;
			}
			return $sum;
		};
		//Общая сумма всех элементов массива, где каждый символ соли представлен в виде числа
		$generalSaltSum = $func_getSaltSum($this->saltNumberSegments);
		//Умножаем, чтобы не уйти в отрицательные значения при дальнейших операциях
		$generalSaltSum = $generalSaltSum * 3.14;
		foreach ($cipherKeyNumbers as $cipherNumKey => $cipherKeySymb){
			//Складываем и умножаем (либо вычитаем и делим в зависимости от четной или нечетной позиции) подмассивы элементов соли между собой.
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

		#Гаврилов
		//ЗАТЕСТИ 100 000 СОЛЕЙ, ВЕРНУТ ЛИ ОНИ ОДНУ И ТУ ЖЕ СУММУ С ОДНИМ И ТЕМ ЖЕ КЛЮЧОМ

		return $generalSaltSum;
	}


	/**
	 * Метод шифрования текста
	 *
	 * @param integer $resultCipherLength фейковая длина шифра
	 * @param string $userCipherKey пользовательский ключ шифрования
	 * @return string
	 */
	public function encryptText(string $openText, int $resultCipherLength = 50, ?string $userSalt, ?string $userCipherKey = null): string
	{
		$this->encrypt = true;
		$this->text = $openText;
		$this->salt = $userSalt;
		//Текст переводим в кодировку base64 и шифруем уже их. Это позволит передавать для шифрования любой текст с любыми символами, так как фактически после кодирования мы работаем гарантированно с числами и латинскими символами, а эти символы есть в ключах
		$this->text = base64_encode($this->text);
		//Массив пользовательских ключей шифрования
		$userCipherKeyArr = ($userCipherKey ? [mb_substr($userCipherKey, 0, pow($this->matrixDepth, 2)), mb_substr($userCipherKey, pow($this->matrixDepth, 2), pow($this->matrixDepth, 2))] : []);
		//Фейковая длина не может быть меньше 50 символов
		$resultCipherLength = ($resultCipherLength < 50 ? 50 : $resultCipherLength);
		//Максимальное значение для окна захвата символов - примерно половина от длины ключа шифра
		$this->windowSizeFirst = $this->getRandNum(floor(pow($this->matrixDepth, 2) / 2), 12);
		$this->shiftCountFirst = $this->getRandNum(999, 99);
		//Флаг реверса ключа шифра, который используется для формирования первой матрицы. Ключ для второй матрицы всегда имеет противоположное значение
		$reverseCipherKey = ($this->getRandNum(3, 1) == 1 ? 0 : 1);
		//Хэш пользовательских ключей
		$userCipherKeyHash = 0;
		if ($userCipherKey) {
			$this->transformSaltByUserKey($userCipherKeyArr);
			$userCipherKeyHash = $this->getUserKeySymbSumm($userCipherKeyArr);
		}
		$this->saltNumberSegments = $this->getSaltNumbersArr();
		//Индекс ключа шифрования на основании которого будет формироваться 1я матрица
		//cipherKeyIndex_fake - фейковый ключ шифра, который помещается в полезную нагрузку шифра. При передаче соли реальный ключ шифра преобразуется в соответствии с солью. При шифровании используется реальный ключ шифра, но в полезную нагрузку кладется фейковый ключ шифра. Таким образом, расшифровка БЕЗ передачи соли не сформирует действительный ключ шифра, использующийся при шифровании и расшифровка не будет успешной
		$cipherKeyIndex = $cipherKeyIndex_fake = $this->getRandNum(10);
		//Если передается соль, используем ее для определения ключа шифра
		if ($this->salt) {
			$cipherKeyIndex = $this->getRealCipherKey($cipherKeyIndex);
		}
		//Если передаются пользовательские ключи ишфрования, для шифрования берутся они, в противном случае, берется один из подготовленных ключей
		$this->cipherKey = (!empty($userCipherKeyArr) ? $userCipherKeyArr[0] : file_get_contents(self::$keyFilesPath . "cipherKey_$cipherKeyIndex.txt"));
		//Ключ второго шифра для формирования второй матрицы строится на основании другого ключа из массива $this->cipherKeyStorage (следующего ключ после ключа первой матрицы, либо первый ключ массива, если ключ для первый матрицы оказался последним в массиве)
		//Если передаются ключи - для шифрования берутся они, в противном случае один из подготовленных ключей
		$this->cipherKey_second = (!empty($userCipherKeyArr) ? $userCipherKeyArr[1] : file_get_contents(self::$keyFilesPath . "cipherKey_" . ($cipherKeyIndex == 9 ? 0 : $cipherKeyIndex + 1) . ".txt"));
		//Если передается соль, формируем из нее хэш на сумму всех символов соли, который будет использоваться для запутывания всех этапов работы алгоритма 
		//Хэш соли формируем ПОСЛЕ определения ключа шифра, так как ключ используется при формировании хэша 
		if ($this->salt) {
			$this->saltHashSum = $this->getHashSalt() + $userCipherKeyHash;
		}
		//версия приложения в зашифрованном виде
		$encryptVersion = $this->setVersion($this->salt ? $cipherKeyIndex_fake : $cipherKeyIndex);
		$this->windowSizeSecond = $this->getRandNum(floor(pow($this->matrixDepth, 2) / 2), 12);
		$this->shiftCountSecond = $this->shiftCountFirst + $this->getRandNum(1999, 99);
		//Заполняем массив с параметрами преобразования матриц (пока что данными для преобразования первой матрицы)
		$shiftCipherKeyParams = [
									0 => $this->windowSizeFirst, 
									1 => $this->shiftCountFirst, 
									2 => $reverseCipherKey,
									3 => $this->windowSizeSecond,
									4 => $this->shiftCountSecond,
								];
		//массив с параметрами трансформации матриц, который был изменен с помощью переданной соли
		$salted_shiftCipherKeyParams = [];
		//Ниже преобразуем ключевые параметры шифра, если была передана соль
		//Здесь дополнительно преобразуем ключ шифрования, так как только в методе setVersion происходит формирование ключа
		if ($this->salt) {
			$this->cipherKey = $this->useSaltToCipherKey($this->cipherKey);
			$this->cipherKey_second = $this->useSaltToCipherKey($this->cipherKey_second);
			$salted_shiftCipherKeyParams = $this->useSaltToCipherKeyParams($shiftCipherKeyParams);
			$this->windowSizeFirst = $salted_shiftCipherKeyParams[0];
			$this->shiftCountFirst = $salted_shiftCipherKeyParams[1];
			$this->windowSizeSecond = $salted_shiftCipherKeyParams[3];
			$this->shiftCountSecond = $salted_shiftCipherKeyParams[4];
		}
		//Сдвигаем ключ шифра для первой матрицы
		$this->shiftedCipherKey_first = $this->shiftCipherKey($this->cipherKey, $this->windowSizeFirst, $this->shiftCountFirst, $shiftCipherKeyParams[2]);
		//Для ключа второй матрицы флаг реверса обязательно меняется на противоположный
		$reverseCipherKey = ($reverseCipherKey ? 0 : 1);
		//Сдвигаем ключ шифра для второй матрицы
		$this->shiftedCipherKey_second = $this->shiftCipherKey($this->cipherKey_second, $this->windowSizeSecond, $this->shiftCountSecond, $reverseCipherKey);
		//первая матрица на основе преобразованного ключа шифра
		$firstMatrix = $this->fillMatrix($this->shiftedCipherKey_first, (int)substr(array_sum($this->salt ? $salted_shiftCipherKeyParams : $shiftCipherKeyParams), -1, 1));
		//Добавляем 1 к предыдущей сумме параметров матрицы, так как это дает 50% шанс, что паттерн заполнения изменится для второй матрицы (так как паттерны делятся по двойкам: 0,1 - 1й паттерн, 2,3 - 2й и так далее). На самом деле, нам не обязательно, чтобы паттерн менялся, так как сама последовательность символов для формирования матрицы разная, поэтому добавление 1 позволит с равной вероятностью получить как тот же паттерн заполнения матрицы, что был для 1й матрицы (0 превратится в 1 - и то и то 1й паттерн), так и следующий паттерн (1 превратится в 2 - это уже 2й паттерн).
		//вторая матрица на основе второго преобразованного ключа шифра
		$secondMatrix = $this->fillMatrix($this->shiftedCipherKey_second, (int)substr(array_sum($this->salt ? $salted_shiftCipherKeyParams : $shiftCipherKeyParams) + 1, -1, 1));
		//Только буквы для рандомной вставки между параметрами полезной нагрузки для трансформации матриц
		$lettersArr = array_flip($this->lettersArr);
		//Формируем итоговую строку с параметрами формирования матриц. В качестве разделителя между параметрами формирования матриц использовать только случайные БУКВЫ, без знаков препинаний и различных спецсимволов (@, ^ и т.д.), потому что эти символы, в свою очередь, будут использоваться для обособления в параметрах преобразований матрицы первой части указателя на реальную длину шифруемого текста
		$matrixParam_resultString = implode('', array_map(function($el) use($lettersArr) {return $el . $lettersArr[array_rand($lettersArr)];}, $shiftCipherKeyParams));
		$hashTextParams = $this->getTextHashPointer($this->text, $matrixParam_resultString);
		//первый вектор инициализации в виде биграммы
		$initVector_bigramma_first = $hashTextParams['firstVector'];
		//второй вектор инициализации в виде биграммы
		$initVector_bigramma_second = $hashTextParams['secondVector'];
		//первый вектор инициализации в виде числа
		$initVector_num_first = $this->getVector($initVector_bigramma_first, 'vert');
		//второй вектор инициализации в виде числа
		$initVector_num_second = $this->getVector($initVector_bigramma_second, 'hor');
		$this->shiftedMatrixArr[1] = $this->shiftMatrix($firstMatrix, 1, $initVector_num_first, $initVector_num_second);
		$this->shiftedMatrixArr[2] = $this->shiftMatrix($secondMatrix, 0, $initVector_num_first, $initVector_num_second);
		//Промежуточный зашифрованный текст (без внедренных фейковых символов)
		$ecnryptText_interim = $this->transformSourceText($this->text);
		$this->encryptLengthDelimeter = array_filter($this->getStrArr($this->cipherKey), function($el){return preg_match('/[^a-zа-ё0-9]/ui', $el);});
		//Указатель на длину шифруемого текста
		$encryptTextLengthPointer = $this->encryptLengthPointer($matrixParam_resultString);
		//Первая часть указателя на длину исходного сообщения без разграничителей. Например, 3546 -> 35
		$encryptLengthWithoutDelimeter_first = mb_substr((string)$encryptTextLengthPointer, 0, $this->getRandNum(mb_strlen((string)$encryptTextLengthPointer) + 1));
		//Вторая часть указателя на длину исходного сообщения с разграничителями. Например, 3546 -> {46"
		$encryptLengthPointer_second = $this->encryptLengthDelimeter[array_rand($this->encryptLengthDelimeter)] . mb_substr((string)$encryptTextLengthPointer, mb_strlen($encryptLengthWithoutDelimeter_first)) . $this->encryptLengthDelimeter[array_rand($this->encryptLengthDelimeter)];
		//Первая часть указателя на длину исходного сообщения с разграничителями. Например, 3546 -> *35$
		$encryptLengthPointer_first = $this->encryptLengthDelimeter[array_rand($this->encryptLengthDelimeter)] . $encryptLengthWithoutDelimeter_first . $this->encryptLengthDelimeter[array_rand($this->encryptLengthDelimeter)];
		$resultCipherLength = $this->calcLenFakeSymb($resultCipherLength, $ecnryptText_interim, $initVector_bigramma_first . $initVector_bigramma_second . $matrixParam_resultString . $encryptVersion . $encryptLengthPointer_first . $encryptLengthPointer_second);
		//Итоговый зашифрованный текст исходного сообщения (заполненный фейковыми символами)
		$resultCipherText = $this->fillFakeLength($ecnryptText_interim, $resultCipherLength, $this->createFakeLengthHash($ecnryptText_interim, $matrixParam_resultString));                  
		//Итоговый шифр, включающий в себя зашифрованный текст исходного сообщений + полезная нагрузка шифра
		$resutCipher = $this->constructCipherText($initVector_bigramma_first, $initVector_bigramma_second, $resultCipherText, $matrixParam_resultString, $encryptVersion, $encryptLengthPointer_first, $encryptLengthPointer_second);

		return $resutCipher;
	}


	/**
	 * Метод возвращает указатель на реальную длину открытого сообщения, используя параметры трансформации ключей шифрования
	 * Метод создает значение, содержащее информацию о количестве символов исходного сообщения, чтобы поместить его в шифр и при этом не подсвечивать реальное количество фейковых символов или символов исходного сообщения.
	 *
	 * @param string $matrixParam_resultString параметры преобразования матриц
	 * @return int
	 */
	private function encryptLengthPointer(string $matrixParam_resultString): int
	{
		//Добавлять 1000 нужно, чтобы гарантированно указатель на длину открытого сообщения состоял из 4 цифр. В противном случае, выше вероятность, что один из указателей на длину будет состоять просто из двух подряд идущих спецсимволов без цифр внутри (все 3 цифры указателя будут в первой части). Тогда при генерации сотни шифров короткого сообщения "1" будет легче распознать паттерн, скрывающий указатель на длину открытого сообщения
		$resultEncryptLengthPointer = array_sum(preg_split('/[a-zа-ё]/iu', $matrixParam_resultString)) + mb_strlen($this->text) + 1000;

		return $resultEncryptLengthPointer;
	}


	/**
	 * Метод получает реальную длину открытого сообщения из указателя + параметры преобразования матрицы
	 *
	 * @param string $matrixParam_resultString параметры преобразования матриц
	 * @param int $realLengthPointer указатель на реальную длину открытого сообщения
	 * @return int
	 */
	private function getRealStringLength(string $matrixParam_resultString, int $realLengthPointer): int
	{
		$realStringLenght = $realLengthPointer - array_sum(preg_split('/[a-zа-ё]/iu', $matrixParam_resultString)) - 1000;

		return $realStringLenght;
	}

	#Гаврилов
	//ПЕРЕД РЕЛИЗОМ ПОМЕНЯЙ ФУНКЦИИ ХЭШИРОВАНИЯ НА АКТУАЛЬНЫЕ ДЛЯ АКТУАЛЬНОЙ ВЕРСИИ PHP
	/**
	 * Метод формирует хэш для заполнения отрезками из него пространство между символами исходного сообщения для достижения желаемой фейковой длины
	 *
	 * @param string $clearCipherText чистый зашифрованный исходный текст (без фейковых символов и полезной нагрузки)
	 * @param string $matrixParam_resultString передаваемые параметры трансформации матриц в виде строке выступают в качестве соли для формирования уникального хэша для каждого вариант шифра открытого сообщения. Соль будет уникальна для каждого шифра, даже если открытое сообщение одно и то же (параметры трансформации матрицы генерятся случайным образом для каждого шифра)
	 * @return string
	 */
	private function createFakeLengthHash(string $clearCipherText, string $matrixParam_resultString): string
	{
		$firstHash = hash('sha512', $clearCipherText . $matrixParam_resultString);
		$secondHash = hash('whirlpool', $clearCipherText . $matrixParam_resultString);
		$thirdHash = hash('sha512', $this->reverseString($firstHash) . $matrixParam_resultString);
		$fourthHash = hash('sha512', $this->reverseString($secondHash) . $matrixParam_resultString);
		//Предварительный хэш для заполнения строки фейковыми символами
		$finalHash = base64_encode(
									$firstHash . 
									$secondHash . 
									$thirdHash . 
									$fourthHash . 
									$this->reverseString($firstHash) . 
									$this->reverseString($secondHash) . 
									$this->reverseString($thirdHash) . 
									$this->reverseString($fourthHash)
								);
		$hashArr = $this->getStrArr($finalHash);
		//Получаем уникальные цифры из соли, чтобы с их помощью определить какие буквы из хэша будем заменять спецсимволами. Например, если из ключа мы получим числа [1, 3, 5, 9], из соли мы достаем символы с позиций [1, 3, 5, 9] и заменяем их. Это делается, чтобы последовательность фейковых символов не выглядела искусственно (как просто последовательность латинских символов, тогда как в начале и конце будут спецсимволы и кирилица)
		$uniqueNumArr = array_unique(array_filter($this->getStrArr($matrixParam_resultString), function($el){return preg_match('/[0-9]/', $el);}));
		//Массив спецсимволов. Они будут участвовать в подмене букв соли
		$symbolsArr = array_values(array_filter($this->getStrArr($this->shiftedCipherKey_first), function($el){return preg_match('/[\W]/', $el);}));
		//Массив кирилических букв. Они будут участвовать в подмене букв соли
		$lettersArr = array_values(array_filter($this->getStrArr($this->shiftedCipherKey_second), function($el){return preg_match('/[а-ёА-Ё]/', $el);}));
		//Массив символов соли для замены на спецсимволы
		$replaceSymbArr = array_map(function($el) use($hashArr) {return $hashArr[$el];}, $uniqueNumArr);
		//Массив символов соли для замены на кирилические буквы
		$replaceLettersArr = array_map(function($el) use($hashArr) {return array_reverse($hashArr)[$el];}, $uniqueNumArr);
		$n = $m = 0;
		foreach ($hashArr as $symbPos => &$symb){
			if (in_array($symb, $replaceSymbArr) && $symbPos % 2 !== 0) {
				if (array_key_exists($n, $symbolsArr) === false) {
					$n = 0;
				}
				$hashArr[$symbPos] = $symbolsArr[$n];
			}
			if (in_array($symb, $replaceLettersArr) && $symbPos % 2 === 0) {
				if (array_key_exists($m, $lettersArr) === false) {
					$m = 0;
				}
				$hashArr[$symbPos] = $lettersArr[$m];
			}
			$n++;
			$m++;
		}
		return implode('', $hashArr);
	}


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


	/**
	 * Метод возвращает "реальный" ключ шифра, использующийся при шифровании (это определяется с помощью соли), а не "фейковый"
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
	 * Метод трансформирует пользовательскую соль, основываясь на пользовательском ключе. Некоторые символы из первого ключа заменяются символами из второго ключа, которые находятся на той же позиции (плюс небольшой модификатор). Например, два массива: [a,b,c,d], [l,n,r,y]. В соли символ 'a' будет заменен на 'l', 'b' на 'r' и так далее
	 *
	 * @param array $userKeyArr массив двух пользовательских ключей
	 * @return void
	 */
	private function transformSaltByUserKey(array $userKeyArr): void
	{
		//Вычленяем латинские символы и цифры из первого и второго ключей 
		$firstClearUserKey = $this->getStrArr(preg_replace('/[^0-9a-zA-Z]/', '', $userKeyArr[0]));
		$secondClearUserKey = $this->getStrArr(preg_replace('/[^0-9a-zA-Z]/', '', $userKeyArr[1]));
		//Цикл замены в соли символов, встречающихся в 1м ключе, символами, встречающимися во 2м ключе
		$shift = 1;		//Переменная нужна для того, чтобы с помощью нее учитывать расположение символов в массивах при совершении замены в соли. Без этой переменной если пара символов в двух ключах перемещалась на другую позицию, но относительно друг друга оставалась на одной и той же позиции, фактического запутывания соли не происходило.
		foreach ($firstClearUserKey as $symbKey => $symbVal){
			$this->salt = str_replace($symbVal, '*', $this->salt);
			$shift = ($symbKey + $shift >= count($secondClearUserKey) ? $shift - count($secondClearUserKey) : $shift);
			$this->salt = str_replace($secondClearUserKey[$symbKey + $shift], $symbVal, $this->salt);
			$this->salt = str_replace('*', $secondClearUserKey[$symbKey + $shift], $this->salt);
			$shift++;
		}
	}


	/**
	 * Метод возвращает число, основанное на разнице в позициях символов в двух ключах относительно друг друга. Уникальное число будет суммироваться с хэшом соли, который, в свою очередь, перемешает весь алгоритм совершенно по другому, чем если бы ключ не передавался и для запутывания применялась только соль
	 *
	 * @param array $userKeyArr массив ключей шифра
	 * @return integer
	 */
	private function getUserKeySymbSumm(array $userKeyArr): int
	{
		//Оставляем в массивах только кирилические буквы и спецсимволы из ключа. Латинские символы и цифры использовались на другом этапе запутывания (при замене символов в соли)
		$firstClearUserKey = $this->getStrArr(preg_replace('/[0-9a-zA-Z]/', '', $userKeyArr[0]));
		$secondClearUserKey = $this->getStrArr(preg_replace('/[0-9a-zA-Z]/', '', $userKeyArr[1]));
		//Результирующая сумма для преобразования соли
		$resultSum = 0;
		foreach ($firstClearUserKey as $symbKey => $symbVal){
			//Позиция символа первого массива во втором массиве
			$secondSymbKey = array_search($symbVal, $secondClearUserKey);
			//Получаем разницу между позициями найденного символа во втором массиве и в первом.
			$symbDiff = $secondSymbKey - $symbKey;
			//Если разница отрицательная, выводим ее в плюс
			$symbDiff = (int)str_replace('-', '', $symbDiff);
			$resultSum += $symbDiff;
			//Для дополнительного запутывания добавляем либо позицию первого символа, либо второго, также (ниже) умножаем, либо делим
			$resultSum = ($resultSum + ($symbKey % 2 === 0 ? $secondSymbKey : $symbKey));
			$resultSum = ($symbKey % 2 === 0 ? pow($resultSum * 3.14, 2) : sqrt($resultSum / 3.14));
		}
		$resultSum = str_replace('.', '', substr($resultSum . "", 0, 11));

		return (int)$resultSum;
	}


	#Гаврилов
	//ЕСЛИ ПЕРЕДАВАТЬ ОДНУ И ТУ ЖЕ СОЛЬ И ОДИН И ТОТ ЖЕ КЛЮЧ ПРИ ШИФРОВАНИИ ОДНОЙ И ТОЙ ЖЕ СТРОКИ (БЕЗ ФЕЙКОВЫХ СИМВОЛОВ), КОГДА НАЧНУТСЯ КОЛИЗИИ? вЕДЬ КЛЮЧЕВЫЕ ПАРАМЕТРЫ ОДНИ И ТЕЖЕ. А ЕСЛИ ПЕРЕДАВАТЬ ТОЛЬКО ОДНУ И ТУ ЖЕ СОЛЬ С КЛЮЧАМИ АЛГОРИТМА?


	/**
	 * Метод дешифровки текста
	 * @param string $cipherText зашифрованный текст для дешифрования
	 * @param string $userCipherSalt пользовательская соль для алгоритма
	 * @param string $userCipherKey пользовательские ключи для алгоритма
	 *
	 * @return string
	 */
	public function decryptText(string $cipherText, ?string $userCipherSalt = null, ?string $userCipherKey = null): string
	{	
		$this->encrypt = false;
		$this->text = $cipherText;
		$this->salt = $userCipherSalt;
		$userCipherKeyArr = ($userCipherKey ? [mb_substr($userCipherKey, 0, pow($this->matrixDepth, 2)), mb_substr($userCipherKey, pow($this->matrixDepth, 2), pow($this->matrixDepth, 2))] : []);
		//Начинаем очищать шифр от полезной нагрузки, чтобы получить зашифрованную строку
		//Сначала удаляем первый вектор иницилазиации
		$clearCipherText = mb_substr($this->text, $this->vectorLength);
		//Получаем 2 вектора инициализации с начала и конца строки
		$vectorFirst = mb_substr($this->text, 0, $this->vectorLength);
		$initVector_num_first = $this->getVector($vectorFirst, 'vert');
		//Получаем строку, содержащую информацию с параметрами преобразования матриц + первая часть указателя на длину открытого сообщения
		//Отрезок с параметрами преобразования матриц изначально состоит из 5 сегментов ([цифра]+[буква]{1}, например, 123d). Однако, в этот отрезок на рандомную позицию вставляется сегмент с первой частью указателя на длину открытой строки ([какой-то символ]{1}[цифра]{n}+[какой-то символ]{1}), например, |23!. Итоговый вариант отрезка с параметрами преобразования матрицы - 20e89№19%0о0e22г1169w.
		//Получаем отрезок с параметрами преобразования матриц + первая часть указателя на длину исходной строки - 20e89№19%0о0e22г1169w
		#Гаврилов
		//ЗДЕСЬ И ДАЛЕЕ ЗАМЕНЯЙ ЦИФРУ 3 НА ДЛИНУ ВЕКТОРА ИНИЦИАЛИЗАЦИИ (ЛЮБОГО), А НЕ ХАРДКОРЬ
		preg_match('/([^a-zа-ё]+[a-zа-ё]{1}){5}/ui', mb_substr($this->text, $this->vectorLength), $shiftCipherKeyParams_matches);
		//Теперь очищаем от полезной нагрузки с параметрами формирования матриц
		$clearCipherText = mb_substr($clearCipherText, mb_strlen($shiftCipherKeyParams_matches[0]));
		//Здесь вычленяем сегмент с 1й частью указателя на длину открытого сообщения - №19%
		preg_match('/[^a-zа-ё0-9]{1}[0-9]+[^a-zа-ё0-9]{1}/ui', $shiftCipherKeyParams_matches[0], $realLengthMatch_first);
		//Получаем массив параметров преобразования матриц (уже без 1й части указателя на реальную длину) - 20e890о0e22г1169w => [20],[890],[0],[22],[1169]
		//Сначала в виде строки, затем преобразовываем в массив числовых параметров преобразования матрицы
		$shiftCipherKeyParams_string = str_replace($realLengthMatch_first[0], '', $shiftCipherKeyParams_matches[0]);
		$shiftCipherKeyParams_arr = preg_split('/[^0-9]{1}/', str_replace($realLengthMatch_first[0], '', $shiftCipherKeyParams_matches[0]), 0, PREG_SPLIT_NO_EMPTY);
		$shiftCipherKeyParams_arr = array_map(function($el){return (int)$el;}, $shiftCipherKeyParams_arr);
		//Очищаем сегмент с 1й частью указателя на длину открытого сообщения от спецсимволов - №19% => 19
		$realLength_first = mb_substr($realLengthMatch_first[0], 1, mb_strlen($realLengthMatch_first[0]) - 2);
		//Получаем сегмент со 2й частью указателя на длину открытого сообщения
		preg_match('/([a-zа-ё0-9]{0,' . $this->vectorLength . '})([^a-zа-ё0-9]{1}[0-9]*[^a-zа-ё0-9]{1})([a-zа-ё0-9]{0,' . $this->vectorLength . '})([a-zа-ё0-9]{6})$/ui', $this->text, $realLengthMatch_second);
		//Очищаем сегмент со 2й частью указателя на длину открытого сообщения
		//Вычитаем двойку из длины второго указателя на длину открытого сообщения, чтобы получить реальную длину указателя без жвух спецсимволов вокруг, которые ее обрамляют
		$realLength_second = mb_substr($realLengthMatch_second[2], 1, mb_strlen($realLengthMatch_second[2]) - 2);
		//Получаем отрезок, содержаший: версию алгоритма (6 символов), 2ю часть указателя на длину открытого сообщения (длина $realLengthMatch_second[1]), второй вектор инициализации (2 символа)
		$vectorSecond = mb_substr($realLengthMatch_second[1] . $realLengthMatch_second[3], -$this->vectorLength);
		$initVector_num_second = $this->getVector($vectorSecond, 'hor');
		//Теперь очищаем от полезной нагрузки, связанной с версией алгоритма, 2й частью указателя на длину открытого сообщения сообщения и вторым вектором инициализации
		$clearCipherText = mb_substr($clearCipherText, 0, (0 - mb_strlen($vectorSecond) - mb_strlen($realLengthMatch_second[2]) - mb_strlen($this->version) * 2)); 	//Вычитаем длину версии алгоритма умноженную на 2, потому что в зашифрованном виде версия содержит столько же фейковых символов, сколько и реальных цифр, указывающих на версию
		$userCipherKeyHash = 0;
		//Если передаются пользовательские ключи, используем их для преобразования соли
		if ($userCipherKey) {
			$this->transformSaltByUserKey($userCipherKeyArr);
			$userCipherKeyHash = $this->getUserKeySymbSumm($userCipherKeyArr);
		}
		$this->saltNumberSegments = $this->getSaltNumbersArr();
		$cipherKeyIndex = $this->getCipherKey();
		if ($this->salt) {
			$cipherKeyIndex = $this->getRealCipherKey($cipherKeyIndex);
		}
		$this->cipherKey = (!empty($userCipherKeyArr) ? $userCipherKeyArr[0] : file_get_contents(self::$keyFilesPath . "cipherKey_$cipherKeyIndex.txt"));
		//Ключ второго шифра для формирования второй матрицы строится на основании другого ключа из массива $this->cipherKeyStorage (следующего ключ после ключа первой матрицы, либо первый ключ массива, если ключ для первый матрицы оказался последним в массиве)
		$this->cipherKey_second = (!empty($userCipherKeyArr) ? $userCipherKeyArr[1] : file_get_contents(self::$keyFilesPath . "cipherKey_" . ($cipherKeyIndex == 9 ? "0.txt" : $cipherKeyIndex + 1 . ".txt")));
		//Преобразуем хэш соли только здесь по коду, так как в методе getHashSalt() участвует cipherKey, который определяется выше
		if ($this->salt) {
			$this->saltHashSum = $this->getHashSalt() + $userCipherKeyHash;
		}
		//Только здесь дополнительно преобразуем ключ шифрования, так как только в методе setVersion происходит формирование ключа
		$this->windowSizeFirst = $shiftCipherKeyParams_arr[0];
		$this->shiftCountFirst = $shiftCipherKeyParams_arr[1];
		$this->windowSizeSecond = $shiftCipherKeyParams_arr[3];
		$this->shiftCountSecond = $shiftCipherKeyParams_arr[4];
		$salted_shiftCipherKeyParams = [];
		//Если передается соль, используем ее для преобразования параметров шифрования
		if ($this->salt) {
			$this->cipherKey = $this->useSaltToCipherKey($this->cipherKey);
			$this->cipherKey_second = $this->useSaltToCipherKey($this->cipherKey_second);
			$salted_shiftCipherKeyParams = $this->useSaltToCipherKeyParams($shiftCipherKeyParams_arr);
			$this->windowSizeFirst = $salted_shiftCipherKeyParams[0];
			$this->shiftCountFirst = $salted_shiftCipherKeyParams[1];
			$this->windowSizeSecond = $salted_shiftCipherKeyParams[3];
			$this->shiftCountSecond = $salted_shiftCipherKeyParams[4];
		}
		//Параметр переворачивания ключа шифра (дополнительный фактор запутывания)
		$reverseCipherKey = ($shiftCipherKeyParams_arr['2'] % 2 === 0) ? 0 : 1;
		$this->shiftedCipherKey_first = $this->shiftCipherKey($this->cipherKey, $this->windowSizeFirst, $this->shiftCountFirst, $reverseCipherKey);
		//Для второго ключа параметр переворачивания ключа обязательно противоположный
		$reverseCipherKey = ($reverseCipherKey ? 0 : 1);
		$this->shiftedCipherKey_second = $this->shiftCipherKey($this->cipherKey_second, $this->windowSizeSecond, $this->shiftCountSecond, $reverseCipherKey);
		//первая матрица на основе преобразованного ключа шифра
		$firstMatrix = $this->fillMatrix($this->shiftedCipherKey_first, (int)substr(array_sum($this->salt ? $salted_shiftCipherKeyParams : $shiftCipherKeyParams_arr), -1, 1));
		//вторая матрица на основе второго преобразованного ключа шифра
		$secondMatrix = $this->fillMatrix($this->shiftedCipherKey_second, (int)substr(array_sum($this->salt ? $salted_shiftCipherKeyParams : $shiftCipherKeyParams_arr) + 1, -1, 1));
		$this->shiftedMatrixArr[1] = $this->shiftMatrix($firstMatrix, 1, $initVector_num_first, $initVector_num_second);
		$this->shiftedMatrixArr[2] = $this->shiftMatrix($secondMatrix, 0, $initVector_num_first, $initVector_num_second);
		$realStringLength = $this->getRealStringLength(str_replace($realLengthMatch_first[0], '', $shiftCipherKeyParams_matches[0]), $realLength_first . $realLength_second);
		//Очищенный от фейковых символов текст для расшифровки
		$clearDecryptText = $this->cleanFakeSymb($clearCipherText, $realStringLength);
		$checkFakeSymbols = $this->checkFakeSymbols($this->fakeSymbolString, $this->createFakeLengthHash($clearDecryptText, $shiftCipherKeyParams_string));
		//Если проверка на наполнение фейковыми символами не пройдена возвращаем фейковую строку
		if (!$checkFakeSymbols) {
			return $this->getFakeText();
		}
		//Дешифруем полностью очищенный от всей полезной нагрузки зашифрованный текст
		$ecnryptText_interim = $this->transformSourceText($clearDecryptText);
		$compareTextHash = $this->getTextHashPointer($ecnryptText_interim, $shiftCipherKeyParams_string);
		//Проверяем совпадает ли указатель на хэш расшифрованной строки с указателем на хэш исходной строки, который содержится в первом и втором векторах инициализации
		if ($compareTextHash['firstVector'] !== $vectorFirst || $compareTextHash['secondVector'] !== $vectorSecond) {;
			return $this->getFakeText();
		}

		return base64_decode($ecnryptText_interim); 
	}


	/**
	 * Метод преобразовывает ключ шифра, используя соль
	 * Перебираем символы соли, находим их в ключе и определенным образом перемещаем на новые позиции в ключе
	 *
	 * @param string $cipherKey - ключ для преобразования
	 * @return string
	 */
	private function useSaltToCipherKey(string $cipherKey): string
	{
		//Новый, преобразованный с помощью соли ключ шифрования
		$newCipherKey = null;
		$cipherKeySymbArr = $this->getStrArr($cipherKey);
		preg_match_all('/[1-9]+/', $this->salt, $saltNumbers);
		$saltNumbers = array_map(function($el){return intval($el);}, $saltNumbers[0]);
		$saltNumbersCount = 0;
		foreach ($this->getStrArr($this->salt) as $saltKey => $saltSymb){
			$сipherSymbKey = array_search($saltSymb, $cipherKeySymbArr);
			//новая позиция в ключе шифра символа из соли
			$newPosition = $сipherSymbKey + $saltKey + $saltNumbers[$saltNumbersCount];
			//Если число из перебираемого массива чисел соли нечетное, новая позиция символа берется с конца ключа шифра, а не с начала. Например, если новая позиция = 15, то она пересчитывается на 169 - 15 = 154 
			if ($saltNumbers[$saltNumbersCount] % 2 !== 0) {	
				$newPosition = count($cipherKeySymbArr) - $newPosition;
			}
			//заменяемый символ в ключе шифра
			$replaceSymbol = null;
			//Проверяем новую позицию символа из соли. Если новая позиция больше чем длина ключа шифра, пересчитываем ее, чтобы уменьшить. Если она в итоге становится меньше нуля, то либо кладем символ в начало ключа шифра, либо в самый конец, в зависимости от того текущая итерация перебора соли четная или нет
			if ($newPosition >= count($cipherKeySymbArr)) {
				$newPosition = $saltKey - $сipherSymbKey - $saltNumbers[$saltNumbersCount];
			}
			if ($newPosition < 0) {
				$newPosition = ($saltKey % 2 !== 0 ? 0 : count($cipherKeySymbArr) - 1);
			}
			$replaceSymbol = $cipherKeySymbArr[$newPosition];
			$cipherKeySymbArr[$newPosition] = $saltSymb;
			$cipherKeySymbArr[$сipherSymbKey] = $replaceSymbol;
			$saltNumbersCount++;
			if ($saltNumbersCount == count($saltNumbers)) {
				$saltNumbersCount = 0;
			}
		}
		$newCipherKey = implode('', $cipherKeySymbArr);

		return $newCipherKey;
	}


	/**
	 * Метод преобразовывает массив символов соли в массив чисел, где каждое число - ключ элемента в массиве [0 => a, 1 => b, 2 => c ...], а затем делит массив на сегменты, количеством, равным квадратному корню из длины соли. Этот массив сегментов нужен для более случайного перемешивания параметров шифрования с использованием соли
	 *
	 * @return array
	 */
	private function getSaltNumbersArr(): array
	{
		$cipherSaltArr = str_split($this->salt);
		//Массив с латинскими буквами в верхнем регистре, который мы ниже объединим с массивом латинских букв в нижнем регистре и на его основе [n => 2, H => 3, s => 4 ...] будем считать сумму ключей символов в соли
		$upperLatinLetters = array_map(function($el){return strtoupper($el);}, array_flip($this->latinLetters));
		$cipherSaltArr = array_map(function($el) use($upperLatinLetters) {return preg_match('/[^0-9]/', $el) ? array_flip(array_flip($this->latinLetters) + array_merge($upperLatinLetters, []))[$el] : (int)$el;}, $cipherSaltArr);
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


	/**
	 * Метод применяет соль к параметрам преобразования матриц, чтобы по разному формировались ключи к матрицам, в зависимости от секретного ключа
	 *
	 * @param array $cipherKeyParams не измененный еще массив параметров преобразования матриц
	 * @return array
	 */
	private function useSaltToCipherKeyParams(array $cipherKeyParams): array
	{
		$transformedCipherKeyParams = $cipherKeyParams;
		//Для формирования новых параметров преобразования матриц используем цифры суммы позиций символов соли (начиная с конца, так как в начале числа могут быть более близкими от соли к соли)
		$shiftWindowSize_first = (int)substr($this->saltHashSum, -1);
		$shiftWindowSize_second = (int)substr($this->saltHashSum, -2, 1);
		//Двузначное число из начала суммы символов добавляем к количеству итераций сдвига первого ключа, двузначное число из конца суммы, соответственно, к количеству итераций второго ключа
		$shiftIteration_first = (int)substr($this->saltHashSum, -4, 2);
		$shiftIteration_second = (int)substr($this->saltHashSum, -6, 2);
		//Трансформируем массив с параметрами сдвига ключа шифра. Если новые значений окна захвата символов больше примерно половины от ключа шифра - через вычитание делаем окно захвата меньше исходного, а не больше
		$transformedCipherKeyParams[0] = ($transformedCipherKeyParams[0] + $shiftWindowSize_first >= floor(pow($this->matrixDepth, 2) / 2)) ? $transformedCipherKeyParams[0] - $shiftWindowSize_first : $transformedCipherKeyParams[0] + $shiftWindowSize_first;
		$transformedCipherKeyParams[1] = $transformedCipherKeyParams[1] + $shiftIteration_first;
		$transformedCipherKeyParams[3] = ($transformedCipherKeyParams[3] + $shiftWindowSize_second >= floor(pow($this->matrixDepth, 2) / 2)) ? $transformedCipherKeyParams[3] - $shiftWindowSize_second : $transformedCipherKeyParams[3] + $shiftWindowSize_second;
		$transformedCipherKeyParams[4] = $transformedCipherKeyParams[4] + $shiftIteration_second;

		return $transformedCipherKeyParams;
	}


	/**
	 * Метод формирует массив-указатель на хэш открытого сообщения, который кладется в шифр для сравнения такого же массива-указателя на хэш дешифруемой строки. Если они не совпадают - в шифре был подменен символ исходной строки. В этом случае возвращаем пользователю фейковую строку 
	 * 
	 * @param string $text текст на хэш которого будет указывать полученный массив
	 * @param string $cipherKeyParamString строка с параметрами преобразования ключа и случайными разделителями между параметрами. Нужны для уникальности результатов работы метода для каждого шифра
	 * @return array
	 */
	private function getTextHashPointer(string $text, string $cipherKeyParamString): array
	{
		//Массив-указатель
	  	$hashPointerArr = [
			'firstVector' => null,		//Элемент формируется на основе цифр хэша строки
			'secondVector' => null		//Элемент формируется на основе букв хэша строки
		];
		//Хэш строки. Подмешиваем строку с параметрами преобразования матриц для уникальности
		$hashText = hash('whirlpool', $text . $this->reverseString($cipherKeyParamString));
		//Кодируем в base64 для разбавления хэша более широким диапазоном используемых символов. В алгоритме хэша используются цифры 0-9 и буквы abcdef. При кодировании в base64 больше символов.
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
	 * Метод возвращает фейковую строку, преобразуя поступивший для дешфирования текст. Метод вызывается в случае, если пользователю нужно вернуть фейковое сочетание, а не реально получившийся результат. Например, если пользователь заменил один фейковый символ на другой без этого метода ему бы вернулся исходный текст, так как фейковый символ не участвует в шифровании. Этот же метод вернет ему фейковый текст, чтобы он понял, что подменяя символы в шифре, нормальный результат не получить
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
	 * @param string $cipherParamStr строка со всей полезной нагрузкой, использующейся для шифрования
	 * @return int
	 */
	private function calcLenFakeSymb(int $cipherLen, string $textCipher, string $cipherParamStr): int
	{
		$fakeSymbCount = $cipherLen - mb_strlen($textCipher) - mb_strlen($cipherParamStr);

		return ($fakeSymbCount > 0 ? $fakeSymbCount : 0);
	}


	/**
	 * Метод очищает зашифрованный текст от фейковых символов
	 *
	 * @param string $decryptText текст для расшифровки
	 * @param int $realStringLenght реальная длина исходного текста
	 * @return string
	 */
	private function cleanFakeSymb(string $decryptText, int $realStringLenght): string
	{
		//На примере строки Zceb4Т0ea002f2fП8c6eХab3fЦ15a0Шe185т2271O61766752108601йb1c4}5af1t99bfЗ325fKb60e541d093b
		//Количество символов в одном сегменте в дешифруемой строке. Каждый сегмент состоит из одного реального символа исходной строки + фейковые символы из заранее подготовленного хэша. Например, если сегмент равен 5, то в строке из примера первый сегмент будет Zceb4, где Z - реальный символ зашифрованной исходной строки, а ceb4 - отрезок с фейковыми символами
		$symbSegmentsLen = floor(mb_strlen($decryptText) / $realStringLenght);
		//массив символов дешифруемой строки
		$cleanDecryptTextArr = [];
		//массив сегментов фейковых символов, которые вставляется между символами шифра изначальной строки
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
			$cleanDecryptTextArr[] = mb_substr($decryptText, $i * $symbSegmentsLen, 1);
			$fakeSegmentsArr[] = mb_substr($decryptText, $i * $symbSegmentsLen + 1, $symbSegmentsLen - 1);
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
	 * Метод проверяет хэш фейковых символов, вычленненный из дешифруемой строки с хэшем фейковых символов, который должен был быть помещен в зашифрованное сообщение
	 *
	 * @param string $fakeSymbolString фейковые символы, полученные в результате очистки дешифруемой строки
	 * @param string $cipherTextHash хэш для заполнения исходной строки фейковыми символами
	 * @return bool
	 */
	private function checkFakeSymbols(string $fakeSymbolString, string $cipherTextHash): bool
	{
		$hashComparePart = substr($cipherTextHash, 0, strlen($fakeSymbolString));

		return $hashComparePart === $fakeSymbolString;
	}


	#Гаврилов
	//УДАЛИ ИЗ МЭДЖИКА СКРИПТ, КОГДА ПЕРЕДАЕШЬ ЕГО СЕБЕ НА КОМП

	#Гаврилов
	//ПОПРОБУЙ УКАЗАТЬ МАКСИМАЛЬНУЮ ДЛИНУ ТЕКСТА 1000
	//ПОПРОБУЙ УКАЗАТЬ РЕАЛЬНЫЙ ТЕКСТ РОВНО 1000 СИМВОЛОВ И ФЕЙКОВУЮ ДЛИНУ 1000 / 0


	/**
	 * Метод заполняет фейковыми значениями шифруемый текст до достижения желаемой длины
	 *
	 * @param string $interimCipherText промежуточный зашифрованный текст (без заполненных фейковых символов)
	 * @param int $resultCipherLength фейковая длина шифра
	 * @param string $cipherLengthHash хэш для заполнения исходной строки фейковыми значениями
	 * @return string
	 */
	private function fillFakeLength(string $interimCipherText, int $resultCipherLength, string $cipherLengthHash): string
	{
		//Количество символов в каждом сегменте, которое будет проставляться после каждой буквы исходного сообщения. Например если значение = 3, то в строке "test" после каждого символа будет 3 фейковых символа: "t[fr2]e[sdn]s[12f]t[sdz]"
		$fakeSymbSegmentLen = floor($resultCipherLength / mb_strlen($interimCipherText));
		$fakeSegmentsArr = [];
		$n = 0;
		while ($n < mb_strlen($interimCipherText))
		{
			$fakeSegmentsArr[$n] = mb_substr($cipherLengthHash, $fakeSymbSegmentLen * $n, $fakeSymbSegmentLen);

			$n++;
		}
		$interimCipherTextArr = $this->getStrArr($interimCipherText);
		$newResultCipher = [];
		foreach ($fakeSegmentsArr as $fakeSymbSegmentKey => $fakeSymbSegment) {
			$newResultCipher[$fakeSymbSegmentKey] = $interimCipherTextArr[$fakeSymbSegmentKey] . $fakeSymbSegment;
		}
		$newResultCipher = implode('', $newResultCipher);
		$fakeSymbRemainder = $resultCipherLength + mb_strlen($interimCipherText) - mb_strlen($newResultCipher);
		$fakeSymbRemainderStr = mb_substr($cipherLengthHash, $fakeSymbSegmentLen * mb_strlen($interimCipherText), $fakeSymbRemainder);
		$newResultCipher = $newResultCipher . $fakeSymbRemainderStr;

		return $newResultCipher;
  	}


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
	private function constructCipherText($vectorFirst, $vectorSecond, $cipherText, $matrixParams, $cipherVersion, $fakeLenFirst, $fakeLenSecond)
	{
		//Позиция разделения строки с параметрами матриц после которогой будет вставлена первая часть фейковой длины. Например, параметры преобразования матрицы - 20e890о0e22г1169w, первая часть версии - №1%, позиция разделения - 5. Итоговый сегмент с параметрами преобразования матрицы + первой частью версии алгоритма - 20e89№1%0о0e22г1169w
		//По аналогии работает шифрование второй части фейковой длины с версией алгоритма ниже в этом методе
		//Максимальная позиция  куда вставляется отрезок с первой частью указателя на длину исходной строки ( №1% ) должна быть равна длине сегмента с параметрами матрицы ( 20e890о0e22г1169w ) минус 1, так как если позиция будет равна длине сегмента, отрезок вставится в самый конец сегмента ( 20e890о0e22г1169w№1% ) и при расшифровке будет проблема
		$matrixParamsDelimeter = $this->getRandNum(mb_strlen($matrixParams));
		$matrixParam_resultString = mb_substr($matrixParams, 0, $matrixParamsDelimeter) . $fakeLenFirst . mb_substr($matrixParams, $matrixParamsDelimeter);
		//Место разделения строки с параметрами матриц после которогой будет вставлена первая часть фейковой длины
		$cipherVerDelimeter = $this->getRandNum(mb_strlen($vectorSecond) + 1);
		$transformСipherVer = mb_substr($vectorSecond, 0, $cipherVerDelimeter) . $fakeLenSecond . mb_substr($vectorSecond, $cipherVerDelimeter);
		//var_dump("Вторая часть нагрузки - $transformСipherVer$cipherVersion");
		$resultCipherText = $vectorFirst . $matrixParam_resultString . $cipherText . $transformСipherVer . $cipherVersion;

		#Гаврилов
		//ПОЛЬЗОВАТЕЛЬ МОЖЕТ ТАК СЛОМАТЬ СТРОКУ, ЧТО РЕГУЛЯРКА ПРИ РАСШИФРОВКЕ НЕ ОБРАБОТАЕТСЯ (НАПРИМЕР, ОЖИДАЕТСЯ 6 СИМВОЛОВ В КОНЦЕ, А ОНА ЗАСУНЕТ 7). ТОГДА TRYCATCH И ВОЗВРАЩЕНИЕ РАНДОМНЫХ ЗНАЧЕНИЙ. ПОТЕСТИ

		return $resultCipherText;

		//([a-zа-ё0-9]{0,3})([^a-zа-ё0-9]{1}[0-9]*[^a-zа-ё0-9]{1})([a-zа-ё0-9]{0,3})([a-zа-ё0-9]{6})$
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


	/**
	 * Метод получает номер ключа шифрования из сегмента с информацией по версии
	 *
	 * @return int
	 */
	private function getCipherKey(): int
    {
		//Вся информация по версии шифра находится в последних 6ти символах
		$cipherVersionInfo = mb_substr($this->text, -6);
      	$numberArr = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
		$versionSymbArr = preg_split('//u', $cipherVersionInfo, -1, PREG_SPLIT_NO_EMPTY);
		//Вычленяем числа из строки с шифром
		$versionNumberArr  = array_values(array_intersect($versionSymbArr, $numberArr));
		$cipherKeyNum = $versionNumberArr[2];

		return $cipherKeyNum;
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
		$cipherVersion = $this->version;
		#Гаврилов
		//ВЫНЕСИ ФОРМИРОВАНИЕ КЛЮЧА ШИФРОВАНИЯ ЗА ПРЕДЕЛЫ ДАННОГО МЕТОДА
		// $this->cipherKey = $this->cipherKeyStorage[$cipherKeyIndex];
		// //Ключ второго шифра для формирования второй матрицы строится на основании другого ключа из массива $this->cipherKeyStorage (следующего ключ после ключа первой матрицы, либо первый ключ массива, если ключ для первый матрицы оказался последним в массиве)
		// $this->cipherKey_second = $this->cipherKeyStorage[$cipherKeyIndex == (count($this->cipherKeyStorage) - 1) ? 0 : $cipherKeyIndex + 1];
		//Флаг реверсивности (относится только к формированию версии, к реверсивности других параметров шифра отношения не имеет). Второе число в массиве параметров формирования версии. Если число четное - массив с буквами/цифрами, на основании которого цифры версии преобразуются в буквы, не реверсим, иначе реверсим. Дополнительный фактор запутывания
		$reverseLettersArr = $this->getRandNum(10);
		if (!($reverseLettersArr % 2 === 0)) {
			$this->lettersArr = array_combine(array_keys($this->lettersArr), array_reverse(array_values($this->lettersArr)));
		}
		//Бьем версию на массив цифр
		$versionSymbArr = str_split((string)$cipherVersion);
		//Массив цифр, участвующих в шифровании версии алгоритма
		//1 цифра - паттерн набора версии
		//2 цифра - флаг реверсивности массива букв, на основании которого цифры версии будут преобразовываться в буквы. Например версия 123 [1,2,3] должна преобразоваться в буквы на основании массива [a,b,c]. Если флаг реверсивности 0 - версия будет abc, если 1 - cba
		//3 цифра - рандомная (ВОЗМОЖНОСТЬ РАЗМЕСТИТЬ ПОЛЕЗНУЮ НАГРУЗКУ)
		#Гаврилов
		//ВОТ ЗДЕСЬ 3 ЧИСЛО СДЕЛАТЬ ВЕРСИЮ КЛЮЧА ШИФРА, А 3 ЧИСЛО В ВЕРСИИ ВСЕГО АЛГОРИТМА ИСПОЛЬЗОВАТЬ ТРЕХЗНАЧНОЕ ЧИСЛО
		//$cipherNumArr = [$pattern, $reverseLettersArr, $this->getRandNum(10)];
		$cipherNumArr = [$pattern, $reverseLettersArr, $cipherKeyIndex];
		
		//Массив всех символов версии, на основании которого будет сформирована итоговая строка с версией
		$cipherSymbArr = [];
		//Ниже мы определеяем порядок размещения букв и цифр, которые будут участвовать в шифровании версии алгоритма (в зависимости от паттерна, определенного выше). Буквы означают цифры шифра. Например, версия алгоритма 123 в буквенном выражении "fpa" 
		switch ($pattern) {
			case 1:
				$cipherSymbArr = array_map(function($el) {return array_search((int)$el, $this->lettersArr);}, $versionSymbArr);
				break;
			case 2:
				$cipherSymbArr[] = array_search((int)$versionSymbArr[0], $this->lettersArr);
				$cipherSymbArr[] = array_search((int)($versionSymbArr[1] . $versionSymbArr[2]), $this->lettersArr);
				$cipherSymbArr[] = array_rand($this->lettersArr);
				break;
			case 3:
				$cipherSymbArr[] = array_search((int)($versionSymbArr[0] . $versionSymbArr[1]), $this->lettersArr);
				$cipherSymbArr[] = array_search((int)$versionSymbArr[2], $this->lettersArr);
				$cipherSymbArr[] = array_rand($this->lettersArr);
				break;
			case 4:
				$cipherSymbArr[] = array_rand($this->lettersArr);
				$cipherSymbArr[] = array_search((int)$versionSymbArr[0], $this->lettersArr);
				$cipherSymbArr[] = array_search((int)($versionSymbArr[1] . $versionSymbArr[2]), $this->lettersArr);
				break;
			case 5:
				$cipherSymbArr[] = array_rand($this->lettersArr);
				$cipherSymbArr[] = array_search((int)($versionSymbArr[0] . $versionSymbArr[1]), $this->lettersArr);
				$cipherSymbArr[] = array_search((int)$versionSymbArr[2], $this->lettersArr);
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
		$transformedMatrix = ($matrixNum == 1 ? $this->shiftedMatrixArr[1] : $this->shiftedMatrixArr[2]);
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
			$this->shiftedMatrixArr[1] = $transformedMatrix;
		} else {
			$this->shiftedMatrixArr[2] = $transformedMatrix;
		}
	}


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
				$firstBigrammaSymbCoord = $this->getSymbCoords($this->shiftedMatrixArr[$this->encrypt ? 2 : 1], mb_substr($bigramma, 0, 1));
				$secondBigrammaSymbCoord = $this->getSymbCoords($this->shiftedMatrixArr[$this->encrypt ? 1 : 2], mb_substr($bigramma, 1, 1));
				//Если координаты биграммы не определились - в массив с координатами положится сам символ, а не масссив координат. Это позволит скрипту в дальнейшем определять символ биграммы нашелся или нет. Учитывая, что мы будем возвращать ошибку, если в шифруемой строке пользователя будут нераспознаваемые символы, смысла в этом нет вроде бы. Но пока рабоатет лучше не трогать...
				$bigrammaCoords[0] = $firstBigrammaSymbCoord ? $firstBigrammaSymbCoord : mb_substr($bigramma, 0, 1);
				$bigrammaCoords[1] = $secondBigrammaSymbCoord ? $secondBigrammaSymbCoord : mb_substr($bigramma, 1, 1);
			}	
			$symbCoord = $this->getSymbCoords($this->shiftedMatrixArr[$transformedMatrixNum], $symbol);
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
			$nearBigrammaSymbCoords = ($nearBigrammaSymbKey !== false) ? $this->getSymbCoords(($this->shiftedMatrixArr[($transformedMatrixNum == 1 ? 2 : 1)]), $this->getStrArr($text)[$nearBigrammaSymbKey]) : false;
			//Если столбцы символов биграммы в соседних матрицах совпадают - мы преобразуем символы биграммы, используя в качестве номера строки координаты символов противоположной матрицы. Например. Биграмма Fh, ее координаты F[1 матрица][5,6], h[2][2,6]. Шифруемая биграмма будет с координатами [1][2,6],[2][5,6]. 
			if (is_array($bigrammaCoords[0]) && is_array($bigrammaCoords[1]) && $bigrammaCoords[0][1] == $bigrammaCoords[1][1]) {
				if ($this->encrypt) {
					if ($symbKey % 2 !== 0) {
						$transfomedTextSymbArr[] = $this->shiftedMatrixArr[2][$bigrammaCoords[1][0]][$bigrammaCoords[1][1]];
						//Матрицы перестраиваются один раз при обработке полной биграммы, у которой совпадают столбцы. Если их перестраивать каждую итерацию при шифровании/дешифровании биграммы - могут возникнуть проблемы (если координаты символов биграммы полностью совпадают между матрицами М[1,1], А[1,1])
						$this->transformMatrix(1, $symbKey, $bigrammaCoords[1]);
						$this->transformMatrix(2, $symbKey, $bigrammaCoords[0]);
					} else {
						$transfomedTextSymbArr[] = $this->shiftedMatrixArr[1][$bigrammaCoords[0][0]][$bigrammaCoords[0][1]];
					}
				} else {
					if ($symbKey % 2 !== 0) {
						$transfomedTextSymbArr[] = $this->shiftedMatrixArr[1][$bigrammaCoords[1][0]][$bigrammaCoords[1][1]];
						$this->transformMatrix(1, $symbKey, $bigrammaCoords[1]);
						$this->transformMatrix(2, $symbKey, $bigrammaCoords[0]);
					} else {
						$transfomedTextSymbArr[] = $this->shiftedMatrixArr[2][$bigrammaCoords[0][0]][$bigrammaCoords[0][1]];
					}
				}

				continue;
			}
			//Если биграмма неполная (символ не имеет "пары"), либо соседний символ является нераспознанным, просто меняем координаты строки и столбца символа местами
			if ($nearBigrammaSymbKey === false || $nearBigrammaSymbCoords === false) {
				if ($this->encrypt) {
					$transfomedTextSymbArr[] = $this->shiftedMatrixArr[($transformedMatrixNum == 1 ? 2 : 1)][$symbCoord[1]][$symbCoord[0]];
					$this->transformMatrix(($transformedMatrixNum == 1 ? 2 : 1), $symbKey, [$symbCoord[1], $symbCoord[0]]);
				} else {
					$transfomedTextSymbArr[] = $this->shiftedMatrixArr[($transformedMatrixNum == 1 ? 2 : 1)][$symbCoord[1]][$symbCoord[0]];
					$this->transformMatrix(($transformedMatrixNum == 1 ? 2 : 1), $symbKey, [$symbCoord[1], $symbCoord[0]]);
				}

				continue;
			//Стандартная обработки биграммы согласно алгоритму двойного квадрата Полибия - координаты символов биграммы передаваемой строки меняеются местами (строка/столбец и их порядок в массиве координат), таким образом формируется биграмма преобразованной строки
			} else {
				if ($this->encrypt) {
					$transfomedTextSymbArr[] = $this->shiftedMatrixArr[($transformedMatrixNum == 1 ? 2 : 1)][$nearBigrammaSymbCoords[0]][$symbCoord[1]];
					$this->transformMatrix(($transformedMatrixNum == 1 ? 2 : 1), $symbKey, [$nearBigrammaSymbCoords[0], $symbCoord[1]]);
				} else {
					$matrixIndex = ($transformedMatrixNum == 1 ? 2 : 1);
					$transfomedTextSymbArr[] = $this->shiftedMatrixArr[$matrixIndex][$bigrammaCoords[$transformedMatrixNum == 1 ? 1 : 0][0]][$bigrammaCoords[$transformedMatrixNum == 1 ? 0 : 1][1]];
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
	private function getRandNum(int $max, int $min = 1): int
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
	public function getStrArr(string $str): array
	{
		return preg_split('//u', $str, -1, PREG_SPLIT_NO_EMPTY);
	}

}


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
	//В РАЗДЕЛЕ API НАВЕРНОЕ НУЖНО НАПИСАТЬ В НАЧАЛЕ КАКАЯ ЧАСТЬ ЗАПРОСА ПРЕДШЕСТВУЕТ ЕНДПОИНТАМ? HTTPS://sIMPLEcIPHER/API/ENPOINT

$cipherText = 'جۆری نادروستی کلیلی کۆد Y$% два три';
// $cipherText = '1111111111111111111111111';
$salt = 'NTI0M2FmNWEwOGU3NDY2YTc5MAFiMTEyOTdlNmY1NTQzY2Q4MzYzMmJkMTNiODRjOGI2YjY4NjEwYjNmM2NjZGJhOWY1NjRiYmU3OTEzZjdhZmIzNDExM2QwZTgwMjhkZDE1OTIwMDlhY2YxZjIxMDljNDA4MTllZjc3MmEzOTI';
$userKey = '*OПр÷Гкцg+U~WВ€Cf7Я⇔ЖTIh">x.!ФaЩ3_\Е,С10≠Ёcл×:s VRDмчNnlSф2сяpЫ-Lтыщ©@=пУиёюЙэ6q(;πiE4шMОzу?[Jr№хk%<vШ9|^o}ДjзьаБЧHЪъte/d&ЭF]оAбнЦХ{ZНЗКPQKuеbАmвG#ТгyИ)5й§ЛЬжМwР`XYЮ8д$B*≠"K8Длв÷и$~i:{Пs;5oэl !h0⇔-тжшдяКYT_СЮZ#юЁЫъ,огЙu4VCQ2Гр§H%ХЧEAТmемфN@<6Э^/IЯvА\ЗкyнцπчtРё)МФSdОP№а[1?rЦИ×pLНБzЬRщcбУЪjх=зnШ&Oу`ЩЛawbпь|g]X9(F.BGJDйM}3©e7kЕU>сыWx+qfВ€Ж';
// $userKey = null;
// $salt = null;
//$testCipher = (new SimpleCipher($cipherText, $salt))->encryptText(40);
$n = 1;
$saltNew = $salt;
// while ($n <= 500) {
	// $randomNumb_pos = unpack("N", openssl_random_pseudo_bytes(4))[1] % (160 - 1) + 1;
	// $randimNumb_symb = unpack("N", openssl_random_pseudo_bytes(4))[1] % (59 - 1) + 1;
	// $saltArr = str_split($saltNew);
	// shuffle($saltArr);
	// $saltNew = implode('', $saltArr);
	// //if ($salt !== $saltNew) {
	// 	// var_dump('here');
	// 	$decryptText = (new SimpleCipher($testCipher, $saltNew))->decryptText();
	// 	if ($decryptText === $cipherText) {
	// 		var_dump($saltNew);
	// 		var_dump('Жаль!');
	// 	}
	// //}
	// var_dump($n);

#Гаврилов
//СЕЙЧАС ВТОРОЙ УКАЗАТЕЛЬ НА РЕАЛЬНУЮ ДЛИНУ ИСХОДНОЙ СТРОКИ КЛАДЕТСЯ ВО ВТОРОЙ ВЕКТОР ИНИЦИАЛИЗАЦИИ. ЛУЧШЕ КЛАСТЬ В ВЕРСИЮ? В ПОЛЕДНИЕ 6 СИМВОЛОВ?
//НАДО ПОНЯТЬ КАКОВА ВЕРОЯТНОСТЬ ПОЛУЧИТЬ 2 ОДИНАКОВЫЕ ВЕРСИИ И КАКОВА ВЕРОЯТНОСТЬ ПОЛУЧИТЬ 2 ОДИНАКОВЫХ ВЕКТОРА. С ДРУГОЙ СТОРОНЫ, ВЕТОРЫ КОРОЧЕ ПО ДЛИНЕЮ МЕНЕЕ ПРИМЕЧАТЕЛЬНЫ.


	#Гаврилов
	//ПОПРОБУЙ ТУТ СИНТАКСИЧЕСКИЕ ОШИБКИи, ОШИБКИ ПАРСИНГА ПО КОДУ И ОБРАТИСЬ С ОПЕРАЦИЕЙ ШИФРОВАНИЯ ИЗ ИНТЕФЕЙСА. ВО-ПЕРВЫХ, ОШИБКА ДОЛЖНА ЗАЛОГИРОВАТЬСЯ, ВО ВТОРЫХ ДЕЛАТЕЛЬНО, ЧТОБЫ В КОНСОЛИ НЕ БЫЛО ВИДНО, ЧТО ЗА ОШИБКА (УБРАТЬ ЗАГОЛОВКИ ОТОБРАЖЕНИЯ ОШИБОК?), В ТРЕТЬИХ ПОЛЬЗОВАТЕЛЬ ДОЛЖЕН ПОЛУЧИТЬ КОРРЕКТНЫЙ ОТВЕТ. ПРИ ШИФРОВАНИИ ОШИКУ, ПРИ ДЕШИФРОВКЕ ОШИБКУ ИЛИ ПРОСТО КРИВОЙ ШИФР? ЧТО БЕЗОПАСНЕЕ?

	// $testCipher = (new SimpleCipher())->encryptText($cipherText, 150, $salt, $userKey);
	// echo '<pre>'; var_dump($testCipher); echo'</pre>';
	// $decryptText = (new SimpleCipher())->decryptText($testCipher, $salt, $userKey);
	// echo '<pre>'; var_dump($decryptText); echo'</pre>';


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

	// if ($decryptText !== $cipherText) {
	// 	var_dump('ОШИПКА!');
	// }
//  	$n++;
// }

	#Гаврилов
	//ПРОВЕДИ ТЕСТИРОВАНИЕ КЛЮЧА И ШИФРА. ПЕРЕБИРАЙ СЛУЧАЙНЫЕ СОЛЬ И КЛЮЧ И ИЩИ КОЛЛИЗИИ, ПЕРЕБИРАЙ ОДИН СИМВОЛ В КЛЮЧЕ (меняя ее на любой другой по алфавиту) И ОДИН СИМВОЛ В СОЛИ И ИЩИ КОЛЛИЗИИ, А ТАКЖЕ ИЗМЕНЯЙ ПЕРВЫЙ, ВТОРОЙ (ПЕРВЫЙ СОХРАНЯЙ), ТРЕТИЙ (ВТОРОЙ СОХРАНЯЙ) и ищи коллизии
	//НАПИШИ ГРАФИК ТЕСТИРОВАНИЯ И РАСПИШИ В НЕМ ЧТО ТЫ ТЕСТИРОВАЛ И КАКИЕ РЕЗУЛЬТАТЫ

#Гаврилов
//ПЕРЕПИСАТЬ ИСПОЛЬЗОВАНИЯ КЛЮЧА СЛЕДУЮЩИМ ОБРАЗОМ. БРАТЬ НЕ СУММУ ВСЕХ ЧИСЕЛ И ИЗ НЕЕ ВЫЧЛЕНЯТЬ ЦИФРЫ, А ГЕНЕРИТЬ СУММЫ ПО ОТРЕЗКАМ: СУММА ПЕРВЫХ ПЯТИ СИМВОЛОВ, СУММА ВТОРЫХ ПЯТИ СИМВОЛОВ И ТАК СКОЛЬКО НАДО. ТО ЕСТЬ, В КАЧЕСТВЕ КЛЮЧЕЙ ДЛЯ МАССИВА С СЕГМЕНТАМИ СИМВОЛОВ ПОЛЬЗОВАТЕЛЬСКОГО КЛЮЧА ИСПОЛЬЗОВАТЬ НЕ ПРОСТО ГОЛЫЕ ЦИФРЫ, А СОВОКУПНОСТЬ СИМВОЛОВ, СУММА КОТОРЫХ (СЛОЖИВ ИХ ПОЗИЦИИ) ИЛИ ПОСЛЕДНЕЕ ЧИСЛО ИЗ СУММЫ БУДЕТ БОЛЕЕ ЗАВИСИМЫМ ОТ ЛЮБЫХ ИЗМЕНЕНИЙ КЛЮЧА. С ДРУГОЙ СТОРОНЫ, ЕСЛИ МЫ СЕЙЧАС БЕРЕМ ЦИФРЫ ИЗ СТАТИЧЕСКИХ КЛЮЧЕЙ, НА НИХ НИКАК НЕЛЬЗЯ ПОВЛИЯТЬ, ПЕРЕДАВАЙ ДИНАМИЧЕСКИЙ ПОЛЬЗОВАТЕЛЬСКИЙ КЛЮЧ. ДОБАВИТЬ ЭТУ ЗАДАЧУ В БЭКЛОГ, ЕСЛИ БУДЕТ СЛОЖНО РЕАЛИЗОВЫВАТЬСЯ?


#Гаврилов
//ОДИН РАЗ ПРИ НАЖАТИИ НА КНОПКУ ЗАШИФРОВАТЬ Я ПОЛУЧИЛ ШИФР НЕ ЖЕЛАЕМОЙ ДЛИНЫ, А МЕНЬШЕЙ. ХЗ ПОЧЕМУ
//ВЫБИРАЛ КОЛИЧЕСТВО ФЕЙКОВЫХ СИМВОЛОВ 67. ЛИБО СТАРЫЕ РЕЗУЛЬТАТ, ЛИБО ШИФР ОБРЕЗАЛСЯ ПО КАКОМУ-ТО СИМВОЛУ И НЕ СФОРМИРОВАЛСЯ ПОЛНОСТЬЮ? В ЭТОМ СЛУЧАЕ МОГУТ БЫТЬ ВИНОВАТЫ СПЕЦСИМВОЛЫ? ПРОВЕДИ ТЕСТИРОВАНИЕ НА ТЫСЯЧАХ ИТЕРАЦИЙ ШИФРОВАНИЯ И ПРОВЕРЬ ИТОГОВУЮ ДЛИНУ ШИФРА


//КУЧА ОШИБОК ЕСЛИ ПОПЫТАТЬСЯ ОТПРАВИТЬ ПРОСТОЙ ТЕКСТ В РАСШИФРОВКУ, НАПРИМЕР "МАМА МЫЛА РАМУ" ИХ НАДО КОРРЕКТНО ОБРАБАТЫВАТЬ

#Гаврилов
//затестить передачу в шифрование перенос строк, перенос кареток и всего такого
