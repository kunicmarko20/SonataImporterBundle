<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle;

use KunicMarko\SonataImporterBundle\DependencyInjection\Compiler\AddImportClassesToAdminCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class SonataImporterBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new AddImportClassesToAdminCompilerPass());
    }
}
