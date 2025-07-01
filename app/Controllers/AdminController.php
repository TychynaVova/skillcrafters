<?php


namespace App\Controllers;

use Core\Auth;
use App\Core\Controller;
use App\Models\User;
use App\Models\Courses;
use App\Models\Module;
/* #TODO */
use App\Models\Lesson;
use App\Models\Quiz;

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
            case 'courses':
                $courses = new Courses();
                $courses = $courses->getAllCourses();
                $this->view->renderPartial('admin/partials/courses', ['courses' => $courses]);
                break;
            case 'editCourse':
                $id = $_GET['id'] ?? null;
                if (!$id) {
                    echo 'Курс не знайдено';
                    return;
                }
                $coursModel = new Courses();
                $course = $coursModel->find($id);
                if (!$course) {
                    echo 'Курс не знайдено';
                    return;
                }
                $this->view->renderPartial('admin/partials/edit_cours_form', ['course' => $course]);
                break;
            case 'createCourse':
                $this->view->renderPartial('admin/partials/add_cours_form');
                break;
            /*case 'lessons':
                $courseId = $_GET['id'];
                $lessonModel = new Lesson();
                $lessons = $lessonModel->getByCourseId($courseId);
                $this->view->renderPartial('admin/lessons/list', ['lessons' => $lessons, 'courseId' => $courseId]);
                break;
            case 'quizzes':
                $courseId = $_GET['id'];
                $quizModel = new Quiz();
                $quizzes = $quizModel->getByCourseId($courseId);
                $this->view->renderPartial('admin/quizzes/list', ['quizzes' => $quizzes, 'courseId' => $courseId]);
                break;
            case 'quizOptions':
                $courseId = $_GET['id'];
                $quizModel = new Quiz();
                $quizzes = $quizModel->getByCourseId($courseId);
                $this->view->renderPartial('admin/quizzes/list', ['quizzes' => $quizzes, 'courseId' => $courseId]);
                break;*/
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

            case 'updateCourse':
                $id = $_POST['id'];

                $courseModel = new Courses();
                $existingCourse = $courseModel->find($id); // отримаємо старий запис

                if (!$existingCourse) {
                    echo 'Курс не знайдено';
                    return;
                }

                $imagePath = $existingCourse['image'];

                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['image']['tmp_name'];
                    $name = basename($_FILES['image']['name']);
                    $targetPath = __DIR__ . '/../../public/uploads/' . $name;

                    if (move_uploaded_file($tmpName, $targetPath)) {
                        $imagePath = '/uploads/' . $name;
                    }
                }

                $fields = [
                    'title' => $_POST['title'],
                    'slug' => $_POST['slug'],
                    'description' => $_POST['description'],
                    'status' => $_POST['status'],
                    'price' => $_POST['price'],
                    'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                    'level' => $_POST['level'],
                    'duration' => $_POST['duration'],
                    'language' => $_POST['language'],
                    'image' => $imagePath
                ];

                if ($imagePath !== null) {
                    $fields['image'] = $imagePath;
                }

                $courseModel = new Courses();
                $course = $courseModel->update($id, $fields);

                if (!$course) {
                    echo 'Помилка при оновлені';
                    return;
                }

                $_GET['action'] = 'courses';
                $this->loadContent();

                break;
            case 'saveNewCourse':
                $imagePath = null;

                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['image']['tmp_name'];
                    $name = basename($_FILES['image']['name']);
                    $targetPath = __DIR__ . '/../../public/uploads/' . $name;

                    if (move_uploaded_file($tmpName, $targetPath)) {
                        $imagePath = '/uploads/' . $name;
                    }
                }

                $fields = [
                    'title' => $_POST['title'],
                    'slug' => $_POST['slug'],
                    'description' => $_POST['description'],
                    'status' => $_POST['status'],
                    'price' => $_POST['price'],
                    'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                    'level' => $_POST['level'],
                    'duration' => $_POST['duration'],
                    'language' => $_POST['language'],
                    'image' => $imagePath,
                ];

                $courseModel = new Courses();
                $newCourseId = $courseModel->create($fields);

                if (!$newCourseId) {
                    echo 'Помилка при створенні курсу';
                    return;
                }

                // Після створення — повертаємось на список курсів
                $_GET['action'] = 'courses';
                $this->loadContent();
                break;
        }
    }
}
