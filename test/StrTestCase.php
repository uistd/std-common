<?php
/**
 * 还没有确定用PHPUnit 还是 Codeception 时间关系，先随便写一点测试代码
 */
namespace ffan\utils;

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
