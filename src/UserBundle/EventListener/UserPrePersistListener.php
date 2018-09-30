<?php
namespace UserBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use UserBundle\Entity\User;

/**
 * Class UserPrePersistListener
 * @package UserBundle\EventListener
 */
class UserPrePersistListener implements EventSubscriber
{
    /**
     * @param LifecycleEventArgs $args
     *
     * @throws \Exception
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if($entity instanceof User && $entity->getIsSuperUser()) {
            throw new \Exception('Entity cannot be deleted');
        }
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return ['preRemove'];
    }
}