<?php

namespace Wiring\Controller;

interface ControllerInterface
{
    /**
     * Get an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     * @return mixed Entry.
     */
    public function get($id);

    /**
     * Check if the container can return an entry for the given identifier.
     *
     * @param string $id Identifier of the entry to look for.
     * @return boolean
     */
    public function has($id);
}