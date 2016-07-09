<?php

namespace Corephalcon\Backend\Controllers;

use Phalcon\Mvc\View;
use Corephalcon\Modeldb\Models\Users;
use Corephalcon\Commons\SecuritySystem;
use Corephalcon\Commons\Authentication;

class LoginController extends ControllerBase
{
    public function initialize()
    {
        $this->assets
            ->addJs('/js/jquery-1.12.4.min.js')
            ->addJs('/js/jquery.validate.min.js')
            ->addJs('/js/jquery.datetimepicker.full.min.js')
            ->addJs('/js/fine-uploader.min.js')
            ->addJs('/js/alertify.min.js')
            ->addJs('/ckeditor/tinymce.min.js');
    }
    public function indexAction()
    {
        if (!Authentication::CheckAuth())
            $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);//Render đến View tham khảo tại https://docs.phalconphp.com/en/latest/reference/views.html#control-rendering-levels
        else
            return $this->response->redirect('backend/users/index');
    }

    public function loginAction()
    {
        if (!$this->request->isPost()) {
            if (!$this->security->checkToken()) {
                return $this->dispatcher->forward(array(
                    'controller' => "login",
                    'action' => 'index'
                ));//forward ko lam doi trang
            }
            //return;
        }
        //$response = new \Phalcon\Http\Response();

        $username = $this->request->getPost("UserName");
        $password = $this->request->getPost("Password");

        $_checklogin = $this->checklogin($username, $password);

        if ($_checklogin == 0)//Success
        {
            return $this->response->redirect('/backend/users/index');
        }

        if ($_checklogin == 3 || $_checklogin == 4 || $_checklogin == 5) {
            $this->flashSession->error('wrong pass');//$this->flash->error("Wrong username or password");
            return $this->response->redirect('/quanly');
        }
        if ($_checklogin == 2) {
            $this->flashSession->error("Account not active");//$this->flash->error("Account not active");
            return $this->response->redirect('/quanly');
        }

        return $this->response->redirect('/quanly');
    }

    ////////////////////function helper////////////////////////////////////////////////////////////////////
    private function checklogin($Username, $Password)
    {
        $user = Users::findFirst("username = '$Username'");
        if ($user) {
            if (SecuritySystem::HashPassword($Password, $Username) == $user->password) {//dùng hàm strcmp so sánh chuỗi với binary
                if ($user->is_active == '1' & $user->is_del == '0') {
                    $this->registerSessionUser($user);
                    return 0;
                }// Success
                else if ($user->is_del == '1')
                    return 1;// account del
                else if ($user->is_active == '0')
                    return 2;// account not active
            } else {
                return 3;//Wrong password
            }
        } else {
            return 4;// Wrong username
        }
        return 5;
    }

    private function registerSessionUser($user)
    {
        $this->session->set('sessionUser', $user->id);
    }

}

