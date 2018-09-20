<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
#use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="image")
 */
class Image
{
    const WEB_FILEPATH = DIRECTORY_SEPARATOR . 'uploaded' . DIRECTORY_SEPARATOR;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="images")
     * @ORM\JoinColumn(nullable=true)
     */
    private $post;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="image.description_blank")
     */
    private $description;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="image.file_blank")
     *
     * @var File
     */
    private $file;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="image.thumb_blank")
     *
     * @var File
     */
    private $thumb;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank(message="image.title_blank")
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     * @Assert\Email()
     */
    private $authorEmail;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $publishedAt;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Comment",
     *      mappedBy="image",
     *      orphanRemoval=true
     * )
     * @ORM\OrderBy({"publishedAt" = "DESC"})
     */
    private $comments;

    public function __construct()
    {
        $this->publishedAt = new \DateTime();
        $this->comments = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescription()
    {
        return $this->description;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }
    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;
    }

    /**
     * Is the given User the author of this Post?
     *
     * @param User $user
     *
     * @return bool
     */
    public function isAuthor(User $user)
    {
        return $user->getEmail() === $this->getAuthorEmail();
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
        $this->post = $post;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function addComment(Comment $comment)
    {
        $this->comments->add($comment);
        $comment->setImage($this);
    }

    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    public function getFile()
    {
        if(is_string($this->file) && is_file($this->file)) {
            $this->file = new File($this->file);
        }

        return $this->file;
    }
    public function setFile(File $file)
    {
        $this->file = $file;
        $this->getThumb();
    }
    public function isFile()
    {
        return is_file($this->getFile());
    }
    public function setThumb(File $thumb)
    {
        $this->thumb = $thumb;
        return;
    }
    public function isThumb()
    {
        return $this->isFile() && !empty($this->thumb) && is_file($this->thumb);
    }
    public function getWebFile()
    {
        if(!$this->isFile()) {
            return null;
        }

        return self::WEB_FILEPATH . $this->getFile()->getFilename();
    }
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
    private function createThumb()
    {
        if(!$this->isFile()) {
            return null;
        }

        /** @var File $file */
        $file = $this->getFile();

        switch ($file->getMimeType()) {
            case image_type_to_mime_type(IMAGETYPE_JPEG):
                $img = imagecreatefromjpeg($file);
                break;
            case image_type_to_mime_type(IMAGETYPE_PNG):
                $img = imagecreatefrompng($file);
                break;
            default:
                // should throw FileException
                static::mimeTypeToFileExtension($file->getMimeType());
                throw new \Exception('Coding Error: static::mimeTypeToFileExtension() should throw FileException');
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

    public function getFileRatio()
    {
        if(!$this->isFile()) {
            return null;
        }

        $imageSize = getimagesize($this->getFile());

        return array(
            'src'       => $this->getWebFile(),
            'title'     => $this->getTitle(),
            'width'     => $imageSize[0],
            'height'    => $imageSize[1]
        );
    }

    public function getTitle()
    {
        return $this->title;
    }
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public static function getAllwedFileExtensions():array
    {
        return array(
            image_type_to_mime_type(IMAGETYPE_PNG)  => image_type_to_extension(IMAGETYPE_PNG),
            image_type_to_mime_type(IMAGETYPE_JPEG) => image_type_to_extension(IMAGETYPE_JPEG)
        );
    }

    public static function mimeTypeToFileExtension(string $mimetype):string
    {
        $fileTypes = static::getAllwedFileExtensions();
        if(!array_key_exists($mimetype, $fileTypes)) {
            throw new FileException(sprintf('Not allowed MimeType %s allowed are: [ %s ]', $mimetype, implode(', ', $fileTypes)));
        }

        return $fileTypes[ $mimetype ];
    }

    public function __toString()
    {
        return $this->title;
    }
}
