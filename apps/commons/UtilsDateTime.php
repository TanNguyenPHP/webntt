<?php
/**
 * Created by PhpStorm.
 * User: SONY
 * Date: 6/7/2016
 * Time: 1:57 PM
 */

namespace Corephalcon\commons;

class UtilsDateTime
{
    public static function ConvertStringToDateTime($date)
    {
        //$testDate=DateTime::createFromFormat('d/m/Y H:i', $date);
        return \DateTime::createFromFormat('d/m/Y H:i', $date);//YmdHis
    }
}