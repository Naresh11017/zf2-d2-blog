<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Controller\Factory;

use Blog\Controller\SearchController;
use Blog\Form\SearchForm;
use Blog\Service\CategoryService;
use Blog\Service\PostService;
use Blog\Service\SearchService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Sets up the Search Controller
 *
 * @package Blog\Controller
 */
class SearchControllerFactory
    implements FactoryInterface
{
    /**
     * Returns SearchController instance.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return BlogController
     * @override
     **/
    public function createService(ServiceLocatorInterface $serviceLocator) {
        /* @var $serviceLocator Zend\Mvc\Controller\ControllerManager */
        $sm = $serviceLocator->getServiceLocator();

        $em = $sm->get('em');

        $categoryService = new CategoryService($em);
        $categories = $categoryService->getPublishedWithCount();

        $postService = new PostService($em);
        $years = $postService->getValidYears();

        $form = new SearchForm();
        $form->setCategoryList($categories);
        $form->setYearList($years);

        $service = new SearchService($em);
        $service->setForm($form);

        $controller = new SearchController();
        $controller->setService($service);

        return $controller;

    }
}