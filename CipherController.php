<?php

class CipherController
{
    /**
     * @var int версия алгоритма шифрования. Прописывается последняя версия актуальная (на случай если текст шифруется)
     */
    private $cipherVer = 123;
    /**
     * @var string соль для шифрования
     */
    private $cipherSalt = null;
    /**
     * @var string шифрование/дешифрование
     */
    private $action;
    // /**
    //  * @var string текст для шифрования
    //  */
    // private $encryptText;
    /**
     * @var string текст для дешифрования
     */
    private $decryptText;
    /**
     * @var array массив параметров запроса
     */
    private $rqstParams;
    /**
     * @var object экземпляр класса шифровальщика;
     */
    private $cipherObj;
    /**
     * @var string фейковый ключ для валидации шифруемой строки на наличие неподходящих символов ЗАПРАШИВАТЬ ПЕРВЫЙ КЛЮЧ ИЗ АКТУАЛЬНОЙ ВЕРСИИ ШИФРА, ПЕРЕМЕШИВАТЬ ЕГО И ВОЗВРАЩАТЬ. ДАЛЬШЕ УЖЕ ПРОВОДИТ ВАЛИДАЦИЮ
     */
    private $fakeKey;
    /**
     * @var array $routes списки доступных роутов api
     * TODO
     * ЗДЕСЬ ТАК ЖЕ ДОЛЖНЫ БЫТЬ ПРОПИСАНЫ ОБЯЗАТЕЛЬНЫЕ И НЕ ОБЯЗАТЕЛЬНЫЕ ПЕРЕДАВАЕМЫЕ POST АРГУМЕНТЫ И ПАРАМЕТРЫ ИХ ВАЛИДАЦИИ
     */
    private $routes = [
        //TODO
        //ПЕРЕИМЕНУЙ НА ENCRYPTTEXT
        'getEncryptText' => [
            // 'methodName' => 'getEncryptText',
            'method' => 'POST',
            'methodParams' => [
                'text' => [
                    'important' => true,
                    'validation' => [
                        'validationRegular' => null,
                        'validationMethod' => null,
                    ]
                ],
                'fakeLength' => [
                    'important' => true,
                    'validation' => [
                        'validationRegular' => '/[0-9]+/',
                        'validationMethod' => 'fakeLengthValidation',
                    ]
                ],
                'cipherSalt' => [
                    'important' => true,
                    'validation' => [
                        'validationRegular' => '/^[0-9a-zA-Z]{171}$/',
                        'validationMethod' => null,
                    ]
                ],
                'cipherKey' => [
                    'important' => false,
                    'validation' => [
                        'validationRegular' => null,
                        'validationMethod' => null,
                    ]
                ],
            ]
        ],
        //ODd59g756б0≠39©ш28v2084пXгqqf]ЕydyЧ|YoO 41_TIbc114h
        //TODO
        //ПЕРЕИМЕНУЙ НА DECRYPTTEXT
        'getDecryptText' => [
            //'methodName' => 'getDecryptText',
            'method' => 'POST',
            'methodParams' => [
                'text' => [
                    'important' => true,
                ],
                'cipherSalt' => [
                    'important' => true,
                    'validation' => [
                        //ГАВРИЛОВ
                        //При дешифровке могут передавать любую соль? мы не должны подсказывать, что есть "правильный" формат соли? Потести попередавай кривые соли: пару спецсимвоов, 0, кирилические буквы. можно вообще при несоблюдении формата соли просто возвращать "кривую" фейковую строку?
                        'validationRegular' => null,
                        'validationMethod' => null,
                    ]
                ],
                'cipherKey' => [
                    'important' => false,
                    'validation' => [
                        'validationRegular' => null,
                        'validationMethod' => null,
                    ]
                ],
            ]
        ],

        #Гаврилов
        //РЕАЛИЗОВАТЬ МЕХАНИКУ СЖАТОГО ШИФРОВАНИЯ. ЕСЛИ ПЕРЕДАЕТСЯ ЗАГОЛОВОК СЖАТОГО ШИФРОВАНИЯ, ИСПОЛЬЗУЕТСЯ ПРОВЕРКА НА НАЛИЧИЕ НЕВАЛИДНЫХ СИМВОЛОВ И ТОГДА КОЛИЧЕСТВО СИМВОЛОВ В ИТОГОВОМ ШИФРЕ СНИЗИТСЯ В 3 РАЗА, НО МОЖНО БУДЕТ ШИФРОВАТЬ ТОЛЬКО СИМВОЛЫ, НАХОДЯЩИЕСЯ В КЛЮЧЕ (латиница, кирилица, цифры, некоторые спецсимволы)

        #Гаврилов
        //при изменении текста в поле текст для шифра рассчитывай итоговое количество в поле желаемая длина шифра, умножая количество символов на 4 и прибавляя полезную нагрузку + несколько символов, чтобы полезную нагрузку нельзя было вычислить

        //TODO
        //ПЕРЕИМЕНУЙ НА GETCIPHERSALT
        'createCipherSalt' => [
            'method' => 'GET',
            'methodParams' => []
        ],
        'getCipherKey' => [
            'method' => 'GET',
            'methodParams' => []
        ],
    ];
    
    
    /**
     * Конструктор
     *
     * @param string $cipherSalt Соль для шифрования
     * @param string $encryptText Текст для шифрования
     * @param integer $fakeLength Желаемая длина шифра
     * @param string $decryptText Текст для расшифрования
     */
    public function __construct(string $action)
    {

        set_error_handler([$this, "myErrorHandler"]);
        $this->action = $action;
        $this->rqstParams = json_decode(file_get_contents('php://input'), true) ?? [];
        $this->cipherSalt = array_key_exists('cipherSalt', $this->rqstParams) !== false ? $this->rqstParams['cipherSalt'] : null;

        // var_dump($this->rqstParams);

        // var_dump($test);

        $checkEndpoint = $this->checkRoute($this->action);
        //$checkEndPointErr = null;
        if (!$checkEndpoint['result']) {
            // $responseObj = [
               // $checkEndPointErr = $checkEndpoint['errorMsg']
            // ];
            $this->returnResponse([], $checkEndpoint['responseCode'], $checkEndpoint['errorMsg']);
        } else {
            require_once ("./CipherVersion.php");
            switch ($this->action) {
                case 'getEncryptText':
                    $this->getEncryptText(
                        $this->rqstParams['text'], 
                        (array_key_exists('fakeLength', $this->rqstParams) !== false ? $this->rqstParams['fakeLength'] : 50),
                        (array_key_exists('cipherKey', $this->rqstParams) !== false ? $this->rqstParams['cipherKey'] : null),
                    );
                    break;
                case 'getDecryptText':
                    $this->getDecryptText(
                        $this->rqstParams['text'],
                        (array_key_exists('cipherKey', $this->rqstParams) !== false ? $this->rqstParams['cipherKey'] : null)
                    );
                    break;
                case 'createCipherSalt':
                    $this->createCipherSalt();
                    break;
                case 'getCipherKey':
                    $this->getCipherKey();
                    break;
            }
        }

        die();


        
        //$this->cipherVer = $decryptText ? CipherVersion::getCipherVersion($decryptText) : $this->cipherVer;
        #Гаврилов
        //ПРОВЕРКА СУЩЕСТВУЕТ ЛИ СКРИПТ С ТАКОЙ ВЕРСИЕЙ. еСЛИ НЕТ - СООБЩЕНИЕ ОБ ОШИБКЕ РАЗРАБАМ И СООБЩЕНИЕ БЕЗ УКАЗАНИЯ НА ВЕРСИЮ ДЛЯ ПОЛЬЗОВАТЕЛЕЙ
        //require_once ("./cipher_ver" . $this->cipherVer . ".php");

        //$encryptText = $this->getEncryptText();

        
    }

