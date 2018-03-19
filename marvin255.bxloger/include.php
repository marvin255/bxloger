<?php

use marvin255\bxloger\Autoloader;
use marvin255\bxloger\Log;
use marvin255\bxloger\log\EventLog;
use marvin255\bxloger\log\QueuedLoggerInterface;
use Bitrix\Main\Event;
use Bitrix\Main\EventManager;

require_once __DIR__ . '/lib/Autoloader.php';

//подключаем свой psr совместимый автозагрузчик
Autoloader::register('\\Psr\\Log', __DIR__ . '/psr_log');
Autoloader::register('\\marvin255\\bxloger', __DIR__ . '/lib');

//событие для того, чтобы другие модули могли подключить свой логер
$event = new Event('marvin255.bxloger', 'createLoger');
$event->send();

//если в событии не подключен логер, то инстантим по умолчанию
if (!$customLoger = $event->getParameter('loger')) {
    $customLoger = new EventLog;
}

//если был определн логер с очередью запросов, то нужно записать очереь по событию
if ($customLoger instanceof QueuedLoggerInterface) {
    EventManager::getInstance()->addEventHandler(
        'main',
        'OnAfterEpilog',
        ['\\marvin255\\bxloger\\EventManager', 'onAfterEpilog']
    );
}

//задаем логер в контейнер для передачи в приложение
Log::set($customLoger);
