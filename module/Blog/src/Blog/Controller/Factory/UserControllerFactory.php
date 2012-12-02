<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Controller\Factory;

use Blog\Controller\UserController;
use Blog\Service\UserService;
use Zend\EventManager\Event;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Sets up the user controller.
 *
 * @package Blog\Controller
 */
class UserControllerFactory
    implements FactoryInterface
{
    /**
     * Returns UserController instance.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return UserController
     * @override
     **/
    public function createService(ServiceLocatorInterface $serviceLocator) {
        /* @var $serviceLocator Zend\Mvc\Controller\ControllerManager */
        $sm = $serviceLocator->getServiceLocator();

        $em = $sm->get('em');

        $service = new UserService($em);

        $controller = new UserController();
        $controller->setUserService($service);

        return $controller;

    }
}