    #Гаврилов
    //ПЕРЕДАВАТЬ С ШИФРОВАНИЕМ И ДЕШИФРОВКОЙ ЕЩЕ ОДИН ПАРАМЕТР "ДЕМОНСТРАЦИОННАЯ СТРАНИЦА": FALSE\TRUE. ПЕРЕДАВАТЬ ЕГО ТОЛЬКО С ДЕМОНСТРАЦИОННОЙ СТРАНИЦЫ. ЕСЛИ ПЕРЕДАЕТСЯ TRUE - ДЕЛАТЬ ПОЛЕ С СОЛЬЮ НЕОБЯЗАТЕЛЬНЫМ ДЛЯ ПЕРЕДАЧИ. МОЖНО ЛИ КАК-ТО ПРОВЕРЯТЬ ОТКУДА ИДЕТ ЗАПРОС ПО IP НАПРИМЕР?, ЧТОБЫ ПОЛЬЗОВАТЕЛЬ НЕ МОГ ПОДСТАВИТЬ ЭТОТ ЗАГОЛОВОК, ОТПРАВЛЯЯ СВОЙ ЗАПРОС СО СВОЕГО СЕРВЕРА
    


    /**
     * Метод проводит валидацию текста перед его шифрованием, например, на наличие нераспознанных символов
     *
     * @param array $fakeLength фейковая длина
     * @return bool
     */
    private function fakeLengthValidation(array $fakeLength): bool
    {
        return $fakeLength[0] < 900;
    }


