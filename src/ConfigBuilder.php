<?php

namespace Unity\Component\Config;
use Unity\Component\Config\SourcesMatcher;

class ConfigBuilder
{
    protected $src;
    protected $ext;
    protected $driverAlias;
    protected $container;
    protected $cachePath;

    /**
     * Sets the source for configurations
     *
     * @param $src
     *
     * @return mixed
     */
    function setSource($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Sets the extension of the configuration files
     *
     * @param mixed $ext
     *
     * @return ConfigBuilder
     */
    function setExt($ext)
    {
        $this->ext = $ext;

        return $this;
    }

    /**
     * Sets the Driver to be used to get Configuration values
     *
     * @param string $driver
     *
     * @return ConfigBuilder
     */
    function setDriver($driver)
    {
        $this->driverAlias = $driver;

        return $this;
    }

    /**
     * @return bool
     */
    function hasDriver()
    {
        return !is_null($this->driverAlias);
    }

    /**
    * Sets the DI container
    *
    * @return ConfigBuilder
    */
    function setContainer(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
    * Gets the DI container
    *
    * @return ContainerInterface
    */
    function getContainer()
    {
        return $this->container;
    }

    /**
    * Checks if a ContainerInterface instance was set
    *
    * @return bool
    */
    function hasContainer()
    {
        return !is_null($this->container);
    }

    /**
     * Sets the cache path
     *
     * It's also actives the caching
     *
     * @return ConfigBuilder
     */
    function setCachePath($path)
    {
        $this->cachePath = $path;

        return $this;
    }

    /**
     * Returns the cache path.
     */
    function getCachePath()
    {
        return $this->cachePath;
    }

    /**
     * Checks if cache is enabled
     *
     * @return bool
     */
    function isCacheEnabled()
    {
        return !is_null($this->cachePath);
    }

    function setupDependencies()
    {
        $container = $this->getContainer();

        $container->register('config',            Config::class);
        $container->register('loader',            Loader::class);
        $container->register('drivers',           DriversRegistry::class);
        $container->register('source',            Source::class)->give($container);
        $container->register('sourcesCollection', SourcesCollection::class);
        $container->register('sourcesMatcher',    SourcesMatcher::class)->give($container);

        if($this->isCacheEnabled()) {
            $container->register('configCache', Cache::class)
                ->give($this->getCachePath());
        }

        $container->register('php',  PhpDriver::class);
        $container->register('ini',  IniDriver::class);
        $container->register('json', JsonDriver::class);
        $container->register('yml',  YmlDriver::class);
    }

    /**
     * Builds and returns a new instance of Config class
     *
     * @return Config
     */
    function build()
    {
        if (!$this->hasContainer()) {
            $this->setContainer(new Container());
        }

        $this->setupDependencies();

        $container = $this->getContainer();

        $data = $container->get('loader')
            ->load(
                $this->src,
                $this->ext,
                $this->driverAlias
            );

        return $container->make('config', $data);
    }
}
