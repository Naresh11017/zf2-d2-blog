<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Controller;

use Blog\Form\LoginForm;
use Blog\Service\UserService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * User Controller.
 *
 * @package Blog\Controller
 */
class UserController
    extends AbstractActionController
{
    /**
     * @var UserService
     **/
    protected $_userService;

    /**
     * Authenticates the user
     *
     * @param void
     * @return mixed {Zend\Http\PhpEnvironment\Response, ViewModel}
     **/
    public function loginAction()
    {
        $params = array(
            'controller' => 'blog',
            'action' => 'view-all'
        );

        if($this->_userService->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('blog/default', $params);
        }

        $request = $this->getRequest();

        if($request->isPost()) {
            if($this->_userService->auth($request->getPost())) {
                return $this->redirect()->toRoute('blog/default', $params);
            }
        }

        return new ViewModel(array(
            'form' => $this->_userService->getForm(),
            'messages' => $this->_userService->getMessages(UserService::MSG_NOTICE),
            'errors' => $this->_userService->getMessages(UserService::MSG_ERROR)
        ));
    }

    /**
     * De-Authenticates the user
     *
     * @param void
     * @return Zend\Http\PhpEnvironment\Response
     **/
    public function logoutAction()
    {
        $this->_userService->logout();

        return $this->redirect()->toRoute('blog/default');
    }

    /**
     * Sets UserService
     *
     * @param UserService $userService
     * @return void
     **/
    public function setUserService(UserService $userService)
    {
        $this->_userService = $userService;
    }
}
