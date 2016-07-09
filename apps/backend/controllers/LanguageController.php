<?php
namespace Corephalcon\Backend\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Corephalcon\Modeldb\Models\Language as Lang;

class LanguageController extends ControllerBase
{
    public function indexAction()
    {

        $languages = Lang::findAll();

        $this->view->data = $languages;
    }
    public function newAction()
    {

    }
    public function createAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "lanuage",
                'action' => 'create'
            ));
            return;
        }

        $langs = new Lang();
        $langs->lang = $this->request->getPost("lang");
        $langs->code = $this->request->getPost("code");
        $langs->is_status = '1';
        if (!$langs->save()) {
            foreach ($langs->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "language",
                'action' => 'new'
            ));

            return;
        }
        $this->flash->success("Tạo mới thành công");

        /*return $this->dispatcher->forward(array(
            'controller' => "users",
            'action' => 'index'
        ));*/
        return $this->response->redirect('index');

    }
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $langs = Lang::findFirstByid($id);
            if (!$langs) {
                $this->flash->error("user was not found");
                return $this->response->redirect('/backend/users/index');
            }

            $this->view->data = $langs;

        }
    }
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "language",
                'action' => 'index'
            ));

            return;
        }

        $id = $this->request->getPost("id");
        $langs = Lang::findFirstByid($id);

        if (!$langs) {
            $this->flash->error("Không tìm thấy ngôn ngữ");

            $this->dispatcher->forward(array(
                'controller' => "language",
                'action' => 'index'
            ));

            return;
        }

        $langs->lang = $this->request->getPost("lang");
        $langs->is_status = isset($_POST["is_status"]) ? '1' : '0';
        $langs->code = $this->request->getPost("code");
        if (!$langs->save()) {

            foreach ($langs->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "language",
                'action' => 'edit',
                'params' => array($langs->id)
            ));

            return;
        }

        $this->flash->success("Đã lưu");
        return $this->response->redirect('/backend/language/index');
    }
}
