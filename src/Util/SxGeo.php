<?php

namespace Util;

/***************************************************************************\
 * | Sypex Geo                  version 2.2.3                                  |
 * | (c)2006-2014 zapimir       zapimir@zapimir.net       http://sypex.net/    |
 * | (c)2006-2014 BINOVATOR     info@sypex.net                                 |
 * |---------------------------------------------------------------------------|
 * |     created: 2006.10.17 18:33              modified: 2014.06.20 18:57     |
 * |---------------------------------------------------------------------------|
 * | Sypex Geo is released under the terms of the BSD license                  |
 * |   http://sypex.net/bsd_license.txt                                        |
 * |---------------------------------------------------------------------------|
 * |   https://sypexgeo.net/ru/docs/sxgeo22/                                   |
 * |   https://sypexgeo.net/ru/download/                                       |
 * \***************************************************************************/


/**
 * Custom modification for standard class SxGeo. Support only fullish database
 *
 * @author DarkVss
 */
class SxGeo {
    public const DATABASE_FILE_URL = "https://sypexgeo.net/files/SxGeoCity_utf8.zip";

    /** Database file info */
    /**
     * @var int Версия файла (21 => 2.1)
     */
    protected int $_version;
    /**
     * @var int Время создания (Unix timestamp)
     */
    protected int $_createdAt;
    /**
     * @var int Парсер (0 - Universal, 1 - SxGeo Country, 2 - SxGeo City, 11 - GeoIP Country, 12- GeoIP City, 21 - ipgeobase)
     */
    protected int $_parserType;
    /**
     * @var int Кодировка (0 - UTF-8, 1 - latin1, 2 - cp1251)
     */
    protected int $_charset;
    /**
     * @var int Элементов в индексе первых байт (до 255)
     */
    protected int $_mainIndexFirstBytesLength;
    /**
     * @var int Элементов в основном индексе (до 65 тыс.)
     */
    protected int $_mainIndexLength;
    /**
     * @var int Блоков в одном элементе индекса (до 65 тыс.)
     */
    protected int $_indexElementBlocksAmount;
    /**
     * @var int Количество диапазонов (до 4 млрд.)
     */
    protected int $_dbItemsAmount;
    /**
     * @var int Размер ID-блока в байтах (1 для стран, 3 для городов)
     */
    protected int $_idBlockLength;
    /**
     * @var int Максимальный размер записи региона (до 64 КБ)
     */
    protected int $_regionMaxLength;
    /**
     * @var int Максимальный размер записи города (до 64 КБ)
     */
    protected int $_cityMaxLength;
    /**
     * @var int Размер справочника регионов
     */
    protected int $_regionsAmount;
    /**
     * @var int Размер справочника городов
     */
    protected int $_citiesAmount;
    /**
     * @var int Максимальный размер записи страны(до 64 КБ)
     */
    protected int $_countryMaxLength;
    /**
     * @var int Размер справочника стран
     */
    protected int $_countriesAmount;
    /**
     * @var int Размер описания формата упаковки города/региона/страны
     */
    protected int $_packSize;
    protected $_databaseFilePointer;
    protected int $_databaseBegin;
    protected string $_b_idx_str;
    protected string $_m_idx_str;
    protected array $_b_idx_arr;
    protected array $_m_idx_arr;
    protected int $_block_len;
    protected array $_pack;
    protected string $_database;
    protected string $_regionsDatabase;
    protected string $_citiesDatabase;

    protected int $_regionsDatabaseBegin;
    protected int $_citiesDatabaseBegin;

