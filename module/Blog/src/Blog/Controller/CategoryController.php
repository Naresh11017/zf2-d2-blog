<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Controller;

use Blog\Service\CategoryService;
use Blog\Service\PostService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Category Controller.
 *
 * @package Blog\Controller
 */
class CategoryController
    extends AbstractActionController
{
    /**
     * @var CategoryService
     **/
    protected $_categoryService;

    /**
     * @var PostService
     **/
    protected $_postService;

    /**
     * Blogs from a certain category
     *
     * @param void
     * @return ViewModel
     **/
    public function blogsAction()
    {
        $query = $this->getRequest()->getQuery();
        $category = $this->_categoryService->getFromName($query->name);
        $posts = $this->_postService->getPagedFromCategory($category, $query->page);

        return new ViewModel(array(
            'category' => $category,
            'posts' => $posts
        ));
    }

    /**
     * Not much to do here.  Everything's in the view.
     *
     * @param void
     * @return void
     **/
    public function viewAction()
    {
        // Silence is golden.
    }

    /**
     * Sets CategoryService
     *
     * @param CategoryService $service
     * @return void
     **/
    public function setCategoryService(CategoryService $service)
    {
        $this->_categoryService = $service;
    }

    /**
     * Sets PostService
     *
     * @param PostService $service
     * @return void
     **/
    public function setPostService(PostService $service)
    {
        $this->_postService = $service;
    }

}