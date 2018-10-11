<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

class ImageGalleryConnector
{

    public function __construct(Image $image = null, Gallery $gallery = null)
    {
        if($image instanceof Image) {
            $image->addGallery($gallery);
        }

        if($gallery instanceof Gallery) {
            $gallery->addImage($image);
        }
    }

    /**
     * @var Gallery
     *
     * @ORM\ManyToOne(targetEntity="Gallery")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    protected $gallery;

    /**
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="Image")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    protected $image;

    /**
     * @var integer
     */
    protected $weight;

    /**
     * @return Gallery
     */
    public function getGallery(): Gallery
    {
        return $this->gallery;
    }

    /**
     * @param Gallery $gallery
     *
     * @return $this
     */
    public function setGallery(Gallery $gallery)
    {
        $this->gallery = $gallery;

        return $this;
    }

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

    /**
     * @return ArrayCollection|Image[]
     */
    public function getImages()
    {
        return $this->gallery->getImages();
    }

    /**
     * @return ArrayCollection|Gallery[]
     */
    public function getGalleries()
    {
        return $this->image->getGalleries();
    }

    /**
     * @param Image $image
     *
     * @return $this
     */
    public function addImage(Image $image): self
    {
        $this->gallery->addImage($image);

        return $this;
    }

    /**
     * @param Gallery $gallery
     *
     * @return $this
     */
    public function addGallery(Gallery $gallery): self
    {
        $this->image->addGallery($gallery);

        return $this;
    }

    /**
     * @param Image $image
     *
     * @return bool
     */
    public function removeImage(Image $image): bool
    {
        return $this->gallery->removeImage($image);
    }

    /**
     * @param Gallery $gallery
     *
     * @return bool
     */
    public function removeGallery(Gallery $gallery): bool
    {
        return $this->image->removeGallery($gallery);
    }

    /**
     * @param Image $image
     *
     * @return bool
     */
    public function hasImage(Image $image): bool
    {
        return $this->gallery->hasImage();
    }

    /**
     * @param Image $image
     *
     * @return bool
     */
    public function hasGallery(Image $image): bool
    {
        return $this->image->hasGallery();
    }
}