    public array $ID2ISO = [
        '', "AP", "EU", "AD", "AE", "AF", "AG", "AI", "AL", "AM", "CW", "AO", "AQ", "AR", "AS", "AT", "AU",
        "AW", "AZ", "BA", "BB", "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BM", "BN", "BO", "BR", "BS",
        "BT", "BV", "BW", "BY", "BZ", "CA", "CC", "CD", "CF", "CG", "CH", "CI", "CK", "CL", "CM", "CN",
        "CO", "CR", "CU", "CV", "CX", "CY", "CZ", "DE", "DJ", "DK", "DM", "DO", "DZ", "EC", "EE", "EG",
        "EH", "ER", "ES", "ET", "FI", "FJ", "FK", "FM", "FO", "FR", "SX", "GA", "GB", "GD", "GE", "GF",
        "GH", "GI", "GL", "GM", "GN", "GP", "GQ", "GR", "GS", "GT", "GU", "GW", "GY", "HK", "HM", "HN",
        "HR", "HT", "HU", "ID", "IE", "IL", "IN", "IO", "IQ", "IR", "IS", "IT", "JM", "JO", "JP", "KE",
        "KG", "KH", "KI", "KM", "KN", "KP", "KR", "KW", "KY", "KZ", "LA", "LB", "LC", "LI", "LK", "LR",
        "LS", "LT", "LU", "LV", "LY", "MA", "MC", "MD", "MG", "MH", "MK", "ML", "MM", "MN", "MO", "MP",
        "MQ", "MR", "MS", "MT", "MU", "MV", "MW", "MX", "MY", "MZ", "NA", "NC", "NE", "NF", "NG", "NI",
        "NL", "NO", "NP", "NR", "NU", "NZ", "OM", "PA", "PE", "PF", "PG", "PH", "PK", "PL", "PM", "PN",
        "PR", "PS", "PT", "PW", "PY", "QA", "RE", "RO", "RU", "RW", "SA", "SB", "SC", "SD", "SE", "SG",
        "SH", "SI", "SJ", "SK", "SL", "SM", "SN", "SO", "SR", "ST", "SV", "SY", "SZ", "TC", "TD", "TF",
        "TG", "TH", "TJ", "TK", "TM", "TN", "TO", "TL", "TR", "TT", "TV", "TW", "TZ", "UA", "UG", "UM",
        "US", "UY", "UZ", "VA", "VC", "VE", "VG", "VI", "VN", "VU", "WF", "WS", "YE", "YT", "RS", "ZA",
        "ZM", "ME", "ZW", "A1", "XK", "O1", "AX", "GG", "IM", "JE", "BL", "MF", "BQ", "SS",
    ];

    public bool $_isDatabaseBatchMod = false;
    public bool $_isDatabaseMemoryMod = false;

