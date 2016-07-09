<?php
namespace Corephalcon\Backend\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Corephalcon\Modeldb\Models\Picture;
use Corephalcon\Modeldb\Models\Album;
use Corephalcon\Commons\ParamsConstant as params;
use Corephalcon\Commons\UploadHandler;

class PictureController extends ControllerBase
{

    public function indexAction()
    {
        $album = "";
        $limit = 10;
        $page = 1;
        if (isset($_GET['album']))
            $album = $_GET['album'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];
        if (isset($_GET['page']))
            $page = $_GET['page'];

        $pics = Picture::findPicPaging($page, $limit, $album);

        $data = array(
            "pictures" => $pics,
            "albums" => Album::find(),
            "album" => $album,
            "limit" => $limit,
            "page" => $page
        );

        return $this->view->data = $data;
    }

    public function newAction()
    {
        $this->view->data = Album::find();
    }

    public function delAction()
    {
        $id = $this->request->getPost("id");
        $pic = Picture::findFirstByid($id);
        $pic->is_del = '1';
        if (!$pic) {
            $this->flash->error("Hình ảnh ko tồn tại");

            $this->dispatcher->forward(array(
                'controller' => "picture",
                'action' => 'index'
            ));

            return;
        }
        try {

            if (!$pic->save()) {
                foreach ($pic->getMessages() as $message) {
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
        $uploader = new UploadHandler();

        // Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $uploader->allowedExtensions = array(); // all files types allowed by default

        // Specify max file size in bytes.
        $uploader->sizeLimit = null;

        // Specify the input name set in the javascript.
        $uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default

        // If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
        $uploader->chunksFolder = "chunks";

        $method = $_SERVER["REQUEST_METHOD"];
        try {
            if ($method == "POST") {
                header("Content-Type: text/plain");

                // Assumes you have a chunking.success.endpoint set to point here with a query parameter of "done".
                // For example: /myserver/handlers/endpoint.php?done
                if (isset($_GET["done"])) {
                    $result = $uploader->combineChunks(params::pathfolderpicture);
                } // Handles upload requests
                else {
                    // Call handleUpload() with the name of the folder, relative to PHP's getcwd()

                    $result = $uploader->handleUpload(str_replace("\\", "", params::pathfolderpicture));
                    // To return a name used for uploaded file you can use the following line.
                    $result["uploadName"] = $uploader->getUploadName();
                    $pic = new Picture();
                    $pic->id_album = $_REQUEST['albumid'];
                    $pic->datecreate = date('YmdHis');
                    $pic->position = '0';
                    $pic->is_del = '0';
                    $pic->name = $result["name"];
                    $pic->dir = $result["target"];
                    $pic->is_show = '1';
                    if (!$pic->save())
                        return $this->response->redirect('/backend/picture/new');

                }

                return json_encode($result);
            } // for delete file requests
            else if ($method == "DELETE") {
                $result = $uploader->handleDelete("files");
                return json_encode($result);
            } else {
                header("HTTP/1.0 405 Method Not Allowed");
            }
        } catch (Exception $e) {
            return $this->response->redirect('/backend/picture/new');
        }
    }
}

