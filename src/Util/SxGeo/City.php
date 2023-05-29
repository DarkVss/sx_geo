<?php

namespace Util\SxGeo;

final class City {
    private function __construct() { }

    protected int $_ID;
    protected string $_nameRu;
    protected string $_nameEn;
    protected \Util\SxGeo\Coordinate $_coordinate;
    protected \Util\SxGeo\Region $_region;
    protected \Util\SxGeo\Country $_country;

    /**
     * @param int                 $ID Identifier in database
     * @param string              $nameRu
     * @param string              $nameEn
     * @param float               $lat
     * @param float               $lon
     * @param \Util\SxGeo\Region  $region
     * @param \Util\SxGeo\Country $country
     *
     * @return static
     */
    public static function init(int $ID, string $nameRu, string $nameEn,  float $lat, float $lon, \Util\SxGeo\Region $region, \Util\SxGeo\Country $country) : static {
        $instance = new static();

        $instance->_ID = $ID;
        $instance->_nameRu = $nameRu;
        $instance->_nameEn = $nameEn;
        $instance->_coordinate = Coordinate::init($lat, $lon);
        $instance->_region = $region;
        $instance->_country = $country;

        return $instance;
    }

    /**
     * @return int
     */
    public function ID() : int { return $this->_ID; }

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

    /**
     * @return \Util\SxGeo\Region
     */
    public function Region() : Region { return $this->_region; }

    /**
     * @return \Util\SxGeo\Country
     */
    public function Country() : Country { return $this->_country; }
}
