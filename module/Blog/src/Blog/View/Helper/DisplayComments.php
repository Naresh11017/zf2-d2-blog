<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\View\Helper;

use Blog\Model\Comment;
use Blog\Service\UserService;
use Doctrine\Common\Collections\Collection;
use Zend\View\Helper\AbstractHelper;

/**
 * DisplayComments
 *
 * @package Blog\View
 */
class DisplayComments
    extends AbstractHelper
{

    /**
     * @var string
     */
    protected $_role;

    /**
     * Returns HTML for comments
     *
     * @param Doctrine\Common\Collections\Collection $comments
     * @return string
     * @override
     **/
    public function __invoke(Collection $comments)
    {
        $count = count($comments);

        if($count == 0) {
            return '';
        }

        $string = '';

        for($i = 0; $i < $count; $i++) {
            $comment = $comments[$i];

            if(!$comment->getParentId()) {
                $string .= $this->_commentBox($comment, false);

                for($j = 0; $j < $count; $j++) {
                    $reply = $comments[$j];

                    if($reply->getParentId() == $comment->getId()) {
                        $string .= $this->_commentBox($reply, true);
                    }
                }
            }
        }

        if($this->_role == UserService::ROLE_ADMIN) {
            $string .= '
                <script type="text/javascript">
                    function deleteComment(id) {
                        var url = "' . $this->view->myUrl('blog/default/query', array('controller' => 'comment', 'action' => 'delete')) . '?id=" + id;

                        $.get(url, function(data) {
                            $("#comment-" + id).fadeOut(400);
                        });
                    }
                </script>
            ';
        }

        return $string;
    }

    /**
     * HTML for a single comment
     *
     * @param Comment $comment
     * @param boolean $is_child
     * @return string
     **/
    public function _commentBox(Comment $comment, $is_child = false)
    {
        $extra = ($comment->getEmail() == 'rkeplin@gmail.com') ? ' rob' : '';
        $extra .= ($is_child) ? ' reply' : '';

        if($comment->getWebsite()) {
            $author = '<a title="'. $comment->getName() .'\'s website" href="'. $comment->getWebsite() .'">'. $comment->getName() . '</a>';
        } else {
            $author = $comment->getName();
        }

        $string = '<div id="comment-'. $comment->getId() .'" class="comment-container'. $extra .'">';
        $string .=      '<h2>'. $author .' - '. $comment->getDateAdded()->format("F jS, Y @ g:ia") . ' <a class="reply" title="Reply to this comment" href="javascript:replyToComment(\''. $comment->getId() .'\')">reply</a></h2>';
        $string .=      '<div class="comment">';

        $string .=          '<div class="photo">'
                .                '<a title="Get a gravatar" href="http://www.gravatar.com">'
                .                      $this->view->gravatar($comment->getEmail(), array('img_size' => 50), array('alt' => 'gravatar', 'title' => 'avatar' ))
                .                '</a>'
                .          '</div>';

        $string .=      stripslashes($comment->getComment());

        if($this->_role == UserService::ROLE_ADMIN) {
            $string .= '<p>';
            $string .=  sprintf('<a title="edit" href="%s">edit</a> | ', $this->view->myUrl('blog/default/query', array('controller' => 'comment', 'action' => 'edit', 'id' => $comment->getId())));
            $string .=  sprintf('<a title="delete" href="javascript:deleteComment(%s)">delete</a>', $comment->getId());
            $string .= '</p>';
        }

        $string .=      '<div class="clear"></div>';
        $string .=      '</div>';
        $string .= '</div>';

        return $string;
    }

    /**
     * Sets role
     *
     * @param string $role
     * @return void
     **/
    public function setRole($role)
    {
        $this->_role = $role;
    }
}