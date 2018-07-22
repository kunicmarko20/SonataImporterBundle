<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle;

use KunicMarko\Importer\ImportConfiguration;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
interface SonataImportConfiguration extends ImportConfiguration
{
    public static function adminClass(): string;
    public static function format(): string;
}
