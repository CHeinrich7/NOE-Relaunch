<?php
namespace ToolboxBundle\Interfaces;

use UserBundle\Interfaces\UserInterface;

interface ModifiedInterface extends CreatedInterface
{
    /**
     * @return UserInterface
     */
    public function getCreatedBy(): UserInterface;

    /**
     * @return UserInterface
     */
    public function getModifiedBy(): UserInterface;
}