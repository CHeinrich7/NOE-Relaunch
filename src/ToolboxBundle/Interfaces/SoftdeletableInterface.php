<?php
namespace ToolboxBundle\Interfaces;

#use Doctrine\ORM\Mapping as ORM;
#use Gedmo\Mapping\Annotation as Gedmo;
use UserBundle\Interfaces\UserInterface;

//use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Add following lines to your Entity to enable softdelete:
 * Gedmo\SoftDeleteable(fieldName="deletedAt")
 * Gedmo\SoftDeleteable(fieldName="deletedBy")
 */
interface SoftdeletableInterface extends TimestampableInterface, ModifiedInterface
{
    /**
     * @return \DateTime
     */
    public function getDeletedAt(): \DateTime;

    /**
     * @return UserInterface
     */
    public function getDeletedBy(): UserInterface;
}