<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\View\Helper\Factory;

use Blog\Service\QuoteService;
use Blog\View\Helper\DisplayQuote;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Sets up DisplayQuote
 *
 * @package Blog\View
 */
class DisplayQuoteFactory
    implements FactoryInterface
{
    /**
     * Returns DisplayQuote instance.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return DisplayQuote
     * @override
     **/
    public function createService(ServiceLocatorInterface $serviceLocator) {
        /* @var $serviceLocator Zend\View\HelperPluginManager */
        $sm = $serviceLocator->getServiceLocator();

        $em = $sm->get('em');

        $service = new QuoteService($em);
        $quote = $service->getRandom();

        $helper = new DisplayQuote();
        $helper->setQuote($quote);

        return $helper;
    }
}