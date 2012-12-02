<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Controller;

use Blog\Form\SearchForm;
use Blog\Service\SearchService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Search Controller.
 *
 * @package Blog\Controller
 */
class SearchController
    extends AbstractActionController
{
    /**
     * @var SearchService
     **/
    protected $_service;

    /**
     * Searches published blog posts
     *
     * @param void
     * @return ViewModel
     * @throws AccessProhibitedException
     **/
    public function indexAction()
    {
        $request = $this->getRequest();

        $viewVars = array();

        if($request->isGet()) {
            $options = $request->getQuery()->toArray();
            $page = $request->getQuery('page');

            $viewVars['posts'] = $this->_service->pagedResults($options, $page);
        }

        $viewVars['form'] = $this->_service->getForm();
        $viewVars['messages'] = $this->_service->getMessages(SearchService::MSG_NOTICE);
        $viewVars['errors'] = $this->_service->getMessages(SearchService::MSG_ERROR);

        return new ViewModel($viewVars);
    }

    /**
     * Sets SearchService
     *
     * @param PostService $service
     * @return void
     **/
    public function setService(SearchService $service)
    {
        $this->_service = $service;
    }

}
