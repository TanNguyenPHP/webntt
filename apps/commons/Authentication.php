<?php
/**
 * Created by PhpStorm.
 * User: SONY
 * Date: 5/26/2016
 * Time: 9:48 PM
 */
namespace Corephalcon\commons;

use Phalcon\Session\Adapter;
use Phalcon\Di;

class Authentication
{
    public static function CheckAuth()
    {
        //https://docs.phalconphp.com/en/latest/reference/di.html
        if (empty(Di::getDefault()->getSession()->get('sessionUser'))) {
            return false;
        }
        return true;
    }
}