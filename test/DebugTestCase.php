<?php
/**
 * 还没有确定用PHPUnit 还是 Codeception 时间关系，先随便写一点测试代码
 */
namespace ffan\php\utils;

require_once '../Str.php';
require_once '../Debug.php';
Debug::timerStart();
usleep(10000);
error_reporting(-1);

echo Debug::varFormat(true), PHP_EOL;
echo Debug::varFormat(false), PHP_EOL;
echo Debug::varFormat(null), PHP_EOL;
echo Debug::varFormat(100), PHP_EOL;
echo Debug::varFormat([1, 2, 3]), PHP_EOL;
echo Debug::varFormat(function () {
    return true;
}), PHP_EOL;
echo Debug::varFormat(12.39), PHP_EOL;
echo Debug::varFormat('ab中' . pack('ii', 0xffff, 100)), PHP_EOL;
Debug::timerStart();
usleep(100000);
echo 'use time:' . Debug::timerStop(), PHP_EOL;
echo 'total time:' . Debug::timerStop(), PHP_EOL;

function test_func1($a)
{
    test_func2($a, 1);
}

function test_func2($b, $c){
    test_func3($b, $c);
}

function test_func3($d, $e){
    $a = new test4();
    $a->func4();
}
class test4{
    function func4(){
        echo Debug::codeTrace();
    }
}
test_func1('test');
test_func1($_SERVER);