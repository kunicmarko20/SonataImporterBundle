<?php

namespace KunicMarko\SonataImporterBundle\Controller;

use KunicMarko\SonataImporterBundle\Admin\AdminWithImport;
use KunicMarko\SonataImporterBundle\Exception\AdminMissingInterface;
use KunicMarko\SonataImporterBundle\Exception\ImportClassMissing;
use KunicMarko\SonataImporterBundle\Form\AdminImport as AdminImportForm;
use KunicMarko\SonataImporterBundle\DTO\AdminImport as AdminImportDTO;
use KunicMarko\Importer\Import;
use KunicMarko\Importer\ImporterFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use KunicMarko\Importer\Exception\ImporterException;
use function get_class;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
trait ImportActionTrait
{
    /**
     * @var ImporterFactory
     */
    private $importerFactory;

    /**
     * @required
     */
    public function setImporterFactory(ImporterFactory $importerFactory): void
    {
        $this->importerFactory = $importerFactory;
    }

    public function importAction(Request $request)
    {
        /** @var FormInterface $form */
        $form = $this->createForm(AdminImportForm::class, $import = new AdminImportDTO());

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->renderWithExtraParams(
                $this->container->getParameter('sonata.importer.templates')['form'],
                ['form' => $form->createView()]
            );
        }

        try {
            $importer = $this->importerFactory->getImporter($type = $import->file->getClientOriginalExtension());

            $importer
                ->useImportClass($this->getImportClass($type))
                ->fromFile($import->file)
                ->import();

            $this->addFlash('success', 'Imported successfully.');
        } catch (ImporterException $exception) {
            $this->addFlash('error', $exception->getMessage());

            return new RedirectResponse($this->admin->generateUrl('import'));
        }

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    private function getImportClass(string $type): Import
    {
        if (!$this->admin instanceof AdminWithImport) {
            throw new AdminMissingInterface(get_class($this->admin));
        }
        dump($this->admin->getImportClasses());
        if (!array_key_exists($type, $importClasses = $this->admin->getImportClasses())) {
            throw new ImportClassMissing($type);
        }

        return $importClasses[$type];
    }
}
