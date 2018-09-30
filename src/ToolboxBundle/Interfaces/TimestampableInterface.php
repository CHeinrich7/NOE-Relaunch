<?php
namespace ToolboxBundle\Interfaces;

/**
 * HINT: do not forget to use "Gedmo\Mapping\Annotation as Gedmo" in your entity even it's marked as not used!
 */
interface TimestampableInterface extends CreatedInterface
{
    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime;
}