<?php

namespace Texier\Framework;

class Session
{
    /**
     * Constructeur de classe. Initialise la session.
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * Met fin à la session
     * @return void
     */
    public function destroy()
    {
        session_destroy();
    }

    /**
     * @param string $name
     * @param string $value
     * @return void
     */
    public function setAttribute(string $name, string $value)
    {
        $name = htmlspecialchars($name);
        $value = htmlspecialchars($value);
        $_SESSION[$name] = $value;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getAttribute(string $name): string
    {
        if ($this->existsAttribute($name)) {
            return $_SESSION[$name];
        }
        
        return false;
    }

    /**
     * @param string $name
     * @return void
     */
    public function removeAttribute(string $name)
    {
        unset($_SESSION[$name]);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function existsAttribute(string $name): bool
    {
        return isset($_SESSION[$name]) && $_SESSION[$name] != "";
    }
}
