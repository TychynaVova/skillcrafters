<?php


namespace App\Controllers;

use Core\Auth;
use App\Core\Controller;
use App\Models\Courses;
use App\Models\Module;

class ModulesController extends Controller
{

    public function listModules(): void
    {
        Auth::requireRole(1);
        $courseId = $_GET['id'] ?? null;
        $page = $_GET['page'] ?? 1;

        $moduleModel = new Module();
        $data = $moduleModel->getByCourseId($courseId, $page);

        $this->view->render('admin/modules/list', [
            'modules' => $data['modules'],
            'pagination' => [
                'current' => $data['page'],
                'total' => $data['totalPages'],
                'url' => "modules/list?action=getAll&&id={$courseId}&page="
            ],
            'courseId' => $courseId,
            'isAdmin' => true
        ]);
    }

    public function addModule(): void
    {
        Auth::requireRole(1);
        $courses = new Courses();
        $courses = $courses->getAllCourses();
        $this->view->renderPartial('admin/modules/add', ['courses' => $courses, 'id' => $_GET['id']]);
    }

    public function newModule(): void
    {
        Auth::requireRole(1);
        $moduleModel = new Module();
        $data = [
            'course_id' => $_POST['course_id'],
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'position' => $_POST['position'] ?? $moduleModel->getNextPosition($_POST['course_id'])
        ];

        if ($moduleModel->createModule($data)) {
            $this->view->renderPartial('admin/modules/list', ['course' => $_POST['course_id']]);
        }
    }

    public function editModule()
    {
        Auth::requireRole(1);
        
        $modId = $_GET['mod'] ?? null;
        if (!$modId) {
            echo 'Модуль не знайдено';
            return;
        }
        $moduleModel = new Module();
        $module = $moduleModel->find($modId);
        $courses = new Courses();
        $courses = $courses->getAllCourses();
        if (!$module) {
            echo 'Модуль не знайдено';
            return;
        }
        $this->view->renderPartial('admin/modules/edit_modules_form', ['module' => $module, 'courses' => $courses]);
    }
}
