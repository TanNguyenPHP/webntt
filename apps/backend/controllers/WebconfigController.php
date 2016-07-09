<?php
/**
 * Created by PhpStorm.
 * User: SONY
 * Date: 6/22/2016
 * Time: 11:33 PM
 */
namespace Corephalcon\Backend\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Corephalcon\Modeldb\Models\Webconfig;

class WebconfigController extends ControllerBase
{
    public function indexAction()
    {
        $data = Webconfig::findall('');
        return $this->view->data = $data;
    }

    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $config = Webconfig::findFirst("id_lang = '$id'");
            if (!$config) {
                $this->flash->error("Không tìm thấy");
                return $this->response->redirect('/backend/webconfig/index');
            }
            $this->view->data = $config;
        }
    }

    public function saveAction()
    {
        $id = $this->request->getPost("id");
        $config = Webconfig::findFirst("id_lang = '$id'");

        if (!$config) {
            $this->flash->error("Cấu hình không tồn lại");

            $this->dispatcher->forward(array(
                'controller' => "webconfig",
                'action' => 'index'
            ));

            return;
        }

        $config->title = $this->request->getPost("Tagtitle");
        $config->meta = $this->request->getPost("Tagmeta");
        $config->address = $this->request->getPost("address");
        $config->companyname = $this->request->getPost("company");
        $config->cellphone = $this->request->getPost("cellphone");
        $config->email = $this->request->getPost("email");
        if (!$config->save()) {

            foreach ($config->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "webconfig",
                'action' => 'edit',
                'params' => array($config->id_lang)
            ));

            return;
        }

        $this->flash->success("Đã lưu");
        return $this->response->redirect('/backend/webconfig/index');
    }
}