    /**
     * Метод валидации api роута
     *
     * @param string $routeName имя проверяеямого роута
     * @return array
     */
    private function checkRoute($routeName)
    {
        $checkRouteArr = [
            'result' => true, 
            'errorMsg' => null,
            'responseCode' => 200,
        ];
        //$rqstParams = json_decode(file_get_contents('php://input'));
        if (array_key_exists($routeName, $this->routes) !== false) {
            if ($_SERVER['REQUEST_METHOD'] !== $this->routes[$routeName]['method']) {
                $checkRouteArr['result'] = false;
                $checkRouteArr['errorMsg'] = 'method not supported for endpoint';
                $checkRouteArr['responseCode'] = 405;

                return $checkRouteArr;
            } else if (!empty($this->routes[$routeName]['methodParams'])) {
                foreach ($this->routes[$routeName]['methodParams'] as $paramName => $paramData){
                    // var_dump($paramName);
                    // var_dump($this->rqstParams);
                    //Если параметр обязательный
                    if ($paramData['important'] === true && (array_key_exists($paramName, $this->rqstParams) === false || !$this->rqstParams[$paramName])) {
                    //if ($paramData['important'] === true) {
                        //Если обязательный параметр отсутствует в запросе
                        // if (array_key_exists($paramName, $this->rqstParams) === false || !$this->rqstParams[$paramName]) {
                            //Если отсутствующее обязательное поле - соль для шифра, она может отсутствовать, если запрос пришел с того же сервера (с демонстрационной страницы)
                            if ($paramName == 'cipherSalt' && $_SERVER['REMOTE_ADDR'] === $_SERVER['SERVER_ADDR']) {
                                continue;
                            } else {
                                $checkRouteArr['result'] = false;
                                $checkRouteArr['errorMsg'] = "a required field <$paramName> is missing";
                                $checkRouteArr['responseCode'] = 400;

                                return $checkRouteArr;
                            }
                    }
                            #Гаврилов
                            //ПОПРОБУЙ ОТПРАВИТЬ ЕНДПОИНТ С КОМПА, НЕ С СЕРВЕРА БЕЗ СОЛИ. ЗАПРОС НЕ ДОЛЖЕН ПРОЙТИ
                        //Если в запросе предусмотрено конкретное значение для передаеваемого параметра запроса, происходит валидация
                        // } else 
                        if (array_key_exists('validation', $paramData) !== false) {
                            //Если для валидации параметра применяется регулярное выражение
                            if ($paramData['validation']['validationRegular']) {
                                if (preg_match($paramData['validation']['validationRegular'], $this->rqstParams[$paramName]) == false) {
                                    $checkRouteArr['result'] = false;
                                    $checkRouteArr['errorMsg'] = "Invalid field format <$paramName>";
                                    $checkRouteArr['responseCode'] = 400;

                                    return $checkRouteArr;
                                }         
                            //Если для валидации применяется метод класса                   
                            } 
                            
                            if ($validationMethod = $paramData['validation']['validationMethod']) {
                                $validationMethodResult = call_user_func(array($this, $validationMethod), [$this->rqstParams[$paramName]]);
                                if (!$validationMethodResult) {
                                    $checkRouteArr['result'] = false;
                                    $checkRouteArr['errorMsg'] = "Invalid field format <$paramName>";
                                    $checkRouteArr['responseCode'] = 400;

                                    return $checkRouteArr;
                                }
                            }
                            
                        }
                    //}
                    
                }
            }
        } else {
            $checkRouteArr['result'] = false;
            $checkRouteArr['errorMsg'] = 'endpoint not found';
            $checkRouteArr['responseCode'] = 404;
        }

        return $checkRouteArr;
    }


