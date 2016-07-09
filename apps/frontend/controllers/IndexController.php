<?php

namespace Corephalcon\Frontend\Controllers;
use Corephalcon\Modeldb\Models\Sliderpic;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        return $this->view->data = Sliderpic::findAll();
    }

}

