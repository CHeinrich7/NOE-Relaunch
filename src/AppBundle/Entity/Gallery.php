<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use ToolboxBundle\Interfaces\EntityInterface;
use ToolboxBundle\Traits\EntityTrait;

class Gallery implements EntityInterface
{
    use EntityTrait;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank(gallery.description_blank)
     */
    protected $description;

    /**
     * @var ArrayCollection|ImageGalleryConnector[]
     */
    protected $images;

    /**
     * @ORM\ManyToOne(targetEntity="Image", inversedBy="galleryPreviews")
     * @ORM\JoinColumn(referencedColumnName="id")
     * @Assert\NotBlank(gallery.preview_image_blank)
     *
     * @var Image
     */
    protected $previewImage;


    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|Image[]
     */
    public function getImages()
    {
        $images = new ArrayCollection();

        foreach($this->images as $imageConnector)
        {
            $images->add($imageConnector->getImage());
        }

        return $images;
    }

    /**
     * @param Image $image
     *
     * @return ImageGalleryConnector|bool
     */
    protected function getConnectorByImage(Image $image)
    {
        foreach($this->images as $imageConnector)
        {
            if($imageConnector->getImage() === $image) {
                return $imageConnector;
            }
        }

        return false;
    }

    /**
     * @param Image $image
     *
     * @return bool
     */
    public function hasImage(Image $image)
    {
        return ( false !== $this->getConnectorByImage($image) );
    }

    /**
     * @param Image $image
     *
     * @return $this
     */
    public function addImage(Image $image): self
    {
        if(!$this->hasImage($image)) {
            return $this;
        }

        $connector = new ImageGalleryConnector($image, $this);

        $this->images->add($connector);

        return $this;
    }

    /**
     * @param Image $image
     *
     * @return Gallery
     */
    public function setPreviewImage(Image $image): self
    {
        if($this->previewImage === $image) {
            return $this;
        }

        $this->previewImage = $image;

        $image->addGalleryPreview($this);

        return $this;
    }
}