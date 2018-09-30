<?php
namespace UserBundle\Interfaces;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;
use ToolboxBundle\Interfaces\EntityInterface;
use ToolboxBundle\Interfaces\SoftdeletableInterface;
use UserBundle\Entity\Role;

interface UserInterface extends SoftdeletableInterface, EntityInterface, BaseUserInterface
{
    /**
     * @return boolean
     */
    public function getIsActive (): bool;

    /**
     * @param boolean $isDeletable
     *
     * @return $this
     */
    public function setIsDeletable ( $isDeletable );

    /**
     * @return boolean
     */
    public function getIsDeletable (): bool;


    /**
     * @param boolean $isSuperUser
     *
     * @return $this
     */
    public function setIsSuperUser ( $isSuperUser );

    /**
     * @return boolean
     */
    public function getIsSuperUser (): bool;

    /**
     * @return string
     */
    public function getSalt (): string;

    /**
     * @param string $salt
     *
     * @return $this
     */
    public function setSalt ($salt);

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword ( $password );

    /**
     * @return string
     */
    public function getPassword (): string;

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername ( $username );

    /**
     * @return string
     */
    public function getUsername (): string;

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
    public function getRoles (): array;
}