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
                $this->view->renderPartial('admin/partials/users', ['users' => $users]);
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

    public function updateUser(): void
    {
        Auth::requireRole(1);
        $action = $_GET['action'] ?? 'dashboard';

        switch ($action) {
            case 'updateUser':
                $id = $_POST['id'];

                $fields = [
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name'],
                    'nick_name' => $_POST['nick_name'],
                    'email' => $_POST['email'],
                    'role_id' => $_POST['role_id'],
                    'status' => $_POST['status'],
                    'blocked_reason' => $_POST['blocked_reason'] ?? null,
                ];

                $userModel = new User();
                $user = $userModel->update($id, $fields);

                if (!$user) {
                    echo 'Помилка при оновлені';
                    return;
                }
                $_GET['action'] = 'users';
                $this->loadContent();
                break;
            
            default:
                # code...
                break;
        }

    }
}
