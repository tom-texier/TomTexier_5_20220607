<?php

namespace Texier\Framework;

class Configuration
{
    /**
     * @var array $params
     */
    private static $params;

    /**
     * Récupère un paramètre de configuration du fichier /config/*.ini
     * @param string $name Nom du paramètre à récupérer
     * @param string|null $defaultValue Valeur par défaut à retourner si le paramètre n'est pas trouvé
     * @return mixed|null
     * @throws \Exception
     */
    public static function get(string $name, string $defaultValue = null)
    {
        return self::getParams()[$name] ?? $defaultValue;
    }

    /**
     * Transform les paramètres de configuration du fichier .ini en tableau
     * @return array|false
     * @throws \Exception
     */
    private static function getParams()
    {
        if(self::$params === null) {
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
