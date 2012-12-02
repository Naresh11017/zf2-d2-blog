<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Form;

use Zend\Captcha;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Contact Form
 *
 * @package Blog\Form
 */
class ContactForm
    extends Form
{
    /**
     * @var Zend\InputFilter\InputFilter
     **/
    protected $_filter;

    /**
     * Constructor
     **/
    public function __construct($name = 'contact-form')
    {
        parent::__construct($name);

        $this->setAttribute('method', 'post');

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
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Email Address (Will not be published)',
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
                'value' => 'Send',
                'id' => 'submitbtn',
            ),
        ));

    }

    /**
     * Since the input filter was set how I want when adding the captcha element,
     * and I want to adjust the other default filters, I have overriden this method.
     * Not sure how I feel about it.
     *
     * @param void
     * @return Zend\InputFilter\InputFilter
     * @override
     * @todo Fix this up
     **/
    public function getInputFilter()
    {
        if($this->_filter == null) {
            $filter = parent::getInputFilter();

            //What the h4ck!?  Maybe I can just modify these filters instead of re-adding them?
            $filter->remove('name')
                   ->remove('email')
                   ->remove('comment');

            $factory = new InputFactory();

            $filter->add($factory->createInput(array(
                'name'     => 'name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 50,
                        ),
                    ),
                ),
            )));

            $filter->add($factory->createInput(array(
                'name'     => 'email',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'EmailAddress',
                        'options' => array(
                            'message' => array(
                                'Please enter a valid email address.  Thanks!'
                            ),
                        ),
                    ),
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $filter->add($factory->createInput(array(
                'name'     => 'comment',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 3000,
                        ),
                    ),
                ),
            )));

            $this->_filter = $filter;
        }

        return $this->_filter;
    }

}