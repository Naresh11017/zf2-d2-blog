<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Controller\Factory;

use Blog\Controller\CategoryController;
use Blog\Service\CategoryService;
use Blog\Service\PostService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Sets up the category controller.
 *
 * @package Blog\Controller
 */
class CategoryControllerFactory
    implements FactoryInterface
{
    /**
     * Returns CategoryController instance.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CategoryController
     * @override
     **/
    public function createService(ServiceLocatorInterface $serviceLocator) {
        /* @var $serviceLocator Zend\Mvc\Controller\ControllerManager */
        $sm = $serviceLocator->getServiceLocator();

        $em = $sm->get('em');

        $postService = new PostService($em);
        $categoryService = new CategoryService($em);

        $controller = new CategoryController($em);
        $controller->setPostService($postService);
        $controller->setCategoryService($categoryService);

        return $controller;

    }
}