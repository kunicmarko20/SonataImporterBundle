<?php

namespace KunicMarko\SonataImporterBundle\Admin;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
trait ImportAdminTrait
{
    /**
     * @var array
     */
    protected $importClasses = [];

    public function getImportClasses(): array
    {
        return $this->importClasses;
    }

    public function setImportClasses(array $importClasses): void
    {
        $this->importClasses = $importClasses;
    }
}
