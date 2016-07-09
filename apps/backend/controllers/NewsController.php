<?php
namespace Corephalcon\Backend\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Corephalcon\Modeldb\Models\News;
use Corephalcon\Modeldb\Models\Category;
use Corephalcon\Modeldb\Models\Language as Lang;
use Corephalcon\Commons\ParamsConstant as Params;
use Corephalcon\Commons\UtilsDateTime;
use Corephalcon\Commons\UtilsSEO;
use Phalcon\Di;

class NewsController extends ControllerBase
{

    public function indexAction()
    {

        $dateTo = "";
        $dateFrom = "";
        $filter = "";
        $cat = "";
        $limit = 10;
        $page = 1;
        $id_lang = "";

        if (!empty($_GET['DateTo']))
            $dateTo = UtilsDateTime::ConvertStringToDateTime($_GET['DateTo'])->format('Ymd235959');
        if (!empty($_GET['DateFrom']))
            $dateFrom = UtilsDateTime::ConvertStringToDateTime($_GET['DateFrom'])->format('Ymd000000');
        if (isset($_GET['filter']))
            $filter = $_GET['filter'];
        if (isset($_GET['cat']))
            $cat = (int)$_GET['cat'];
        if (isset($_GET['limit']))
            $limit = (int)$_GET['limit'];
        if (isset($_GET['page']))
            $page = (int)$_GET['page'];

        $listnews = News::findNewsPaging($page, $limit, $filter, $dateTo, $dateFrom, $cat, $id_lang);

        if ($dateFrom != '')
            $dateFrom = \DateTime::createFromFormat('YmdHis', $dateFrom)->format('d/m/Y H:i');
        if ($dateTo != '')
            $dateTo = \DateTime::createFromFormat('YmdHis', $dateTo)->format('d/m/Y H:i');
        $data = array(
            "listnews" => $listnews,
            "cats" => Category::findConditionAll(),
            "dateTo" => $dateTo,
            "dateFrom" => $dateFrom,
            "filter" => $filter,
            "cat" => $cat,
            "limit" => $limit,
            "page" => $page
        );

        return $this->view->data = $data;
    }

    public function editAction($id)
    {

        $news = News::findFirstByid($id);
        if (!$news) {
            $this->flash->error("Bài viết không tồn tại");
            return $this->response->redirect('/backend/news/index');
        }
        $data = array(
            "news" => $news,
            "cats" => Category::findAll(),
            "langs" => Lang::findAll()
        );

        return $this->view->data = $data;
    }

    public function saveAction()
    {

        $news = News::findFirstByid($this->request->getPost('id'));
        if (!$news) {
            $this->flash->error("Bài viết không tồn tại");
            return $this->response->redirect('/backend/news/index');
        }

        $news->title = $this->request->getPost('title');
        $news->content_short = $this->request->getPost('content_short');
        $news->content = $this->request->getPost('content');
        $news->position = $this->request->getPost('position');
        $news->id_category = $this->request->getPost('cat');
        $news->id_lang = $this->request->getPost('lang');
        $news->seo_title = $this->request->getPost('seo_title');
        $news->seo_desc = $this->request->getPost('seo_desc');
        $news->id_user = Di::getDefault()->getSession()->get('sessionUser');
        $news->is_status = isset($_POST["is_status"]) ? '1' : '0';
        $news->slug = UtilsSEO::CreateSlug($news->title) . '-' . date('dmYHi');
        try {
            if (isset($_FILES['avatar_image'])) {
                if ($_FILES['avatar_image']['size'] != 0) {
                    $this::saveImg($_FILES['avatar_image']);
                    $news->avatar_image = Params::pathfolderavatarimage . $_FILES['avatar_image']['name'];
                }
            }

            if (!$news->save()) {
                foreach ($news->getMessages() as $message) {
                    $this->flash->error($message);
                }
                $this->dispatcher->forward(array(
                    'controller' => "news",
                    'action' => 'index'
                ));
            }
        } catch (Exception $e) {
            $this->flash->error(var_dump($e));
            $this->dispatcher->forward(array(
                'controller' => "news",
                'action' => 'index'
            ));
        }

        return $this->response->redirect('/backend/news/index');

    }

    public function newAction()
    {

        $data = array(
            "langs" => Lang::findAll(),
            "cats" => Category::findAll()
        );
        $this->view->data = $data;
    }

    public function createAction()
    {
        $news = new News();
        $news->title = $this->request->getPost('title');
        $news->content_short = $this->request->getPost('content_short');
        $news->content = $this->request->getPost('content');
        $news->position = $this->request->getPost('position');
        $news->id_category = $this->request->getPost('cat');
        $news->id_lang = $this->request->getPost('lang');
        $news->seo_title = $this->request->getPost('seo_title');
        $news->seo_desc = $this->request->getPost('seo_desc');
        $news->datecreate = date('YmdHis');
        $news->id_user = Di::getDefault()->getSession()->get('sessionUser');
        $news->is_del = '0';
        $news->is_status = '1';
        $news->slug = UtilsSEO::CreateSlug($news->title) . '-' . date('dmYHi');

        try {
            $this::saveImg($_FILES['avatar_image']);
            $news->avatar_image = Params::pathfolderavatarimage . $_FILES['avatar_image']['name'];
            if (!$news->save()) {
                foreach ($news->getMessages() as $message) {
                    $this->flash->error($message);
                }
                $this->dispatcher->forward(array(
                    'controller' => "news",
                    'action' => 'new'
                ));
            }
        } catch (Exception $e) {
            $this->flash->error(var_dump($e));
            $this->dispatcher->forward(array(
                'controller' => "news",
                'action' => 'new'
            ));
        }

        return $this->response->redirect('/backend/news/index');

    }

    private function saveImg($file)
    {
        $uploadfile = Params::pathfolderavatarimage . basename($file['name']);
        $this::saveFile($file['tmp_name'], $uploadfile);
    }
}