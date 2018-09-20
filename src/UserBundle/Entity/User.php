<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass="UserBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="user")
 *
 * @UniqueEntity(fields="username", message="There can not be one User twice in Database!")
 */
class User extends SoftdeletableEntity implements UserInterface
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=25)
     * @Constraints\NotBlank()
     * @Constraints\Length(min = "5", minMessage="Mindestlänge: 5 Zeichen")
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable = false)
     */
    protected $salt = null;

    /**
     * algorithm: sha512
     * encode_as_base64: true
     *
     * @var string
     * @ORM\Column(type="string", length=128, nullable = false)
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
     * @return integer
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive ( $isActive )
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsActive ()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isDeletable
     *
     * @return User
     */
    public function setIsDeletable ( $isDeletable )
    {
        $this->isDeletable = $isDeletable;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsDeletable ()
    {
        return $this->isDeletable;
    }

    /**
     * @param boolean $isSuperUser
     *
     * @return User
     */
    public function setIsSuperUser ( $isSuperUser )
    {
        $this->isSuperUser = $isSuperUser;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsSuperUser ()
    {
        return $this->isSuperUser;
    }

    /**
     * @return string
     */
    public function getSalt ()
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
     * @return User
     */
    public function setPassword ( $password )
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword ()
    {
        return $this->password;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername ( $username )
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername ()
    {
        return $this->username;
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
    public function getRoles ()
    {
        return array( $this->role->getRole() );
    }

    /**
     * @return Role
     */
    public function getRole ()
    {
        return $this->role;
    }

    /**
     * @param Role $role
     *
     * @return User
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
     * @return User
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