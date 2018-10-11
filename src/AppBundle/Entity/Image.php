<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use ToolboxBundle\Interfaces\CreatedInterface;
use ToolboxBundle\Interfaces\EntityInterface;
use ToolboxBundle\Traits\CreatedTrait;
use ToolboxBundle\Traits\EntityTrait;

#use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="image")
 */
class Image implements EntityInterface, CreatedInterface
{
    const WEB_FILEPATH = DIRECTORY_SEPARATOR . 'uploaded' . DIRECTORY_SEPARATOR;

    use EntityTrait, CreatedTrait;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $description;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="image.file_blank")
     *
     * @var File
     */
    protected $file;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="image.thumb_blank")
     *
     * @var File
     */
    protected $thumb;

    /**
     * @ORM\OneToMany(
     *      targetEntity="CommentImage",
     *      mappedBy="image",
     *      orphanRemoval=true
     * )
     * @ORM\OrderBy({"publishedAt" = "DESC"})
     *
     * @var ArrayCollection|CommentImage[]
     */
    protected $comments;

    /**
     * @ORM\OneToMany(
     *      targetEntity="ImageGalleryConnector",
     *      mappedBy="image",
     *      orphanRemoval=false
     * )
     * @ORM\OrderBy({"weight" = "ASC"})
     *
     * @var ArrayCollection|ImageGalleryConnector[]
     */
    protected $galleries;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Gallery",
     *      mappedBy="previewImage",
     *      orphanRemoval=false
     * )
     *
     * @var ArrayCollection|Gallery[]
     */
    protected $galleryPreviews;


    public function __construct()
    {
        $this->comments         = new ArrayCollection();
        $this->galleries        = new ArrayCollection();
        $this->galleryPreviews  = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description = null): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return ArrayCollection|CommentImage[]
     */
    public function getComments(): ArrayCollection
    {
        return $this->comments;
    }

    /**
     * @param CommentImage $comment
     *
     * @return $this
     */
    public function addComment(CommentImage $comment): self
    {
        $this->comments->add($comment);
        $comment->setImage($this);

        return $this;
    }

    /**
     * @param CommentImage $comment
     *
     * @return bool
     */
    public function hasComment(CommentImage $comment): bool
    {
        return $this->comments->contains($comment);
    }

    /**
     * @param CommentImage $comment
     *
     * @return bool
     */
    public function removeComment(CommentImage $comment)
    {
        return $this->comments->removeElement($comment);
    }

    public function getFile()
    {
        if(is_string($this->file) && is_file($this->file)) {
            $this->file = new File($this->file);
        }

        return $this->file;
    }

    /**
     * @param File $file
     *
     * @return $this
     */
    public function setFile(File $file): self
    {
        $this->file = $file;
        $this->getThumb();

        return $this;
    }

    /**
     * @return bool
     */
    public function isFile()
    {
        return is_file($this->getFile());
    }

    /**
     * @param File $thumb
     *
     * @return $this
     */
    public function setThumb(File $thumb): self
    {
        $this->thumb = $thumb;
        return $this;
    }

    /**
     * @return bool
     */
    public function isThumb()
    {
        return $this->isFile() && !empty($this->thumb) && is_file($this->thumb);
    }

    /**
     * @return null|string
     */
    public function getWebFile()
    {
        if(!$this->isFile()) {
            return null;
        }

        return self::WEB_FILEPATH . $this->getFile()->getFilename();
    }

    /**
     * @return null|File
     */
    public function getThumb()
    {
        if(!$this->isFile()) {
            return null;
        }

        if($this->isThumb() && $this->thumb instanceof File) {
            return $this->thumb;
        }

        if($this->isThumb()) {
            return new File($this->thumb);
        }

        return $this->createThumb();
    }

    /**
     * @throws FileException
     * @throws \LogicException
     *
     * @return null|File
     */
    protected function createThumb()
    {
        if(!$this->isFile()) {
            return null;
        }

        /** @var File $file */
        $file = $this->getFile();

        // throws FileException if mimeType is not allowed
        static::mimeTypeToFileExtension($file->getMimeType());

        switch ($file->getMimeType())
        {
            case image_type_to_mime_type(IMAGETYPE_JPEG):
            case image_type_to_mime_type(IMAGETYPE_PNG):
            case image_type_to_mime_type(IMAGETYPE_GIF):
                $img = imagecreatefrompng($file);
                break;
            default:
                throw new \LogicException(sprintf(
                    'please, developer. your forgot to map the MimeType "%s" in this switch ...'
                ), $file->getMTime());
        }

        $fileRatio = $this->getFileRatio();

        if($fileRatio['width'] >= $fileRatio['height']) {
            $thumb = imagescale($img, 200, -1);
        } else {
            $thumb = imagescale($img, -1, 200);
        }

        $thumbName = md5(uniqid()).'.'.$file->guessExtension();

        $nfs = implode(
            DIRECTORY_SEPARATOR,
            array(
                $this->get('kernel')->getRootDir(),
                '..',
                'nfs',
                'upload'
            )
        );
        $nfs .= DIRECTORY_SEPARATOR;

        file_put_contents($nfs.$thumbName, $thumb);

        $this->thumb = new File($nfs.$thumbName);

        return $this->thumb;
    }

    /**
     * @return array|null
     */
    public function getFileRatio()
    {
        if(!$this->isFile()) {
            return null;
        }

        $imageSize = getimagesize($this->getFile());

        return array(
            'src'       => $this->getWebFile(),
            'caption'   => $this->getCaption(),
            'width'     => $imageSize[0],
            'height'    => $imageSize[1]
        );
    }

    /**
     * @return array
     */
    public static function getAllowedFileExtensions(): array
    {
        return array(
            image_type_to_mime_type(IMAGETYPE_PNG)  => image_type_to_extension(IMAGETYPE_PNG),
            image_type_to_mime_type(IMAGETYPE_JPEG) => image_type_to_extension(IMAGETYPE_JPEG),
            image_type_to_mime_type(IMAGETYPE_GIF)  => image_type_to_extension(IMAGETYPE_GIF)
        );
    }

    /**
     * @param string $mimetype
     *
     * @return string
     *
     * @throws FileException
     */
    public static function mimeTypeToFileExtension(string $mimetype):string
    {
        $fileTypes = static::getAllowedFileExtensions();
        if(!array_key_exists($mimetype, $fileTypes)) {
            throw new FileException(sprintf('Not allowed MimeType %s allowed are: [ %s ]', $mimetype, implode(', ', $fileTypes)));
        }

        return $fileTypes[ $mimetype ];
    }

    /**
     * @return ArrayCollection|Gallery[]
     */
    public function getGalleries()
    {
        $galleries = new ArrayCollection();

        foreach($this->galleries as $galleryConnector)
        {
            $galleries->add($galleryConnector->getGallery());
        }

        return $galleries;
    }

    /**
     * @param Gallery $gallery
     *
     * @return ImageGalleryConnector|bool
     */
    protected function getConnectorByGallery(Gallery $gallery)
    {
        foreach($this->galleries as $galleryConnector)
        {
            if($galleryConnector->getGallery() === $gallery) {
                return $galleryConnector;
            }
        }

        return false;
    }

    /**
     * @param Gallery $gallery
     *
     * @return bool
     */
    public function hasGallery(Gallery $gallery)
    {
        return ( false !== $this->getConnectorByGallery($gallery) );
    }

    /**
     * @param Gallery $gallery
     *
     * @return $this
     */
    public function addGallery(Gallery $gallery): self
    {
        if(!$this->hasGallery($gallery)) {
            return $this;
        }

        $connector = new ImageGalleryConnector($this, $gallery);

        $this->galleries->add($connector);

        return $this;
    }

    /**
     * @return Gallery[]|ArrayCollection
     */
    public function getGalleryPreviews(): ArrayCollection
    {
        return $this->galleryPreviews;
    }

    public function hasGalleryPreview(Gallery $gallery)
    {
        return $this->galleryPreviews->contains($gallery);
    }

    public function addGalleryPreview(Gallery $gallery): self
    {
        if($this->hasGalleryPreview($gallery)) {
            return $this;
        }

        $this->galleryPreviews->add($gallery);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->caption;
    }
}
