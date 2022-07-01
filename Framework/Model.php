<?php

namespace Texier\Framework;

abstract class Model
{
    private $db;

    public function getDb()
    {
        return $this->db;
    }

    protected function executeRequest(string $sql, array $params = [])
    {

    }
}