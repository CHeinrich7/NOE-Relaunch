<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment_image")
 */
class CommentImage extends Comment
{
    const COMMENT_TYPE = self::COMMENT_TYPE_IMAGE;

    /**
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="Image")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $image;

    /**
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
    }

    /**
     * @param Image $image
     *
     * @return $this
     */
    public function setImage(Image $image)
    {
        $this->image = $image;

        return $this;
    }
}