<?php
namespace AppBundle\Entity;

class ImageComment extends Comment
{
    /**
     * @ORM\Column(type="smallint")
     */
    protected $commentType = self::COMMENT_IMAGE;
}