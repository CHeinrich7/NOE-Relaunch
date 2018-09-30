<?php
namespace AppBundle\Entity;

class ImagePost extends Comment
{
    /**
     * @ORM\Column(type="smallint")
     */
    protected $commentType = self::COMMENT_POST;
}