<?php

use Texier\Framework\Controller;

class ControllerHome extends Controller
{
    /**
     * Génère la page d'accueil
     * @return void
     */
    public function index()
    {
        $this->generateView([]);
    }
}
