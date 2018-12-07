<?php

namespace marvin255\bxlogger;

use marvin255\bxlogger\log\EventLog;
use marvin255\bxlogger\log\QueuedLoggerInterface;
use Bitrix\Main\Event;
use Bitrix\Main\EventManager;
use Psr\Log\LoggerInterface;

/**
 * Класс для логирования данных.
 *
 * Служит контейнером для обработчика логов.
 */
class Log
{
    /**
     * Объект для записи логов.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected static $logger;

    /**
     * Возвращает из контейнера объект логера.
     *
     * @return \Psr\Log\LoggerInterface
     */
    public static function get()
    {
        if (self::$logger === null) {
            self::$logger = self::instant();
        }

        return self::$logger;
    }

    /**
     * Задает контейнеру объект логера.
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public static function set(LoggerInterface $logger)
    {
        self::$logger = $logger;
    }

    /**
     * Инициирует событие для создания логгера, если событие ничего не вернуло,
     * то инициирует логгер по умолчанию.
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected static function instant()
    {
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

        return $customLogger;
    }
}
