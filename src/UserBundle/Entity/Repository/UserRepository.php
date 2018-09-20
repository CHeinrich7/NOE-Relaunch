<?php

namespace UserBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use UserBundle\Entity\User;

/**
 * Class UserRepository
 * @package UserBundle\Entity\Repository
 */
class UserRepository extends EntityRepository  implements UserProviderInterface
{
    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {

        try {
            $user = $this->findOneBy(['username' => $username]);
        } catch (NoResultException $e) {
            $message = sprintf(
                'Unable to find an active admin AcmeUserBundle:User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }

        return $user;
    }
    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        /* @var $user User */
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->find($user->getId());
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
        || is_subclass_of($class, $this->getEntityName());
    }

    /**
     * @param array $lowerRoles
     *
     * @return array
     */
    public function findAllByRoles(array $lowerRoles)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('u');

        $qb
            ->select('u.username as name', 'u.id as id', 'r.role as role')
            ->join('u.role', 'r')
            ->orderBy('u.username')
            // ->where('u.isSuperUser = false')
            ->andWhere('r.role in (:roles)')
            ->setParameter('roles', $lowerRoles)
            ->distinct(true);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Form                    $form
     *
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function updateUserFromForm(Form $form, EncoderFactoryInterface $encoderFactory)
    {
        /** @var User $user */
        $user = $form->getData();

        $encoder = $encoderFactory->getEncoder($user);

        $user->setSalt(md5(time()));
        $password = $encoder->encodePassword($user->getNewPassword(), $user->getSalt());

        $user
            ->eraseCredentials()
            ->setPassword($password);

        $this->_em->persist($user);
        $this->_em->flush($user);
    }
} 