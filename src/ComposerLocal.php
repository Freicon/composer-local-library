<?php

namespace Freicon\Composer\Local;


use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Repository\PathRepository;

class ComposerLocal implements PluginInterface, EventSubscriberInterface
{

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->addPathIfExists("/usr/lib/php/packets", $composer, $io);
        $this->addPathIfExists("/var/composer/packets", $composer, $io);
        $this->addPath("../*", $composer, $io);
    }

    public static function getSubscribedEvents()
    {
        return array();
    }

    /**
     * @param Composer $composer
     * @param IOInterface $io
     */
    protected function addPathIfExists($pathName, Composer $composer, IOInterface $io)
    {
        if (file_exists($pathName)) {
            $this->addPath($pathName, $composer, $io);
        }
    }

    /**
     * @param $pathName
     * @param Composer $composer
     * @param IOInterface $io
     */
    protected function addPath($pathName, Composer $composer, IOInterface $io)
    {
        $composer->getRepositoryManager()->prependRepository(
            new PathRepository(array('url' => $pathName), $io, $composer->getConfig())
        );
    }
}