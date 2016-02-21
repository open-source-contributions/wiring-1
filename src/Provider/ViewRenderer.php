<?php

namespace Wiring\Provider;

use League\Plates\Engine;

class ViewRenderer extends Engine implements ViewRendererInterface
{
    /**
     * Create a new template engine instance.
     * 
     * @param string $directory View path
     * @param string $fileExtension File extension
     */
    public function __construct($directory = null, $fileExtension = 'phtml')
    {
        parent::__construct($directory, $fileExtension);
    }
}