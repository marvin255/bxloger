<?php

namespace marvin255\bxlogger\log;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

/**
 * Логирование с параллельным использование нескольких логеров.
 */
class Composite extends AbstractLogger implements QueuedLoggerInterface
{
    /**
     * @var array
     */
    protected $loggers = [];

    /**
     * @inheritdoc
     */
    public function log($level, $message, array $context = [])
    {
        foreach ($this->loggers as $logger) {
            $logger->log($level, $message, $context);
        }
    }

    /**
     * @inheritdoc
     */
    public function flush()
    {
        foreach ($this->loggers as $logger) {
            if ($logger instanceof QueuedLoggerInterface) {
                $logger->flush();
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function changeQueueUsageStatus($isQueueSwitchedOn)
    {
        foreach ($this->loggers as $logger) {
            if ($logger instanceof QueuedLoggerInterface) {
                $logger->changeQueueUsageStatus($isQueueSwitchedOn);
            }
        }

        return $this;
    }

    /**
     * Задает список логеров.
     *
     * @param array $loggers
     *
     * @return \marvin255\bxlogger\log\Composite
     */
    public function setLoggers(array $loggers)
    {
        $this->loggers = [];
        foreach ($loggers as $logger) {
            $this->addLogger($logger);
        }

        return $this;
    }

    /**
     * Добавляет логер к списку.
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return \marvin255\bxlogger\log\Composite
     */
    public function addLogger(LoggerInterface $logger)
    {
        $this->loggers[] = $logger;

        return $this;
    }

    /**
     * Перед уничтожением объекта пробуем отправить данные в лог.
     */
    public function __destruct()
    {
        $this->flush();
    }
}