    /**
     * SxGeo constructor.
     *
     * @param string $database
     * @param int    $mod
     *
     * @throws \Exception
     */
    public function __construct(string $database, int $mod = \Util\SxGeo\DatabaseMod::FILE) {
        $this->_databaseFilePointer = @fopen($database, "rb") ?: throw new \Exception(message: "Database file not found", code: 503);

        $header = fread($this->_databaseFilePointer, 40); // В версии 2.2 заголовок увеличился на 8 байт
        if (str_starts_with($header, "SxG") === false) {
            throw new \Exception(message: "Unknown database file `{$database}`", code: 503);
        }

        /**
         * version - Версия файла (21 => 2.1)
         * createdAt - Время создания (Unix timestamp)
         * parserType - Парсер (0 - Universal, 1 - SxGeo Country, 2 - SxGeo City, 11 - GeoIP Country, 12- GeoIP City, 21 - ipgeobase) !! 3 - country+ city
         * charset - Кодировка (0 - UTF-8, 1 - latin1, 2 - cp1251) !! 0 - UTF-8
         * mainIndexFirstBytesLength - Элементов в индексе первых байт (до 255)
         * mainIndexLength - Элементов в основном индексе (до 65 тыс.)
         * indexElementBlocksAmount - Блоков в одном элементе индекса (до 65 тыс.)
         * dbItemsAmount - Количество диапазонов (до 4 млрд.)
         * idBlockLength - Размер ID-блока в байтах (1 для стран, 3 для городов)
         * regionMaxLength - Максимальный размер записи региона (до 64 КБ)
         * cityMaxLength - Максимальный размер записи города (до 64 КБ)
         * regionsAmount - Размер справочника регионов
         * citiesAmount - Размер справочника городов
         * countryMaxLength - Максимальный размер записи страны(до 64 КБ)
         * countriesAmount - Размер справочника стран
         * packSize - Размер описания формата упаковки города/региона/страны
         */
        $info = unpack("Cversion/NcreatedAt/CparserType/Ccharset/CmainIndexFirstBytesLength/nmainIndexLength/nindexElementBlocksAmount/NdbItemsAmount/CidBlockLength/nregionMaxLength/ncityMaxLength/NregionsAmount/NcitiesAmount/ncountryMaxLength/NcountriesAmount/npackSize", substr($header, 3));
        if ($info["mainIndexFirstBytesLength"] * $info["mainIndexLength"] * $info["indexElementBlocksAmount"] * $info["dbItemsAmount"] * $info["createdAt"] * $info["idBlockLength"] === 0) {
            throw new \Exception(message: "Database corrupted", code: 503);
        }
        if (in_array($info["charset"], \Util\SxGeo\Charset::AVAILABLE) === false) {
            throw new \Exception(message: "Unknown database charset", code: 503);
        }

        $this->_version = $info["version"]; // original name: `ver`
        $this->_createdAt = $info["createdAt"]; // original name: `time`
        $this->_parserType = $info["parserType"]; // original name: `type`
        $this->_charset = $info["charset"]; // original name: `charset`
        $this->_mainIndexFirstBytesLength = $info["mainIndexFirstBytesLength"]; // original name: `b_idx_len`
        $this->_mainIndexLength = $info["mainIndexLength"]; // original name: `m_idx_len`
        $this->_indexElementBlocksAmount = $info["indexElementBlocksAmount"]; // original name: `range`
        $this->_dbItemsAmount = $info["dbItemsAmount"]; // original name: `db_items`
        $this->_idBlockLength = $info["idBlockLength"]; // original name: `id_len`
        $this->_regionMaxLength = $info["regionMaxLength"]; // original name: `max_region`
        $this->_cityMaxLength = $info["cityMaxLength"]; // original name: `max_city`
        $this->_regionsAmount = $info["regionsAmount"]; // original name: `region_size`
        $this->_citiesAmount = $info["citiesAmount"]; // original name: `city_size`
        $this->_countryMaxLength = $info["countryMaxLength"]; // original name: `max_country`
        $this->_countriesAmount = $info["countriesAmount"]; // original name: `country_size`
        $this->_packSize = $info["packSize"]; // original name: `pack_size`
        if ($this->_charset !== \Util\SxGeo\Charset::UTF_8) {
            throw new \Exception(message: "Database file encoding not UTF-8", code: 503);
        }

        if ($this->_parserType !== \Util\SxGeo\ParseType::SX_GEO_COUNTRY + \Util\SxGeo\ParseType::SX_GEO_CITY) {
            throw new \Exception(message: "Unknown database file parse type", code: 503);
        }

        $this->_block_len = 3 + $this->_idBlockLength;
        $this->_pack = $this->_packSize ? explode("\0", fread($this->_databaseFilePointer, $this->_packSize)) : [];
        $this->_b_idx_str = fread($this->_databaseFilePointer, $this->_mainIndexFirstBytesLength * 4) ?: throw new \Exception(message: "Can't read database file", code: 503);
        $this->_m_idx_str = fread($this->_databaseFilePointer, $this->_mainIndexLength * 4) ?: throw new \Exception(message: "Can't read database file", code: 503);

        $this->_databaseBegin = ftell($this->_databaseFilePointer) !== false ? ftell($this->_databaseFilePointer) : throw new \Exception(message: "Can't read database file", code: 503);
        $this->_isDatabaseMemoryMod = $mod & \Util\SxGeo\DatabaseMod::MEMORY;
        $this->_isDatabaseBatchMod = $mod & \Util\SxGeo\DatabaseMod::BATCH;

        if ($this->_isDatabaseBatchMod) {
            $this->_b_idx_arr = array_values(unpack("N*", $this->_b_idx_str));
            unset ($this->_b_idx_str);
            $this->_m_idx_arr = str_split($this->_m_idx_str, 4);
            unset ($this->_m_idx_str);
        }
        if ($this->_isDatabaseMemoryMod) {
            $this->_database = fread($this->_databaseFilePointer, $this->_dbItemsAmount * $this->_block_len) ?: throw new \Exception(message: "Can't read database file", code: 503);
            $this->_regionsDatabase = $this->_regionsAmount > 0 ?
                (fread($this->_databaseFilePointer, $this->_regionsAmount) ?: throw new \Exception(message: "Can't read database file", code: 503))
                : '';
            $this->_citiesDatabase = $this->_citiesAmount > 0 ?
                (fread($this->_databaseFilePointer, $this->_citiesAmount) ?: throw new \Exception(message: "Can't read database file", code: 503))
                : '';
        }

        $this->_regionsDatabaseBegin = $this->_databaseBegin + $this->_dbItemsAmount * $this->_block_len;
        $this->_citiesDatabaseBegin = $this->_regionsDatabaseBegin + $this->_regionsAmount;
    }

