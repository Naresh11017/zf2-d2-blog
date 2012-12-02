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
use Blog\View\Helper\AdminLinks;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Sets up AdminLinks
 *
 * @package Blog\View
 */
class AdminLinksFactory
    implements FactoryInterface
{
    /**
     * Returns AdminLinks instance.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return AdminLinks
     * @override
     **/
    public function createService(ServiceLocatorInterface $serviceLocator) {
        /* @var $serviceLocator Zend\View\HelperPluginManager */
        $sm = $serviceLocator->getServiceLocator();

        $authService = new AuthenticationService();

        $helper = new AdminLinks();
        $helper->setAuthService($authService);

        return $helper;

    }
}