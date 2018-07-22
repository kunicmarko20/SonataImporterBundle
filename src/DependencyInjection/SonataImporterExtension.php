<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\DependencyInjection;

use KunicMarko\SonataImporterBundle\Admin\AdminWithImport;
use KunicMarko\SonataImporterBundle\Controller\ControllerWithImport;
use KunicMarko\SonataImporterBundle\SonataImportConfiguration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class SonataImporterExtension extends ConfigurableExtension
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $container->setParameter('sonata.importer.templates', $mergedConfig['templates']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('controllers.xml');
        $loader->load('extensions.xml');

        $container->registerForAutoconfiguration(SonataImportConfiguration::class)
            ->addTag('sonata.importer.configuration');

        $container->registerForAutoconfiguration(ControllerWithImport::class)
            ->addTag('sonata.importer.controller');

        $container->registerForAutoconfiguration(AdminWithImport::class)
            ->addTag('sonata.importer.admin');
    }
}
