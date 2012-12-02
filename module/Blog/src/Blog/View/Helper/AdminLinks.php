<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\View\Helper;

use Zend\Authentication\AuthenticationService;
use Zend\View\Helper\AbstractHelper;

/**
 * AdminLinks
 *
 * @package Blog\View
 */
class AdminLinks
    extends AbstractHelper
{
    /**
     * @var Zend\Authentication\AuthenticationService
     **/
    protected $_authService;

    /**
     * Returns HTML for administrator links
     *
     * @param void
     * @return string
     * @override
     **/
    public function __invoke()
    {
        if(!$this->_authService->hasIdentity()) {
            return false;
        }

        return sprintf(
            '<div class="adminPanel">
                <h1>Administrative Panel</h1>
                <div class="breadcrumbs">
                    <a title="create post" href="%s">Create Post</a> |
                    <a title="manage posts" href="%s">Manage Posts</a> |
                    <a title="view comments" href="%s">Manage Comments</a> |
                    <a title="log out" href="%s">Logout</a>
                </div>
            </div>',
            $this->view->myUrl('blog/default', array('controller' => 'blog', 'action' => 'create')),
            $this->view->myUrl('blog/default', array('controller' => 'blog', 'action' => 'view-all')),
            $this->view->myUrl('blog/default', array('controller' => 'comment', 'action' => 'view')),
            $this->view->myUrl('blog/default', array('controller' => 'user', 'action' => 'logout'))
        );
    }

    /**
     * Sets AuthenticationService
     *
     * @param AuthenticationService $authService
     * @return void
     **/
    public function setAuthService(AuthenticationService $authService)
    {
        $this->_authService = $authService;
    }
}