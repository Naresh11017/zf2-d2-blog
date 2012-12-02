<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\View\Helper;

use Zend\View\Helper\Url;
use Zend\View\Helper\AbstractHelper;

/**
 * Notification
 *
 * @package Blog\View
 */
class MyUrl
    extends AbstractHelper
{
    /**
     * Generates lower case url given the name of a route.
     *
     * @see    Zend\Mvc\Router\RouteInterface::assemble()
     * @param  string  $name               Name of the route
     * @param  array   $params             Parameters for the link
     * @param  array   $options            Options for the route
     * @param  boolean $reuseMatchedParams Whether to reuse matched parameters
     * @return string Url                  For the link href attribute
     * @throws Exception\RuntimeException  If no RouteStackInterface was provided
     * @throws Exception\RuntimeException  If no RouteMatch was provided
     * @throws Exception\RuntimeException  If RouteMatch didn't contain a matched route name
     */
    public function __invoke($name = null, array $params = array(), $options = array(), $reuseMatchedParams = false)
    {
        
        $url = $this->view->url($name, $params,$options, $reuseMatchedParams);
        
        return strtolower($url);
    }
}