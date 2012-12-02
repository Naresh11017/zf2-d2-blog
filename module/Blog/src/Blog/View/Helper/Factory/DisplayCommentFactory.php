<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\View\Helper\Factory;

use Blog\Service\UserService;
use Blog\View\Helper\DisplayComments;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Sets up DisplayComments
 *
 * @package Blog\View
 */
class DisplayCommentFactory
    implements FactoryInterface
{
    /**
     * Returns DisplayComments instance.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return DisplayQuote
     * @override
     **/
    public function createService(ServiceLocatorInterface $serviceLocator) {
        /* @var $serviceLocator Zend\View\HelperPluginManager */
        $sm = $serviceLocator->getServiceLocator();

        $em = $sm->get('em');

        $service = new UserService($em);
        $role = $service->getCurrentRole();

        $helper = new DisplayComments();
        $helper->setRole($role);

        return $helper;
    }
}