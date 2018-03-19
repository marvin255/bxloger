<?php

namespace marvin255\bxloger;

use marvin255\bxloger\log\QueuedLoggerInterface;

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
        $loger = Log::get();

        if ($loger && ($loger instanceof QueuedLoggerInterface)) {
            $loger->flush();
        }
    }
}
