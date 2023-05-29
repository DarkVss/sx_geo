<?php

namespace Util\SxGeo;

final class Charset {
    private function __construct() { }

    /**
     * UTF-8
     */
    public const UTF_8 = 0;
    /**
     * latin1
     */
    public const LATIN1 = 1;
    /**
     * cp1251
     */
    public const CP1251 = 2;

    public const AVAILABLE = [
        self::UTF_8,
        self::LATIN1,
        self::CP1251,
    ];

    public const LABELS = [
        self::UTF_8 => "UTF-8",
        self::LATIN1 => "latin1",
        self::CP1251 => "cp1251",
    ];
}
