<?php

namespace Util\SxGeo;

final class Coordinate {
    private function __construct() { }

    protected float $_lat;
    protected float $_lon;

    /**
     * @param float $lat
     * @param float $lon
     *
     * @return static
     */
    public static function init(float $lat, float $lon) : static {
        $instance = new static();

        $instance->_lat = $lat;
        $instance->_lon = $lon;

        return $instance;
    }

    /**
     * @return float
     */
    public function Lat() : float {
        return $this->_lat;
    }

    /**
     * @return float
     */
    public function Lon() : float {
        return $this->_lon;
    }
}
