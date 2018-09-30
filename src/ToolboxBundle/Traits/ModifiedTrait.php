<?php
namespace ToolboxBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use UserBundle\Interfaces\UserInterface;

trait ModifiedTrait
{
    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Interfaces\UserInterface")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     * @Gedmo\Blameable(on="create")
     */
    protected $createdBy;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Interfaces\UserInterface")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     * @Gedmo\Blameable(on={"create", "update"})
     */
    protected $modifiedBy;

    /**
     * @return UserInterface
     */
    public function getCreatedBy(): UserInterface
    {
        return $this->createdBy;
    }

    /**
     * @return UserInterface
     */
    public function getModifiedBy(): UserInterface
    {
        return $this->modifiedBy ?? $this->getCreatedBy();
    }
}