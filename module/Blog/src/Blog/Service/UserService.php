<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Service;

use Blog\Form\LoginForm;
use DoctrineModule\Authentication\Adapter\ObjectRepository;
use Zend\Authentication\AuthenticationService;

/**
 * User Service
 *
 * @package Blog\Service
 */
class UserService
    extends AbstractService
{
    /**
     * @const string
     **/
    const ROLE_ADMIN = 'admin';

    /**
     * @const string
     **/
    const ROLE_GUEST = 'guest';

    /**
     * @const string
     **/
    const ENTITY_USER = 'Blog\Model\User';

    /**
     * @var LoginForm
     **/
    private $form;

    /**
     * @var Zend\Authentication\AuthenticationService
     **/
    private $authService;

    /**
     * Authorizes user
     *
     * @param array $data
     * @return boolean
     **/
    public function auth($data)
    {
        $form = $this->getForm();
        $form->setData($data);

        if(!$form->isValid()) {

            $this->addMessage('Stop it!', self::MSG_ERROR);

            return false;
        }

        $adapter = new ObjectRepository(array(
            'objectManager' => $this->em,
            'identityClass' => 'Blog\Model\User',
            'identityProperty' => 'email',
            'credentialProperty' => 'password',
            'credentialCallable' => 'Blog\Model\User::hashPassword'
        ));

        $adapter->setIdentityValue($data['email']);
        $adapter->setCredentialValue($data['password']);

        $result = $this->getAuthService()->authenticate($adapter);

        if(!$result->isValid()) {
            //Probably should not give users too much info
            //$form->get('email')->setMessages($result->getMessages());

            $this->addMessage('Stop it!', self::MSG_ERROR);

            return false;
        }

        $this->addMessage('Successfully logged in.', self::MSG_NOTICE);

        return true;
    }

    /**
     * De-Authorizes current user
     *
     * @param void
     * @return boolean
     **/
    public function logout()
    {
        $authService = $this->getAuthService();

        if($authService->hasIdentity()) {
            $authService->clearIdentity();

            $this->addMessage('Successfully logged out.', self::MSG_NOTICE);

            return true;
        }

        return false;
    }

    /**
     * Finds a user by their ID
     *
     * @param int $id
     * @return User
     **/
    public function findById($id)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('u')
           ->from(self::ENTITY_USER, 'u')
           ->where('u.id = :id')
           ->setParameter('id', $id);

        $query = $qb->getQuery();
        $result = $query->getSingleResult();

        return $result;
    }

    /**
     * Sets Loginform
     *
     * @param LoginForm $form
     * @return void
     **/
    public function setForm(LoginForm $form)
    {
        $this->form = $form;
    }

    /**
     * Gets LoginForm
     *
     * @param void
     * @return LoginForm
     **/
    public function getForm()
    {
        if(null === $this->form) {
            $this->form = new LoginForm();
        }

        return $this->form;
    }

    /**
     * Gets AuthenticationService
     *
     * @param void
     * @return Zend\Authentication\AuthenticationService
     **/
    public function getAuthService()
    {
        if(null === $this->authService) {
            $this->authService = new AuthenticationService();
        }

        return $this->authService;
    }

    /**
     * Gets the current user's role
     *
     * @param void
     * @return string
     **/
    public function getCurrentRole()
    {
        if($this->getAuthService()->hasIdentity()) {
            return self::ROLE_ADMIN;
        }

        return self::ROLE_GUEST;
    }
}