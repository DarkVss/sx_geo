<?php

namespace Util\SxGeo;

final class Country {
    private function __construct() { }

    protected int $_ID;
    protected string $_nameRu;
    protected string $_nameEn;
    protected string $_ISO;
    protected \Util\SxGeo\Coordinate $_coordinate;

    /**
     * @param int    $ID Identifier in database
     * @param string $nameRu
     * @param string $nameEn
     * @param string $ISO ISO name
     * @param float  $lat
     * @param float  $lon
     *
     * @return static
     */
    public static function init(int $ID, string $nameRu, string $nameEn, string $ISO, float $lat, float $lon) : static {
        $instance = new static();

        $instance->_ID = $ID;
        $instance->_ISO = $ISO;
        $instance->_coordinate = Coordinate::init($lat, $lon);
        $instance->_nameRu = $nameRu;
        $instance->_nameEn = $nameEn;

        return $instance;
    }

    /**
     * @return int
     */
    public function ID() : int { return $this->_ID; }

    /**
     * @return string
     */
    public function ISO() : string { return $this->_ISO; }

    /**
     * @return \Util\SxGeo\Coordinate
     */
    public function Coordinate() : Coordinate { return $this->_coordinate; }

    /**
     * @return string
     */
    public function NameRu() : string { return $this->_nameRu; }

    /**
     * @return string
     */
    public function NameEn() : string { return $this->_nameEn; }
}
