<?php
namespace Corephalcon\Modeldb\Models;
use Phalcon\Mvc\Model;
class Sliderpic extends Model
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
    public $dir;

    /**
     *
     * @var string
     */
    public $desc;

    /**
     *
     * @var string
     */
    public $position;

    /**
     *
     * @var string
     */
    public $is_show;
    public $is_del;
    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'sliderpic';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Sliderpic[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }
    public static function findAll()
    {
        return parent::find("is_del = '0' ");
    }
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Sliderpic
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
