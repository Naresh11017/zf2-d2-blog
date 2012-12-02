<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Service;

use Blog\Form\ContactForm;
use Zend\Mail\Exception\RuntimeException;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;

/**
 * Contact Service
 *
 * @package Blog\Service
 */
class ContactService
    extends AbstractMessageService
{
    /**
     * @var ContactForm
     **/
    protected $_form;

    /**
     * Sends the contact message via email
     *
     * @param array $data
     * @return boolean
     **/
    public function send($data)
    {
        $form = $this->getForm();
        $form->setData($data);

        if($form->isValid()) {

            $data = $form->getData();

            $message = 'Name: ' . $data['name'] . "\r\n";
            $message .= 'Email: ' . $data['email'] . "\r\n\r\n";
            $message .= $data['comment'];

            $mail = new Message();
            $mail->setBody($message);
            $mail->setFrom('rob@robkeplin.com');
            $mail->addTo('rkeplin@gmail.com', 'Rob Keplin');
            $mail->setSubject('From robkeplin.com');

            $transport = new Sendmail();

            try {
                $transport->send($mail);
            } catch(RuntimeException $e) {
                $this->addMessage('The email did not get sent due to sendmail failing. Doh!', self::MSG_ERROR);

                return false;
            }

            $form->setData(array(
                'name' => '',
                'email' => '',
                'comment' => ''
            ));

            $this->addMessage('Thanks.  Get back to you soon!', self::MSG_NOTICE);

            return true;
        }

        $this->addMessage('Hey, Wait! Something went wrong down there.  Please fix the errors and try again.', self::MSG_ERROR);

        return false;
    }

    /**
     * Gets ContactForm
     *
     * @param void
     * @return ContactForm
     **/
    public function getForm()
    {
        if(null === $this->_form) {
            $this->_form = new ContactForm();
        }

        return $this->_form;
    }

    /**
     * Sets ContactForm
     *
     * @param ContactForm $form
     * @return void
     **/
    public function setForm(ContactForm $form)
    {
        $this->_form = $form;
    }
}