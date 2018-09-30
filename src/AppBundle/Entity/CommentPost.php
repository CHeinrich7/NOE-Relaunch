<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment_post")
 */
class CommentPost extends Comment
{
    const COMMENT_TYPE = self::COMMENT_TYPE_POST;

    /**
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Post")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $post;

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     *
     * @return $this
     */
    public function setPost(Post $post)
    {
        $this->post = $post;

        return $this;
    }
}