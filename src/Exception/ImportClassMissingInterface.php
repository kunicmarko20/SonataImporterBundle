<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\Exception;

use KunicMarko\Importer\Exception\ImporterException;
use KunicMarko\SonataImporterBundle\SonataImport;
use RuntimeException;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class ImportClassMissingInterface extends RuntimeException implements ImporterException
{
    public function __construct(string $importClass)
    {
        parent::__construct(sprintf(
            'ImportClass "%s" has to implement "%s".',
            $importClass,
            SonataImport::class
        ));
    }
}
