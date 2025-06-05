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
     * Конструктор
     *
     * @param string $cipherSalt Соль для шифрования
     * @param string $encryptText Текст для шифрования
     * @param integer $fakeLength Желаемая длина шифра
     * @param string $decryptText Текст для расшифрования
     */
    public function __construct(?string $cipherSalt = null)
    {
        $this->cipherSalt = $cipherSalt;

        require_once ("./CipherVersion.php");
        //$this->cipherVer = $decryptText ? CipherVersion::getCipherVersion($decryptText) : $this->cipherVer;
        #Гаврилов
        //ПРОВЕРКА СУЩЕСТВУЕТ ЛИ СКРИПТ С ТАКОЙ ВЕРСИЕЙ. еСЛИ НЕТ - СООБЩЕНИЕ ОБ ОШИБКЕ РАЗРАБАМ И СООБЩЕНИЕ БЕЗ УКАЗАНИЯ НА ВЕРСИЮ ДЛЯ ПОЛЬЗОВАТЕЛЕЙ
        //require_once ("./cipher_ver" . $this->cipherVer . ".php");

        //$encryptText = $this->getEncryptText();
    }


    /**
     * Метод формирует соль для шифра на основе уникальных для момента формирования параметрах
     *
     * @return string
     */
    public function createCipherSalt()
    {
        $cipherSalt = base64_encode(hash('whirlpool', $_SERVER['REMOTE_ADDR'] . time()));
        $cipherSalt = str_replace('=', '', $cipherSalt);

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
        $this->cipherVer = $this->cipherVer;
        require_once ("./cipher_ver" . $this->cipherVer . ".php");
        $testCipher = (new SimpleCipher($encryptText, $this->cipherSalt))->encryptText($fakeLength);

        return $testCipher;
    }


    /**
     * Метод получения зашифрованного сообщения
     *
     * @return string
     */
    public function getDecryptText(string $decryptText): string
    {
        $this->cipherVer = CipherVersion::getCipherVersion($decryptText);
        require_once ("./cipher_ver" . $this->cipherVer . ".php");
        $testCipher = (new SimpleCipher($decryptText, $this->cipherSalt))->decryptText();

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
        $resultCipherArr[] = (new CipherController($rqstParams->cipherSalt))->getEncryptText($rqstParams->encryptText, $rqstParams->fakeLength);
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
    $resultDecryptText = (new CipherController($rqstParams->cipherSalt))->getDecryptText($rqstParams->decryptText);
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







//NTI0M2FmNWEwOGU3NDY2YTc5MDFiMTEyOTdlNmY1NTQzY2Q4MzYzMmJkMTNiODRjOGI2YjY4NjEwYjNmM2NjZGJhOWY1NjRiYmU3OTEzZjdhZmIzNDExM2QwZTgwMjhkZDE1OTIwMDlhY2YxZjIxMDljNDA4MTllZjc3MmEzOTI
/*YTVhYmU2NjY0YmJlNjQzOWNiZWFhNGM3NmMxYTE5YTc5ZmI1OWFhZWQ1YTA5YzM2ZDI1NTQzMzc2M2RlZTI2NGRhMmJkYjkwYTc2MjI0NjJiYjRhNzU2YTM0YzdlYTdjN2VjMWU3MzA1Zjg5NmNlMmNmODgyZmIwMzliOTg2ZjY
MWY3NmUyNDU5MTA1ZGI2YmU0OTgwNTJmMDQ1OTI4MjY0NTI4ZTZmNjBlZmJlYWViNDI5Y2Y4NjZiY2Y2MmNhNzI1YjJiMzk3YWEwN2JlMzYwY2I1YzlhZDUwY2YyMjUxNDBlY2RkNzc1NTE2MjE2ZjA4MDQwY2E0ZDRkYmNhYWM
*/