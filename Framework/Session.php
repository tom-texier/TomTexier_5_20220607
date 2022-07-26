<?php

namespace Texier\Framework;

class Session
{
    public function __construct()
    {
        session_start();
    }

    public function destroy()
    {
        session_destroy();
    }

    public function setAttribute(string $name, string $value)
    {
        $name = htmlspecialchars($name);
        $value = htmlspecialchars($value);
        $_SESSION[$name] = $value;
    }

    public function getAttribute(string $name): string
    {
        if ($this->existsAttribute($name)) {
            return $_SESSION[$name];
        }
        
        return false;
    }

    public function removeAttribute(string $name)
    {
        unset($_SESSION[$name]);
    }

    public function existsAttribute(string $name): bool
    {
        return isset($_SESSION[$name]) && $_SESSION[$name] != "";
    }
}