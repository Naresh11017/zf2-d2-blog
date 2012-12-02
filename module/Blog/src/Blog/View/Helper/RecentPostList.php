<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * RecentPostList
 *
 * @package Blog\View
 */
class RecentPostList
    extends AbstractHelper
{
    /**
     * @var array
     **/
    private $posts;

    /**
     * Returns HTML list items for recent posts
     *
     * @param void
     * @return string
     * @override
     **/
    public function __invoke()
    {
        $string = '';

        foreach($this->posts as $post)
        {
            $url = $this->view->myUrl('blog/default/query', array('action' => 'view', 'title' => urlencode($post->getTitle())));
            $string .= sprintf('<li><a title="%s" href="%s">%s</a></li>', $post->getTitle(), $url, $post->getTitle());
        }

        return $string;
    }

    /**
     * Sets posts
     *
     * @param array $posts
     * @return void
     **/
    public function setPosts(array $posts)
    {
        $this->posts = $posts;
    }

}