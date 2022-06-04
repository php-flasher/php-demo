<?php

/*
 * This file is part of the PHPFlasher package.
 * (c) Younes KHOUBZA <younes.khoubza@gmail.com>
 */

namespace App;

use Flasher\Prime\Config\Config;
use Flasher\Prime\EventDispatcher\EventDispatcher;
use Flasher\Prime\Flasher;
use Flasher\Prime\FlasherInterface;
use Flasher\Prime\Plugin\PluginInterface;
use Flasher\Prime\Response\Resource\ResourceManager;
use Flasher\Prime\Response\ResponseManager;
use Flasher\Prime\Storage\StorageBag;
use Flasher\Prime\Storage\StorageManager;

final class FlasherManager
{
    /**
     * @var FlasherManager
     */
    private static $instance;

    /**
     * @var FlasherInterface
     */
    private $flasher;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var StorageManager
     */
    private $storageManager;

    /**
     * @var ResourceManager
     */
    private $resourceManager;

    private function __construct()
    {
        $config          = new Config(require __DIR__.'/../config/flasher.php');
        $eventDispatcher = new EventDispatcher();
        $storageManager  = new StorageManager(new StorageBag(new SessionBag()), $eventDispatcher);
        $resourceManager = new ResourceManager($config);
        $responseManager = new ResponseManager($resourceManager, $storageManager, $eventDispatcher);
        $flasher         = new Flasher($config->get('default'), $responseManager, $storageManager);

        $this->flasher         = $flasher;
        $this->config          = $config;
        $this->storageManager  = $storageManager;
        $this->resourceManager = $resourceManager;
    }

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return FlasherInterface
     */
    public static function getFlasher()
    {
        $self = self::getInstance();

        return $self->flasher;
    }

    /**
     * @return void
     */
    public static function addPlugin(PluginInterface $plugin)
    {
        $self = self::getInstance();

        $self->addResources($plugin);
        $self->addFactory($plugin);
    }

    /**
     * @return void
     */
    private function addResources(PluginInterface $plugin)
    {
        $name = $plugin->getAlias();

        $scripts = $this->config->get("adapters.{$name}.scripts", $plugin->getScripts());
        $this->resourceManager->addScripts($name, $scripts);

        $styles = $this->config->get("adapters.{$name}.styles", $plugin->getStyles());
        $this->resourceManager->addStyles($name, $styles);

        $options = $this->config->get("adapters.{$name}.options", $plugin->getOptions());
        $this->resourceManager->addOptions($name, $options);
    }

    /**
     * @return void
     */
    private function addFactory(PluginInterface $plugin)
    {
        $name = $plugin->getAlias();

        $storageManager = $this->storageManager;
        $this->flasher->addFactory($name, function () use ($plugin, $storageManager) {
            $factory = $plugin->getFactory();

            return new $factory($storageManager);
        });
    }
}
