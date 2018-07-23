<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\Tests\Fixtures;

use PHPUnit\Framework\TestCase;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class Admin extends TestCase
{
    /**
     * @var string
     */
    public $route = 'import';

    public function generateUrl(string $route)
    {
        $this->assertSame($this->route, $route);

        return 'test';
    }
}
