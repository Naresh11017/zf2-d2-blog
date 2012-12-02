<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Service;

use Doctrine\ORM\EntityManager;

/**
 * Abstract Service
 *
 * @package Blog\Service
 */
abstract class AbstractService
    extends AbstractMessageService
{
    /**
     * @var Doctrine\ORM\EntityManager
     **/
    protected $em;

    /**
     * Constructor
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Sets EntityManager
     *
     * @param EntityManager $em
     * @return void
     **/
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Gets EntityManager
     * @param void
     * @return EntityManager
     **/
    public function getEntityManager()
    {
        return $this->em;
    }
}