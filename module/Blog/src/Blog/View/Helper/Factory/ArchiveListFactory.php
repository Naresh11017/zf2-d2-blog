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
use Blog\View\Helper\ArchiveList;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Sets up ArchiveList
 *
 * @package Blog\View
 */
class ArchiveListFactory
    implements FactoryInterface
{
    /**
     * Returns AchiveList instance.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ArchiveList
     * @override
     **/
    public function createService(ServiceLocatorInterface $serviceLocator) {
        /* @var $serviceLocator Zend\View\HelperPluginManager */
        $sm = $serviceLocator->getServiceLocator();

        $em = $sm->get('em');

        $service = new PostService($em);
        $years = $service->getValidYears();

        $helper = new ArchiveList();
        $helper->setYears($years);

        return $helper;

    }
}