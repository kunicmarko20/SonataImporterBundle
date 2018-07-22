<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\Controller;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
interface ControllerWithImport
{
    public function setImportConfigurations(array $importConfigurations): void;
}
