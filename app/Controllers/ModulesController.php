<?php


namespace App\Controllers;

use Core\Auth;
use App\Core\Controller;
use App\Models\Module;

class ModulesController extends Controller
{

    public function listModules()
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

    public function addModule()
    {
        Auth::requireRole(1);
        $courseId = $_GET['id'] ?? null;
        $this->view->renderPartial('admin/modules/add', ['course' => $courseId]);
    }
}
