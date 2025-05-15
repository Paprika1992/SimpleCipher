<?php

class CipherController
{
    /**
     * @var int версия алгоритма шифрования. Прописывается последняя версия актуальная (на случай если текст шифруется)
     */
    private $cipherVer = 123;
    /**
     * @var string текст для шифрования
     */
    private $encryptText;
    /**
     * @var string текст для расшифровки
     */
    private $decryptText;
    /**
     * @var int желаемая длина шифра
     */
    private $fakeLength;
    

    
    /**
     * Конструктор
     *
     * @param string $encryptText Текст для шифрования
     * @param integer $fakeLength Желаемая длина шифра
     */
    public function __construct(?int $fakeLength = null, ?string $encryptText = null, ?string $decryptText = null)
    {
        $this->encryptText = $encryptText;
        $this->fakeLength = $fakeLength;
        $this->decryptText = $decryptText;

        require_once ("./CipherVersion.php");
        $this->cipherVer = $decryptText ? CipherVersion::getCipherVersion($decryptText) : $this->cipherVer;
        #Гаврилов
        //ПРОВЕРКА СУЩЕСТВУЕТ ЛИ СКРИПТ С ТАКОЙ ВЕРСИЕЙ. еСЛИ НЕТ - СООБЩЕНИЕ ОБ ОШИБКЕ РАЗРАБАМ И СООБЩЕНИЕ БЕЗ УКАЗАНИЯ НА ВЕРСИЮ ДЛЯ ПОЛЬЗОВАТЕЛЕЙ
        require_once ("./cipher_ver" . $this->cipherVer . ".php");

        //$encryptText = $this->getEncryptText();
    }


    /**
     * Метод получения зашифрованного сообщения
     *
     * @return string
     */
    public function getEncryptText(): string
    {
        $testCipher = (new SimpleCipher($this->encryptText))->encryptText($this->fakeLength);

        return $testCipher;
    }

    /**
     * Метод получения зашифрованного сообщения
     *
     * @return string
     */
    public function getDecryptText(): string
    {
        $testCipher = (new SimpleCipher($this->decryptText))->decryptText();

        return $testCipher;
    }
}

#Гаврилов
//ПРОВЕРКА ПЕРЕДАНЫ ЛИ НУЖНЫЕ ПАРАМЕТРЫ И ИХ ВАЛИДАЦИЯ (ДЛИНА, ТИП)
$rqstParams = json_decode(file_get_contents('php://input'));

//echo json_encode($rqstParams);

if ($rqstParams->action == 'encrypt') {
    //echo json_encode($rqstParams);
    $resultCipherArr = [];
    $n = 1;
    while ($n <= $rqstParams->cipherCount) {
        $resultCipherArr[] = (new CipherController($rqstParams->fakeLength, $rqstParams->encryptText))->getEncryptText();
        $n++;
    }
    //echo json_encode($resultCipherArr);
    $resultCipherObj = [
        'cipherArr' => $resultCipherArr,
        #Гаврилов
        //КОД ОТВЕТА?
        'encryptMsg' => 'Все ок',
    ];
    // $encryptText = (new CipherController('test', 50))->getEncryptText();
    // var_dump($encryptText);

   echo json_encode($resultCipherObj);
} else if ($rqstParams->action == 'decrypt') {
    $resultDecryptText = (new CipherController(null, null, $rqstParams->decryptText))->getDecryptText();
    $resultCipherObj = [
        'decryptText' => $resultDecryptText,
        #Гаврилов
        //КОД ОТВЕТА?
        'decryptMsg' => 'Все ок',
    ];

    echo json_encode($resultCipherObj);
}

