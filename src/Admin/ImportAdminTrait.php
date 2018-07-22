<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\Admin;

use KunicMarko\SonataImporterBundle\SonataImport;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
trait ImportAdminTrait
{
    /**
     * @var array
     */
    private $importClasses = [];

    public function getImportClasses(): array
    {
        return $this->importClasses;
    }

    public function setImportClasses(array $importClasses): void
    {
        $this->importClasses = $importClasses;
    }

    public function addImportClass(string $key, SonataImport $importClass): void
    {
        $this->importClasses[$key] = $importClass;
    }
}
