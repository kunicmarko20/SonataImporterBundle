<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\Exception;

use KunicMarko\Importer\Exception\ImporterException;
use KunicMarko\SonataImporterBundle\Controller\ImportActionTrait;
use RuntimeException;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class ControllerMissingMethod extends RuntimeException implements ImporterException
{
    public function __construct(string $adminClass, string $method)
    {
        parent::__construct(sprintf(
            'Controller "%s" is missing "%s" method. Make sure your Controller uses "%s" trait.',
            $adminClass,
            $method,
            ImportActionTrait::class
        ));
    }
}
