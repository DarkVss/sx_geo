<?php

namespace Util\SxGeo;

final class DatabaseMod {
    private function __construct() { }

    /**
     * Работа с файлом базы, режим по умолчанию
     */
    public const FILE = 0;
    /**
     * Кэширование БД в памяти, еще увеличивает скорость пакетной обработки, но требует больше памяти, для загрузки всей базы в память
     */
    public const MEMORY = 1;
    /**
     * Пакетная обработка, увеличивает скорость при обработке множества IP за раз
     */
    public const BATCH = 2;
}
