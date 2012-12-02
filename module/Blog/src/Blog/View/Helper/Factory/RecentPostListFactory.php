<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\View\Helper\Factory;

use Blog\Service\PostService;
use Blog\View\Helper\RecentPostList;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Sets up RecentPostList
 *
 * @package Blog\View
 */
class RecentPostListFactory
    implements FactoryInterface
{
    /**
     * Returns RecentPostList instance.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return RecentPostList
     * @override
     **/
    public function createService(ServiceLocatorInterface $serviceLocator) {
        /* @var $serviceLocator Zend\View\HelperPluginManager */
        $sm = $serviceLocator->getServiceLocator();

        $em = $sm->get('em');

        $service = new PostService($em);
        $posts = $service->getMostRecent(5);

        $helper = new RecentPostList();
        $helper->setPosts($posts);

        return $helper;
    }
}