<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Controller\Factory;

use Blog\Controller\ContactController;
use Blog\Service\ContactService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Sets up the contact controller
 *
 * @package Blog\Controller
 */
class ContactControllerFactory
    implements FactoryInterface
{
    /**
     * Returns ContactController instance.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ContactController
     * @override
     **/
    public function createService(ServiceLocatorInterface $serviceLocator) {

        $service = new ContactService();

        $controller = new ContactController();
        $controller->setService($service);

        return $controller;

    }
}