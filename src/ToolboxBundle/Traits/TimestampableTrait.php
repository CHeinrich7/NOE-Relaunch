<?php
namespace ToolboxBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * HINT: do not forget to use "Gedmo\Mapping\Annotation as Gedmo" in your entity even it's marked as not used!
 */
trait TimestampableTrait
{
    use CreatedTrait;

    /**
     * @Gedmo\Timestampable({"create", "update"})
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt ?? $this->getCreatedAt();
    }
}