<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\Exception;

use KunicMarko\Importer\Exception\ImporterException;
use RuntimeException;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class ImportConfigurationMissing extends RuntimeException implements ImporterException
{
    public function __construct(string $type)
    {
        parent::__construct(sprintf('ImportClass for type "%s" is missing.', $type));
    }
}
