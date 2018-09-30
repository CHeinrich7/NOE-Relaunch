<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ToolboxBundle\Traits\EntityTrait;
use ToolboxBundle\Traits\SoftdeletableTrait;
use UserBundle\Interfaces\UserInterface;

/**
 * @ORM\Entity(repositoryClass="UserBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="userdata")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @Gedmo\SoftDeleteable(fieldName="deletedBy")
 *
 * @UniqueEntity(fields="caption", message="There can not be one User twice in Database!")
 */
class User implements UserInterface
{
    use
        EntityTrait,
        SoftdeletableTrait
    ;

    /**
     * @var string
     * @ORM\Column(type="string", length=25)
     * @Constraints\NotBlank()
     * @Constraints\Length(min = "5", minMessage="Mindestlänge: 5 Zeichen")
     */
    protected $caption;

    /**
     * @var string
     * @ORM\Column(type="string", length=32)
     */
    protected $salt = null;

    /**
     * algorithm: sha512
     * encode_as_base64: true
     *
     * @var string
     * @ORM\Column(type="string", length=128)
     * @Constraints\Length(max = "128", maxMessage="Maximallänge: 128 Zeichen")
     */
    protected $password;

    /**
     * @var boolean
     * @ORM\Column(name="is_superuser", type="boolean", options={"default" = 0})
     */
    protected $isSuperUser = false;

    /**
     * @var boolean
     * @ORM\Column(name="is_active", type="boolean", options={"default" = 1})
     */
    protected $isActive  = true;

    /**
     * @var boolean
     * @ORM\Column(name="is_deletable", type="boolean", options={"default" = 1})
     */
    protected $isDeletable = true;

    /**
     * @var string
     * @Constraints\Length(min = "5", minMessage="Mindestlänge: 5 Zeichen")
     * @Constraints\NotBlank(message="Dieses Feld darf nicht leer sein")
     */
    protected $newPassword = null;

    /**
     * @var string
     * @Constraints\NotBlank(message="Dieses Feld darf nicht leer sein")
     */
    protected $oldPassword = null;

//    /**
//     * @ORM\OneToOne(targetEntity="Profile", cascade={"persist", "remove"})
//     * @Constraints\Valid
//     */
//    protected $profile;

    /**
     * @var Role
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumn(referencedColumnName="id")
     * @Constraints\Valid
     */
    protected $role;

    /**
     * @param boolean $isActive
     *
     * @return $this
     */
    public function setIsActive ( $isActive )
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsActive (): bool
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isDeletable
     *
     * @return $this
     */
    public function setIsDeletable ( $isDeletable )
    {
        $this->isDeletable = $isDeletable;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsDeletable (): bool
    {
        return $this->isDeletable;
    }

    /**
     * @param boolean $isSuperUser
     *
     * @return $this
     */
    public function setIsSuperUser ( $isSuperUser )
    {
        $this->isSuperUser = $isSuperUser;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsSuperUser (): bool
    {
        return $this->isSuperUser;
    }

    /**
     * @return string
     */
    public function getSalt (): string
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     *
     * @return $this
     */
    public function setSalt ($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword ( $password )
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword (): string
    {
        return $this->password;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername ( $username )
    {
        $this->setCaption( $username );

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername (): string
    {
        return $this->getCaption();
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles (): array
    {
        return array( $this->role->getRole() );
    }

    /**
     * @return Role
     */
    public function getRole (): Role
    {
        return $this->role;
    }

    /**
     * @param Role $role
     *
     * @return $this
     */
    public function setRole ($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @param boolean   $withPasswordAndSalt
     *
     * @return $this
     */
    public function eraseCredentials ($withPasswordAndSalt = false)
    {
        $this->newPassword = null;
        $this->oldPassword = null;

        if($withPasswordAndSalt === true) {
            $this->password = null;
            $this->salt     = null;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getOldPassword ()
    {
        return $this->oldPassword;
    }

    /**
     * @param string $oldPassword
     *
     * @return $this
     */
    public function setOldPassword ($oldPassword)
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    /**
     * @param string $newPassword
     *
     * @return $this
     */
    public function setNewPassword ( $newPassword )
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    /**
     * @return string
     */
    public function getNewPassword ()
    {
        return $this->newPassword;
    }
}