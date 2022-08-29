<?php

use Texier\Framework\Controller;

class ControllerHome extends Controller
{
    public function index()
    {
        $this->generateView([]);
    }
}
