<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Form;

use Blog\Model\Comment;
use Zend\Captcha;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Comment Form
 *
 * @package Blog\Form
 */
class CommentForm
    extends Form
{
    /**
     * Constructor
     **/
    public function __construct($name = 'comment-form')
    {
        parent::__construct($name);

        $this->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setObject(new Comment());

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));

        $this->add(array(
            'name' => 'website',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Website',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Email Address (Will not be published)',
            ),
        ));

        $this->add(array(
            'name' => 'parentId',
            'attributes' => array(
                'id' => 'parentId',
                'style' => 'width: 275px;',
            ),
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Reply To'
            ),
        ));

        $this->add(array(
            'name' => 'comment',
            'attributes' => array(
                'type'  => 'textarea',
                'style' => 'height: 100px; width: 450px;'
            ),
            'options' => array(
                'label' => 'Comment',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Captcha',
            'name' => 'captcha',
            'options' => array(
                'label' => 'Please enter the letters below',
                'captcha' => new Captcha\Figlet(array(
                    'wordlen' => 4,
                    'useNumbers' => false,
                    'timeout' => 60000,
                    'messages' => array(
                        Captcha\Figlet::BAD_CAPTCHA => 'Kindly re-enter the 4 letters above. Just spam prevention, nothing personal!',
                        Captcha\Figlet::MISSING_ID => 'Kindly re-enter the 4 letters above. Just spam prevention, nothing personal!',
                        Captcha\Figlet::MISSING_VALUE => 'Kindly re-enter the 4 letters above. Just spam prevention, nothing personal!'
                    )
                ))
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Submit Comment',
                'id' => 'submitbtn',
            ),
        ));

    }

    /**
     * Sets the reply to list for the dropdown field
     *
     * @param array $list
     * @return void
     **/
    public function setReplyToList($list)
    {
        $options = array();
        $options[0] = 'No one in particular.';

        foreach($list as $comment)
        {
            $options[$comment->getId()] = $comment->getName() . ' - ' . $comment->getDateAdded()->format('F jS, Y @ g:ia');
        }

        $select = $this->get('parentId');
        $select->setValueOptions($options);
    }
}