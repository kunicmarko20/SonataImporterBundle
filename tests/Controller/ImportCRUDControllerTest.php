<?php

namespace KunicMarko\SonataImporterBundle\Tests\Controller;

use KunicMarko\Importer\ImporterFactory;
use KunicMarko\Importer\Reader\CsvReader;
use KunicMarko\SonataImporterBundle\Tests\Fixtures\Admin;
use KunicMarko\SonataImporterBundle\Tests\Fixtures\CRUDController;
use KunicMarko\SonataImporterBundle\Tests\Fixtures\ImportConfiguration;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ImportCRUDControllerTest extends TestCase
{
    /**
     * @var CRUDController
     */
    private $controller;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Admin
     */
    private $admin;

    /**
     * @var UploadedFile|MockObject
     */
    private $file;

    public function setUp()
    {
        $this->form = $this->prophesize(FormInterface::class);
        $this->form->handleRequest(Argument::cetera())->shouldBeCalled();

        $this->container = $this->prophesize(ContainerInterface::class);
        $this->container->getParameter(Argument::cetera())->willReturn(['form' => 'form.template']);

        $this->admin = new Admin();

        $this->file = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller = new CRUDController(
            $this->form->reveal(),
            $this->container->reveal(),
            $this->admin,
            $this->file
        );

        $importerFactory = new ImporterFactory();
        $importerFactory->addReader(new CsvReader());
        $this->controller->setImporterFactory($importerFactory);
    }

    public function testNotSubmitted()
    {
        $this->form->createView(Argument::cetera())->shouldBeCalled();
        $this->form->isSubmitted()->willReturn(false);

        $this->controller->importAction(new Request());
    }

    public function testNotValid()
    {
        $this->form->createView(Argument::cetera())->shouldBeCalled();
        $this->form->isSubmitted()->willReturn(true);
        $this->form->isValid()->willReturn(false);

        $this->controller->importAction(new Request());
    }

    public function testNoImportConfiguration()
    {
        $this->form->isSubmitted()->willReturn(true);
        $this->form->isValid()->willReturn(true);

        $this->file->expects($this->once())
            ->method('getClientOriginalExtension')
            ->willReturn($type = 'csv');

        $this->controller->importAction(new Request());
    }

    public function testMissingImportConfigurationForType()
    {
        $this->form->isSubmitted()->willReturn(true);
        $this->form->isValid()->willReturn(true);

        $this->file->expects($this->once())
            ->method('getClientOriginalExtension')
            ->willReturn($type = 'csv');


        $this->controller->setImportConfigurations([Admin::class => []]);
        $this->controller->importAction(new Request());
    }

    public function testValid()
    {
        $this->form->isSubmitted()->willReturn(true);
        $this->form->isValid()->willReturn(true);

        $this->file->expects($this->once())
            ->method('getClientOriginalExtension')
            ->willReturn($type = 'csv');

        $this->file->expects($this->once())
            ->method('getPathname')
            ->willReturn(__DIR__ . '/../Fixtures/fake.csv');

        $this->controller->flashType = 'success';
        $this->admin->route = 'list';

        $this->controller->setImportConfigurations([Admin::class => [$type => new ImportConfiguration()]]);
        $this->controller->importAction(new Request());
    }
}
