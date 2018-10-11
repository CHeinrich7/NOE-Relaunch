<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\Validator\Constraints as Assert;
use ToolboxBundle\Interfaces\CreatedInterface;
use ToolboxBundle\Interfaces\EntityInterface;
use ToolboxBundle\Traits\CreatedTrait;
use ToolboxBundle\Traits\EntityTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="comment_type", type="smallint")
 * @ORM\DiscriminatorMap({1 = "CommentImage", 2 = "CommentChain"})
 */
abstract class Comment implements EntityInterface, CreatedInterface
{
    const COMMENT_TYPE_IMAGE    = 1;
    const COMMENT_TYPE_CHAIN    = 2;

    use EntityTrait, CreatedTrait;

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
     * @Assert\IsTrue(message = "comment.is_spam")
     */
    public function isLegitComment()
    {
        $containsInvalidCharacters = false !== strpos($this->content, '@');

        return !$containsInvalidCharacters;
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

    public function getType()
    {
        if(!defined('static::COMMENT_TYPE')) {
            throw new NoSuchPropertyException('The Property static::COMMENT_TYPE is not defined');
        }

        return static::COMMENT_TYPE;
    }
}
