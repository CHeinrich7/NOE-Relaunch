<?php
namespace ToolboxBundle\Interfaces;

interface EntityInterface
{
    /**
     * @return integer
     */
    public function getId(): int;

    /**
     * @return string
     */
    public function getCaption(): string;

    /**
     * @return $this
     */
    public function setCaption($caption);
}