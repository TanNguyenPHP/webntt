<?php
namespace Corephalcon\Modeldb\Models;

use Phalcon\Mvc\Model;

class News extends Model
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
    public $content;

    /**
     *
     * @var string
     */
    public $seo_title;

    /**
     *
     * @var string
     */
    public $seo_desc;

    /**
     *
     * @var string
     */
    public $seo_keyword;

    /**
     *
     * @var string
     */
    public $url;

    /**
     *
     * @var integer
     */
    public $id_lang;

    /**
     *
     * @var string
     */
    public $is_status;

    /**
     *
     * @var string
     */
    public $is_del;

    /**
     *
     * @var string
     */
    public $position;

    /**
     *
     * @var string
     */
    public $is_home;

    /**
     *
     * @var integer
     */
    public $id_category;

    /**
     *
     * @var string
     */
    public $avatar_image;

    /**
     *
     * @var string
     */
    public $datecreate;
    public $id_user;
    public $content_short;
    public $slug;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'news';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return News[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return News
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public static function findNewsPaging($page = '', $limit = '', $filter = '', $dateTo = '', $dateFrom = '', $cat = '', $id_lang = '', $content = '')
    {
        $queryBuilder = new \Phalcon\Mvc\Model\Query\Builder(self::buildparams($page, $limit, $filter, $dateTo, $dateFrom, $cat, $id_lang, $content));

        $paginator = new \Phalcon\Paginator\Adapter\QueryBuilder(array(
            "builder" => $queryBuilder,
            "limit" => (int)$limit,
            "page" => (int)$page
        ));
        return $paginator->getPaginate();
    }

    public static function findAllNewsOfCategory($cat = '', $id_lang = '', $page = '', $limit = '')
    {
        $queryBuilder = new \Phalcon\Mvc\Model\Query\Builder(self::buildparams($page, $limit, '', '', '', $cat, $id_lang, ''));

        return $queryBuilder->getQuery()->execute();
    }

    public static function findFirstNewsOfCategory($cat = '', $id_lang = '')
    {
        return parent::findFirst("id_category = '$cat' and id_lang = '$id_lang'");
    }

    private static function buildparams($page, $limit, $filter = '', $dateTo = '', $dateFrom = '', $cat = '', $id_lang = '', $content = '')
    {
        $conditions = '1=1';
        if ($filter != '')
            $conditions = $conditions . " and title like '%$filter%' ";
        if ($dateFrom != '')
            $conditions = $conditions . " and datecreate >= '$dateFrom'";
        if ($dateTo != '')
            $conditions = $conditions . " and datecreate <= '$dateTo'";
        if ($cat != '')
            $conditions = $conditions . " and id_category = '$cat'";
        if ($id_lang != '')
            $conditions = $conditions . " and id_lang = '$id_lang'";
        $conditions = $conditions . " and is_del != '1' ";
        $cols = array('id', 'title', 'position', 'id_category', 'datecreate', 'id_lang', 'is_status', 'avatar_image', 'content_short', 'slug');
        if ($content != '')
            $cols = array('id', 'title', 'position', 'id_category',
                'datecreate', 'id_lang', 'is_status',
                'avatar_image', 'content', 'content_short',
                'slug', 'seo_title', 'seo_desc', 'seo_keyword');
        return $params = array(
            'models' => array('Corephalcon\Modeldb\Models\News'),
            'columns' => $cols,
            'conditions' => $conditions,
            // or 'conditions' => "created > '2013-01-01' AND created < '2014-01-01'",
            'order' => 'datecreate desc, title'
            //'limit' =>  "$limit"
        );
    }

}
