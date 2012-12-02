<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Form;

use Blog\Model\Category;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * Category Fieldset
 *
 * @package Blog\Form
 */
class CategoryFieldset
    extends Fieldset
{
    /**
     * Constructor
     **/
    public function __construct()
    {
        parent::__construct('category');

        $this->setHydrator(new ClassMethodsHydrator(false))
             ->setObject(new Category());

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Category'
            ),
        ));
    }
}