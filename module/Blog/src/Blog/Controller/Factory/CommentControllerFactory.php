<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Controller\Factory;

use Blog\Controller\CommentController;
use Blog\Service\CommentService;
use Blog\Service\UserService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

/**
 * Sets up the comment controller.
 *
 * @package Blog\Controller
 */
class CommentControllerFactory
    implements FactoryInterface
{
    /**
     * Returns CommentController instance.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CommentController
     **/
    public function createService(ServiceLocatorInterface $serviceLocator) {
        /* @var $serviceLocator Zend\Mvc\Controller\ControllerManager */
        $sm = $serviceLocator->getServiceLocator();

        $em = $sm->get('em');

        $service = new CommentService($em);

        $controller = new CommentController();
        $controller->setEntityManager($em);
        $controller->setService($service);

        $acl = new Acl();
        $acl->addRole(new Role(UserService::ROLE_GUEST));
        $acl->addRole(new Role(UserService::ROLE_ADMIN));
        $acl->addResource($controller);

        $acl->allow(UserService::ROLE_ADMIN, $controller);
        $acl->allow(UserService::ROLE_GUEST, $controller, array('add'));

        $controller->setAcl($acl);

        return $controller;

    }
}