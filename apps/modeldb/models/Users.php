<?php
namespace Corephalcon\Modeldb\Models;

use Phalcon\Mvc\Model;

class Users extends Model
{

    /**
     *
     * @var string
     */
    public $id;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $datecreate;

    /**
     *
     * @var string
     */
    public $is_active;

    /**
     *
     * @var string
     */
    public $is_del;

    /**
     *
     * @var string
     */
    public $room;

    /**
     *
     * @var string
     */
    public $email;
    public $desc;
    public $group;
    public $address;
    public $name;
    public $dob;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public static function findparams($username, $name)
    {
        $querystring = '1=1';
        if ($username != '')
            $querystring = $querystring . " and username like '%$username%' ";
        if ($name != '')
            $querystring = $querystring . " and name like '%$name%'";
        return parent::find($querystring);
    }

    public static function findUsersPaging($username, $name, $page, $limit)
    {
        $queryBuilder = new \Phalcon\Mvc\Model\Query\Builder(self::buildparams($username, $name, $page, $limit));

        $paginator = new \Phalcon\Paginator\Adapter\QueryBuilder(array(
            "builder" => $queryBuilder,
            "limit" => (int)$limit,
            "page" => (int)$page
        ));
        return $paginator->getPaginate();
    }

    private static function buildparams($username, $name, $page, $limit)
    {
        $conditions = '1=1';
        if ($username != '')
            $conditions = $conditions . " and username like '%$username%' ";
        if ($name != '')
            $conditions = $conditions . " or name like '%$name%'";
        return $params = array(
            'models' => array('Corephalcon\Modeldb\Models\Users'),
            'columns' => array('id', 'username', 'name', 'is_active','datecreate'),
            'conditions' => $conditions,
            // or 'conditions' => "created > '2013-01-01' AND created < '2014-01-01'",
            'order' => array('name')
            // or 'limit' => array(20, 20),
        );
    }
}
