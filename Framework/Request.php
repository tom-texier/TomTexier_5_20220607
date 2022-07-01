<?php

namespace Texier\Framework;

class Request
{
    private $params;
    private Session $session;

    public function __construct(array $params)
    {
        $this->params = $params;
        $this->session = new Session();
    }

    public function existsParam(string $name): bool
    {
        return !empty($this->params[$name]);
    }

    public function getParam(string $name): mixed
    {
        if ($this->existsParameter($name)) {
			return htmlspecialchars($this->parameters[$name]);
		}
		else {
			throw new \Exception("Paramètre '$name' absent de la requête");
		}
    }

    public function getSession()
    {
        return $this->session;
    }
}