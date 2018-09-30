<?php
namespace ToolboxBundle\Services;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use UserBundle\Entity\Role;
use UserBundle\Entity\SoftdeletableEntity;
use UserBundle\Entity\User;

class EntitySecurityService
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var RoleHierarchyInterface
     */
    private $roleHierarchyObj;

    /**
     * @var array
     */
    private $roleHierarchyArr;

    public function __construct(
        AuthorizationCheckerInterface   $checker,
        TokenStorageInterface           $tokenStorage,
        RoleHierarchyInterface          $roleHierarchy
    ) {
        $this->checker          = $checker;
        $this->tokenStorage     = $tokenStorage;
        $this->roleHierarchyObj = $roleHierarchy;

        $this->initRoleHierarchyArr();
    }

    /**
     * current user have to be owner of entity
     *
     * @param SoftdeletableEntity $entity
     *
     * @return boolean
     */
    public function isEntityGranted(SoftdeletableEntity $entity)
    {
        $user = $this->getUser();

        if ($user instanceof User !== true) {
            return false;
        }

        /*
         * if $entity is instanceof User:
         *     user can only edit itself
         * else
         *    User can only edit entities created by itself
         */
        $entityUser = $this->getUserOfEntity($entity);

        // entity->user::id === token->user::id?
        return ($entityUser->getId() === $user->getId());
    }

    /**
     * current user have to be owner or admin
     *
     * @param SoftdeletableEntity $entity
     *
     * @return boolean
     */
    public function isEntityGrantedWithAdminRights(SoftdeletableEntity $entity)
    {
        $isGranted = $this->isEntityGranted($entity);

        return $isGranted || $this->isGranted(Role::ROLE_ADMIN);
    }

    /**
     * Get a user from the Security Token Storage.
     *
     * @return User|null
     */
    public function getUser()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        /** @var User $user */
        if (!is_object($user = $token->getUser())) {
            return null;
        }

        return $user;
    }

    /**
     * current user have to be owner or has to have minimum admin rights
     * and higher rights than $entity s role
     *
     * @param SoftdeletableEntity $entity
     *
     * @return boolean
     */
    public function isEntityGrantedWithCurrentRights(SoftdeletableEntity $entity)
    {
        $isGranted = $this->isEntityGrantedWithAdminRights($entity);

        if ($isGranted !== true) {
            return false;
        }

        $nextHigherRole = $this->getNextHigherRole($entity);

        if($nextHigherRole === false) {
            return false;
        }

        return $isGranted || $this->isGranted($nextHigherRole);
    }

    /**
     * Checks if the attributes are granted against the current authentication token and optionally
     * supplied object.
     *
     * @param mixed $attributes The attributes
     * @param mixed $object     The object
     *
     * @return bool
     */
    private function isGranted($attributes, $object = null)
    {
        return $this->checker->isGranted($attributes, $object);
    }

    /**
     * @param SoftdeletableEntity $entity
     *
     * @return User
     */
    private function getUserOfEntity(SoftdeletableEntity $entity)
    {
        if ($entity instanceof User) {
            /** @var User $entityUser */
            return $entity;
        } else {
            /** @var User $entityUser */
            return $entity->getCreatedBy();
        }
    }

    /**
     * @param SoftdeletableEntity $entity
     *
     * @return string|false
     */
    private function getNextHigherRole(SoftdeletableEntity $entity)
    {
        $userEntity = $this->getUserOfEntity($entity);

        if($userEntity->getRole() instanceof Role !== true) {
            return Role::ROLE_APPLICANT;
        }

        $roleKey = $this->roleHierarchyArr[$userEntity->getRole()->getRole()];


        // higher roles have lower key
        $roleKey--;

        if($roleKey >= 0) {
            /**
             * @var string  $roleRole
             * @var integer $currentRoleKey
             */
            foreach($this->roleHierarchyArr as $roleRole => $currentRoleKey) {
                if($currentRoleKey === $roleKey) {
                    return $roleRole;
                }
            }
        }

        return false;
    }

    public function getLowerRoles()
    {
        return $this->roleHierarchyArr;

        $roleRole = $this->getUser()->getRole()->getRole();

        $currentRoleKey = $this->roleHierarchyArr[$roleRole];

        $roles = [];

        foreach($this->roleHierarchyArr as $roleName => $roleKey) {
            if($roleKey > $currentRoleKey) {
                $roles[] = $roleName;
            }
        }

        return $roles;
    }

    private function initRoleHierarchyArr()
    {
        $roleHierarchy = $this->roleHierarchyObj->getReachableRoles([new Role(Role::ROLE_SUPER_ADMIN)]);

        foreach ($roleHierarchy as $roleKey => $role) { /** @var Role $role */
            $this->roleHierarchyArr[$role->getRole()] = $roleKey;
        }
    }
}