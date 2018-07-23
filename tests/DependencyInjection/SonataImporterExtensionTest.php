<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\Tests\DependencyInjection;

use KunicMarko\SonataImporterBundle\DependencyInjection\SonataImporterExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use KunicMarko\SonataImporterBundle\Controller\ImportCRUDController;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class SonataImporterExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadsFormServiceDefinition(): void
    {
        $this->container->setParameter('kernel.project_dir', $param = 'test');

        $this->load();

        $this->assertContainerBuilderHasService(
            ImportCRUDController::class
        );
    }

    protected function getContainerExtensions(): array
    {
        return [new SonataImporterExtension()];
    }
}
