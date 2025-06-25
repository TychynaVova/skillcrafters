<?php


namespace App\Controllers;

use Core\Auth;
use App\Core\Controller;
use App\Models\User;

class AdminController extends Controller
{

    public function dashboard(): void
    {
        Auth::requireRole(1);
        $userModel = new User();
        $users = $userModel->getAllUsers();
        $this->view->render('admin', ['users' => $users]);
    }

    public function loadContent(): void
    {
        Auth::requireRole(1);
        $action = $_GET['action'] ?? 'dashboard';

        switch ($action) {
            case 'users':
                $userModel = new User();
                $users = $userModel->getAllUsers();
                $this->view->renderPartial('admin/partials/users', ['users' => $users]); // тільки контент
                break;

            case 'editUser':
                $id = $_GET['id'] ?? null;
                if (!$id) {
                    echo 'Користувача не знайдено';
                    return;
                }
                $userModel = new User();
                $user = $userModel->find($id);
                if (!$user) {
                    echo 'Користувача не знайдено';
                    return;
                }
                $this->view->renderPartial('admin/partials/edit_user_form', ['user' => $user]);
                break;
        }
    }
}
