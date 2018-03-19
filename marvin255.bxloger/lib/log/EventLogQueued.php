<?php

namespace marvin255\bxloger\log;

use Psr\Log\AbstractLogger;
use Bitrix\Main\DB\Connection;
use Bitrix\Main\HttpRequest;

/**
 * Логирование в журнал событий битрикса с использоанием очереди.
 */
class EventLogQueued extends AbstractLogger implements QueuedLoggerInterface
{
    /**
     * @var \Bitrix\Main\DB\Connection
     */
    protected $connection;
    /**
     * @var \Bitrix\Main\HttpRequest|null
     */
    protected $request;
    /**
     * @var bool
     */
    protected $isQueueSwitchedOn = true;
    /**
     * @var array
     */
    protected $queue = [];
    /**
     * @var string
     */
    protected $tableName = 'b_event_log';
    /**
     * @var array
     */
    protected $tableFields = [
        'AUDIT_TYPE_ID',
        'MODULE_ID',
        'ITEM_ID',
        'SITE_ID',
        'USER_ID',
        'GUEST_ID',
    ];

    /**
     * @param \Bitrix\Main\DB\Connection $connection
     * @param \Bitrix\Main\HttpRequest   $request
     */
    public function __construct(Connection $connection, HttpRequest $request = null)
    {
        $this->connection = $connection;
        $this->request = $request;
    }

    /**
     * @inheritdoc
     */
    public function log($level, $message, array $context = [])
    {
        $this->queue[] = [$level, $message, $context];
        if (!$this->isQueueSwitchedOn) {
            $this->flush();
        }
    }

    /**
     * @inheritdoc
     */
    public function flush()
    {
        if ($this->queue) {
            $this->insertQueueToLog();
            $this->queue = [];
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function changeQueueUsageStatus($isQueueSwitchedOn)
    {
        $this->isQueueSwitchedOn = (bool) $isQueueSwitchedOn;

        return $this;
    }

    /**
     * Перед уничтожением объекта пробуем отправить данные в лог.
     */
    public function __destruct()
    {
        $this->flush();
    }

    /**
     * Записывает данные в лог.
     *
     *
     * @throws \InvalidArgumentException
     */
    protected function insertQueueToLog()
    {
        $this->connection->query($this->createQueueSql());
    }

    /**
     * Создает запрос для записи данных в лог.
     *
     * @return array
     */
    protected function createQueueSql()
    {
        $sqlHelper = $this->connection->getSqlHelper();

        $fieldNames = '';
        $batchList = [];
        foreach ($this->queue as $item) {
            $preparedData = $sqlHelper->prepareInsert(
                $this->tableName,
                $this->createItemForInsert($item)
            );
            $fieldNames = $preparedData[0];
            $batchList[] = $preparedData[1];
        }

        $resultSql = "INSERT INTO {$this->tableName} ({$fieldNames}) VALUES ";
        $resultSql .= '(' . implode('), (', $batchList) . ')';

        return $resultSql;
    }

    /**
     * Преобразует запись из очереди в запись для базы данных.
     *
     * @param array $queuItem
     *
     * @return array Ассоциативный массив вида "имя поля в таблице => значение"
     */
    protected function createItemForInsert(array $queuItem)
    {
        $return = [];

        list($severity, $description, $context) = $queuItem;

        foreach ($this->tableFields as $fieldName) {
            $return[$fieldName] = isset($context[$fieldName])
                ? $context[$fieldName]
                : '';
        }

        $return['SEVERITY'] = strtoupper($severity);
        $return['TIMESTAMP_X'] = time();
        $return['USER_AGENT'] = $this->request
            ? $this->request->getUserAgent()
            : '';
        $return['REQUEST_URI'] = $this->request
            ? $this->request->getRequestUri()
            : '';
        $return['REMOTE_ADDR'] = $this->request
            ? $this->request->getRemoteAddress()
            : '';
        $return['DESCRIPTION'] = $description;

        if (empty($return['SITE_ID'])) {
            $return['SITE_ID'] = defined('SITE_ID') ? SITE_ID : '';
        }

        return $return;
    }
}
