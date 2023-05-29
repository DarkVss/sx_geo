<?php

namespace Util\SxGeo;

final class Region {
    private function __construct() { }

    protected int $_ID;
    protected string $_nameRu;
    protected string $_nameEn;
    protected string $_ISO;

    /**
     * @param int    $ID  Identifier in database
     * @param string $nameRu
     * @param string $nameEn
     * @param string $ISO ISO name
     *
     * @return static
     */
    public static function init(int $ID, string $nameRu, string $nameEn, string $ISO) : static {
        $instance = new static();

        $instance->_ID = $ID;
        $instance->_nameRu = $nameRu;
        $instance->_nameEn = $nameEn;
        $instance->_ISO = $ISO;

        return $instance;
    }

    /**
     * @return int
     */
    public function ID() : int { return $this->_ID; }

    /**
     * @return string
     */
    public function NameRu() : string { return $this->_nameRu; }

    /**
     * @return string
     */
    public function NameEn() : string { return $this->_nameEn; }

    /**
     * @return string
     */
    public function ISO() : string { return $this->_ISO; }
}