    /**
     * @param string $ipn IP-address represented in HEX-string
     * @param int    $min
     * @param int    $max
     *
     * @return int
     */
    protected function _searchIDx(string $ipn, int $min, int $max) : int {
        if ($this->_isDatabaseBatchMod) {
            while ($max - $min > 8) {
                $offset = ($min + $max) >> 1;
                if ($ipn > $this->_m_idx_arr[$offset]) {
                    $min = $offset;
                } else {
                    $max = $offset;
                }
            }
            /** @noinspection PhpStatementHasEmptyBodyInspection */
            while ($ipn > $this->_m_idx_arr[$min] && $min++ < $max) {
            }
        } else {
            while ($max - $min > 8) {
                $offset = ($min + $max) >> 1;
                if ($ipn > substr($this->_m_idx_str, $offset * 4, 4)) {
                    $min = $offset;
                } else {
                    $max = $offset;
                }
            }
            /** @noinspection PhpStatementHasEmptyBodyInspection */
            while ($ipn > substr($this->_m_idx_str, $min * 4, 4) && $min++ < $max) {
            }
        }

        return $min;
    }

    /**
     * @param string $str
     * @param string $ipn
     * @param int    $min
     * @param int    $max
     *
     * @return ?int return found ID, otherwise - NULL
     */
    protected function _searchNumber(string $str, string $ipn, int $min, int $max) : ?int {
        if ($max - $min <= 1) {
            $min++;
        } else {
            $ipn = substr($ipn, 1);
            while ($max - $min > 8) {
                $offset = ($min + $max) >> 1;
                if ($ipn > substr($str, $offset * $this->_block_len, 3)) {
                    $min = $offset;
                } else {
                    $max = $offset;
                }
            }
            /** @noinspection PhpStatementHasEmptyBodyInspection */
            while ($ipn >= substr($str, $min * $this->_block_len, 3) && ++$min < $max) {
            }
        }

        return hexdec(bin2hex(substr($str, $min * $this->_block_len - $this->_idBlockLength, $this->_idBlockLength))) ?: null;
    }

    /**
     * @param string $ip
     *
     * @return ?int return found ID, otherwise - NULL
     *
     * @throws \Exception
     */
    protected function _getNumber(string $ip) : ?int {
        $ip1n = (int) $ip; // Первый байт
        // TODO: upgrade checking mech by https://www.iana.org/assignments/iana-ipv4-special-registry/iana-ipv4-special-registry.xhtml
        if ($ip1n == 0 || $ip1n == 10 || $ip1n == 127 || $ip1n >= $this->_mainIndexFirstBytesLength || false === ($ipn = ip2long($ip))) {
            throw new \Exception(message: "Invalid IP(e.g. local IP-address)", code: 400);
        }

        $ipn = pack("N", $ipn);
        $blocks = $this->_isDatabaseBatchMod === true ?
            ["min" => $this->_b_idx_arr[$ip1n - 1], "max" => $this->_b_idx_arr[$ip1n]]
            : unpack("Nmin/Nmax", substr($this->_b_idx_str, ($ip1n - 1) * 4, 8));

        if ($blocks["max"] - $blocks["min"] > $this->_indexElementBlocksAmount) {
            $part = $this->_searchIDx($ipn, floor($blocks["min"] / $this->_indexElementBlocksAmount), floor($blocks["max"] / $this->_indexElementBlocksAmount) - 1);
            $min = $part > 0 ? $part * $this->_indexElementBlocksAmount : 0;
            $max = $part > $this->_mainIndexLength ? $this->_dbItemsAmount : ($part + 1) * $this->_indexElementBlocksAmount;
            if ($min < $blocks["min"]) {
                $min = $blocks["min"];
            }
            if ($max > $blocks["max"]) {
                $max = $blocks["max"];
            }
        } else {
            ["min" => $min, "max" => $max] = $blocks;
        }

        $length = $max - $min;
        if ($this->_isDatabaseMemoryMod) {
            return $this->_searchNumber($this->_database, $ipn, $min, $max);
        } else {
            fseek($this->_databaseFilePointer, $this->_databaseBegin + $min * $this->_block_len);

            return $this->_searchNumber(fread($this->_databaseFilePointer, $length * $this->_block_len), $ipn, 0, $length);
        }
    }

