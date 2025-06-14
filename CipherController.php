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
    private $cipherSalt;
    /**
     * @var string шифрование/дешифрование
     */
    private $action;
    /**
     * @var string текст для шифрования
     */
    private $encryptText;
    /**
     * @var string текст для дешифрования
     */
    private $decryptText;
    /**
     * @var array массив параметров запроса
     */
    private $rqstParams;
    /**
     * @var array $routes списки доступных роутов api
     * TODO
     * ЗДЕСЬ ТАК ЖЕ ДОЛЖНЫ БЫТЬ ПРОПИСАНЫ ОБЯЗАТЕЛЬНЫЕ И НЕ ОБЯЗАТЕЛЬНЫЕ ПЕРЕДАВАЕМЫЕ POST АРГУМЕНТЫ И ПАРАМЕТРЫ ИХ ВАЛИДАЦИИ
     */
    private $routes = [
        'getEncryptText' => [
            // 'methodName' => 'getEncryptText',
            'method' => 'POST',
            'methodParams' => [
                'encryptText' => [
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
                        'validationMethod' => 'encryptTextValidation',
                    ]
                ],
            ]
        ],
        'getDecryptText' => [
            //'methodName' => 'getDecryptText',
            'method' => 'POST',
            'methodParams' => [
                'decryptText' => [
                    'important' => true,
                ],
            ]
        ],
        'createCipherSalt' => [
            //'methodName' => 'createCipherSalt',
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
        $this->action = $action;
        $this->rqstParams = json_decode(file_get_contents('php://input'), true);
        $this->cipherSalt = array_key_exists('cipherSalt', $this->rqstParams) ? $this->rqstParams['cipherSalt'] : null;

        $checkEndpoint = $this->checkRoute($this->action);
        if (!$checkEndpoint['result']) {
            $responseObj = [
                'errorMsg' => $checkEndpoint['errorMsg']
            ];
            $this->returnResponse($responseObj, $checkEndpoint['responseCode']);
        } else {
            call_user_func(array($this, $this->action), $this->rqstParams['encryptText'], (array_key_exists('fakeLength', $this->rqstParams) !== false ? $this->rqstParams['fakeLength'] : 0));
        }

        var_dump('here');
        die();


        require_once ("./CipherVersion.php");
        //$this->cipherVer = $decryptText ? CipherVersion::getCipherVersion($decryptText) : $this->cipherVer;
        #Гаврилов
        //ПРОВЕРКА СУЩЕСТВУЕТ ЛИ СКРИПТ С ТАКОЙ ВЕРСИЕЙ. еСЛИ НЕТ - СООБЩЕНИЕ ОБ ОШИБКЕ РАЗРАБАМ И СООБЩЕНИЕ БЕЗ УКАЗАНИЯ НА ВЕРСИЮ ДЛЯ ПОЛЬЗОВАТЕЛЕЙ
        //require_once ("./cipher_ver" . $this->cipherVer . ".php");

        //$encryptText = $this->getEncryptText();

        set_error_handler([$this, "myErrorHandler"]);
    }


    /**
     * Метод проводит валидацию текста перед его шифрованием, например, на наличие нераспознанных символов
     *
     * @param string $encryptText текст для шифрования
     * @return bool
     */
    private function encryptTextValidation($encryptText)
    {
        return false;
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
                $checkRouteArr['errorMsg'] = 'Метод не поддерживается для этого ендпоинта';
                $checkRouteArr['responseCode'] = 405;

                return $checkRouteArr;
            } else if (!empty($this->routes[$routeName]['methodParams'])) {
                foreach ($this->routes[$routeName]['methodParams'] as $paramName => $paramData){
                    //Если параметр обязательный
                    if ($paramData['important'] === true) {
                        //Если обязательный параметр отсутствует в запросе
                        if (array_key_exists($paramName, $this->rqstParams) === false) {
                            $checkRouteArr['result'] = false;
                            $checkRouteArr['errorMsg'] = "Обязательный параметр $paramName не найден";
                            $checkRouteArr['responseCode'] = 400;

                            return $checkRouteArr;
                        //Если в запросе предусмотрено конкретное значение для передаеваемого параметра запроса, происходит валидация по регулярному значению 
                        } else if ($paramData['validation']) {
                            //Если для валидации параметра применяется регулярное выражение
                            if ($paramData['validation']['validationRegular']) {
                                if (preg_match($paramData['validation']['validationRegular'], $this->rqstParams[$paramName]) === false) {
                                    $checkRouteArr['result'] = false;
                                    $checkRouteArr['errorMsg'] = "Некорректный формат параметра $paramName";
                                    $checkRouteArr['responseCode'] = 400;

                                    return $checkRouteArr;
                                }         
                            //Если для валидации применяется метод класса                   
                            } else if ($validationMethod = $paramData['validation']['validationMethod']) {
                                $validationMethodResult = call_user_func(array($this, $validationMethod), [$this->rqstParams[$paramName]]);
                                if (!$validationMethodResult) {
                                    $checkRouteArr['result'] = false;
                                    $checkRouteArr['errorMsg'] = "Некорректный формат параметра $paramName";
                                    $checkRouteArr['responseCode'] = 400;

                                    return $checkRouteArr;
                                }
                            }
                            
                        }
                    }
                    
                }
            }
        } else {
            $checkRouteArr['result'] = false;
            $checkRouteArr['errorMsg'] = 'Ендпоинт не найден';
            $checkRouteArr['responseCode'] = 404;
        }

        return $checkRouteArr;
    }


    #Гаврилов
    //для апи - ВОЩВРАЩАЙ ОШИБКУ, ЕСЛИ РОУТ НЕ НАЙДЕН

    /**
     * Метод формирует соль для шифра на основе уникальных для момента формирования параметрах
     *
     * @return string
     */
    public function createCipherSalt()
    {
        $cipherSalt = base64_encode(hash('whirlpool', time() . $_SERVER['REMOTE_ADDR']));
        $cipherSalt = str_replace('=', '', $cipherSalt);

        $this->returnResponse([
            'cipherSalt' => $cipherSalt]
        );
    }


    /**
     * 
     * REQUEST_TIME_FLOAT
     * REMOTE_ADDR
     * time()
     */

    //TODO
    //ПЕРЕНЕСТИ КОЛИЧЕСТВО ИТЕРАЦИЙ ВЫЗОВА МЕТОДА ШИФРОВАНИЯ В JS. НА БЭКЕ НЕ ОБРАБАТЫВАЕМ ЭТОТ ПАРАМЕТР, ТОЛЬКО НА СТОРОНЕ jS ЦИКЛОМ ОБРАЩАЕМСЯ К РОУТУ GETECNRYPTtEXT

     /**
     * Метод получения зашифрованного сообщения
     *
     * @return array
     */
    public function getEncryptText(string $encryptText, int $fakeLength = 50): void
    {
        $this->encryptText = $encryptText;
        require_once ("./cipher_ver" . $this->cipherVer . ".php");
        // $this->cipherVer = $this->cipherVer;
        $resultCipher = null;
        //$n = 1;
        //while ($n <= $cipherCount) {
            // $resultCipherArr[] = (new CipherController($rqstParams->action, $rqstParams->cipherSalt))->getEncryptText($rqstParams->encryptText, $rqstParams->fakeLength);
            $resultCipher = (new SimpleCipher($encryptText, $this->cipherSalt))->encryptText($this->rqstParams['fakeLength']);
            //$n++;
            // var_dump($resultCipherArr);
        //}
        
        $this->returnResponse([
            'cipherArr' => $resultCipher,
        ]);

        //return $$resultCipherArr;
    }


    /**
     * Метод получения зашифрованного сообщения
     *
     * @return string
     */
    public function getDecryptText(string $decryptText): void
    {
        $this->decryptText = $decryptText;
        $this->cipherVer = CipherVersion::getCipherVersion($decryptText);
        require_once ("./cipher_ver" . $this->cipherVer . ".php");
        $testCipher = (new SimpleCipher($decryptText, $this->cipherSalt))->decryptText();

        $this->returnResponse([
            'decryptText' => $testCipher,
        ]);
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

    $errorLogMsg = "Ошибка ошибка: " . $errArr[$errno] . " $errstr в файле $errfile на $errline<br />";

    if ($this->action == 'decrypt') {
        $this->returnResponse([
            'decryptText' => $this->getRandomText($this->decryptText),
            'errorMsg' => $errorLogMsg
        ], 200);
        
    } else {
        #Гаврилов
        //ЧТО ВОЗВРАЩАТЬ ЕСЛИ ПРИ ШИФРОВАНИИ ОШИБКИ? ПРОСТО ТЕКСТ ОШИБКИ И КОД
    }
    exit(1);

    /* Не запускаем внутренний обработчик ошибок PHP */
    return true;
}

    private function returnResponse($responseObj, $responseCode = 200)
    {
        //var_dump($responseObj);
        header('Content-Type: application/json');
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

#Гаврилов
//ПРОВЕРКА ПЕРЕДАНЫ ЛИ НУЖНЫЕ ПАРАМЕТРЫ И ИХ ВАЛИДАЦИЯ (ДЛИНА, ТИП)
$rqstParams = json_decode(file_get_contents('php://input'));

switch ($rqstParams->action) {
    case 'encrypt':
        (new CipherController($_GET['endpoint'], $rqstParams->cipherSalt))->getEncryptText($rqstParams->encryptText, $rqstParams->fakeLength, $rqstParams->cipherCount);
        break;
    case 'decrypt':
        (new CipherController($_GET['endpoint'], $rqstParams->cipherSalt))->getDecryptText($rqstParams->decryptText);
        break;
    case 'getSalt':
        (new CipherController($_GET['endpoint']))->createCipherSalt();
        break;
}
