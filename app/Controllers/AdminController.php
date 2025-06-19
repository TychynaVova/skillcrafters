<?php

use Core\Auth;
use App\Core\Controller;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard(): void {
        
        Auth::requireRole(1);
        $userModel = new User();
        $users = $userModel->getAllUsers();
        $this->view->render('home', ['users' => $users]);

    }
}