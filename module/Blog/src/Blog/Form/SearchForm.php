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
 * Search Form
 *
 * @package Blog\Form
 */
class SearchForm
    extends Form
{
    /**
     * Constructor
     **/
    public function __construct($name = 'search-form')
    {
        parent::__construct($name);

        $this->setAttribute('method', 'get');

        $this->add(array(
            'name' => 'search',
            'attributes' => array(
                'type'  => 'text',
                'style' => 'width: 300px;',
            ),
            'options' => array(
                'label' => 'Search',
            ),
        ));

        $this->add(array(
            'name' => 'category',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'style' => 'height: 26px;'
            ),
            'options' => array(
                'label' => 'Category'
            ),
        ));

        $this->add(array(
            'name' => 'year',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'style' => 'height: 26px;'
            ),
            'options' => array(
                'label' => 'Year'
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Search',
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
        $options = array('0' => 'All Categories');

        foreach($list as $category)
        {
            $options[$category['id']] = $category['name'] . ' (' . $category['count'] . ')';
        }

        $select = $this->get('category');
        $select->setValueOptions($options);

        return $this;
    }

    /**
     * Sets the archive year list
     *
     * @param array $list
     * @return void
     **/
    public function setYearList($list)
    {
        $options = array('0' => 'All Years');

        foreach($list as $year)
        {
            $options[$year] = $year;
        }

        $select = $this->get('year');
        $select->setValueOptions($options);

        return $this;
    }
}