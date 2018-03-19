<?php

namespace marvin255\bxloger\log;

use Psr\Log\AbstractLogger;
use CEventLog;

/**
 * Логирование в журнал событий битрикса.
 */
class EventLog extends AbstractLogger
{
    /**
     * @inheritdoc
     */
    public function log($level, $message, array $context = [])
    {
        $this->insertTolog($level, $message, $context);
    }

    /**
     * Вносит данные в лог с помощью CEventLog.
     *
     * @param string $level
     * @param string $message
     * @param array  $context
     */
    protected function insertTolog($level, $message, array $context)
    {
        $arrayToInsert = $context;
        $arrayToInsert['SEVERITY'] = strtoupper($level);
        $arrayToInsert['DESCRIPTION'] = $message;

        $res = CEventLog::add($arrayToInsert);
    }
}
