<?php
namespace ffan\php\utils;

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
