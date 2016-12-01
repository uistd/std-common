<?php
/**
 * 还没有确定用PHPUnit 还是 Codeception 时间关系，先随便写一点测试代码
 */
namespace ffan\utils;

require_once '../Env.php';

error_reporting(-1);
var_dump(Env::isDev());
var_dump(Env::isTest());
var_dump(Env::isProduct());
Env::setDev();
try {
    Env::setTest();
    echo "Error!!!\n";
} catch (\Exception $err) {
    echo $err->getMessage(), PHP_EOL;
}
var_dump(Env::isDev());
var_dump(Env::isTest());
var_dump(Env::isProduct());
var_dump(Env::get());