<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Service;

use Blog\Form\SearchForm;
use Blog\Util\Paginator\PaginatorFactory;
use Blog\Service\CategoryService;
use Blog\Service\PostService;

/**
 * Search Service
 *
 * @package Blog\Service
 */
class SearchService
    extends AbstractService
{
    /**
     * @const string
     **/
    const ENTITY_POST = 'Blog\Model\Post';

    /**
     * @var SearchForm
     **/
    private $form;

    /**
     * Gets paged search results from search options
     *
     * @param array $options
     * @param int $page
     * @return Zend\Paginator\Paginator
     **/
    public function pagedResults(array $options, $page = 1)
    {
        $this->getForm()->setData($options);

        $config = $this->em->getConfiguration();
        $config->addCustomDatetimeFunction('YEAR', 'Blog\Util\Query\Mysql\Year');

        $qb = $this->em->createQueryBuilder();

        $qb->select('p')
           ->from(self::ENTITY_POST, 'p')
           ->andWhere('p.isPublished = :published')
           ->orderBy('p.dateAdded', 'DESC');

        $params = array(
            'published' => 1
        );

        if(isset($options['search']) && $options['search']) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('p.content', ':search'),
                    $qb->expr()->like('p.title', ':search')
                )
            );

            $params['search'] = '%' . $options['search'] . '%';
        }

        if(isset($options['category']) && $options['category']) {
            $qb->join('p.category', 'c');
            $qb->andWhere('c.id = :category_id');
            $params['category_id'] = $options['category'];
        }

        if(isset($options['year']) && $options['year']) {
            $qb->andWhere('YEAR(p.dateAdded) = :year');
            $params['year'] = $options['year'];
        }

        $qb->setParameters($params);

        $paginator = PaginatorFactory::create($qb, $page, 5);

        return $paginator;
    }

    /**
     * Sets SearchForm
     *
     * @param SearchForm
     * @return void
     **/
    public function setForm(SearchForm $form)
    {
        $this->form = $form;
    }

    /**
     * Gets SearchForm
     *
     * @return SearchForm
     **/
    public function getForm()
    {
        return $this->form;
    }
}