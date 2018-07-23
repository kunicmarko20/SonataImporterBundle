<?php

namespace KunicMarko\SonataImporterBundle\Tests\Fixtures;

use KunicMarko\SonataImporterBundle\Controller\ImportActionTrait;
use KunicMarko\SonataImporterBundle\Form\AdminImport as AdminImportForm;
use KunicMarko\SonataImporterBundle\DTO\AdminImport as AdminImportDTO;
use PHPUnit\Framework\TestCase;
use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class CRUDController extends TestCase
{
    use ImportActionTrait;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var ContainerInterface
     */
    public $container;

    /**
     * @var AdminInterface
     */
    public $admin;

    /**
     * @var string
     */
    public $flashType = 'error';

    /**
     * @var UploadedFile
     */
    private $file;

    public function __construct(
        FormInterface $form,
        ContainerInterface $container,
        $admin,
        UploadedFile $file
    ) {
        parent::__construct();
        $this->form = $form;
        $this->container = $container;
        $this->admin = $admin;
        $this->file = $file;
    }

    public function createForm(string $formName, AdminImportDTO $object): FormInterface
    {
        $this->assertSame(AdminImportForm::class, $formName);

        $object->file = $this->file;

        return $this->form;
    }

    public function renderWithExtraParams(string $template, array $parameters)
    {
        $this->assertSame('form.template', $template);

        return new Response();
    }

    public function addFlash(string $type, string $message)
    {
        $this->assertSame($this->flashType, $type);
    }
}
