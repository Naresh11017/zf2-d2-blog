<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Controller;

use Blog\Form\ContactForm;
use Blog\Service\ContactService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Contact Controller.
 *
 * @package Blog\Controller
 */
class ContactController
    extends AbstractActionController
{
    /**
     * @var ContactService
     **/
    protected $_service;

    /**
     * Displays contact form
     *
     * @param void
     * @return ViewModel
     **/
    public function indexAction()
    {
        $request = $this->getRequest();

        if($request->isPost()) {
            $this->_service->send($request->getPost());
        }

        return new ViewModel(array(
            'form' => $this->_service->getForm(),
            'messages' => $this->_service->getMessages(ContactService::MSG_NOTICE),
            'errors' => $this->_service->getMessages(ContactService::MSG_ERROR)
        ));
    }

    /**
     * Sets ContactService
     *
     * @param ContactService $service
     * @return void
     **/
    public function setService(ContactService $service)
    {
        $this->_service = $service;
    }

}