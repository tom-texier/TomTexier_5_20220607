<?php

namespace Texier\Framework;

class Configuration
{
    private static $params;

    public static function get($name, $defaultValue = null)
    {
        return self::getParams()[$name] ?? $defaultValue;
    }

    private static function getParams()
    {
        if(self::$params == null) {
            $pathFile = "../config/prod.ini";

            if(!file_exists($pathFile)) {
                $pathFile = "../config/dev.ini";
            }

            if(!file_exists($pathFile)) {
                throw new \Exception("Aucun fichier de configuration trouvé");
            }
            else {
                self::$params = parse_ini_file($pathFile);
            }
        }

        return self::$params;
    }
}