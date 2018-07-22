<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\Exception;

use KunicMarko\Importer\Exception\ImporterException;
use KunicMarko\SonataImporterBundle\SonataImportConfiguration;
use RuntimeException;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class ImportConfigurationMissingInterface extends RuntimeException implements ImporterException
{
    public function __construct(string $importClass)
    {
        parent::__construct(sprintf(
            'ImportConfiguration "%s" has to implement "%s".',
            $importClass,
            SonataImportConfiguration::class
        ));
    }
}
