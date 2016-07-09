<?php
namespace Corephalcon\Modeldb\Models;

use Phalcon\Mvc\Model;

class MapsInfo extends Model
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
    public $title;

    /**
     *
     * @var string
     */
    public $image;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var string
     */
    public $position;
    public $id_lang;
    public $is_status;
    public $is_del;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'maps_info';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return MapsInfo[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MapsInfo
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public static function findAll($lang)
    {
        $condition = "is_del ='0' ";
        if ($lang != '')
            $condition = $condition . "and id_lang = '$lang'";
        return parent::find("$condition");
    }

}
