<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\Tests;

use KunicMarko\SonataImporterBundle\DependencyInjection\Compiler\AutoConfigureCompilerPass;
use KunicMarko\SonataImporterBundle\SonataImporterBundle;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class SonataImporterBundleTest extends TestCase
{
    /**
     * @var SonataImporterBundle
     */
    private $bundle;

    protected function setUp()
    {
        $this->bundle = new SonataImporterBundle();
    }

    public function testBundle(): void
    {
        $this->assertInstanceOf(Bundle::class, $this->bundle);
    }

    public function testCompilerPasses(): void
    {
        $containerBuilder = $this->prophesize(ContainerBuilder::class);

        $containerBuilder->addCompilerPass(
            Argument::type(AutoConfigureCompilerPass::class),
            Argument::cetera()
        )->shouldBeCalledTimes(1);

        $this->bundle->build($containerBuilder->reveal());
    }
}
