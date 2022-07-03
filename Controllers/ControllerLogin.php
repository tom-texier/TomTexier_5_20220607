<?php

use App\Model\Manager\UsersManager;
use Texier\Framework\Controller;

class ControllerLogin extends Controller
{
    private UsersManager $usersManager;

    public function __construct()
    {
        $this->usersManager = new UsersManager();
    }

    public function index()
    {
        $this->generateView();
    }
}