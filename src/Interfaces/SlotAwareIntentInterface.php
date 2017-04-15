<?php

namespace Wiring\Intent;

interface SlotAwareIntentInterface
{
    /**
     * Returns all defined slots as array
     *
     * @return array
     */
    public function getSlots();

    /**
     * Adds a new slot to the intent
     *
     * @param array
     *
     * @return void
     */
    public function addSlot($slot);
}
