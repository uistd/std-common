<?php
/**
 * 还没有确定用PHPUnit 还是 Codeception 时间关系，先随便写一点测试代码
 */
namespace ffan\php\utils;

require_once '../Ip.php';

error_reporting(-1);

echo Ip::get(), PHP_EOL;
echo Ip::getLong(), PHP_EOL;
$_SERVER['HTTP_X_FORWARDED_FOR'] = '8.8.8.9';
echo Ip::get(), PHP_EOL;
$_SERVER['HTTP_X_FORWARDED_FOR'] = 'unknown, 8.8.8.8';
echo Ip::get(), PHP_EOL;
$_SERVER['HTTP_X_FORWARDED_FOR'] = '61.139.2.59,192.168.1.2';
echo Ip::get(), PHP_EOL;
var_dump(Ip::isInternal(Ip::getLong()));
var_dump(Ip::isInternal('127.0.0.1'));
var_dump(Ip::isInternal('192.168.1.12'));
var_dump(Ip::isInternal('10.16.135.139'));
var_dump(Ip::isInternal('172.16.213.14'));
var_dump(Ip::isInternal('61.139.2.69'));
