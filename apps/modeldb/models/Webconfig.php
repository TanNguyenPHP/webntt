<?php

namespace Corephalcon\Modeldb\Models;
use Phalcon\Mvc\Model;
class Webconfig extends Model
{

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $meta;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $cellphone;

    /**
     *
     * @var string
     */
    public $companyname;

    /**
     *
     * @var integer
     */
    public $id_lang;

    /**
     *
     * @var string
     */
    public $fax;

    /**
     *
     * @var string
     */
    public $facebook;

    /**
     *
     * @var string
     */
    public $google;

    /**
     *
     * @var string
     */
    public $twitter;
    public $forum;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'webconfig';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Webconfig[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }
    public static function findAll($id_lang="")
    {
        return parent::find("id_lang = '$id_lang'");
    }
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Webconfig
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
