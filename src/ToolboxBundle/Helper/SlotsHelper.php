<?php

namespace ToolboxBundle\Helper;

use Symfony\Component\Templating\Helper\SlotsHelper as SymfonySlotsHelper;

/**
 * Class SlotsHelper
 * @package ToolboxBundle\Helper
 */
class SlotsHelper extends SymfonySlotsHelper {
    protected $add = false;
    protected $append = true;

    /**
     * Prepend slot content or starts a new slot if slot doesn't exist.
     *
     * @param string $name The slot name
     *
     * @throws \InvalidArgumentException if a slot with the same name is already started
     */
    public function prepend($name) {
        $this->append = false;
        $this->add = true;
        $this->add($name);
    }

    /**
     * Append slot content or starts a new slot if slot doesn't exist.
     *
     * @param string $name The slot name
     *
     * @throws \InvalidArgumentException if a slot with the same name is already started
     */
    public function append($name) {
        $this->append = true;
        $this->add = true;
        $this->add($name);
    }

    /**
     * remove slot and add new one
     *
     * @param string $name The slot name
     */
    public function replace($name) {
        if (isset($this->slots[$name])) {
            unset($this->slots[$name]);
        }
        $this->add($name);
    }

    /**
     * add slot if not exist
     *
     * @param string $name The slot name
     */
    public function addIfNotExist($name) {
        if (!isset($this->slots[$name])) {
            $this->add($name);
        }
        else {
            $this->add('unused_fake_slot');
        }
    }

    /**
     * Check slot and open it
     *
     * @param string $name The slot name
     */
    private function add($name) {
        if (in_array($name, $this->openSlots)) {
            throw new \InvalidArgumentException(sprintf('A slot named "%s" is already started.', $name));
        }

        $this->openSlots[] = $name;

        if(!isset($this->slots[$name])) {
            $this->slots[$name] = '';
        }

        ob_start();
        ob_implicit_flush(0);
    }



    /**
     * Stops a slot.
     *
     * @throws \LogicException if no slot has been started
     */
    public function stop()
    {
        if (!$this->openSlots) {
            throw new \LogicException('No slot started.');
        }

        $name = array_pop($this->openSlots);

        $slotData = ob_get_clean();
        if($this->add) {
            if($this->append) {
                $this->slots[$name] .= $slotData;
            }
            else {
                $this->slots[$name] = $slotData.$this->slots[$name];
            }
        }
        else {
            $this->slots[$name] = $slotData;
        }

        $this->add = false;
        $this->append = true;
    }
}