    /**
     * Метод формирует соль для шифра на основе уникальных для момента формирования параметрах
     *
     * @return void
     */
    public function getCipherKey()
    {
        require_once ("./versions/" . $this->cipherVer . "/cipher.php");
        $firstKeyArr = $secondKeyArr = preg_split('//u', SimpleCipher::getFakeCipherKey(), -1, PREG_SPLIT_NO_EMPTY);
        shuffle($firstKeyArr);
        shuffle($secondKeyArr);
        $resultKey = implode('', $firstKeyArr) . implode('', $secondKeyArr);

        // var_dump($resultKey);

        #Гаврилов
        //ПЕРЕПИШИ ВСЕ МЕТОДЫ РАЗБИТИЯ СТРОКИ НА МАССИВ ПО СИМВОЛАМ НА mb_str_split с самописного решения, так как новая версия php

        $this->returnResponse(
            ['cipherKey' => $resultKey]
        );
    }


    /**
     * Метод формирует соль для шифра на основе уникальных для момента формирования параметрах
     *
     * @return void
     */
    public function createCipherSalt()
    {
        $cipherSalt = base64_encode(hash('whirlpool', time() . $_SERVER['REMOTE_ADDR']));
        $cipherSalt = str_replace('=', '', $cipherSalt);

        $this->returnResponse(
            ['cipherSalt' => $cipherSalt]
        );
    }


    #Гаврилов
    //ПРОВЕРЬ КАЖДУЮ ПРОВЕРКУ ВНУТРИ МЕТОДА
    /**
     * Метод валидации ключа шифра
     *
     * @param string $cipherKey переданный ключ шифра
     * @return boolean
     */
    private function validateCipherKey(string $cipherKey)
    {
        $cipherLengh = count(preg_split('//u', SimpleCipher::getFakeCipherKey(), -1, PREG_SPLIT_NO_EMPTY));
        $cipherKey_first = mb_substr($cipherKey, 0, $cipherLengh);
        $cipherKey_second = mb_substr($cipherKey, $cipherLengh);
        $cipherKey_first_arr = preg_split('//u', $cipherKey_first, -1, PREG_SPLIT_NO_EMPTY);
        $cipherKey_second_arr = preg_split('//u', $cipherKey_second, -1, PREG_SPLIT_NO_EMPTY);

        // if (!$this->checkInvalidChars($cipherKey_first, SimpleCipher::getFakeCipherKey()) || !$this->checkInvalidChars($cipherKey_second, SimpleCipher::getFakeCipherKey())) {
        //     // var_dump('da1');
        //     return false;
        // }

        //Каждый ключ должен быть конкретной длины (как фейковый ключ, сформированный на основании одного из реальных ключей)
        if (count(array_unique($cipherKey_first_arr)) !== $cipherLengh || mb_strlen($cipherKey) !== $cipherLengh * 2 || count(array_unique($cipherKey_second_arr)) !== $cipherLengh) {
            return false;
        }

        //Правильно ли я понимаю, что с помощью base_64 можно закодировать любой символ в сочетание латинских букв и чисел? спецсимвол, например ‰ или китайский иероглиф или эмодзи?

        //Проверяем расстояние Хэмминга. Какое количество символов одного массива символов ключа находится на тех же позициях, что и символы второго массива символов ключа
        $HammingDistance = 0;
        foreach ($cipherKey_first_arr as $symbKey => $symb){
            if (array_search($symb, $cipherKey_second_arr) === $symbKey) {
                $HammingDistance++;
            }
        }

        if ($HammingDistance / $cipherLengh > 0.3) {
            // var_dump('da3');
            return false;
        }

        //Проверяем пересечение биграмм ключей шифра. Неважно на какой позиции они находятся в двух ключах, но если биграмм больше 25% - считаем, что ключи недостаточно отличаются
        $bigrammCipherKeyArr = [];
        $n = 0;
        while ($n < $cipherLengh) {
            if (mb_strstr($cipherKey_second, mb_substr($cipherKey_first, $n, 2)) !== false) {
                $bigrammCipherKeyArr[] = mb_substr($cipherKey_first, $n, 2);
            }
            $n = $n + 2;
        }
 
        if (count($bigrammCipherKeyArr) / ($cipherLengh / 2) > 0.25) {
            // var_dump('da4');
            return false;
        }

        return true;
    }
    

    //TODO
    //ПЕРЕНЕСТИ КОЛИЧЕСТВО ИТЕРАЦИЙ ВЫЗОВА МЕТОДА ШИФРОВАНИЯ В JS. НА БЭКЕ НЕ ОБРАБАТЫВАЕМ ЭТОТ ПАРАМЕТР, ТОЛЬКО НА СТОРОНЕ jS ЦИКЛОМ ОБРАЩАЕМСЯ К РОУТУ GETECNRYPTtEXT

