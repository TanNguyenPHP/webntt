<?php
namespace Corephalcon\Backend\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Corephalcon\Modeldb\Models\Album;
use Corephalcon\Commons\ParamsConstant as params;
use Corephalcon\Commons\RemoveUnicode;

class AlbumController extends ControllerBase
{
    public function indexAction()
    {
        $filter = "";
        if (isset($_GET['filter'])) {
            $filter = $_GET['filter'];
        }
        $data = array('filter' => $filter,
            'albums' => Album::findAll($filter));
        return $this->view->data = $data;
    }

    public function editstatusAction()
    {
        $id = $this->request->getPost("id");
        $album = Album::findFirstByid($id);
        if ($album->is_website == '0')
            $album->is_website = '1';
        else
            $album->is_website = '0';
        try {

            if (!$album->save()) {
                foreach ($album->getMessages() as $message) {
                    $this->flash->error($message);
                }
                return $this::sendText('Lỗi');
            }
        } catch (Exception $e) {
            return $this::sendText('Lỗi');
        }
        $data = "Hiển thị";
        if ($album->is_website == '0')
            $data = 'Đóng';


        return $this::sendText($data);
    }
    public function delAction()
    {
        $id = $this->request->getPost("id");
        $album = Album::findFirstByid($id);
        $album->is_del = '1';
        if (!$album) {
            $this->flash->error("Album không tồn tại");

            $this->dispatcher->forward(array(
                'controller' => "album",
                'action' => 'index'
            ));

            return;
        }
        try {

            if (!$album->save()) {
                foreach ($album->getMessages() as $message) {
                    $this->flash->error($message);
                }
                return $this::sendText('fa fa-times');
            }
        } catch (Exception $e) {
            return $this::sendText('fa fa-times');
        }

        return $this::sendText('fa fa-check-circle');
    }
    public function createAction()
    {
        $album = new Album();

        $album->name = $this->request->getPost("name");
        $folder = RemoveUnicode::stripUnicode($album->name);
        $dir = params::pathfolderpicture . $folder;
        $result = parent::createFolder($dir);
        $is_website = isset($_POST["website"]) ? '1' : '0';
        if ($result == 1) {
            $album->folder = $folder;
            $album->dir = $dir;
            $album->desc = $this->request->getPost("desc");
            $album->datecreate = date('YmdHis');
            $album->is_del = '0';
            $album->is_website = $is_website;
            if ($album->save())
                return $this->response->redirect('/backend/album/new');
        } else if ($result == 0 || $result == 3) {
            $this->flash->error("Không tạo folder");
            return $this->response->redirect('/backend/album/index');
        } else if ($result == 2) {
            $this->flash->error("Folder đã được tạo");
            return $this->response->redirect('/backend/album/index');
        }

    }

    public function newAction()
    {

    }
}

