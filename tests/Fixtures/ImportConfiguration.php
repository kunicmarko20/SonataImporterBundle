<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\Tests\Fixtures;

use KunicMarko\SonataImporterBundle\SonataImportConfiguration;
use PHPUnit\Framework\TestCase;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ImportConfiguration extends TestCase implements SonataImportConfiguration
{
    public function map(array $item, array $additionalData)
    {
    }

    public function save(array $items, array $additionalData): void
    {
    }

    public static function adminClass(): string
    {
        return Admin::class;
    }

    public static function format(): string
    {
        return 'csv';
    }
}