     /**
     * Метод получения зашифрованного сообщения
     *
     * @return array
     */
    public function getEncryptText(string $encryptText, int $fakeLength, ?string $userCipherKey = null): void
    {
        require_once ("./versions/" . $this->cipherVer . "/cipher.php");
        $resultCipher = null;
        #Гаврилов
        //ВЫНЕСТИ СОЗДАНИЕ ЭКЗЕМЛПРЯА КЛАССА ШИФРОВАНИЯ В КОНСТРУКТОР ВСЕ ТАКИ
        $this->cipherObj = new SimpleCipher($encryptText, $this->cipherSalt);

        if ($userCipherKey) {
            if (!$this->validateCipherKey($userCipherKey)) {
                $this->returnResponse([
                    'encryptText' => null,
                //При получении этой ошибки на фронте дополнительно выводи текст "используйте специальные инструменты для генерации надежного ключа"
                ], 400, 'Invalid type of cipher key');

                return;
            }
        }
        


        // if (!$this->checkInvalidChars($encryptText, SimpleCipher::getFakeCipherKey())) {
        //     $this->returnResponse([
        //         'encryptText' => null,
        //     ], 400, 'The text contains invalid characters');

        //     return;
        // }

        $resultCipher = $this->cipherObj->encryptText($fakeLength, $userCipherKey);
        
        $this->returnResponse([
            'encryptText' => $resultCipher,
        ]);
    }


    #Гаврилов
    //НА ДЕМОНСТРАЦИОННОЙ СТРАНИЦЕ ДОЛЖЕН БЫТЬ АЛЕРТ ЕСЛИ ВАЛИДАЦИЯ НЕ ПРОШЛА
    /**
     * Метод валидирует шифруемый текст на наличие нешифруемых символов, проводя поиск по ним в одном из ключей шифра текущей версии
     *
     * @param string $text шифруемый текст
     * @param string $fakeCipherKey фейковый ключ актуальной версии шифра, сформированный на основе одного из реальных ключей шифра
     * @return bool
     */
    // private function checkInvalidChars(string $text, string $fakeCipherKey)
    // {
    //     $cipherValidSymbArr = preg_split('//u', $fakeCipherKey, -1, PREG_SPLIT_NO_EMPTY);
    //     $encryptUniqueSymbArr = array_unique(preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY));
    //     $getInvalidSymbols = empty(array_diff($encryptUniqueSymbArr, $cipherValidSymbArr));

    //     return $getInvalidSymbols;
    // }

    #Гаврилов
    //ПОПРОБОВАТЬ ДОБАВИТЬ ПЕРЕНОС СТРОКИ И ПЕРЕНОС КАРЕТКИ В СИМВОЛЫ ШИФРА?

    #Гаврилов
    //ДОБАВЬ ИКОНКУ КОПИРОВАНИЯ СОЛИ И КЛЮЧА В СОСЕДНИЙ ИНПУТ ИЗ ИНПУТА, ГДЕ ОН БЫЛ СГЕНЕРИРОВАН

    /**
     * Метод получения зашифрованного сообщения
     *
     * @return string
     */
    public function getDecryptText(string $decryptText, ?string $userCipherKey = null): void
    {
        // var_dump('da');
        $this->decryptText = $decryptText;
        
        $this->cipherVer = CipherVersion::getVersion($this->decryptText);
        #Гаврилов
        //ПРОВЕРКА СУЩЕСТВУЕТ ЛИ ФАЙЛЫ
        require_once ("./versions/" . $this->cipherVer . "/cipher.php");
        $this->cipherObj = new SimpleCipher($this->decryptText, $this->cipherSalt);
        $testCipher =  $this->cipherObj->decryptText($userCipherKey);

        // var_dump($testCipher);

        $this->returnResponse(
            ['decryptText' => $testCipher]
        );

    }


