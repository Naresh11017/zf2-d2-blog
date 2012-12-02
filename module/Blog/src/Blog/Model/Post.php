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
use Doctrine\Common\Collections\ArrayCollection;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Post
 *
 * @package Blog\Model
 * @ORM\Table(name="posts")
 * @ORM\Entity
 */
class Post
    implements InputFilterAwareInterface
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
     * @ORM\Column(name="title", type="string", length=145, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime", nullable=false)
     */
    private $dateAdded;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=false)
     */
    private $dateModified;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_published", type="boolean", nullable=false)
     */
    private $isPublished;

    /**
     * @var \Blog\Model\User
     *
     * @ORM\OneToOne(targetEntity="\Blog\Model\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \Blog\Model\Category
     *
     * @ORM\OneToOne(targetEntity="\Blog\Model\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\Blog\Model\Comment", mappedBy="post")
     **/
    private $comments;

    /**
     * @var Zend\InputFilter\InputFilter
     **/
    private $inputFilter;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * Part of InputFilterAwareInterface, don't need it
     *
     * @param InputFilterInterface
     **/
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    /**
     * Returns input filter for this entity
     *
     * @return Zend\InputFilter\InputFilter
     **/
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'title',
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
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'content',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StringTrim'),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

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
     * Set title
     *
     * @param string $title
     * @return Posts
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Posts
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return Posts
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set dateModified
     *
     * @param \DateTime $dateModified
     * @return Posts
     */
    public function setDateModified($dateModified)
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    /**
     * Get dateModified
     *
     * @return \DateTime
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    /**
     * Set isPublished
     *
     * @param boolean $isPublished
     * @return Posts
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * Get isPublished
     *
     * @return boolean
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * Set User
     *
     * @param \Blog\Model\User $user
     * @return Posts
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get User
     *
     * @return \Blog\Model\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set Category
     *
     * @param \Blog\Model\Category
     * @return Post
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get Category
     *
     * @return \Blog\Model\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Adds a comment
     *
     * @param \Blog\Model\Comment
     * @return Post
     */
    public function addComment($comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Get Comments
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     **/
    public function getComments()
    {
        return $this->comments;
    }
}
