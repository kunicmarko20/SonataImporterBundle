<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\DependencyInjection\Compiler;

use KunicMarko\SonataImporterBundle\Admin\AdminWithImport;
use KunicMarko\SonataImporterBundle\Controller\ImportCRUDController;
use KunicMarko\SonataImporterBundle\Exception\AdminClassNotFound;
use KunicMarko\SonataImporterBundle\Exception\ControllerMissingMethod;
use KunicMarko\SonataImporterBundle\Exception\ImportConfigurationMissingInterface;
use KunicMarko\SonataImporterBundle\SonataImportConfiguration;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AutoConfigureCompilerPass implements CompilerPassInterface
{
    private const DEFAULT_SONATA_CONTROLLER = 'SonataAdminBundle:CRUD';

    public function process(ContainerBuilder $container): void
    {
        $configurations = [];

        foreach ($container->findTaggedServiceIds('sonata.importer.configuration') as $id => $tag) {
            $importConfigurationDefinition = $container->getDefinition($id);
            $importConfigurationClass = $importConfigurationDefinition->getClass();

            if (!is_subclass_of($importConfigurationClass, SonataImportConfiguration::class)) {
                throw new ImportConfigurationMissingInterface($importConfigurationClass);
            }

            /** @var $importConfigurationClass SonataImportConfiguration */
            $configurations[$importConfigurationClass::adminClass()] = [
                $importConfigurationClass::format() => $importConfigurationDefinition
            ];
        }

        foreach ($container->findTaggedServiceIds('sonata.importer.admin') as $id => $tag) {
            $adminDefinition = $container->getDefinition($id);

            if (!$adminDefinition->hasTag('sonata.admin')) {
                continue;
            }

            if ($this->isDefaultControllerRegistered($adminDefinition)) {
                $this->replaceDefaultControllerWithImportController($adminDefinition);
            }
        }

        foreach ($container->findTaggedServiceIds('sonata.importer.controller') as $id => $tag) {
            $controllerDefinition = $container->getDefinition($id);

            if (!method_exists($controllerDefinition->getClass(), $method = 'setImportConfigurations')) {
                throw new ControllerMissingMethod($controllerDefinition->getClass(), $method);
            }

            $controllerDefinition->addMethodCall($method, [$configurations]);
        }
    }

    private function isDefaultControllerRegistered(Definition $definition): bool
    {
        return $definition->getArgument(2) === self::DEFAULT_SONATA_CONTROLLER;
    }

    private function replaceDefaultControllerWithImportController(Definition $definition): void
    {
        $definition->setArgument(2, ImportCRUDController::class);
    }
}
