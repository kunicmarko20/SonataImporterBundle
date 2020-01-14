<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\Tests\DependencyInjection\Compiler;

use KunicMarko\SonataImporterBundle\Controller\ImportCRUDController;
use KunicMarko\SonataImporterBundle\DependencyInjection\Compiler\AutoConfigureCompilerPass;
use KunicMarko\SonataImporterBundle\Tests\Fixtures\Admin;
use KunicMarko\SonataImporterBundle\Tests\Fixtures\Controller;
use KunicMarko\SonataImporterBundle\Tests\Fixtures\ImportConfiguration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class AutoConfigureCompilerPassTest extends TestCase
{
    /**
     * @var AutoConfigureCompilerPass
     */
    private $compilerPass;

    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;

    public function setUp(): void
    {
        $this->compilerPass = new AutoConfigureCompilerPass();
        $this->containerBuilder = new ContainerBuilder();
    }

    public function testProcess(): void
    {
        $this->containerBuilder->setDefinition(
            'admin',
            ($adminDefinition = new Definition(
                Admin::class,
                [
                    0,
                    1,
                    'SonataAdminBundle:CRUD'
                ]
            )
            )->setTags([
                'sonata.importer.admin' => [],
                'sonata.admin' => [],
            ])
        );

        $this->containerBuilder->setDefinition(
            'controller',
            ($controllerDefinition = new Definition(
                Controller::class
            )
            )->setTags([
                'sonata.importer.controller' => [],
            ])
        );

        $this->containerBuilder->setDefinition(
            'importConfiguration',
            (new Definition(
                ImportConfiguration::class
            )
            )->setTags([
                'sonata.importer.configuration' => [],
            ])
        );

        $this->compilerPass->process($this->containerBuilder);

        $this->assertSame(ImportCRUDController::class, $adminDefinition->getArgument(2));

        $methodCall = $controllerDefinition->getMethodCalls()[0];
        $methodName = $methodCall[0];
        $methodArguments = $methodCall[1][0];

        $this->assertSame('setImportConfigurations', $methodName);
        $this->assertCount(1, $methodArguments);
        $this->assertSame(Admin::class, key($methodArguments));
        $this->assertSame('csv', key($methodArguments[Admin::class]));
        $this->assertSame(ImportConfiguration::class, $methodArguments[Admin::class]['csv']->getClass());
    }

    /**
     * @expectedException \KunicMarko\SonataImporterBundle\Exception\ImportConfigurationMissingInterface
     */
    public function testProcessImportConfigurationMissing(): void
    {
        $this->containerBuilder->setDefinition(
            'importConfigurationMissing',
            (new Definition(
                self::class
            )
            )->setTags([
                'sonata.importer.configuration' => [],
            ])
        );

        $this->compilerPass->process($this->containerBuilder);
    }

    /**
     * @expectedException \KunicMarko\SonataImporterBundle\Exception\ControllerMissingMethod
     */
    public function testProcessControllerMissingMethod(): void
    {
        $this->containerBuilder->setDefinition(
            'not.sonata.admin',
            (new Definition(
                Admin::class,
                [
                    0,
                    1,
                    'SonataAdminBundle:CRUD'
                ]
            )
            )->setTags([
                'sonata.importer.admin' => [],
            ])
        );

        $this->containerBuilder->setDefinition(
            'controllerMissingMethod',
            (new Definition(
                self::class
            )
            )->setTags([
                'sonata.importer.controller' => [],
            ])
        );

        $this->compilerPass->process($this->containerBuilder);
    }
}
