<?php

namespace KunicMarko\SonataImporterBundle\DependencyInjection\Compiler;

use KunicMarko\SonataImporterBundle\SonataImport;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AddImportClassesToAdminCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $adminsWithImportClasses = [];

        foreach ($container->findTaggedServiceIds('sonata.importer') as $id => $tag) {
            /** @var $class SonataImport */
            if (!($class = ($importClassDefinition = new Definition($id))->getClass()) instanceof SonataImport) {
                //throw exception
            }

            $admins[$class::adminClass()] = [$class::format() => $importClassDefinition];
        }

        foreach ($adminsWithImportClasses as $admin => $importClasses) {
            $adminDefinition = new Definition($admin);

            if (!method_exists($adminDefinition->getClass(), $method = 'setImportClasses')) {
                //exception
            }

            $adminDefinition->addMethodCall($method, [$importClasses]);
        }
    }
}
