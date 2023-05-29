<?php

namespace Util\SxGeo;

/**
 * Types can be combined
 */
final class ParseType {
    private function __construct() { }

    /**
     * Universal
     */
    public const UNIVERSAL = 0;
    /**
     * SxGeo Country
     */
    public const SX_GEO_COUNTRY = 1;
    /**
     * SxGeo City
     */
    public const SX_GEO_CITY = 2;
    /**
     * GeoIP Country
     */
    public const GEO_IP_COUNTRY = 11;
    /**
     * GeoIP City
     */
    public const GEO_IP_CITY = 12;
    /**
     * ipgeobase
     */
    public const IP_GEO_BASE = 21;

    public const AVAILABLE = [
        self::UNIVERSAL,
        self::SX_GEO_COUNTRY,
        self::SX_GEO_CITY,
        self::GEO_IP_COUNTRY,
        self::GEO_IP_CITY,
        self::IP_GEO_BASE,
    ];
}
