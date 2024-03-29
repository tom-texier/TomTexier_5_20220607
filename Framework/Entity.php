<?php

namespace Texier\Framework;

abstract class Entity
{
    /**
     * Constructeur de classe
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    /**
     * Hydratation des instances
     * @param array $data
     * @return void
     */
    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
		    $key = lcfirst(str_replace('_', '', ucwords($key, '_'))); // Transformation de underscore_case vers camelCase
			$method = 'set' . ucfirst($key); // On récupère le nom du setter correspondant à l'attribut.

			if (method_exists($this, $method)) {
				$this->$method($value); // On appelle le setter.
			}
		}
    }
}
