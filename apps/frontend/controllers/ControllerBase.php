<?php

namespace Corephalcon\Frontend\Controllers;

use Phalcon\Mvc\Controller;
use Corephalcon\Commons\ParamsSEO;

class ControllerBase extends Controller
{
    public function initialize()
    {
        // Add some local CSS resources
        $this->assets
            ->addCss('/stylesheets/style.css')
            ->addCss('/stylesheets/skins/blue.css')
            ->addCss('/stylesheets/responsive.css');


        // And some local JavaScript resources
        $this->assets
            ->addJs('/javascripts/foundation.min.js')
            ->addJs('/javascripts/jquery.easing.1.3.js')
            ->addJs('/javascripts/elasticslideshow.js')
            ->addJs('/javascripts/jquery.carouFredSel-6.0.5-packed.js')
            ->addJs('/javascripts/jquery.cycle.js')
            ->addJs('/javascripts/app.js')
            ->addJs('/javascripts/modernizr.foundation.js')
            ->addJs('/javascripts/slidepanel.js')
            ->addJs('/javascripts/scrolltotop.js')
            ->addJs('/javascripts/hoverIntent.js')
            ->addJs('/javascripts/superfish.js')
            ->addJs('/javascripts/responsivemenu.js')
            ->addJs('/js/jquery/jquery.validate.min.js');

        $this->tag->setTitle("Trường đại học Nguyễn Tất Thành");
        self::setMetaDescription("Test he thống");
    }
    protected function setMetaDescription($content)
    {
        ParamsSEO::$meta_description = "$content";
    }
    protected function sendJson($data)
    {
        $this->view->disable();
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($data));
        return $this->response;
    }
    protected function sendJsonNoConvert($data)
    {
        $this->view->disable();
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent($data);
        return $this->response;
    }
    protected function sendText($data)
    {
        $this->view->disable();
        $this->response->setContentType('text/plain', 'UTF-8');
        $this->response->setContent($data);
        return $this->response;
    }
}
