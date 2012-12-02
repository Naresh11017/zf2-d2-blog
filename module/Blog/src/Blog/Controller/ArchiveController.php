<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Controller;

use Blog\Service\PostService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Archive Controller.
 *
 * @package Blog\Controller
 */
class ArchiveController
    extends AbstractActionController
{
    /**
     * @var PostService
     **/
    protected $_postService;

    /**
     * Not much to do here.  Everything is in the view.
     *
     * @param void
     * @return void
     **/
    public function viewAction()
    {
        // Silence is golden.
    }

    /**
     * The blogs from a certain year
     *
     * @param void
     * @return ViewModel
     **/
    public function blogsAction()
    {
        $query = $this->getRequest()->getQuery();

        $posts = $this->_postService->getPagedFromArchive($query->year, $query->page);

        return new ViewModel(array(
            'year' => $query->year,
            'posts' => $posts
        ));
    }

    /**
     * Sets the PostService
     *
     * @param PostService $postService
     * @return void
     **/
    public function setPostService(PostService $postService)
    {
        $this->_postService = $postService;
    }
}
