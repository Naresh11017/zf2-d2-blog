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
 * ArchiveList
 *
 * @package Blog\View
 */
class ArchiveList
    extends AbstractHelper
{
    /**
     * @var array
     **/
    protected $years;

    /**
     * Returns HTML list items for archive
     *
     * @param void
     * @return string
     * @override
     **/
    public function __invoke()
    {
        $string = '';

        foreach($this->years as $year)
        {
            $url = $this->view->myUrl('blog/default/query', array('controller' => 'archive', 'action' => 'blogs', 'year' => $year));
            $string .= sprintf('<li><a title="%s" href="%s">%s</a></li>', $year, $url, $year);
        }

        return $string;
    }

    /**
     * Sets years
     *
     * @param array $years
     * @return void
     **/
    public function setYears($years)
    {
        $this->years = $years;
    }
}