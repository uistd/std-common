<?php

namespace FFan\Std\Common;

require_once '../vendor/autoload.php';

echo Utils::joinPath('/aaa/bbb', '/ccc/ddd/ee'), PHP_EOL;
echo Utils::joinPath('/aaa/bbb/', 'ccc/ddd/ee/'), PHP_EOL;
echo Utils::joinPath('/aaa/bbb/', '/ccc/ddd/ee/ '), PHP_EOL;
echo Utils::joinFilePath('/aaa/bbb', ' /ccc/ddd/ee/log.php'), PHP_EOL;
echo Utils::joinFilePath('/aaa/bbb', 'ccc/ddd/ee/log.php'), PHP_EOL;
echo Utils::joinFilePath('/aaa/bbb/', '/ccc/ddd/ee/log.php'), PHP_EOL;

echo Utils::fixWithRuntimePath('/aaa/bbbb'), PHP_EOL;
echo Utils::fixWithRuntimePath('aaa/bbbb'), PHP_EOL;
echo Utils::fixWithRuntimePath(' aaa/bbbb '), PHP_EOL;
try{
    Utils::pathWriteCheck('testdir', false);
} catch( \RuntimeException $exp){
    echo $exp->getMessage(), PHP_EOL;
}

try{
    Utils::pathWriteCheck('testdir');
    rmdir('testdir');
} catch( \RuntimeException $exp){
    echo $exp->getMessage(), PHP_EOL;
}

var_dump(Utils::sizeFormat(0));

var_dump(Utils::sizeFormat(100));

var_dump(Utils::sizeFormat(1024));

var_dump(Utils::sizeFormat(1224));

var_dump(Utils::sizeFormat(1024*1024));

var_dump(Utils::sizeFormat(1024*1024 + 200*1024));

var_dump(Utils::sizeFormat(1024*1024*1024));

var_dump(Utils::sizeFormat(1024*1024*1024*3 + 1024 * 1024 * 300, 3, 2));

var_dump(Utils::sizeFormat(1024*1024*1024*1024 * 5));

var_dump(Utils::sizeFormat(1024*1024*1024*1024 + 1024 * 1024 * 1024 * 20));

var_dump(Utils::sizeFormat(1024*1024*1024*1024*1024 * 3));

var_dump(Utils::fileSizeToByte('3Kb'));

var_dump(Utils::fileSizeToByte('3mb'));

var_dump(Utils::fileSizeToByte('3g'));

var_dump(Utils::fileSizeToByte('3t'));

var_dump(Utils::fileSizeToByte('3p'));

var_dump(Utils::fileSizeToByte('3byte'));

var_dump(Utils::delDir(__DIR__ .'/del_test'));

var_dump(Utils::copyDir(__DIR__, __DIR__));

var_dump(Utils::copyDir(__DIR__, __DIR__ .'/test'));

var_dump(Utils::copyDir(__DIR__, dirname(__DIR__) .'/test2'));