<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog;

/**
 * Module
 *
 * @package Blog
 */
class Module
{
    /**
     * Autoloader Config
     *
     * @param void
     * @return array
     **/
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * View Helper Config
     *
     * @param void
     * @return array
     **/
    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'notifications' => 'Blog\View\Helper\Notification',
                'crumbContainer' => 'Blog\View\Helper\CrumbContainer',
                'myUrl' => 'Blog\View\Helper\MyUrl',
            ),
            'factories' => array(
                'adminLinks'     => 'Blog\View\Helper\Factory\AdminLinksFactory',
                'displayComments'=> 'Blog\View\Helper\Factory\DisplayCommentFactory',
                'displayQuote'   => 'Blog\View\Helper\Factory\DisplayQuoteFactory',
                'recentPostList' => 'Blog\View\Helper\Factory\RecentPostListFactory',
                'categoryList'   => 'Blog\View\Helper\Factory\CategoryListFactory',
                'archiveList'    => 'Blog\View\Helper\Factory\ArchiveListFactory'
            )
        );
    }

    /**
     * Service Config
     *
     * @param void
     * @return array
     **/
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'cache' => 'Doctrine\Common\Cache\ArrayCache'
            ),
            'factories' => array(
                'em' => function($sm) {
                    $em = $sm->get('Doctrine\ORM\EntityManager');
                    $cache = $sm->get('cache');

                    $em->getConfiguration()->setQueryCacheImpl($cache);

                    return $em;
                }
            )
        );
    }

    /**
     * Controller Config
     *
     * @param void
     * @return array
     **/
    public function getControllerConfig()
    {
        return array(
            'invokables' => array(
                'Blog\Controller\About'    => 'Blog\Controller\AboutController'
            ),
            'factories' => array(
                'Blog\Controller\Archive'  => 'Blog\Controller\Factory\ArchiveControllerFactory',
                'Blog\Controller\Blog'     => 'Blog\Controller\Factory\BlogControllerFactory',
                'Blog\Controller\Category' => 'Blog\Controller\Factory\CategoryControllerFactory',
                'Blog\Controller\Comment'  => 'Blog\Controller\Factory\CommentControllerFactory',
                'Blog\Controller\Contact'  => 'Blog\Controller\Factory\ContactControllerFactory',
                'Blog\Controller\Search'   => 'Blog\Controller\Factory\SearchControllerFactory',
                'Blog\Controller\User'     => 'Blog\Controller\Factory\UserControllerFactory',
            )
        );
    }

    /**
     * Config
     *
     * @param void
     * @return array
     **/
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
