<?php
namespace Corephalcon\Modeldb\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query;
use Phalcon\Di;

class Category extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $desc;

    /**
     *
     * @var integer
     */
    public $pid;
    public $is_status;
    public $slug;
    public $is_del;
    public $title;
    public $meta_description;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'category';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Category[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Category
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public static function findAll()
    {
        return parent::find("is_status = '1' and is_del = '0'");
    }

    public static function findConditionAll($pid = "",$slug="" ,$is_status = "")
    {
        $queryBuilder = new \Phalcon\Mvc\Model\Query\Builder(self::buildparams($pid ,$slug, $is_status));

        return $queryBuilder->getQuery()->execute();
    }

    private static function buildparams($pid = "",$slug="", $is_status = "")
    {
        $conditions = "is_del = '0' ";
        if ($pid != '')
            $conditions = $conditions . "and pid = '$pid' ";
        if ($is_status != '')
            $conditions = $conditions . "and is_status = '$is_status' ";
        if ($slug != '')
            $conditions = $conditions . "and slug = '$slug' ";
        return $params = array(
            'models' => array('Corephalcon\Modeldb\Models\Category'),
            'conditions' => $conditions,
            'orderby' => array('name')
        );
    }

}
