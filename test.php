<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$file = file_get_contents("./test.txt");

$fileArr = explode("\n", $file);

var_dump(count($fileArr));
// var_dump(strlen(implode('', $fileArr)));

$uniqueArr = array_unique($fileArr);

//Преобразуем массив в единую строку, далее трансформируем в байты и помещаем в файл .bin
// $resultBitStr = implode('', $uniqueArr);
// $resultBitStr = mb_convert_encoding($resultBitStr, 'CP1251', 'UTF-8');
// $bytes = unpack("C*", $resultBitStr);
// file_put_contents('./ent/testEntCipher.bin', $bytes);
//Чтобы получить результаты теста, запускаем из командной строки cmd команду ent.exe testEntCipher.bin > result.txt. В итоге в папке ent в файле result.exe будут результаты теста


var_dump(count($uniqueArr));

