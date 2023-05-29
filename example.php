<?php

require_once "./vendor/autoload.php";


switch (strtolower($argv[1] ?? '')) {
    default:
        {
            echo "[E] Unknown command.\nSupport commands:\n"
                . " 1. update - try to update database file"
                . " 2. resolve - resolve IP"
                . " 3. update - info about currently used database file";
        }
        break;
    case "update":
        {
            try {
                echo match (\Util\SxGeo::updateDatabaseFile("/tmp/123") == true) {
                        true    => "Database file successful updated",
                        false   => "Database file is currently up to date",
                        default => "Database can not be updated(broken answer from service server)",
                    } . "\n";
            } catch (\Exception $e) {
                echo "  FAIL: {$e->getMessage()}\n";
            }
        }
        break;
    case "resolve":
        {
            // Создаем объект
            // Первый параметр - имя файла с базой (используется оригинальная бинарная база SxGeo.dat)
            // Второй параметр - режим работы:
            //     \Util\SxGeo\Mode::FILE   (работа с файлом базы, режим по умолчанию);
            //     \Util\SxGeo\Mode::BATCH (пакетная обработка, увеличивает скорость при обработке множества IP за раз)
            //     \Util\SxGeo\Mode::MEMORY (кэширование БД в памяти, еще увеличивает скорость пакетной обработки, но требует больше памяти)
            try {
                $SxGeo = new \Util\SxGeo(database: "./base1", mod: \Util\SxGeo\DatabaseMod::FILE);
            } catch (\Exception $e) {
                echo " FAIL on init class: {$e->getMessage()}\n";
                exit();
            }

            $ip = "222.229.222.222";

            echo "> resolve city: \n";
            var_export($SxGeo->resolveCity($ip));
        }
        break;
    case "about":
        {
            // Создаем объект
            // Первый параметр - имя файла с базой (используется оригинальная бинарная база SxGeo.dat)
            // Второй параметр - режим работы:
            //     \Util\SxGeo\Mode::FILE   (работа с файлом базы, режим по умолчанию);
            //     \Util\SxGeo\Mode::BATCH (пакетная обработка, увеличивает скорость при обработке множества IP за раз)
            //     \Util\SxGeo\Mode::MEMORY (кэширование БД в памяти, еще увеличивает скорость пакетной обработки, но требует больше памяти)
            try {
                $SxGeo = new \Util\SxGeo(database: "./base", mod: \Util\SxGeo\DatabaseMod::FILE);
            } catch (\Exception $e) {
                echo " FAIL on init class: {$e->getMessage()}\n";
                exit();
            }
            echo "> about database: \n";
            var_export($SxGeo->about());
        }
        break;
}
