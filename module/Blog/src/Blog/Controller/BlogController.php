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
use Blog\Form\PostForm;
use Blog\Model\Category;
use Blog\Model\Post;
use Blog\Service\CategoryService;
use Blog\Service\CommentService;
use Blog\Service\UserService;
use Blog\Service\PostService;
use DateTime;
use Zend\View\Model\ViewModel;
use Zend\View\Model\FeedModel;

/**
 * Blog Controller.
 *
 * @package Blog\Controller
 */
class BlogController
    extends AclController
{
    /**
     * @const string
     **/
    const RESOURCE_ID = 'blog';

    /**
     * @var PostService
     **/
    protected $_postService;

    /**
     * Creates a blog post
     *
     * @parmam void
     * @return mixed {Zend\Http\PhpEnvironment\Response, ViewModel}
     * @throws AccessProhibitedException
     **/
    public function createAction()
    {
        $this->_checkAcl('create');

        $userService = new UserService($this->_em);
        $auth = $userService->getAuthService();
        $user = $userService->findById($auth->getIdentity()->getId());

        $post = new Post();
        $post->setDateAdded(new DateTime());
        $post->setUser($user);

        $form = new PostForm();
        $form->bind($post);

        $categoryService = new CategoryService($this->_em);
        $form->setCategoryList($categoryService->getAll());

        $service = new PostService($this->_em);
        $service->setForm($form);

        $request = $this->getRequest();

        if($request->isPost()) {
            if($service->save($request->getPost())) {
                $params = array('controller' => 'admin', 'action' => 'index');
                return $this->redirect()->toRoute('blog/default', $params);
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'messages' => $this->_postService->getMessages(PostService::MSG_NOTICE),
            'errors' => $this->_postService->getMessages(PostService::MSG_ERROR)
        ));
    }

    /**
     * To update/edit a blog post
     *
     * @parmam void
     * @return ViewModel
     * @throws AccessProhibitedException
     **/
    public function editAction()
    {
        $this->_checkAcl('edit');

        $request = $this->getRequest();

        $post = $this->_postService->getFromId($request->getQuery('id'));

        $categoryService = new CategoryService($this->_em);

        $form = new PostForm();
        $form->bind($post);
        $form->setCategoryList($categoryService->getAll());
        $this->_postService->setForm($form);

        if($request->isPost()) {
            $this->_postService->save($request->getPost());
        }

        return new ViewModel(array(
            'form' => $form,
            'post' => $post,
            'messages' => $this->_postService->getMessages(PostService::MSG_NOTICE),
            'errors' => $this->_postService->getMessages(PostService::MSG_ERROR)
        ));
    }

    /**
     * To view the latest blog post
     *
     * @param void
     * @return ViewModel
     * @throws AccessProhibitedException
     **/
    public function latestAction()
    {
        $this->_checkAcl('latest');

        $post = $this->_postService->getLatest();

        return new ViewModel(array(
            'post' => $post,
            'messages' => $this->_postService->getMessages(PostService::MSG_NOTICE),
            'errors' => $this->_postService->getMessages(PostService::MSG_ERROR)
        ));
    }

    /**
     * To view a specific blog post
     *
     * @param void
     * @return ViewModel
     * @throws AccessProhibitedException
     **/
    public function viewAction()
    {
        $this->_checkAcl('view');

        $request = $this->getRequest();

        $post = $this->_postService->getFromTitle($request->getQuery('title'));
        
        $form = new CommentForm();
        $form->setReplyToList($post->getComments());

        $viewVars = array();

        if($request->isPost()) {

            $service = new CommentService($this->_em);
            $service->setForm($form);
            $service->setPost($post);

            $service->save($request->getPost());

            $viewVars['messages'] = $service->getMessages(CommentService::MSG_NOTICE);
            $viewVars['errors'] = $service->getMessages(CommentService::MSG_ERROR);
        }

        $viewVars['post'] = $post;
        $viewVars['form'] = $form;
        $viewVars['query'] = $request->getQuery();

        return new ViewModel($viewVars);
    }

    /**
     * Views all blog posts, published or not
     *
     * @param void
     * @return ViewModel
     * @throws AccessProhibitedException
     **/
    public function viewAllAction()
    {
        $this->_checkAcl('view-all');

        $posts = $this->_postService->getPaged($this->getRequest()->getQuery('page'));

        return new ViewModel(array(
            'posts' => $posts,
            'messages' => $this->_postService->getMessages(PostService::MSG_NOTICE),
            'errors' => $this->_postService->getMessages(PostService::MSG_ERROR)
        ));
    }

    /**
     * RSS feed of published blog posts
     *
     * @param void
     * @return FeedModel
     * @throws AccessProhibitedException
     **/
    public function rssAction()
    {
        $this->_checkAcl('rss');

        $feed = $this->_postService->getRssFeed();

        $feedModel = new FeedModel();
        $feedModel->setFeed($feed);

        return $feedModel;
    }

    /**
     * Sets PostService
     *
     * @param PostService $service
     * @return void
     **/
    public function setPostService(PostService $service)
    {
        $this->_postService = $service;
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
