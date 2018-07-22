<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\DependencyInjection\Compiler;

use KunicMarko\SonataImporterBundle\Exception\AdminClassNotFound;
use KunicMarko\SonataImporterBundle\Exception\AdminMissingMethod;
use KunicMarko\SonataImporterBundle\Exception\ImportClassMissingInterface;
use KunicMarko\SonataImporterBundle\SonataImport;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AddImportClassesToAdminCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $admins = [];

        foreach ($container->findTaggedServiceIds('sonata.importer') as $id => $tag) {
            $importClassDefinition = $container->getDefinition($id);

            if (!is_subclass_of($importClass = $importClassDefinition->getClass(), SonataImport::class)) {
                throw new ImportClassMissingInterface($importClass);
            }

            /** @var $importClass SonataImport */
            $admins[$importClass::adminClass()] = [$importClass::format() => $importClassDefinition];
        }


        foreach ($container->findTaggedServiceIds('sonata.admin') as $id => $tag) {
            $adminDefinition = $container->getDefinition($id);

            if (!array_key_exists($adminClass = $this->getClass($container, $id), $admins)) {
                continue;
            }

            if (!method_exists($adminClass, $method = 'setImportClasses')) {
                throw new AdminMissingMethod($adminDefinition->getClass(), $method);
            }

            $adminDefinition->addMethodCall($method, [$admins[$adminClass]]);
        }
    }

    private function getClass(ContainerBuilder $container, string $id): ?string
    {
        $definition = $container->getDefinition($id);

        $class = $definition->getClass();

        if ($class[0] !== '%') {
            return $class;
        }

        if ($container->hasParameter($parameter = trim($class, '%'))) {
            return $container->getParameter($parameter);
        }

        throw new AdminClassNotFound($id, $class);
    }
}
