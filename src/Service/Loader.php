<?php

namespace Wiring\Service;

class Loader
{
    protected $path = [];
    protected $filetypes;

    /**
     * Loader constructor.
     *
     * @param array $filetypes
     */
    public function __construct($filetypes = ['php'])
    {
        $this->filetypes = $filetypes;
    }

    /**
     * Add path.
     *
     * @param $path
     */
    public function addPath($path)
    {
        $this->path[] = $path;
    }

    /**
     * Get files load.
     *
     * @return array
     */
    public function load()
    {
        $scripts = [];

        foreach ($this->path as $path) {
            // Get files
            foreach ($this->filetypes as $filetype) {
                $scripts[] = glob($path . "/*.{$filetype}");
            }
        }

        return array_reduce($scripts, 'array_merge', []);
    }
}
