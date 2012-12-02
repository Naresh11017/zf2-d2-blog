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
 * CategoryList
 *
 * @package Blog\View
 */
class CategoryList
    extends AbstractHelper
{
    /**
     * @var array
     **/
    private $categories;

    /**
     * Returns HTML list items for the categories
     *
     * @param void
     * @return string
     * @override
     **/
    public function __invoke()
    {
        $string = '';

        foreach($this->categories as $category) {
            $url = $this->view->myUrl('blog/default/query', array('controller' => 'category', 'action' => 'blogs', 'name' => urlencode($category['name'])));
            $string .= sprintf('<li><a title="%s" href="%s">%s (%s)</a></li>', $category['name'], $url, $category['name'], $category['count']);
        }

        return $string;
    }

    /**
     * Sets categories
     *
     * @param array $categories
     * @return void
     **/
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }
}