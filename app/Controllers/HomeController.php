<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class HomeController extends Controller {
    public function index() {
        $userModel = new User();
        $users = $userModel->getAllUsers();
        $this->view->render('home', ['users' => $users]);
    }

    public function dashboardUser() {
        $userModel = new User();
        $users = $userModel->getAllUsers();
        $this->view->render('home', ['users' => $users]);
    }
}
