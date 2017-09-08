<?php

namespace Unity\Component\Config\Drivers\File;

use Unity\Component\Config\Drivers\FileDriver;

class YamlDriver extends FileDriver
{
    /**
     * Returns the configuration as an array
     *
     * @param $ymlfile
     *
     * @return array
     */
    function parse($ymlfile)
    {
        if(file_exists($ymlfile))
            return yaml_parse_file($ymlfile);
    }
}