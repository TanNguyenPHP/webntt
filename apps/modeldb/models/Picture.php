<?php
namespace Corephalcon\Modeldb\Models;
use Phalcon\Mvc\Model;

class Picture extends Model
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
    public $is_del;

    /**
     *
     * @var string
     */
    public $datecreate;

    /**
     *
     * @var integer
     */
    public $id_album;

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

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'picture';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Picture[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Picture
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public static function findAll($album)
    {
        $condition = "is_del ='0' and is_show='1' ";
        if ($album != '')
            $condition = $condition . "and id_album = '$album'";
        return parent::find("$condition");
    }

    public static function findPicOfAlbum($id = null)
    {
        $query = new \Phalcon\Mvc\Model\Query\Builder();
        $query->addFrom('Webdinhdalat\Modeldb\Models\Picture', 'a')
            ->columns('a')
            ->inWhere('a.id_album',$id);
        return $query->getQuery()->execute();
    }

    public static function findPicPaging($page, $limit, $album)
    {
        $queryBuilder = new \Phalcon\Mvc\Model\Query\Builder(self::buildparams($page, $limit, $album));

        $paginator = new \Phalcon\Paginator\Adapter\QueryBuilder(array(
            "builder" => $queryBuilder,
            "limit" => (int)$limit,
            "page" => (int)$page
        ));
        return $paginator->getPaginate();
    }
    private static function buildparams($page, $limit, $album)
    {
        $conditions = "is_del ='0' and is_show='1' ";
        if ($album != '')
            $conditions = $conditions . "and id_album = '$album' ";
        return $params = array(
            'models' => array('Corephalcon\Modeldb\Models\Picture'),
            'conditions' => $conditions,
            'orderby' => array('name')
        );
    }
}
