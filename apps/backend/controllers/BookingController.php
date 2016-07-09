<?php
namespace Corephalcon\Backend\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Corephalcon\Modeldb\Models\Booking;

class BookingController extends ControllerBase
{

    public function indexAction()
    {
        $name = "";
        $phone = "";
        $page = 1;
        $limit = 10;

        if (isset($_GET['name']))
            $name = $_GET['name'];
        if (isset($_GET['phone']))
            $phone = $_GET['phone'];
        if (isset($_GET['page']))
            $page = $_GET['page'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        $books = Booking::findBookPaging($page, $limit, $name, $phone);

        $data = array(
            'data' => $books,
            'page' => $page,
            'limit' => $limit,
            'name' => $name,
            'phone' => $phone);
        return $this->view->data = $data;
    }

}

