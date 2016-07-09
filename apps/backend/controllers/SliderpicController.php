<?php
/**
 * Created by PhpStorm.
 * User: SONY
 * Date: 09-07-2016
 * Time: 4:27 PM
 */

namespace Corephalcon\Backend\Controllers;

use Corephalcon\Commons\ParamsConstant as params;
use Corephalcon\Modeldb\Models\Sliderpic;
use Corephalcon\Commons\UploadHandler;

class SliderpicController extends ControllerBase
{
    public function indexAction()
    {
        return $this->view->data = Sliderpic::findAll();
    }

    public function newAction()
    {

    }
    public function editstatusAction()
    {
        $id = $this->request->getPost("id");
        $pic = Sliderpic::findFirstByid($id);
        if ($pic->is_show == '0')
            $pic->is_show = '1';
        else
            $pic->is_show = '0';
        try {

            if (!$pic->save()) {
                foreach ($pic->getMessages() as $message) {
                    $this->flash->error($message);
                }
                return $this::sendText('Lỗi');
            }
        } catch (Exception $e) {
            return $this::sendText('Lỗi');
        }
        $data = "fa fa-check-circle";
        if ($pic->is_show == '0')
            $data = 'fa fa-times';


        return $this::sendText($data);
    }
    public function delAction()
    {
        $id = $this->request->getPost("id");
        $pic = Sliderpic::findFirstByid($id);
        if ($pic->is_del == '0')
            $pic->is_del = '1';
        try {

            if (!$pic->save()) {
                foreach ($pic->getMessages() as $message) {
                    $this->flash->error($message);
                }
                return $this::sendText('Lỗi');
            }
        } catch (Exception $e) {
            return $this::sendText('Lỗi');
        }
        $data = "fa fa-check-circle";

        return $this::sendText($data);
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

                    $result = $uploader->handleUpload(rtrim(params::pathfoldersliderpic,'\\'));
                    // To return a name used for uploaded file you can use the following line.
                    $result["uploadName"] = $uploader->getUploadName();
                    $pic = new Sliderpic();
                    $pic->position = '0';
                    $pic->is_show = '1';
                    $pic->name = $result["name"];
                    $pic->dir = $result["target"];
                    $pic->is_del = '0';
                    if (!$pic->save())
                        return $this->response->redirect('/backend/sliderpic/new');

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