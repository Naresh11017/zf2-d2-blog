<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Controller;

use Blog\Form\CommentForm;
use Blog\Service\CommentService;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

/**
 * Comment Controller.
 *
 * @package Blog\Controller
 */
class CommentController
    extends AclController
{
    /**
     * @const string
     **/
    const RESOURCE_ID = 'comment';

    /**
     * @var CommentService
     **/
    protected $_service;

    /**
     * Edits the content of a comment
     *
     * @param void
     * @return ViewModel
     * @throws AccessProhibitedException
     **/
    public function editAction()
    {
        $this->_checkAcl('edit');

        $comment = $this->_service->getOne($this->getRequest()->getQuery('id'));

        $form = new CommentForm();
        $form->bind($comment);
        $form->remove('parentId');
        $form->remove('captcha');

        $form->get('comment')->setAttribute('id', 'commentEditor');
        $form->get('comment')->setAttribute('class', 'ckeditor');
        $form->get('submit')->setValue('Save');

        $this->_service->setForm($form);

        if($this->getRequest()->isPost()) {
            $this->_service->edit($this->getRequest()->getPost());
        }

        return new ViewModel(array(
            'form' => $form,
            'messages' => $this->_service->getMessages(CommentService::MSG_NOTICE),
            'errors' => $this->_service->getMessages(CommentService::MSG_ERROR)
        ));
    }

    /**
     * Lists all the comments
     *
     * @param void
     * @return ViewModel
     * @throws AccessProhibitedException
     **/
    public function viewAction()
    {
        $this->_checkAcl('view');

        $comments = $this->_service->getPaged($this->getRequest()->getQuery('page'));

        return new ViewModel(array(
            'comments' => $comments
        ));
    }

    /**
     * Removes a comment given it's ID
     *
     * @param void
     * @return JsonModel
     * @throws AccessProhibitedException
     **/
    public function deleteAction()
    {
        $this->_checkAcl('delete');

        $comment = $this->_service->getOne($this->getRequest()->getQuery('id'));

        $this->_service->delete($comment);

        return new JsonModel(array(
            'success' => true,
            'message' => 'Successfully removed comment'
        ));
    }

    /**
     * Sets CommentService
     *
     * @param CommentService $service
     * @return void
     **/
    public function setService(CommentService $service)
    {
        $this->_service = $service;
    }

    /**
     * Since this controller is an ACL resource,
     * it needs to have a resource id.
     *
     * @param void
     * @return string
     * @override
     **/
    public function getResourceId()
    {
        return self::RESOURCE_ID;
    }

}
