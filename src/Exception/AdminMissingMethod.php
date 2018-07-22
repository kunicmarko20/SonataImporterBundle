<?php

namespace KunicMarko\SonataImporterBundle\Exception;

use KunicMarko\Importer\Exception\ImporterException;
use KunicMarko\SonataImporterBundle\Admin\AbstractImportAdmin;
use KunicMarko\SonataImporterBundle\Admin\AdminWithImport;
use KunicMarko\SonataImporterBundle\Admin\ImportAdminTrait;
use RuntimeException;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AdminMissingMethod extends RuntimeException implements ImporterException
{
    public function __construct(string $adminClass, string $method)
    {
        parent::__construct(sprintf(
            'Admin "%s" is missing "%s" method. Make sure your Admin extends "%s" or use "%s" trait.',
            $adminClass,
            $method,
            AbstractImportAdmin::class,
            ImportAdminTrait::class
        ));
    }
}
