<?php
/**
 * This file is part of BcBootstrapBundle.
 *
 * (c) 2012-2013 by Florian Eckerstorfer
 */

namespace TC\BootstrapBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * BootstrapExtension
 *
 * @package    BcBootstrapBundle
 * @subpackage DependencyInjection
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2012-2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 * @link       http://bootstrap.braincrafted.com Bootstrap for Symfony2
 */
class TCBootstrapExtension extends Extension implements PrependExtensionInterface
{
    /** @var string */
    private $formTemplate = 'TCBootstrapBundle:Form:form_div_layout.html.twig';

    /** @var string */
    private $menuTemplate = 'TCBootstrapBundle:Menu:menu.html.twig';

    /** @var string */
    private $paginationTemplate = 'TCBootstrapBundle:Pagination:pagination.html.twig';

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yml');
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        // Configure AsseticBundle
        /*if (isset($bundles['AsseticBundle'])) {
            $this->configureAsseticBundle($container);
        }*/

        // Configure TwigBundle
        if (isset($bundles['TwigBundle'])) {
            $this->configureTwigBundle($container);
        }

        // Configure KnpMenuBundle
        if (isset($bundles['TwigBundle']) && isset($bundles['KnpMenuBundle'])) {
            $this->configureKnpMenuBundle($container);
        }

        if (isset($bundles['TwigBundle']) && isset($bundles['KnpPaginatorBundle'])) {
            $this->configureKnpPaginatorBundle($container);
        }
    }

    /**
     * Configures the TwigBundle.
     *
     * @param ContainerBuilder $container The service container
     *
     * @return void
     */
    private function configureTwigBundle(ContainerBuilder $container)
    {
        foreach ($container->getExtensions() as $name => $extension) {
            switch ($name) {
                case 'twig':
                    $container->prependExtensionConfig(
                        $name,
                        array('form'  => array('resources' => array($this->formTemplate)))
                    );
                    break;
            }
        }
    }

    /**
     * Configures the KnpMenuBundle.
     *
     * @param ContainerBuilder $container The service container
     *
     * @return void
     */
    private function configureKnpMenuBundle(ContainerBuilder $container)
    {
        foreach ($container->getExtensions() as $name => $extension) {
            switch ($name) {
                case 'knp_menu':
                    $container->prependExtensionConfig(
                        $name,
                        array('twig' => array('template'  => $this->menuTemplate))
                    );
                    break;
            }
        }
    }

    /**
     * Configures the KnpPaginatorBundle.
     *
     * @param ContainerBuilder $container The service container
     *
     * @return void
     */
    private function configureKnpPaginatorBundle(ContainerBuilder $container)
    {
        foreach ($container->getExtensions() as $name => $extension) {
            switch ($name) {
                case 'knp_paginator':
                    $container->prependExtensionConfig(
                        $name,
                        array('template' => array('pagination' => $this->paginationTemplate))
                    );
                    break;
            }
        }
    }
}
