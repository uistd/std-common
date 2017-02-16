<?php
/**
 * 还没有确定用PHPUnit 还是 Codeception 时间关系，先随便写一点测试代码
 */
namespace ffan\php\utils;

require_once '../Env.php';
require_once '../Config.php';

error_reporting(-1);
var_dump(Env::isDev());
var_dump(Env::isTest());
var_dump(Env::isProduct());
var_dump(Env::getEnv());

echo Env::getCharset(), PHP_EOL;
echo Env::getTimezone(), PHP_EOL;
echo Env::getRuntimePath(), PHP_EOL;

var_dump(Env::isAjaxRequest());
