<?php

namespace Texier\Framework;

abstract class Entity
{
    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    public function hydrate(array $data)
    {

    }
}