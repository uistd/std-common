<?php

namespace FFan\Std\Common;

require_once '../vendor/autoload.php';

Config::addArray(array('a' => 1, 'b' => 2));
Config::addArray(array('a' => 3, 'b' => 4));

var_dump(Config::get('a'));
var_dump(Config::get('b'));

Config::add('arr', array('key1' => 'a1', 'key2' => 'a2'));

Config::addArray(array(
    'a' => 4,
    'b' => 5,
    'arr' => array(
        'key3' => 'a3',
        'key4' => 'a4'
    )
), true);

var_dump(Config::get('a'));
var_dump(Config::get('b'));

print_r(Config::get('arr'));

Config::addArray(array(
    'arr' => array(
        'key5' => 'a5',
        'key6' => 'a6'
    )
));

print_r(Config::get('arr'));

Config::add('c', array('key1' => 'value1', 'key2' => 'value2'));
var_dump(Config::get('c'));
Config::add('c', array('key3' => 'value3', 'key4' => 'value4'));
var_dump(Config::get('c'));
Config::add('c', array('key5' => 'value5', 'key6' => 'value6'), false);
var_dump(Config::get('c'));

var_dump(Config::get('d', 'Init value'));

var_dump(Config::get('test'));
var_dump(Config::get('test.aaa'));
var_dump(Config::get('test.aaa.bbb'));
var_dump(Config::getInt('test.aaa.bbb'));
var_dump(Config::getString('test.aaa.bbb'));
var_dump(Config::getString('test.aaa.bbb.mmm'));

Config::add('env', 'uat');
var_dump(Config::load('conf.php'));
Config::add('env', '');
var_dump(Config::load('conf.php'));
var_dump(Config::load('conf.php', 'prod'));
