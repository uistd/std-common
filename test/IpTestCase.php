<?php
/**
 * 还没有确定用PHPUnit 还是 Codeception 时间关系，先随便写一点测试代码
 */
namespace ffan\utils;

require_once '../Ip.php';

error_reporting(-1);

echo Ip::get(), PHP_EOL;
echo Ip::getLong(), PHP_EOL;
var_dump(Ip::isInternal(Ip::get()));
var_dump(Ip::isInternal(Ip::getLong()));
var_dump(Ip::isInternal('127.0.0.1'));
var_dump(Ip::isInternal('192.168.1.12'));
var_dump(Ip::isInternal('10.16.135.139'));
var_dump(Ip::isInternal('172.16.213.14'));
var_dump(Ip::isInternal('61.139.2.69'));
