<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="theme")
 */
class Theme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     *
     * @var Image
     */
    protected $background;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="theme.title_blank")
     *
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     * @Assert\Email()
     *
     * @var string
     */
    protected $authorEmail;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="theme.copyright_blank")
     *
     * @var string
     */
    protected $copyright;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="theme.imprint_blank")
     *
     * @var string
     */
    protected $imprint;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="theme.about_blank")
     *
     * @var string
     */
    protected $about;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    protected $inUse;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Image
     */
    public function getBackground(): Image
    {
        return $this->background;
    }

    /**
     * @param Image $background
     *
     * @return $this
     */
    public function setBackground(Image $background): self
    {
        $this->background = $background;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorEmail(): string
    {
        return $this->authorEmail;
    }

    /**
     * @param string $authorEmail
     *
     * @return $this
     */
    public function setAuthorEmail(string $authorEmail): self
    {
        $this->authorEmail = $authorEmail;

        return $this;
    }

    /**
     * @return string
     */
    public function getCopyright(): string
    {
        return $this->copyright;
    }

    /**
     * @param string $copyright
     *
     * @return $this
     */
    public function setCopyright(string $copyright): self
    {
        $this->copyright = $copyright;

        return $this;
    }

    /**
     * @return string
     */
    public function getImprint(): string
    {
        return $this->imprint;
    }

    /**
     * @param string $imprint
     *
     * @return $this
     */
    public function setImprint(string $imprint): self
    {
        $this->imprint = $imprint;

        return $this;
    }

    /**
     * @return string
     */
    public function getAbout(): string
    {
        return $this->about;
    }

    /**
     * @param string $about
     *
     * @return $this
     */
    public function setAbout(string $about): self
    {
        $this->about = $about;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInUse(): bool
    {
        return $this->inUse;
    }

    /**
     * @param bool $inUse
     *
     * @return $this
     */
    public function setInUse(bool $inUse): self
    {
        $this->inUse = $inUse;

        return $this;
    }
}