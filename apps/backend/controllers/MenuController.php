<?php
/**
 * Created by PhpStorm.
 * User: SONY
 * Date: 6/20/2016
 * Time: 4:32 PM
 */
namespace Corephalcon\Backend\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Corephalcon\Modeldb\Models\Menu;
use Corephalcon\Modeldb\Models\Language;
use Corephalcon\Modeldb\Models\Category;
use Corephalcon\Modeldb\Models\Pagetype;

class MenuController extends ControllerBase
{
    public function indexAction()
    {
        $id_lang = "";

        if (isset($_GET['lang']))
            $id_lang = $_GET['lang'];

        $data = array(
            "langs" => Language::findAll(),
            "menus" => Menu::findall($id_lang)
        );

        return $this->view->data = $data;
    }

    public function newAction()
    {
        $cat = Category::findAll();
        $langs = Language::findAll();
        $pagetype = Pagetype::find();


        return $this->view->data = array('cats' => $cat, 'langs' => $langs, 'pagetype' => $pagetype);

    }

    public function editAction($id)
    {
        $cat = Category::findAll();
        $langs = Language::findAll();
        $pagetype = Pagetype::find();

        if (!$this->request->isPost()) {

            $menu = Menu::findFirstByid($id);
            if (!$menu) {
                $this->flash->error("Menu không tồn tại");
                return $this->response->redirect('backend/menu/index');
            }
            return $this->view->data = array('cats' => $cat, 'langs' => $langs, 'pagetype' => $pagetype, 'menu' => $menu);
        }
    }

    public function saveAction()
    {
        $menu = Menu::findFirstByid($this->request->getPost('id'));
        if (!$menu) {
            $this->flash->error("Bài viết không tồn tại");
            return $this->response->redirect('/backend/news/index');
        }
        $menu->name = $this->request->getPost("name");
        $menu->id_lang = $this->request->getPost("lang");
        $menu->position = $this->request->getPost("position");
        $menu->is_active = '1';
        if (isset($_POST["cat"]))
            $menu->slug_category = $this->request->getPost("cat");
        if (isset($_POST["pagetype"]))
            $menu->url = $this->request->getPost("pagetype");
        $menu->meta_description = $this->request->getPost("meta_desc");
        $menu->title = $this->request->getPost("title");
        $menu->is_static = '0';

        try {
            if (!$menu->save()) {
                foreach ($menu->getMessages() as $message) {
                    $this->flash->error($message);
                }
                $this->dispatcher->forward(array(
                    'controller' => "menu",
                    'action' => 'index'
                ));
            }
        } catch (Exception $e) {
            $this->flash->error(var_dump($e));
            $this->dispatcher->forward(array(
                'controller' => "menu",
                'action' => 'index'
            ));
        }
        return $this->response->redirect('/backend/menu/index');
    }

    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "menu",
                'action' => 'create'
            ));
            return;
        }

        $menu = new Menu();
        $menu->name = $this->request->getPost("name");
        $menu->id_lang = $this->request->getPost("lang");
        $menu->position = $this->request->getPost("position");
        $menu->is_active = '1';
        $menu->slug_category = $this->request->getPost("cat");
        $menu->url = $this->request->getPost("pagetype");
        $menu->meta_description = $this->request->getPost("meta_desc");
        $menu->title = $this->request->getPost("title");
        $menu->is_static = '0';
        if (!$menu->save()) {
            foreach ($menu->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "menu",
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

    public function editstatusAction()
    {
        $id = $this->request->getPost("id");
        $menu = Menu::findFirstByid($id);
        if ($menu->is_active == '0')
            $menu->is_active = '1';
        else
            $menu->is_active = '0';
        try {

            if (!$menu->save()) {
                foreach ($menu->getMessages() as $message) {
                    $this->flash->error($message);
                }
                return $this::sendText('Lỗi');
            }
        } catch (Exception $e) {
            return $this::sendText('Lỗi');
        }
        $data = "Mở";
        if ($menu->is_active == '0')
            $data = 'Đóng';

        return $this::sendText($data);
    }
}