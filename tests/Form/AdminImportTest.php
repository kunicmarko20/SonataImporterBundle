<?php

namespace KunicMarko\SonataImporterBundle\Tests\Form;

use KunicMarko\SonataImporterBundle\Form\AdminImport as AdminImportForm;
use KunicMarko\SonataImporterBundle\DTO\AdminImport as AdminImportDTO;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class AdminImportTest extends TypeTestCase
{
    /**
     * @var UploadedFile
     */
    private $uploadedFile;

    private $file;

    public function setUp()
    {
        parent::setUp();

        $this->uploadedFile = new UploadedFile(
            $this->file = tempnam(sys_get_temp_dir(), 'test'),
            'imp.csv'
        );
    }

    public function testSubmitValidData(): void
    {
        $form = $this->factory->create(
            AdminImportForm::class,
            new AdminImportDTO()
        );

        $form->submit([
            'file' => $this->uploadedFile
        ]);

        $this->assertTrue($form->isSynchronized());
        $view = $form->createView();

        $this->assertArrayHasKey('file', $view->children);
    }

    public function tearDown()
    {
        unlink($this->file);
    }
}
