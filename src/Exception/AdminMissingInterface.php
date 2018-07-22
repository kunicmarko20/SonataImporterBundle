<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\Exception;

use KunicMarko\Importer\Exception\ImporterException;
use KunicMarko\SonataImporterBundle\Admin\AdminWithImport;
use RuntimeException;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AdminMissingInterface extends RuntimeException implements ImporterException
{
    public function __construct(string $adminClass)
    {
        parent::__construct(sprintf(
            'Admin "%s" has to implement "%s".',
            $adminClass,
            AdminWithImport::class
        ));
    }
}
