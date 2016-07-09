<?php
namespace Corephalcon\Modeldb\Models;

use Phalcon\Mvc\Model;

class Album extends Model
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
    public $folder;

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
    public $desc;
    public $is_website;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'album';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Album[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    public static function findAll($name = "")
    {
        return parent::find(array("name like '%$name%' and is_del = '0'", 'order' => 'datecreate desc'));
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Album
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public static function findAlbumOfPicPaging($page = "", $limit = "")
    {
        $query = new \Phalcon\Mvc\Model\Query\Builder();// lay nhung album co anh kem theo
        $query->addFrom('Corephalcon\Modeldb\Models\Album', 'a')
            ->columns('a.id,a.name,p.dir')
            ->innerJoin('Corephalcon\Modeldb\Models\Picture', 'p.id_album = a.id', 'p')
            ->where("p.is_del = '0' and a.is_del = '0'")
            ->groupBy(array('a.id'))
            ->orderBy('a.datecreate desc');
        $paginator = new \Phalcon\Paginator\Adapter\QueryBuilder(array(
            "builder" => $query,
            "limit" => (int)$limit,
            "page" => (int)$page
        ));
        return $paginator->getPaginate();
    }

    public static function findAllAlbum($id = "", $name = "", $is_website = "")
    {
        $query = new \Phalcon\Mvc\Model\Query\Builder();
        $query->addFrom('Corephalcon\Modeldb\Models\Album', 'a')
            ->columns('a.name,a.id')
            ->innerJoin('Corephalcon\Modeldb\Models\Picture', 'p.id_album = a.id', 'p')
            ->where("p.is_del = '0' and a.is_del = '0'")
            ->groupBy(array('a.name,a.id'))
            ->orderBy('a.datecreate desc');
        return $query->getQuery()->execute();
    }

    public static function findPicOfAlbum($id_album = "", $name = "", $is_website = "")
    {
        $queryBuilder = new \Phalcon\Mvc\Model\Query\Builder(self::buildparams($id_album, $name, $is_website));

        $PicOfAlbums = $queryBuilder->getQuery()->execute();
        $albums = self::findAllAlbum();
        return array('albums' => $albums, 'pic' => $PicOfAlbums);
    }

    private static function buildparams($id_album = "", $name = "", $is_website = "")
    {
        $conditions = "p.is_del = '0' and a.is_del = '0'";
        if ($id_album != "")
            $conditions = $conditions . " and a.id = '$id_album' ";
        if ($name != '')
            $conditions = $conditions . " and a.name = '$name' ";
        if ($is_website != '')
            $conditions = $conditions . " and a.is_website = '$is_website' ";
        return $params = array(
            'models' => array('a' => 'Corephalcon\Modeldb\Models\Album'),
            'columns' => ('a.name, p.id, p.name, p.dir, p.id_album, p.position'),
            'innerJoin' => array('0' => array('Corephalcon\Modeldb\Models\Picture', 'p.id_album = a.id', 'p')),
            'conditions' => $conditions,
            'order' => array('a.datecreate desc')
            // or 'limit' => array(20, 20),
        );
    }

}
