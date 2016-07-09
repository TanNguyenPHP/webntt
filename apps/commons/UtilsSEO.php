<?php
/**
 * Created by PhpStorm.
 * User: SONY
 * Date: 6/22/2016
 * Time: 11:08 AM
 */

namespace Corephalcon\commons;

use Corephalcon\Commons\RemoveUnicode;

class UtilsSEO
{
    public static function CreateSlug($text)
    {
        $text = RemoveUnicode::SpaceUnicode($text);
        $text= \str_replace(" ","-",$text);
        return $text;
    }
}