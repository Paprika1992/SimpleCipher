<?php

$file = file_get_contents("./test.txt");

$fileArr = explode("\n", $file);

var_dump(count($fileArr));
var_dump(strlen(implode('', $fileArr)));

$uniqueArr = array_unique($fileArr);

var_dump(count($uniqueArr));

