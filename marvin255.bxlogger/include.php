<?php

use marvin255\bxlogger\Autoloader;

require_once __DIR__ . '/lib/Autoloader.php';

//подключаем свой psr совместимый автозагрузчик
Autoloader::register('\\Psr\\Log', __DIR__ . '/psr_log');
Autoloader::register('\\marvin255\\bxlogger', __DIR__ . '/lib');
