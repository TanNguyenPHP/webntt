<?php
namespace Corephalcon\Backend\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Corephalcon\Modeldb\Models\Users;
use Corephalcon\Commons\SecuritySystem;

class UsersController extends ControllerBase
{

    public function indexAction()
    {
        //$this->persistent->parameters = null;
        $filter = '';
        $page = 1;
        $limit = 20;
        if (isset($_GET['filter']))
            $filter = $_GET['filter'];
        if (isset($_GET['page']))
            $page = $_GET['page'];
        $data = Users::findUsersPaging($filter, $filter, $page, $limit);
        $this->view->dataUsers = $data;

        //https://docs.phalconphp.com/en/latest/reference/models.html

    }

    public function newAction()
    {

    }

    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $user = Users::findFirstByid($id);
            if (!$user) {
                $this->flash->error("user was not found");
                return $this->response->redirect('backend/users/index');
            }

            $this->view->user = $user;
            /*
            $this->view->id = $user->id;
            $this->tag->setDefault("id", $user->id);
            $this->tag->setDefault("username", $user->username);
            $this->tag->setDefault("password", $user->password);
            $this->tag->setDefault("datecreate", $user->datecreate);
            $this->tag->setDefault("is_active", $user->is_active);
            $this->tag->setDefault("is_del", $user->is_del);
            $this->tag->setDefault("room", $user->room);
            $this->tag->setDefault("desc", $user->desc);
            */
        }
    }

    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "users",
                'action' => 'create'
            ));

            return;
        }

        $users = Users::findFirstByusername($this->request->getPost("username"));
        if ($users) {
            $this->flash->error("Trùng tên đăng nhập");
            $this->dispatcher->forward(array(
                'controller' => "users",
                'action' => 'new'
            ));
            return;
        }
        $user = new Users;
        $user->username = $this->request->getPost("username");
        $user->password = SecuritySystem::HashPassword($this->request->getPost("password"), $user->username);
        $user->email = $this->request->getPost("email");
        $user->name = $this->request->getPost("name");
        $user->datecreate = date('YmdHis');
        $user->is_active = '1';
        $user->is_del = '0';

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "users",
                'action' => 'new'
            ));

            return;
        }

        $this->flash->success("Tạo mới thành công");
        /*return $this->dispatcher->forward(array(
            'controller' => "users",
            'action' => 'index'
        ));*/
        return $this->response->redirect('backend/users/index');
    }

    public function saveAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "users",
                'action' => 'index'
            ));

            return;
        }

        $id = $this->request->getPost("id");
        $user = Users::findFirstByid($id);

        if (!$user) {
            $this->flash->error("Không tìm thấy users");

            $this->dispatcher->forward(array(
                'controller' => "users",
                'action' => 'index'
            ));

            return;
        }

        $user->name = $this->request->getPost("name");
        $user->is_active = isset($_POST["is_active"]) ? '1' : '0';
        $user->email = $this->request->getPost("email");
        if (!$user->save()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "users",
                'action' => 'edit',
                'params' => array($user->id)
            ));

            return;
        }

        $this->flash->success("Đã lưu");
        return $this->response->redirect('backend/users/index');
    }

    public function deleteAction($id)
    {
        $user = Users::findFirstByid($id);
        if (!$user) {
            $this->flash->error("user was not found");

            $this->dispatcher->forward(array(
                'controller' => "users",
                'action' => 'index'
            ));

            return;
        }

        if (!$user->delete()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "users",
                'action' => 'search'
            ));

            return;
        }

        $this->flash->success("user was deleted successfully");

        $this->dispatcher->forward(array(
            'controller' => "users",
            'action' => "index"
        ));
    }
    public function changepassAction()
    {

    }
    public function savepassAction()
    {
        $id = Di::getDefault()->getSession()->get('sessionUser');
        $user = Users::findFirstByid($id);
        $passold = SecuritySystem::HashPassword($_POST['OldPassword'], $user->username);
        if ($passold == $user->password) {
            $user->password = SecuritySystem::HashPassword($_POST['NewPassword'], $user->username);
            $user->save();
            $this->flash->success("Mật khẩu được thay đổi");
            $this->dispatcher->forward(array(
                'controller' => "users",
                'action' => 'changepass'
            ));
        } else {
            $this->flash->error("Mật khẩu cũ sai");
            $this->dispatcher->forward(array(
                'controller' => "users",
                'action' => 'changepass'
            ));
        }
    }
    public function logoffAction()
    {
        $this->session->destroy();
        return $this->response->redirect('/quanly');
    }

}
