<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quote
 *
 * @package Blog\Model
 * @ORM\Table(name="quotes")
 * @ORM\Entity
 */
class Quote
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="quote", type="string", length=255, nullable=false)
     */
    private $quote;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=45, nullable=false)
     */
    private $author;

    /**
     * @var boolean
     *
     * @ORM\Column(name="year", type="boolean", nullable=false)
     */
    private $year;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quote
     *
     * @param string $quote
     * @return Quotes
     */
    public function setQuote($quote)
    {
        $this->quote = $quote;

        return $this;
    }

    /**
     * Get quote
     *
     * @return string
     */
    public function getQuote()
    {
        return $this->quote;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Quotes
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set year
     *
     * @param boolean $year
     * @return Quotes
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return boolean
     */
    public function getYear()
    {
        return $this->year;
    }
}
