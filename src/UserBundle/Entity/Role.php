<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="Doctrine\ORM\EntityRepository")
 * @ORM\Table(name="userrole")
 *
 * @UniqueEntity(fields="role", message="There can not be one Role twice in Database!")
 */
class Role implements RoleInterface
{
    const ROLE_APPLICANT    = 'ROLE_APPLICANT';
    const ROLE_ADMIN        = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN  = 'ROLE_SUPER_ADMIN';

    /**
     * @param $role
     */
    public function __construct ( $role )
    {
        $this->role = $role;
    }

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=16)
     * @Constraints\NotBlank()
     * @var string
     */
    protected $role = self::ROLE_APPLICANT;

    /**
     * @param $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $role
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function setRole ( $role )
    {
        $this->role = $role;

        if(!in_array($role, [
            Role::ROLE_APPLICANT,
            Role::ROLE_ADMIN
        ])) {
            throw new \Exception('Role::role can not be ' . $role);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getRole ()
    {
        return $this->role;
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->getRole();
    }


}