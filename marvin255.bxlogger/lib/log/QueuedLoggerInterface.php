<?php

namespace marvin255\bxlogger\log;

use Psr\Log\LoggerInterface;

/**
 * Интерфейс для объекта логирования, который не пишет данные каждый раз в лог,
 * а сохраняетих в памяти и дожидается вызова команды для записи.
 */
interface QueuedLoggerInterface extends LoggerInterface
{
    /**
     * Команда для для принудительной записи данных в лог.
     *
     * @return \marvin255\bxlogger\log\QueuedLoggerInterface
     */
    public function flush();

    /**
     * Задает флаг, который указывает нужно или нет испольтзовать очередь
     * перед записью данных в лог.
     *
     * @param bool $isQueueSwitchedOn
     *
     * @return \marvin255\bxlogger\log\QueuedLoggerInterface
     */
    public function changeQueueUsageStatus($isQueueSwitchedOn);
}
