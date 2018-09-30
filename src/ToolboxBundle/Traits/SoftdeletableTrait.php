<?php
namespace ToolboxBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Interfaces\UserInterface;

#use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Add following lines to your Entity to enable softdelete:
 * Gedmo\SoftDeleteable(fieldName="deletedAt")
 * Gedmo\SoftDeleteable(fieldName="deletedBy")
 */
trait SoftdeletableTrait
{
    use
        TimestampableTrait,
        ModifiedTrait
    ;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Interfaces\UserInterface")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    protected $deletedBy;

    /**
     * @return \DateTime
     */
    public function getDeletedAt(): \DateTime
    {
        return $this->deletedAt;
    }

    /**
     * @return UserInterface
     */
    public function getDeletedBy(): UserInterface
    {
        return $this->deletedBy;
    }
}