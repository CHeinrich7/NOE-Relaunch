<?php
namespace ToolboxBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * on a ManyToMany relation don't forget to set the #Gedmo\SortableGroup
 * to the Entity you want to use as groupBy
 */
trait SortableTrait
{
    /**
     * @var integer
     * @ORM\Column(name="weight", type="integer", nullable=false)
     * @Gedmo\SortablePosition
     */
    protected $weight;

    /**
     * @return integer
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @param integer $weight
     *
     * @return $this
     */
    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }
}