<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Service;

use Zend\Mvc\Controller\Plugin\FlashMessenger;

/**
 * Abstract Message Service
 *
 * Allows persistent messages to be added and removed
 *
 * @package Blog\Service
 */
abstract class AbstractMessageService
{
    /**
     * @const string
     **/
    const MSG_ERROR = 'error';

    /**
     * @const string
     **/
    const MSG_NOTICE = 'notice';

    /**
     * @var Zend\Mvc\Controller\Plugin\FlashMessenger
     **/
    protected $_flashMessenger;

    /**
     * Sets FlashMesenger
     *
     * @param FlashMessenger $flashMessenger
     * @return void
     **/
    public function setFlashMessenger(FlashMessenger $flashMessenger)
    {
        $this->_flashMessenger = $flashMessenger;
    }

    /**
     * Gets FlashMessenger
     *
     * @param void
     * @return FlashMessenger
     **/
    public function getFlashMessenger()
    {
        if(null == $this->_flashMessenger) {
            $this->_flashMessenger = new FlashMessenger();
        }

        return $this->_flashMessenger;
    }

    /**
     * Adds a message to storage
     *
     * @param string $message
     * @param string $type
     * @return void
     **/
    public function addMessage($message, $type = self::MSG_ERROR)
    {
        $this->getFlashMessenger()->setNamespace($type);
        $this->getFlashMessenger()->addMessage($message);
    }

    /**
     * Gets messages and removes them from storage
     *
     * @param string $type
     * @return array
     **/
    public function getMessages($type = self::MSG_ERROR)
    {
        $this->getFlashMessenger()->setNamespace($type);

        $messages = $this->_flashMessenger->getMessages();
        $currentMessages = $this->_flashMessenger->getCurrentMessages();

        $this->_flashMessenger->clearMessages();
        $this->_flashMessenger->clearCurrentMessages();

        $messages = array_merge($messages, $currentMessages);

        return $messages;
    }
}