    /**
     * @param int $seek
     * @param int $max
     * @param int $type
     *
     * @return array
     */
    protected function _readData(int $seek, int $max, int $type) : array {
        $raw = '';
        if ($seek !== 0 && $max !== 0) {
            if ($this->_isDatabaseMemoryMod) {
                $raw = substr($type == 1 ? $this->_regionsDatabase : $this->_citiesDatabase, $seek, $max);
            } else {
                fseek($this->_databaseFilePointer, ($type === 1 ? $this->_regionsDatabaseBegin : $this->_citiesDatabaseBegin) + $seek);
                $raw = fread($this->_databaseFilePointer, $max);
            }
        }

        return $this->_unpack($this->_pack[$type], $raw);
    }

    /**
     * @param string $pack
     * @param string $item
     *
     * @return array
     */
    protected function _unpack(string $pack, string $item = '') : array {
        $unpacked = [];
        $empty = empty($item);
        $pack = explode("/", $pack);
        $pos = 0;
        foreach ($pack as $p) {
            [$type, $name] = explode(":", $p);
            $type0 = $type[0];
            if ($empty) {
                $unpacked[$name] = $type0 == "b" || $type0 == "c" ? '' : 0;
                continue;
            }
            $l = match ($type0) {
                "t", "T"      => 1,
                "s", "n", "S" => 2,
                "m", "M"      => 3,
                "d"           => 8,
                "c"           => (int) substr($type, 1),
                "b"           => strpos($item, "\0", $pos) - $pos,
                default       => 4,
            };

            $val = substr($item, $pos, $l);
            $v = match ($type0) {
                "t" => unpack("c", $val),
                "T" => unpack("C", $val),
                "s" => unpack("s", $val),
                "S" => unpack("S", $val),
                "m" => unpack("l", $val . (ord($val[2]) >> 7 ? "\xff" : "\0")),
                "M" => unpack("L", $val . "\0"),
                "i" => unpack("l", $val),
                "I" => unpack("L", $val),
                "f" => unpack("f", $val),
                "d" => unpack("d", $val),
                "n" => current(unpack("s", $val)) / pow(10, $type[1]),
                "N" => current(unpack("l", $val)) / pow(10, $type[1]),
                "c" => rtrim($val, " "),
                "b" => $val,
            };

            if ($type0 === "b") {
                $l++;
            }

            $pos += $l;
            $unpacked[$name] = is_array($v) ? current($v) : $v;
        }

        return $unpacked;
    }

    /**
     * @param string $ip
     *
     * @return ?\Util\SxGeo\City NULL - not found
     */
    public function resolveCity(string $ip) : ?\Util\SxGeo\City { //TODO: this is sample no more
        try {
            if (($ID = $this->_getNumber($ip)) === null || count($this->_pack) === 0) {
                return null;
            }
        } catch (\Exception) {
            return null;
        }

        if ($ID < $this->_countriesAmount) {
            $country = $this->_readData($ID, $this->_countryMaxLength, 0);
            $city = $this->_unpack($this->_pack[2]);
            $city["lat"] = $country["lat"];
            $city["lon"] = $country["lon"];
            $onlyCountryInfo = true;
        } else {
            $city = $this->_readData($ID, $this->_cityMaxLength, 2);
            $country = ["id" => $city["country_id"], "iso" => $this->ID2ISO[$city["country_id"]]];
            unset($city["country_id"]);
            $onlyCountryInfo = false;
        }

        $region = $this->_readData($city["region_seek"], $this->_regionMaxLength, 1);
        if ($onlyCountryInfo === false) {
            $country = $this->_readData($region["country_seek"], $this->_countryMaxLength, 0);
        }
        unset($city["region_seek"]);
        unset($region["country_seek"]);

        return \Util\SxGeo\City::init(
            ID: $city["id"],
            nameRu: $city["name_ru"],
            nameEn: $city["name_en"],
            lat: $city["lat"],
            lon: $city["lon"],
            region: \Util\SxGeo\Region::init(
                ID: $region["id"],
                nameRu: $region["name_ru"],
                nameEn: $region["name_en"],
                ISO: $region["iso"],
            ),
            country: \Util\SxGeo\Country::init(
                ID: $country["id"],
                nameRu: $country["name_ru"],
                nameEn: $country["name_en"],
                ISO: $country["iso"],
                lat: $country["lat"],
                lon: $country["lon"],
            )
        );
    }

