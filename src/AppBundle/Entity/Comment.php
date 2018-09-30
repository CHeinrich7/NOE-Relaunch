<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="comment_type", type="string")
 * @ORM\DiscriminatorMap({"image" = 1, "post" = 2})
 */
/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
abstract class Comment
{
    const COMMENT_IMAGE = 1;
    const COMMENT_POST  = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="comment.blank")
     * @Assert\Length(
     *     min = "10",
     *     minMessage = "comment.too_short",
     *     max = "10000",
     *     maxMessage = "comment.too_long"
     * )
     */
    protected $content;

    /**
     * @ORM\Column(type="string")
     * @Assert\Email()
     */
    protected $authorEmail;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    protected $publishedAt;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $commentType;

    /**
     * @Assert\IsTrue(message = "comment.is_spam")
     */
    public function isLegitComment()
    {
        $containsInvalidCharacters = false !== strpos($this->content, '@');

        return !$containsInvalidCharacters;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getContent()
    {
        return $this->content;
    }
    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }
    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;
    }

    public function getPublishedAt()
    {
        return $this->publishedAt;
    }
    public function setPublishedAt(\DateTime $publishedAt)
    {
        $this->publishedAt = $publishedAt;
    }

    public function getPost()
    {
        return $this->post;
    }
    public function setPost(Post $post)
    {
        $this->commentType = self::PostComment;
        $this->post = $post;
        $this->image = null;
    }

    public function getImage()
    {
        return $this->image;
    }
    public function setImage(Image $image)
    {
        $this->commentType = self::ImageComment;
        $this->image = $image;
        $this->post = null;
    }

    public function getCommentType()
    {
        return $this->commentType;
    }
}
