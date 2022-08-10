<?php

use Texier\Framework\ControllerSecured;

class ControllerAdmin extends ControllerSecured
{
    public function index()
    {
        $this->generateView();
    }
}