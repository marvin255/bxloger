<?php

use marvin255\bxloger\Autoloader;

require_once __DIR__ . '/lib/Autoloader.php';

Autoloader::register('\\marvin255\\bxloger', __DIR__ . '/lib');
Autoloader::register('\\Psr\\Log', __DIR__ . '/psr_log');
