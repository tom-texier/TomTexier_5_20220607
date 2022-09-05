<?php

namespace Texier\Framework;

use PDO;

abstract class Model
{

    private static $db;

    /**
     * Retourne l'instance du PDO
     * @return PDO
     * @throws \Exception
     */
    private static function getDb(): PDO
    {
        if(self::$db === null) {
            $dsn = Configuration::get("dsn");
            $username = Configuration::get("username");
            $password = Configuration::get("password");

            self::$db = new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION]);
        }
        
        return self::$db;
    }

    /**
     * Exécute une requête SQL
     * @param string $sql Requête SQL à exécuter
     * @param array $params Paramètres de la requête
     * @return false|\PDOStatement
     * @throws \Exception
     */
    protected function executeRequest(string $sql, array $params = [])
    {
        if(empty($params)) {
            $result = self::getDb()->query($sql);
        }
        else {
            $result = self::getDb()->prepare($sql);
            $result->execute($params);
        }

        return $result;
    }
}
