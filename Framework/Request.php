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

    public function getParam(string $name)
    {
        if ($this->existsParam($name)) {
			return htmlspecialchars($this->params[$name]);
        }
        
        return false;
    }

    public function getSession()
    {
        return $this->session;
    }
}