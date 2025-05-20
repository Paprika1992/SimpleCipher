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
    private $encryptSalt;
    
    
    /**
     * Конструктор
     *
     * @param string $encryptSalt Соль для шифрования
     * @param string $encryptText Текст для шифрования
     * @param integer $fakeLength Желаемая длина шифра
     * @param string $decryptText Текст для расшифрования
     */
    public function __construct(?string $encryptSalt = null)
    {
        $this->encryptSalt = $encryptSalt;

        require_once ("./CipherVersion.php");
        $this->cipherVer = $decryptText ? CipherVersion::getCipherVersion($decryptText) : $this->cipherVer;
        #Гаврилов
        //ПРОВЕРКА СУЩЕСТВУЕТ ЛИ СКРИПТ С ТАКОЙ ВЕРСИЕЙ. еСЛИ НЕТ - СООБЩЕНИЕ ОБ ОШИБКЕ РАЗРАБАМ И СООБЩЕНИЕ БЕЗ УКАЗАНИЯ НА ВЕРСИЮ ДЛЯ ПОЛЬЗОВАТЕЛЕЙ
        require_once ("./cipher_ver" . $this->cipherVer . ".php");

        //$encryptText = $this->getEncryptText();
    }


    public function createCipherSalt()
    {
        $cipherSalt = base64_encode(hash('whirlpool', $_SERVER['REQUEST_TIME_FLOAT'] . $_SERVER['REMOTE_ADDR'] . time()));

        return $cipherSalt;
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
     * @return string
     */
    public function getEncryptText(string $encryptText, int $fakeLength = 50): string
    {
        $testCipher = (new SimpleCipher($encryptText, $this->encryptSalt))->encryptText($fakeLength);

        return $testCipher;
    }

    /**
     * Метод получения зашифрованного сообщения
     *
     * @return string
     */
    public function getDecryptText(string $decryptText): string
    {
        $testCipher = (new SimpleCipher($decryptText, $this->encryptSalt))->decryptText();

        return $testCipher;
    }
}

#Гаврилов
//ПЕРЕПИШИ ТАКИМ ОБРАЗОМ, ЧТОБЫ ПОД КАЖДУЮ ВЕРСИЮ БЫЛА СВОЯ ПАПКА, А НЕ ВЕРСИЯ В НАЗВАНИИ СКРИПТА



#Гаврилов
//ПРОВЕРКА ПЕРЕДАНЫ ЛИ НУЖНЫЕ ПАРАМЕТРЫ И ИХ ВАЛИДАЦИЯ (ДЛИНА, ТИП)
$rqstParams = json_decode(file_get_contents('php://input'));

//echo json_encode($rqstParams);

if ($rqstParams->action == 'encrypt') {
    //echo json_encode($rqstParams);
    $resultCipherArr = [];
    $n = 1;
    while ($n <= $rqstParams->cipherCount) {
        $resultCipherArr[] = (new CipherController($rqstParams->encryptSalt))->getEncryptText($rqstParams->encryptText, $rqstParams->fakeLength);
        $n++;
    }
    //echo json_encode($resultCipherArr);
    $resultCipherObj = [
        'cipherArr' => $resultCipherArr,
        #Гаврилов
        //КОД ОТВЕТА?
        'encryptMsg' => 'Все ок',
    ];

   echo json_encode($resultCipherObj);
} else if ($rqstParams->action == 'decrypt') {
    $resultDecryptText = (new CipherController($rqstParams->encryptSalt))->getDecryptText($rqstParams->decryptText);
    $resultCipherObj = [
        'decryptText' => $resultDecryptText,
        #Гаврилов
        //КОД ОТВЕТА?
        'decryptMsg' => 'Все ок',
    ];

    echo json_encode($resultCipherObj);
} else if ($rqstParams->action == 'getSalt') {
    $resultSalt = (new CipherController(null))->createCipherSalt();
    $resultSaltObj = [
        'cipherSalt' => $resultSalt,
        #Гаврилов
        //КОД ОТВЕТА?
        'saltMsg' => 'Все ок',
    ];

    echo json_encode($resultSaltObj);

    #Гаврилов
    //ПЕРЕДЕЛАТЬ БЕСКОНЕЧНЫЕ ELSEIF НА SWITCH CASE

}

