<?php
/**
 * Created by PhpStorm.
 * User: SONY
 * Date: 6/28/2016
 * Time: 9:56 AM
 */
namespace Corephalcon\commons;
use Phalcon\Tag;

class CustomTag extends Tag
{
    public static $Description = null;

    public static function setDescription($content)
    {
        self::$Description = $content;
    }

    public static function getDescription()
    {
        return '<meta name="description" content="' + self::$Description + '">';
    }
}
