<?php

namespace marvin255\bxlogger;

use marvin255\bxlogger\log\QueuedLoggerInterface;

/**
 * Менеджер событий для данного модуля.
 */
class EventManager
{
    /**
     * OnAfterEpilog - после загрузки страницы, нужно сбросить очередь лога в
     * хранилище, если лог поддерживает очереди.
     */
    public static function onAfterEpilog()
    {
        $logger = Log::get();

        if ($logger && ($logger instanceof QueuedLoggerInterface)) {
            $logger->flush();
        }
    }
}
