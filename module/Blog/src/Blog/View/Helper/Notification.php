<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Notification
 *
 * @package Blog\View
 */
class Notification
    extends AbstractHelper
{
    /**
     * Returns HTML for notifications
     *
     * @param void
     * @return string
     * @override
     **/
    public function __invoke()
    {
        $view = $this->getView();

        $string = '';

        if(isset($view->messages) && is_array($view->messages) && count($view->messages)) {

            $string .= '<div class="notice">';

            foreach($view->messages as $msg) {
                $string .=  '<p>'. $msg .'</p>';
            }

            $string .= '</div>';
        }

        if(isset($view->errors) && is_array($view->errors) && count($view->errors)) {

            $string .= '<div class="error">';

            foreach($view->errors as $msg) {
                $string .=  '<p>'. $msg .'</p>';
            }

            $string .= '</div>';
        }

        $view->layout()->notifications = $string;
    }
}