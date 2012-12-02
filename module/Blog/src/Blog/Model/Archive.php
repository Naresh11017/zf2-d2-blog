<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Model;

/**
 * Archive Model
 *
 * @package Blog\Model
 */
class Archive
{
    /**
     * @var integer
     **/
    private $year;

    /**
     * @var integer
     **/
    private $count;

    /**
     * Set Year
     *
     * @param integer
     * @return Blog\Model\Archive
     **/
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get Year
     *
     * @return integer
     **/
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set Count
     *
     * @param integer
     * @return Blog\Model\Archive
     **/
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get Count
     *
     * @return integer
     **/
    public function getCount()
    {
        return $this->count;
    }

}