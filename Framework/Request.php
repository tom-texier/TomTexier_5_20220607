<?php

namespace Texier\Framework;

class Request
{
    private $params;
    private Session $session;

    /**
     * Constructeur de classe
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
        $this->session = new Session();
    }

    /**
     * Test la présence d'un paramètre dans la requête
     * @param string $name
     * @return bool Renvoie vrai si le paramètre existe. Faux sinon.
     */
    public function existsParam(string $name): bool
    {
        return !empty($this->params[$name]);
    }

    /**
     * Retourne le paramètre souhaité
     * @param string $name
     * @return false|string
     */
    public function getParam(string $name)
    {
        if ($this->existsParam($name)) {
			return htmlspecialchars($this->params[$name]);
        }
        
        return false;
    }

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }
}
