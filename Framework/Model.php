<?php

namespace Texier\Framework;

use PDO;

abstract class Model
{
    private static $db;

    private static function getDb()
    {
        if(self::$db == null) {
            $dsn = Configuration::get("dsn");
            $username = Configuration::get("username");
            $password = Configuration::get("password");

            self::$db = new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION]);
        }
        
        return self::$db;
    }

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