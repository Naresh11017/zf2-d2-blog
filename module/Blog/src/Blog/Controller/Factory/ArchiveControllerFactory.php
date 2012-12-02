<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Controller\Factory;

use Blog\Controller\ArchiveController;
use Blog\Service\PostService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Sets up the Archive Controller
 *
 * @package Blog\Controller
 */
class ArchiveControllerFactory
    implements FactoryInterface
{
    /**
     * Returns ArchiveController instance.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ArchiveController
     * @override
     **/
    public function createService(ServiceLocatorInterface $serviceLocator) {
        /* @var $serviceLocator Zend\Mvc\Controller\ControllerManager */
        $sm = $serviceLocator->getServiceLocator();

        $em = $sm->get('em');

        $service = new PostService($em);

        $controller = new ArchiveController();
        $controller->setPostService($service);

        return $controller;

    }
}