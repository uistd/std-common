<?php

namespace UiStd\Common;

require_once '../vendor/autoload.php';

error_reporting(-1);

echo Time::beauty(strtotime('yesterday')), PHP_EOL;
echo Time::beauty(strtotime('tomorrow')), PHP_EOL;
echo Time::beauty(strtotime("2017-10-1")), PHP_EOL;
echo Time::beauty(strtotime('+6 hour')), PHP_EOL;
echo Time::beauty(strtotime('last Monday')), PHP_EOL;
echo Time::beauty(strtotime('now')), PHP_EOL;
echo Time::beauty(time()-180), PHP_EOL;
echo Time::beauty(time()-1), PHP_EOL;
echo Time::beauty(strtotime('2000-1-1')), PHP_EOL;
echo Time::beauty(strtotime('+2 year')), PHP_EOL;