<?php
namespace ffan\utils;

require_once '../Config.php';

Config::addArray(array('a' => 1, 'b' => 2));
Config::addArray(array('a' => 3, 'b' => 4));

var_dump(Config::get('a'));
var_dump(Config::get('b'));

Config::addArray(array('a' => 4, 'b' => 5), true);

var_dump(Config::get('a'));
var_dump(Config::get('b'));

Config::add('c', array('key1' => 'value1', 'key2' => 'value2'));
var_dump(Config::get('c'));
Config::add('c', array('key3' => 'value3', 'key4' => 'value4'));
var_dump(Config::get('c'));
Config::add('c', array('key5' => 'value5', 'key6' => 'value6'), false);
var_dump(Config::get('c'));

var_dump(Config::get('d', 'Init value'));
