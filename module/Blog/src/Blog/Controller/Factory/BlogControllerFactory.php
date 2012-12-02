<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Controller\Factory;

use Blog\Controller\BlogController;
use Blog\Service\PostService;
use Blog\Service\UserService;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Sets up the Blog Controller
 *
 * @package Blog\Controller
 */
class BlogControllerFactory
    implements FactoryInterface
{
    /**
     * Returns BlogController instance.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return BlogController
     * @override
     **/
    public function createService(ServiceLocatorInterface $serviceLocator) {
        /* @var $serviceLocator Zend\Mvc\Controller\ControllerManager */
        $sm = $serviceLocator->getServiceLocator();

        $em = $sm->get('em');

        $service = new PostService($em);

        $controller = new BlogController();
        $controller->setEntityManager($em);
        $controller->setPostService($service);

        $acl = new Acl();
        $acl->addRole(new Role(UserService::ROLE_GUEST));
        $acl->addRole(new Role(UserService::ROLE_ADMIN));
        $acl->addResource($controller);

        $acl->allow(UserService::ROLE_ADMIN, $controller);
        $acl->allow(UserService::ROLE_GUEST, $controller);
        $acl->deny(UserService::ROLE_GUEST, $controller, array('create', 'edit', 'view-all'));

        $controller->setAcl($acl);

        return $controller;

    }
}