    /**
     * Get info about database
     *
     * <code>
     * [
     *     "Created"              => "2023.02.28",
     *     "Timestamp"            => 1677616116,
     *     "Charset"              => "utf-8",
     *     "Type"                 => "SxGeo City EN",
     *     "Byte Index"           => 224,
     *     "Main Index"           => 1775,
     *     "Blocks In Index Item" => 3376,
     *     "IP Blocks"            => 5994185,
     *     "Block Size"           => 6,
     *     "City"                 => [
     *         "Max Length" => 127,
     *         "Total Size" => 2681656,
     *     ],
     *     "Region"               => [
     *         "Max Length" => 175,
     *         "Total Size" => 109229,
     *     ],
     *     "Country"              => [
     *         "Max Length" => 147,
     *         "Total Size" => 9387,
     *     ],
     * ]
     * </code>
     *
     * @return array
     */
    public function about() : array {
        return [
            "Created"              => date("Y.m.d", $this->_createdAt),
            "Timestamp"            => $this->_createdAt,
            "Charset"              => \Util\SxGeo\Charset::LABELS[$this->_charset],
            // TODO: remove hardcode check type in constructor and add resolve parse type to relevant class
            "Type"                 => "Unrecognized",
            "Byte Index"           => $this->_mainIndexFirstBytesLength,
            "Main Index"           => $this->_mainIndexLength,
            "Blocks In Index Item" => $this->_indexElementBlocksAmount,
            "IP Blocks"            => $this->_dbItemsAmount,
            "Block Size"           => $this->_block_len,
            "City"                 => [
                "Max Length" => $this->_cityMaxLength,
                "Total Size" => $this->_citiesAmount,
            ],
            "Region"               => [
                "Max Length" => $this->_regionMaxLength,
                "Total Size" => $this->_regionsAmount,
            ],
            "Country"              => [
                "Max Length" => $this->_countryMaxLength,
                "Total Size" => $this->_countriesAmount,
            ],
        ];
    }

    /**
     * Try to update/save database file by filename
     *
     * @param string $databaseFilename
     *
     * @return ?bool TRUE - file updated, FALSE - actual file, NULL - error get info about database file by URL
     *
     * @throws \Exception
     */
    public static function updateDatabaseFile(string $databaseFilename) : ?bool {
        $lastModifiedFile = 0;
        if (is_dir($databaseFilename) === true) {
            throw new \Exception(message: "passed filename is directory\n", code: 403);
        }

        if (file_exists($databaseFilename) === true) {
            if (is_writeable($databaseFilename) === false) {
                throw new \Exception(message: "existed file can not be wrote\n", code: 403);
            }

            $lastModifiedFile = filemtime($databaseFilename);
        } else {
            if (is_writeable(dirname($databaseFilename)) === false) {
                throw new \Exception(message: "existed directory can not be wrote\n", code: 403);
            }
        }

        $headers = @get_headers(static::DATABASE_FILE_URL, true) ?? [];
        $lastModifiedURL = strtotime($headers["Last-Modified"] ?? '');
        if ($lastModifiedURL === false) {
            return null;
        }

        if ($lastModifiedFile >= $lastModifiedURL) {
            return false;
        }

        $exceptedSize = (int) $headers["Content-Length"];
        $data = @file_get_contents(
            static::DATABASE_FILE_URL,
            false,
            stream_context_create([
                "ssl" => [
                    "verify_peer"      => false,
                    "verify_peer_name" => false,
                ],
            ]));
        if ($data === false) {
            throw new \Exception(message: "Download new database file was failed", code: 503);
        }
        $downloadFileSize = strlen($data);
        if ($downloadFileSize !== $exceptedSize) {
            throw new \Exception(message: "Download data {$downloadFileSize} bytes - excepted {$exceptedSize}", code: 409);
        }

        $head = @unpack("Vsig/vver/vflag/vmeth/vmodt/vmodd/Vcrc/Vcsize/Vsize/vnamelen/vexlen", substr($data, 0, 30)) ?: throw new \Exception(message: "Parse new database file was failed", code: 500);
        $rawData = @gzinflate(substr($data, 30 + $head["namelen"] + $head["exlen"], $head["csize"])) ?: throw new \Exception(message: "Extracting new database file was failed", code: 500);

        if (@file_put_contents($databaseFilename, $rawData) === false) {
            throw new \Exception(message: "Successful downloading valid new database file but failed wrote at disk by way `{$databaseFilename}`", code: 500);
        }

        return true;
    }
}
