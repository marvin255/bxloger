<?php

namespace marvin255\bxloger;

use Psr\Log\LoggerInterface;

/**
 * Класс для логирования данных.
 *
 * Служит контейнером для обработчика логов.
 */
class Container
{
    /**
     * Объект для записи логов.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected static $loger = null;

    /**
     * Задает контейнеру объект логера.
     *
     * @param \Psr\Log\LoggerInterface $loger
     */
    public static function set(LoggerInterface $loger)
    {
        self::$loger = $loger;
    }

    /**
     * Возвращает из контейнера объект логера.
     *
     * @return \Psr\Log\LoggerInterface
     */
    public static function get()
    {
        return self::$loger;
    }
}
