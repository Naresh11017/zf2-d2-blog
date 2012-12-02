<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Form;

use Blog\Model\Post;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Post Form
 *
 * @package Blog\Form
 */
class PostForm
    extends Form
{
    /**
     * Constructor
     **/
    public function __construct($name = 'post-form')
    {
        parent::__construct($name);

        $this->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setObject(new Post());

        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type'  => 'text',
                'style' => 'width: 95%;'
            ),
            'options' => array(
                'label' => 'Title',
            ),
        ));

        $this->add(array(
            'type' => 'Blog\Form\CategoryFieldset',
            'name' => 'category',
            'options' => array(
                'label' => 'Category',
            )
        ));

        $this->add(array(
            'name' => 'content',
            'attributes'=> array(
                'type'  => 'textarea',
                'id'    => 'contentEditor',
                'class' => 'ckeditor',
            ),
            'options' => array(
                'label' => 'Content',
            ),
        ));

        $this->add(array(
            'name' => 'isPublished',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Status',
                'value_options' => array(
                    '0' => 'Not Published',
                    '1' => 'Published'
                ),
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 60000
                )
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Save',
                'id' => 'submitbtn',
            ),
        ));

    }

    /**
     * Sets the category dropdown list
     *
     * @param array $list
     * @return void
     **/
    public function setCategoryList($list)
    {
        $options = array();

        foreach($list as $category)
        {
            $options[$category->getId()] = $category->getName();
        }

        $categories = $this->get('category')->get('id');
        $categories->setValueOptions($options);
    }
}