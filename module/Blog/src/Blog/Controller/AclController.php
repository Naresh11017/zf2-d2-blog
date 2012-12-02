<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Controller;

use Blog\Controller\Exception\AccessProhibitedException;
use Blog\Service\UserService;
use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * ACL Controller.
 *
 * @package Blog\Controller
 * @abstract
 */
abstract class AclController
    extends AbstractActionController
        implements ResourceInterface
{
    /**
     * @var Zend\Permissions\Acl\Acl
     **/
    protected $_acl;

    /**
     * @var Doctrine\ORM\EntityManager
     **/
    protected $_em;

    /**
     * Checks if the current user has the priviledge to do something.
     *
     * @param string $priviledge
     * @return AccessProhibitedException
     **/
    protected function _checkAcl($priviledge)
    {
        $service = new UserService($this->_em);

        if(!$this->_acl->isAllowed($service->getCurrentRole(), $this, $priviledge)) {
            throw new AccessProhibitedException('Access is prohibited.');
        }
    }

    /**
     * Sets ACL
     *
     * @param Acl $acl
     * @return void
     **/
    public function setAcl(Acl $acl)
    {
        $this->_acl = $acl;
    }

    /**
     * Sets EntityManager
     *
     * @param EntityManager $em
     * @return void
     **/
    public function setEntityManager(EntityManager $em)
    {
        $this->_em = $em;
    }
}