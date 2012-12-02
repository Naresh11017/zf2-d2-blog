<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\View\Helper;

use Zend\Navigation\Navigation;
use Zend\View\Helper\AbstractHelper;

/**
 * CrumbContainer
 *
 * @package Blog\View
 */
class CrumbContainer
    extends AbstractHelper
{
    /**
     * Sets up the navigation container for breadcrumbs
     *
     * @param array $extraPages
     * @return string
     * @override
     **/
    public function __invoke(array $extraPages)
    {
        $pages = array(
            'label' => 'home',
            'uri' => $this->getView()->myUrl('blog'),
            'pages' => array($extraPages)
        );

        $container = new Navigation(array($pages));
        $this->getView()->navigation()->setContainer($container);
    }
}