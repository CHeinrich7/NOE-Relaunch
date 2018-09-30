<?php
namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints;
use Gedmo\Mapping\Annotation as Gedmo;
use ToolboxBundle\Traits\TimestampableTrait;

/**
 * @package UserBundle\Entity
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @Gedmo\SoftDeleteable(fieldName="deletedBy")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
abstract class SoftdeletableEntity
{
    use TimestampableTrait;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     * @Constraints\DateTime
     */
    protected $deletedAt;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    protected $deletedBy;

    /**
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @return UserInterface
     */
    public function getDeletedBy()
    {
        return $this->deletedBy;
    }
}