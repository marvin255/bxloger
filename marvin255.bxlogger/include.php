<?php

use marvin255\bxlogger\Autoloader;
use marvin255\bxlogger\Log;
use marvin255\bxlogger\log\EventLog;
use marvin255\bxlogger\log\QueuedLoggerInterface;
use Bitrix\Main\Event;
use Bitrix\Main\EventManager;

require_once __DIR__ . '/lib/Autoloader.php';

//подключаем свой psr совместимый автозагрузчик
Autoloader::register('\\Psr\\Log', __DIR__ . '/psr_log');
Autoloader::register('\\marvin255\\bxlogger', __DIR__ . '/lib');

//событие для того, чтобы другие модули могли подключить свой логер
$event = new Event('marvin255.bxlogger', 'createLogger');
$event->send();

//если в событии не подключен логер, то инстантим по умолчанию
if (!$customLogger = $event->getParameter('logger')) {
    $customLogger = new EventLog;
}

//если был определн логер с очередью запросов, то нужно записать очереь по событию
if ($customLogger instanceof QueuedLoggerInterface) {
    EventManager::getInstance()->addEventHandler(
        'main',
        'OnAfterEpilog',
        ['\\marvin255\\bxlogger\\EventManager', 'onAfterEpilog']
    );
}

//задаем логер в контейнер для передачи в приложение
Log::set($customLogger);
