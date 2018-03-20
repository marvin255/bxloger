<?php

namespace marvin255\bxlogger;

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
    protected static $logger = null;

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
     * Возвращает из контейнера объект логера.
     *
     * @return \Psr\Log\LoggerInterface
     */
    public static function get()
    {
        return self::$logger;
    }
}
