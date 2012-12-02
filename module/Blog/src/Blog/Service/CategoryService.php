<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Service;

use Blog\Form\CategoryForm;

/**
 * Category Service
 *
 * @package Blog\Service
 */
class CategoryService
    extends AbstractService
{
    /**
     * @const string
     **/
    const ENTITY_CATEGORY = 'Blog\Model\Category';

    /**
     * @var CategoryForm
     **/
    private $form;

    /**
     * Saves a category
     *
     * @param array $data
     * @return boolean
     * @todo
     **/
    public function save($data = array())
    {
        //todo
    }

    /**
     * Gets all categories
     *
     * @param void
     * @return array
     **/
    public function getAll()
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('c')
           ->from(self::ENTITY_CATEGORY, 'c')
           ->orderBy('c.name', 'ASC');

        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result;
    }

    /**
     * Gets a category from it's name
     *
     * @param string $name
     * @return Category
     **/
    public function getFromName($name)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('c')
           ->from(self::ENTITY_CATEGORY, 'c')
           ->where('c.name = :name')
           ->setParameter('name', $name);

        $query = $qb->getQuery();
        $result = $query->getSingleResult();

        return $result;
    }

    /**
     * Finds a category from it's ID
     *
     * @param int $id
     * @return Category
     **/
    public function findById($id)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('c')
           ->from(self::ENTITY_CATEGORY, 'c')
           ->where('c.id = :id')
           ->setParameter('id', $id);

        $query = $qb->getQuery();
        $result = $query->getSingleResult();

        return $result;
    }

    /**
     * Gets categories with their total number of published posts
     *
     * @param void
     * @return array
     **/
    public function getPublishedWithCount()
    {
        $sql = "SELECT COUNT(c.id) AS count, c.id, c.name "
             . "FROM categories c "
             . "INNER JOIN posts p ON p.category_id = c.id "
             . "WHERE is_published = ? "
             . "GROUP BY c.id "
             . "ORDER BY c.name ASC";

        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->bindValue(1, 1);
        $stmt->execute();
        $data = $stmt->fetchAll();

        return $data;
    }

    /**
     * Sets CategoryForm
     *
     * @param CategoryForm $form
     * @return void
     **/
    public function setForm(CategoryForm $form)
    {
        $this->form = $form;
    }
}