<?php

/**
 * Мок для журнала событий битрикса.
 */
class CEventLog
{
    /**
     * @var array
     */
    protected static $added = [];

    /**
     * Добавляет данные в лог.
     *
     * @param array $add
     */
    public static function add(array $add)
    {
        ksort($add);
        self::$added = $add;
    }

    /**
     * Возвращает добавленные данные.
     *
     * @return array
     */
    public static function getAdded()
    {
        return self::$added;
    }
}
