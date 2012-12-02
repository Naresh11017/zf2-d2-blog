<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\View\Helper\Factory;

use Blog\Service\CategoryService;
use Blog\View\Helper\CategoryList;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Sets up CategoryList
 *
 * @package Blog\View
 */
class CategoryListFactory
    implements FactoryInterface
{
    /**
     * Returns CategoryList instance.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CategoryList
     * @override
     **/
    public function createService(ServiceLocatorInterface $serviceLocator) {
        /* @var $serviceLocator Zend\View\HelperPluginManager */
        $sm = $serviceLocator->getServiceLocator();

        $em = $sm->get('em');

        $service = new CategoryService($em);
        $categories = $service->getPublishedWithCount();

        $helper = new CategoryList();
        $helper->setCategories($categories);

        return $helper;
    }
}