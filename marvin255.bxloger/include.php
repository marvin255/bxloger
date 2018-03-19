<?php

use marvin255\bxloger\Autoloader;
use marvin255\bxloger\Log;
use marvin255\bxloger\log\EventLog;
use Bitrix\Main\Event;

require_once __DIR__ . '/lib/Autoloader.php';

Autoloader::register('\\Psr\\Log', __DIR__ . '/psr_log');
Autoloader::register('\\marvin255\\bxloger', __DIR__ . '/lib');

$event = new Event('marvin255.bxloger', 'createLoger');
$event->send();

if (!$customLoger = $event->getParameter('loger')) {
    $customLoger = new EventLog;
}

Log::set($customLoger);
