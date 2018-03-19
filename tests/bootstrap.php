<?php

$composerAutoloaderPath = dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists($composerAutoloaderPath)) {
    require $composerAutoloaderPath;
} else {
    require __DIR__ . '/../marvin255.bxloger/lib/Autoloader.php';
}

require_once __DIR__ . '/CEventLog.php';
