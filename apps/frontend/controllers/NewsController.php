<?php

namespace Corephalcon\Frontend\Controllers;

use Corephalcon\Modeldb\Models\News;
use Corephalcon\Modeldb\Models\Category;

class NewsController extends ControllerBase
{

    public function indexAction($id = "", $pages = "")
    {
        $page = '1';
        if (isset($_GET['pages']))
            $page = $_GET['pages'];
        $cats = Category::findConditionAll('2', '', '1');
        $cat = $cats[0];
        if ($id != "") {
            foreach ($cats as $item) {
                if ($item->id == $id) {
                    $cat = $item;
                }
            }
        }
        $news = News::findNewsPaging($page, '5', '', '', '', $cat->id, '1', '');
        return $this->view->data = array('news' => $news, 'cats' => $cats, 'cat' => $cat);
    }

    public function detailAction($id)
    {
        $news= News::findFirstByslug($id);
        if (!$news) {
            $this->flash->error("Bài viết không tồn tại");
            return $this->response->redirect('/news/index');
        }
        $cats = Category::findConditionAll('2', '', '1');
        $cat = $cats[0];
        if ($id != "") {
            foreach ($cats as $item) {
                if ($item->id == $news->id_category) {
                    $cat = $item;
                }
            }
        }
        return $this->view->data = array('news' => $news, 'cats' => $cats, 'cat' => $cat);
    }
}