<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Util\Paginator;

use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrinePaginatorAdapter;
use Zend\Paginator\Paginator as ZendPaginator;

/**
 * Paginator Factory
 *
 * @package Blog\Util
 */
class PaginatorFactory
{
    /**
     * Returns a paginator instance from Doctrine query
     *
     * @param mixed {Doctrine\ORM\Query, Doctrine\ORM\QueryBuilder}
     * @param int $page
     * @param int $items_per_page
     * @return Zend\Paginator\Paginator
     **/
    public static function create($qb, $page = 1, $items_per_page = 10)
    {
        $dPaginator = new DoctrinePaginator($qb);
        $adapter = new DoctrinePaginatorAdapter($dPaginator);

        $zfPaginator = new ZendPaginator($adapter);
        $zfPaginator->setCurrentPageNumber($page);
        $zfPaginator->setItemCountPerPage($items_per_page);

        return $zfPaginator;
    }
}