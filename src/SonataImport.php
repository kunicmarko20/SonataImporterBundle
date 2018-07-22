<?php

namespace KunicMarko\SonataImporterBundle;

use KunicMarko\Importer\Import;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
interface SonataImport extends Import
{
    public static function adminClass(): string;
    public static function format(): string;
}
