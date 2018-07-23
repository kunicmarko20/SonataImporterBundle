<?php

namespace KunicMarko\SonataImporterBundle\Tests\Admin;

use KunicMarko\SonataImporterBundle\Admin\AdminWithImport;
use KunicMarko\SonataImporterBundle\Admin\AdminImportExtension;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class AdminImportExtensionTest extends TestCase
{
    /**
     * @var AdminImportExtension
     */
    private $extension;

    public function setUp()
    {
        $this->extension = new AdminImportExtension([
            'action_button' => 'action_button_template',
            'dashboard_action' => 'dashboard_action_template',
        ]);
    }

    public function testConfigureRoutes()
    {
        $routeCollection = $this->prophesize(RouteCollection::class);
        $routeCollection->add(Argument::type('string'))->shouldBeCalled();

        $this->extension->configureRoutes(new class('', '', '') extends AbstractAdmin implements AdminWithImport {

        }, $routeCollection->reveal());
    }

    public function testConfigureRoutesNoCall()
    {
        $routeCollection = $this->prophesize(RouteCollection::class);
        $routeCollection->add()->shouldNotBeCalled();

        $this->extension->configureRoutes($this->getAdmin(), $routeCollection->reveal());
    }

    private function getAdmin(): AdminInterface
    {
        return new class('', '', '') extends AbstractAdmin {
        };
    }

    public function testConfigureActionButtons(): void
    {
        $result = $this->extension->configureActionButtons($this->getAdmin(), [], null, null);

        $this->assertArrayHasKey('import', $result);
        $this->assertArrayHasKey('template', $result['import']);
        $this->assertSame('action_button_template', $result['import']['template']);
    }

    public function testConfigureDashboardActions(): void
    {
        $result = $this->extension->configureDashboardActions($this->getAdmin(), []);

        $this->assertArrayHasKey('import', $result);
        $this->assertArrayHasKey('template', $result['import']);
        $this->assertSame('dashboard_action_template', $result['import']['template']);
    }
}
