<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\Exception;

use KunicMarko\Importer\Exception\ImporterException;
use KunicMarko\SonataImporterBundle\Admin\AdminWithImport;
use RuntimeException;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AdminClassNotFound extends RuntimeException implements ImporterException
{
    public function __construct(string $id, string $class)
    {
        parent::__construct(sprintf(
            'Service "%s" has a parameter "%s" as a class name but the parameter is not found.',
            $id,
            $class
        ));
    }
}
