<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\Controller;

use KunicMarko\Importer\ImportConfiguration;
use KunicMarko\SonataImporterBundle\Exception\AdminHasNoImportConfiguration;
use KunicMarko\SonataImporterBundle\Exception\ImportConfigurationMissing;
use KunicMarko\SonataImporterBundle\Form\AdminImport as AdminImportForm;
use KunicMarko\SonataImporterBundle\DTO\AdminImport as AdminImportDTO;
use KunicMarko\Importer\ImporterFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use KunicMarko\Importer\Exception\ImporterException;
use Symfony\Component\HttpFoundation\Response;
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
     * @var array
     */
    private $importConfigurations = [];

    /**
     * @required
     */
    public function setImporterFactory(ImporterFactory $importerFactory): void
    {
        $this->importerFactory = $importerFactory;
    }

    public function setImportConfigurations(array $importConfigurations): void
    {
        $this->importConfigurations = $importConfigurations;
    }

    public function importAction(Request $request): Response
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
                ->useImportConfiguration($this->getImportConfiguration($type))
                ->fromFile($import->file->getPathname())
                ->import();

            $this->addFlash('success', 'Imported successfully.');
        } catch (ImporterException $exception) {
            $this->addFlash('error', $exception->getMessage());

            return new RedirectResponse($this->admin->generateUrl('import'));
        }

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    private function getImportConfiguration(string $type): ImportConfiguration
    {
        if (!array_key_exists($adminClass = get_class($this->admin), $this->importConfigurations)) {
            throw new AdminHasNoImportConfiguration($adminClass);
        }

        $configurations = $this->importConfigurations[$adminClass];

        if (!array_key_exists($type, $configurations)) {
            throw new ImportConfigurationMissing($type);
        }

        return $configurations[$type];
    }
}
