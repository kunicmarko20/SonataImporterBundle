<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\Exception;

use KunicMarko\Importer\Exception\ImporterException;
use KunicMarko\SonataImporterBundle\Admin\AdminWithImport;
use RuntimeException;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AdminHasNoImportConfiguration extends RuntimeException implements ImporterException
{
    public function __construct(string $adminClass)
    {
        parent::__construct(sprintf(
            'Admin "%s" has no import configurations.',
            $adminClass
        ));
    }
}
