<?php

namespace marvin255\bxloger\log;

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
    protected $logers = [];

    /**
     * @inheritdoc
     */
    public function log($level, $message, array $context = [])
    {
        foreach ($this->logers as $loger) {
            $loger->log($level, $message, $context);
        }
    }

    /**
     * @inheritdoc
     */
    public function flush()
    {
        foreach ($this->logers as $loger) {
            if ($loger instanceof QueuedLoggerInterface) {
                $loger->flush();
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function changeQueueUsageStatus($isQueueSwitchedOn)
    {
        foreach ($this->logers as $loger) {
            if ($loger instanceof QueuedLoggerInterface) {
                $loger->changeQueueUsageStatus($isQueueSwitchedOn);
            }
        }

        return $this;
    }

    /**
     * Задает список логеров.
     *
     * @param array $logers
     *
     * @return \marvin255\bxloger\log\Composite
     */
    public function setLogers(array $logers)
    {
        $this->logers = [];
        foreach ($logers as $loger) {
            $this->addLoger($loger);
        }

        return $this;
    }

    /**
     * Добавляет логер к списку.
     *
     * @param \Psr\Log\LoggerInterface $loger
     *
     * @return \marvin255\bxloger\log\Composite
     */
    public function addLoger(LoggerInterface $loger)
    {
        $this->logers[] = $loger;

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
