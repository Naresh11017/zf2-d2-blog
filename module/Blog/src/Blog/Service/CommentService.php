<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Service;

use Blog\Form\CommentForm;
use Blog\Util\Paginator\PaginatorFactory;
use Blog\Model\Comment;
use Blog\Model\Post;
use DateTime;
use Zend\Mail\Exception\RuntimeException;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;

/**
 * Comment Service
 *
 * @package Blog\Service
 */
class CommentService
    extends AbstractService
{
    /**
     * @const string
     **/
    const ENTITY_COMMENT = 'Blog\Model\Comment';

    /**
     * @var CommentForm
     **/
    private $form;

    /**
     * @var Post
     **/
    private $post;

    /**
     * Saves a comment and sends an email
     *
     * @param array $data
     * @return boolean
     **/
    public function save($data = array())
    {
        $data['comment'] = nl2br($data['comment']);

        $this->form->setData($data);

        if($this->form->isValid()) {
            $comment = $this->form->getData();
            $comment->setDateAdded(new DateTime());
            $comment->setPost($this->post);
            $comment->setStatus(1);
            $comment->setIpAddress($_SERVER['REMOTE_ADDR']);

            //For display purposes
            if($comment->getParentId()) {
                foreach($this->post->getComments() as $oldComment) {
                    if($oldComment->getId() == $comment->getParentId()) {
                        if($oldComment->getParentId()) {
                            $comment->setParentId($oldComment->getParentId());
                        }

                        break;
                    }
                }
            }

            $this->em->persist($comment);
            $this->em->flush();

            $this->post->addComment($comment);

            $this->form->setData(array(
                'name' => null,
                'website' => null,
                'email' => null,
                'comment' => null,
                'parent' => array('id' => 0)
            ));

            $message = strip_tags($comment->getComment()) . "\r\n\r\n";
            $message .= $comment->getEmail() . "\r\n\r\n";
            $message .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

            $mail = new Message();
            $mail->setBody($message);
            $mail->setFrom('rob@robkeplin.com');
            $mail->addTo('rkeplin@gmail.com', 'Rob Keplin');
            $mail->setSubject('New comment on robkeplin.com');

            $transport = new Sendmail();

            try {
                $transport->send($mail);
            } catch(RuntimeException $e) {
                $this->addMessage('A notification email was not sent due to sendmail failing. Doh!', self::MSG_ERROR);
            }

            $this->addMessage('Thanks for commenting.', self::MSG_NOTICE);

            return true;
        }

        $this->addMessage('Hey, Wait!  Something went wrong down there.  Please try commenting again, it did not go through.', self::MSG_ERROR);

        return false;
    }

    /**
     * Saves a comment
     *
     * @param array $data
     * @return boolean
     **/
    public function edit($data)
    {
        $this->form->setData($data);

        if($this->form->isValid()) {

            $comment = $this->form->getData();
            $this->em->persist($comment);
            $this->em->flush();

            $this->addMessage('Updated comment', CommentService::MSG_NOTICE);

            return true;
        }

        $this->addMessage('Something went wrong', CommentService::MSG_ERROR);

        return false;
    }

    /**
     * Gets paged comments
     *
     * @param int $page
     * @return Zend\Paginator\Paginator
     **/
    public function getPaged($page = 1)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('c')
           ->from(self::ENTITY_COMMENT, 'c')
           ->orderBy('c.dateAdded', 'DESC');

        $paginator = PaginatorFactory::create($qb, $page, 10);

        return $paginator;
    }

    /**
     * Gets a comment from it's ID
     *
     * @param int $id
     * @return Comment
     **/
    public function getOne($id)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('c')
           ->from(self::ENTITY_COMMENT, 'c')
           ->where('c.id = :id')
           ->setParameters(array(
               'id' => $id
            ));

        $query = $qb->getQuery();
        $result = $query->getSingleResult();

        return $result;
    }

    /**
     * Removes comment
     *
     * @param Comment $comment
     * @return void
     */
    public function delete(Comment $comment)
    {
        $dql = sprintf('UPDATE %s c SET c.parentId = 0 WHERE c.parentId = %s', self::ENTITY_COMMENT, $comment->getId());
        $query = $this->em->createQuery($dql);
        $query->execute();

        $this->em->remove($comment);
        $this->em->flush();
    }

    /**
     * Sets CommentForm
     *
     * @param CommentForm $form
     * @return void
     **/
    public function setForm(CommentForm $form)
    {
        $this->form = $form;
    }

    /**
     * Sets Post
     *
     * @param Post $post
     * @return void
     **/
    public function setPost(Post $post)
    {
        $this->post = $post;
    }
}