    public function myErrorHandler($errno, $errstr, $errfile, $errline)
    {
    $errArr = [
        1 => 'E_ERROR',
        2 => 'E_WARNING',
        4 => 'E_PARSE',
        8 => 'E_NOTICE',
        16 => 'E_CORE_ERROR',
        32 => 'E_CORE_WARNING',
        64 => 'E_COMPILE_ERROR',
        128 => 'E_COMPILE_WARNING',
        256 => 'E_USER_ERROR',
        512 => 'E_USER_WARNING',
        1024 => 'E_USER_NOTICE',
        2048 => 'E_STRICT',
    ];

    #Гаврилов
    //КОГДА ПЕРЕДЕЛАЕШЬ СТРУКТУРУ НА ПАПКИ/ФАЙЛ ВЕРСИИ, А НЕ КАК СЕЙЧАС ФАЙЛ_ВЕРСИЯ.PHP, ПОСМОТРИ БУДЕТ ЛИ КОРРЕКТНО В ЛОГ ЗАПИСЫВАТЬСЯ НАЗВАНИЕ ФАЙЛА, ЧТОБЫ ПОНИМАТЬ В КАКОМ СКРИПТЕ ПРОИЗОШЛА ОШИБКА
    $errorLogMsg = $errArr[$errno] . " $errstr в файле $errfile на $errline";

    //Если ошибка возникает при дешифровке текста - возвращаем ранломный запутанный текст будто все прошло "по плану", но алгоритм не расшифровал кривое сообщение
    if ($this->action == 'getDecryptText') {
        $this->returnResponse([
            'decryptText' => $this->getRandomText($this->rqstParams['text'])
        ], 200);
        #Гаврилов
        //если ошибка некритичная - не останавливай рабоут скрипта, не выкидывай 500 ошибку
    } else {
        $this->returnResponse([
            // 'errMsg' => 'Возникла непредвиденная ошибкаA, попробуйте еще раз'
        ], 500, $errorLogMsg);
        #Гаврилов
        //ЧТО ВОЗВРАЩАТЬ ЕСЛИ ПРИ ШИФРОВАНИИ ОШИБКИ? ПРОСТО ТЕКСТ ОШИБКИ И КОД
    }

    $logErrMsg = (new DateTime())->format('Y-m-d h:i:s') . " $errorLogMsg \n";
    file_put_contents("./log/err.log", $logErrMsg, FILE_APPEND);

    exit(1);

    /* Не запускаем внутренний обработчик ошибок PHP */
    return true;
}

    private function returnResponse($responseObj, $responseCode = 200, $errMsg = null)
    {
        header('Content-Type: application/json; charset=utf-8');
        //var_dump($responseObj);
        if ($errMsg) {
            #Гаврилов
            //НУЖНО ПЕРЕПИСАТЬ ВСЮ ОБРАБОТКУ ОШИБОК НА ЧТЕНИЕ ЗАГОЛОВКА X-ERROR-MSG, А НЕ КЛЮЧ ОБЪЕКТА ERR.MSG. В js скрипте тоже понадобится скорректировать логику
            header("X-Error-Msg:" . $errMsg);
        }
        
        http_response_code($responseCode);
        echo json_encode($responseObj);
    }

    /**
     * Метод перемешивает полученные текст и возвращает его запутанную версию
     *
     * @param string $text текст для перемешивания
     * @return string
     */
    private function getRandomText($text)
    {
        $fakeText = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        shuffle($fakeText);

        return implode('', $fakeText);
    }


}

#Гаврилов
//ПЕРЕПИШИ ТАКИМ ОБРАЗОМ, ЧТОБЫ ПОД КАЖДУЮ ВЕРСИЮ БЫЛА СВОЯ ПАПКА, А НЕ ВЕРСИЯ В НАЗВАНИИ СКРИПТА

new CipherController($_GET['endpoint']);

// var_dump($_GET);
// var_dump(json_decode(file_get_contents('php://input')));
die();

// #Гаврилов
// //ПРОВЕРКА ПЕРЕДАНЫ ЛИ НУЖНЫЕ ПАРАМЕТРЫ И ИХ ВАЛИДАЦИЯ (ДЛИНА, ТИП)
// $rqstParams = json_decode(file_get_contents('php://input'));


// switch ($rqstParams->action) {
//     case 'encrypt':
//         (new CipherController($_GET['endpoint'], $rqstParams->cipherSalt))->getEncryptText($rqstParams->encryptText, $rqstParams->fakeLength);
//         break;
//     case 'decrypt':
//         (new CipherController($_GET['endpoint'], $rqstParams->cipherSalt))->getDecryptText($rqstParams->decryptText);
//         break;
//     case 'getSalt':
//         (new CipherController($_GET['endpoint']))->createCipherSalt();
//         break;
// }
