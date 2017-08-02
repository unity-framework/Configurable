<?php

namespace Unity\Component\Configurable;

use Unity\Component\Configurable\Drivers\DriveInterface;

class Configurable implements ConfigurableInterface
{
    protected $drive;
    protected $source;

    function __construct(DriveInterface $drive, $source)
    {
        $this->drive = $drive;
        $this->source = $source;
    }

    function get($config)
    {
        return $this->drive->get($config, $this->source);
    }
}