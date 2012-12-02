<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Service;

use Blog\Form\PostForm;
use Blog\Model\Category;
use Blog\Service\CategoryService;
use Blog\Util\Paginator\PaginatorFactory;
use DateTime;
use Zend\Feed\Writer\Feed;

/**
 * Post Service
 *
 * @package Blog\Service
 */
class PostService
    extends AbstractService
{
    /**
     * @const string
     **/
    const ENTITY_POST = 'Blog\Model\Post';

    /**
     * @var PostForm
     **/
    private $form;

    /**
     * Saves a post
     *
     * @param array $data
     * @return boolean
     **/
    public function save($data = array())
    {
        $data['content'] = str_replace(array('&Acirc;', '&#160;'), '', $data['content']);
        $form = $this->getForm();
        $form->setData($data);

        if($form->isValid()) {

            $post = $form->getData();
            $post->setDateModified(new DateTime());

            // Silly Doctrine.
            $categoryService = new CategoryService($this->em);
            $category = $categoryService->findById($post->getCategory()->getId());

            $post->setCategory($category);

            $this->em->persist($post);
            $this->em->flush();

            $this->addMessage('Successfully saved post.', self::MSG_NOTICE);

            return true;
        }

        $this->addMessage('Kindly fix the form errors.', self::MSG_ERROR);

        return false;
    }

    /**
     * Gets paged posts
     *
     * @param int $page
     * @return Zend\Paginator\Paginator
     **/
    public function getPaged($page = 1)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('partial p.{id, title, dateAdded, isPublished}, c')
           ->from(self::ENTITY_POST, 'p')
           ->join('p.category', 'c')
           ->orderBy('p.isPublished', 'DESC')
           ->addOrderBy('p.dateAdded', 'DESC');

        $paginator = PaginatorFactory::create($qb, $page, 10);

        return $paginator;
    }

    /**
     * Gets single post from title
     *
     * @param string $title
     * @return Post
     **/
    public function getFromTitle($title)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('p')
           ->from(self::ENTITY_POST, 'p')
           ->join('p.category', 'c')
           ->join('p.user', 'u')
           ->where('p.title = :title')
           ->andWhere('p.isPublished = :published')
           ->orderBy('p.dateAdded', 'DESC')
           ->setParameters(array(
               'published' => 1,
               'title' => $title
            ));

        $query = $qb->getQuery();
        $result = $query->getSingleResult();

        return $result;
    }

    /**
     * Gets single post from ID
     *
     * @param int $id
     * @return Post
     **/
    public function getFromId($id)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('p')
           ->from(self::ENTITY_POST, 'p')
           ->join('p.category', 'c')
           ->where('p.id = :id')
           ->orderBy('p.dateAdded', 'DESC')
           ->setParameters(array(
               'id' => $id
            ));

        $query = $qb->getQuery();
        $result = $query->getSingleResult();

        return $result;
    }

    /**
     * Gets paged posts from a category
     *
     * @param Category $category
     * @param int $page
     * @return Zend\Paginator\Paginator
     **/
    public function getPagedFromCategory(Category $category, $page)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('partial p.{id, title}')
           ->from(self::ENTITY_POST, 'p')
           ->join('p.category', 'c')
           ->orderBy('p.dateAdded', 'DESC')
           ->where('c.id = :id')
           ->andWhere('p.isPublished = :published')
           ->setParameters(array(
               'id' => $category->getId(),
               'published' => 1
            ));

        $paginator = PaginatorFactory::create($qb, $page);

        return $paginator;
    }

    /**
     * Gets paged posts from a year
     *
     * @param int $year
     * @param int $page
     * @return Zend\Paginator\Paginator
     **/
    public function getPagedFromArchive($year, $page)
    {
        $qb = $this->em->createQueryBuilder();

        $config = $this->em->getConfiguration();
        $config->addCustomDatetimeFunction('YEAR', 'Blog\Util\Query\Mysql\Year');

        $qb->select('partial p.{id, title}')
           ->from(self::ENTITY_POST, 'p')
           ->where('p.isPublished = :published')
           ->andWhere('YEAR(p.dateAdded) = :year')
           ->orderBy('p.dateAdded', 'DESC')
           ->setParameters(array(
               'published' => 1,
               'year' => $year
            ));

        $paginator = PaginatorFactory::create($qb, $page);

        return $paginator;
    }

    /**
     * Gets the latest published post
     *
     * @param void
     * @return Post
     **/
    public function getLatest()
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('p')
           ->from(self::ENTITY_POST, 'p')
           ->orderBy('p.dateAdded', 'DESC')
           ->where('p.isPublished = :published')
           ->setMaxResults(1)
           ->setParameters(array(
               'published' => 1
            ));

        $query = $qb->getQuery();
        $result = $query->getSingleResult();

        return $result;
    }

    /**
     * Gets the most recently published posts
     *
     * @param int $limit
     * @return array
     **/
    public function getMostRecent($limit)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('partial p.{id, title}')
           ->from(self::ENTITY_POST, 'p')
           ->orderBy('p.dateAdded', 'DESC')
           ->where('p.isPublished = :published')
           ->setMaxResults($limit)
           ->setParameters(array(
               'published' => 1
            ));

        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result;
    }

    /**
     * Gets RSS feed of published posts
     *
     * @param void
     * @return Zend\Feed\Writer\Feed
     **/
    public function getRssFeed()
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('p')
           ->from(self::ENTITY_POST, 'p')
           ->where('p.isPublished = :published')
           ->orderBy('p.dateAdded', 'DESC')
           ->setMaxResults(10)
           ->setParameters(array(
               'published' => 1
            ));

        $query = $qb->getQuery();
        $result = $query->getResult();

        $feed = new Feed();
        $feed->setTitle("Rob's Blog");
        $feed->setFeedLink('http://'. $_SERVER['SERVER_NAME'] .'/blog/rss', 'atom');
        $feed->addAuthor(array(
            'name' => 'Rob',
            'email' => 'rob@robkeplin.com',
            'uri' => 'http://' . $_SERVER['SERVER_NAME']
        ));
        $feed->setDescription("A PHP developer's blog");
        $feed->setLink('http://' . $_SERVER['SERVER_NAME']);
        $feed->setDateModified($result[0]->getDateAdded());

        foreach($result as $post)
        {
            $entry = $feed->createEntry();
            $entry->setTitle($post->getTitle());
            $entry->setLink(sprintf('http://'. $_SERVER['SERVER_NAME'] .'/blog/view?title=%s',
                                strtolower(urlencode($post->getTitle()))
                            ));
            $entry->setDescription(substr(strip_tags($post->getContent()), 0, 150) . '...');
            $entry->setDateModified($post->getDateAdded());
            $entry->setDateCreated($post->getDateAdded());

            $feed->addEntry($entry);
        }

        $feed->export('rss');

        return $feed;
    }

    /**
     * Gets years in which published posts exist
     *
     * @param void
     * @return array
     **/
    public function getValidYears()
    {
        $sql = "SELECT DISTINCT YEAR(date_added) AS year "
             . "FROM posts "
             . "WHERE is_published = ? "
             . "ORDER BY date_added DESC";

        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->bindValue(1, 1);
        $stmt->execute();

        $data = $stmt->fetchAll();

        $years = array();

        foreach($data as $row)
        {
            $years[] = $row['year'];
        }

        return $years;
    }

    /**
     * Sets PostForm
     *
     * @param PostForm $form
     * @return void
     **/
    public function setForm(PostForm $form)
    {
        $this->form = $form;
    }

    /**
     * Gets PostForm
     *
     * @param void
     * @return PostForm
     **/
    public function getForm()
    {
        return $this->form;
    }
}