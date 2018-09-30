<?php
namespace ToolboxBundle\Interfaces;

interface CreatedInterface
{
    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime;
}