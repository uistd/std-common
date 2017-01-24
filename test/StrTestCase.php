<?php
/**
 * 还没有确定用PHPUnit 还是 Codeception 时间关系，先随便写一点测试代码
 */
namespace ffan\php\utils;

require_once '../Str.php';

error_reporting(-1);

var_dump(Str::isUtf8('这是字符串'));
var_dump(Str::isUtf8('ab中' . pack('ii', 0xffff, 100)));

print_r(Str::dualSplit('a:1,b:2, c:3'));
print_r(Str::dualSplit('a:1,b:2, c:3,d:4:1'));
print_r(Str::dualSplit('a:1,b:2, c:3,e'));
print_r(Str::dualSplit('a=1|b=2', '|', '='));

echo Str::dualJoin(['a' => 1, 'b' => '2']), PHP_EOL;
echo Str::dualJoin(['a' => 1, 'b' => 2, 3]), PHP_EOL;

echo Str::len('all ascii');
echo Str::len('中国');
echo Str::len('I love 中国');

var_dump(Str::split('|a, b, c, d'));
var_dump(Str::split('a| b |c |d', '|'));
var_dump(Str::split('a| b |c |d', '|', 0));
var_dump(Str::split('|a| b |c |d', '|', 0));
var_dump(Str::split('|a| b |c |d', '|', Str::IGNORE_EMPTY));
var_dump(Str::split('|1, 2,3, 4', ',', Str::TRIM | Str::IGNORE_EMPTY | Str::INTVAL));
$str = 'member_view_detail';
echo Str::camelName($str), PHP_EOL;
echo Str::camelName($str, false), PHP_EOL;

$replace_arr = array(
    'name' => 'bluebird',
    'weekday' => 'Friday',
    'time' => ' PM 5:30'
);

echo Str::tplReplace('Welcome {name}, now is {weekday}{time}, Happy weekend.this is a {jok}', $replace_arr), PHP_EOL;
