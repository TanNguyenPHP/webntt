<?php

namespace Corephalcon\Frontend\Controllers;

use Corephalcon\Modeldb\Models\News;

class AboutController extends ControllerBase
{

    public function indexAction()
    {
        $data = News::findFirstNewsOfCategory('1','1'); // danh muc about
        self::setMetaDescription($data->seo_desc);
        $this->tag->prependTitle($data->seo_title . " | ");
        return $this->view->data = $data;
    }

}
