<?php
namespace Corephalcon\Modeldb\Models;

use Phalcon\Mvc\Model\Validator\Email as Email;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query;
use Phalcon\Di;
use Phalcon\Mvc\Model\Manager as ModelsManager;

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
    public $id_lang;
    public $fax;
    public $facebook;
    public $google;
    public $twitter;

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

    public static function findall($id_lang = '')
    {
        //$queryBuilder = new \Phalcon\Mvc\Model\Query\Builder(self::buildparams($id_lang));
        //$query = $queryBuilder->getPhql();
        //return $queryBuilder->getQuery()->execute();

        $sql = "select w.*,l.lang from webconfig w join `language` l on w.id_lang=l.id where 1=1";
        if ($id_lang != '') {
            $sql = $sql . " and w.id_lang = '$id_lang'";
        }
        $didb = \Phalcon\DI::getDefault()->getShared('db');
        $result = $didb->query("$sql");
        return $result->fetchAll();
    }

    private static function buildparams($id_lang = '')
    {
        //$query = new \Phalcon\Mvc\Model\Query\Builder(self::buildparams($id_lang));

        $conditions = '1=1';
        if ($id_lang != '')
            $conditions = $conditions . " and w.id_lang = '$id_lang' ";
        return $params = array(
            //'models' => array('w' => 'Webdinhdalat\Modeldb\Models\Webconfig'),
            //'columns' => array('w.id_lang', 'w.title', 'w.meta', 'w.email', 'w.address', 'w.cellphone', 'w.companyname'),
            //'joins' => array('join' => array('Webdinhdalat\Modeldb\Models\Language', 'l.id = w.id_lang', 'l'))
            //'conditions' => $conditions
            // or 'conditions' => "created > '2013-01-01' AND created < '2014-01-01'",
            //'order' => array('l.lang')
            // or 'limit' => array(20, 20),
        );
    }
}
