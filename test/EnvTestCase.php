<?php

namespace FFan\Std\Common;

require_once '../vendor/autoload.php';

error_reporting(-1);
var_dump(Env::isDev());
var_dump(Env::isProduct());
var_dump(Env::getEnv());

echo Env::getCharset(), PHP_EOL;
echo Env::getTimezone(), PHP_EOL;
echo Env::getRuntimePath(), PHP_EOL;