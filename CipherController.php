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
     * Конструктор
     *
     * @param string $cipherSalt Соль для шифрования
     * @param string $encryptText Текст для шифрования
     * @param integer $fakeLength Желаемая длина шифра
     * @param string $decryptText Текст для расшифрования
     */
    public function __construct(string $action, ?string $cipherSalt = null)
    {
        $this->cipherSalt = $cipherSalt;
        $this->action = $action;

        require_once ("./CipherVersion.php");
        //$this->cipherVer = $decryptText ? CipherVersion::getCipherVersion($decryptText) : $this->cipherVer;
        #Гаврилов
        //ПРОВЕРКА СУЩЕСТВУЕТ ЛИ СКРИПТ С ТАКОЙ ВЕРСИЕЙ. еСЛИ НЕТ - СООБЩЕНИЕ ОБ ОШИБКЕ РАЗРАБАМ И СООБЩЕНИЕ БЕЗ УКАЗАНИЯ НА ВЕРСИЮ ДЛЯ ПОЛЬЗОВАТЕЛЕЙ
        //require_once ("./cipher_ver" . $this->cipherVer . ".php");

        //$encryptText = $this->getEncryptText();

        set_error_handler([$this, "myErrorHandler"]);
    }


    /**
     * Метод формирует соль для шифра на основе уникальных для момента формирования параметрах
     *
     * @return string
     */
    public function createCipherSalt()
    {
        $cipherSalt = base64_encode(hash('whirlpool', time() . $_SERVER['REMOTE_ADDR']));
        $cipherSalt = str_replace('=', '', $cipherSalt);

        $this->returnText([
            'cipherSalt' => $cipherSalt]
        );
        //return $cipherSalt;
    }


    /**
     * 
     * REQUEST_TIME_FLOAT
     * REMOTE_ADDR
     * time()
     */

    /**
     * Метод получения зашифрованного сообщения
     *
     * @return array
     */
    public function getEncryptText(string $encryptText, int $fakeLength = 50, int $cipherCount = 0): void
    {
        $this->encryptText = $encryptText;
        require_once ("./cipher_ver" . $this->cipherVer . ".php");
        // $this->cipherVer = $this->cipherVer;
        $resultCipherArr = [];
        $n = 1;
        while ($n <= $cipherCount) {
            // $resultCipherArr[] = (new CipherController($rqstParams->action, $rqstParams->cipherSalt))->getEncryptText($rqstParams->encryptText, $rqstParams->fakeLength);
            $resultCipherArr[] = (new SimpleCipher($encryptText, $this->cipherSalt))->encryptText($fakeLength);
            $n++;
            // var_dump($resultCipherArr);
        }
        
        $this->returnText([
            'cipherArr' => $resultCipherArr,
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

        $this->returnText([
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
        $this->returnText([
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

    private function returnText($responseObj, $code = 200)
    {
        //var_dump($responseObj);
        header('Content-Type: application/json');
        http_response_code($code);
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



#Гаврилов
//ПРОВЕРКА ПЕРЕДАНЫ ЛИ НУЖНЫЕ ПАРАМЕТРЫ И ИХ ВАЛИДАЦИЯ (ДЛИНА, ТИП)
$rqstParams = json_decode(file_get_contents('php://input'));

switch ($rqstParams->action) {
    case 'encrypt':
        (new CipherController($rqstParams->action, $rqstParams->cipherSalt))->getEncryptText($rqstParams->encryptText, $rqstParams->fakeLength, $rqstParams->cipherCount);
        break;
    case 'decrypt':
        (new CipherController($rqstParams->action, $rqstParams->cipherSalt))->getDecryptText($rqstParams->decryptText);
        break;
    case 'getSalt':
        (new CipherController($rqstParams->action))->createCipherSalt();
        break;
}
