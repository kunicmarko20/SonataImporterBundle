SonataImporterBundle
====================

Easier handling of Import in Sonata Admin.

Built on top of [Importer](https://github.com/kunicmarko20/importer).

[![PHP Version](https://img.shields.io/badge/php-%5E7.1-blue.svg)](https://img.shields.io/badge/php-%5E7.1-blue.svg)
[![Latest Stable Version](https://poser.pugx.org/kunicmarko/SonataImporterBundle/v/stable)](https://packagist.org/packages/kunicmarko/SonataImporterBundle)
[![Latest Unstable Version](https://poser.pugx.org/kunicmarko/SonataImporterBundle/v/unstable)](https://packagist.org/packages/kunicmarko/SonataImporterBundle)

[![Build Status](https://travis-ci.org/kunicmarko20/SonataImporterBundle.svg?branch=master)](https://travis-ci.org/kunicmarko20/SonataImporterBundle)
[![Coverage Status](https://coveralls.io/repos/github/kunicmarko20/SonataImporterBundle/badge.svg?branch=master)](https://coveralls.io/github/kunicmarko20/SonataImporterBundle?branch=master)

Documentation
-------------

* [Installation](#installation)
* [Configuration](#configuration)
* [How to use](#how-to-use)
    * [Prepare Admin Class](#prepare-admin-class)
    * [Prepare Controller](#prepare-controller)
    * [Autoconfigure ImportClasses](#autoconfigure-importclasses)

## Installation

**1.**  Add dependency with composer

```bash
composer require kunicmarko/sonata-importer-bundle
```

**2.** Register the bundle in your Kernel

```php
return [
    //...
    KunicMarko\SonataImporterBundle\SonataImporterBundle::class => ['all' => true],
];
```

## Configuration

Currently, you can only change the template files used in bundle, default config looks like:

```yaml
# config/packages/sonata_importer.yaml
sonata_importer:
    templates:
        form:                 '@SonataImporter/form.html.twig'
        action_button:        '@SonataImporter/action_button.html.twig'
        dashboard_action:     '@SonataImporter/dashboard_action.html.twig'
```

## How to use

If you haven't already go and read [Importer documentation](https://github.com/kunicmarko20/importer#how-to-use).
I will assume you are already familiar with ImportClasses and I will just explain what is different in
this bundle.

### Prepare Admin Class

Your Admin class has to extend `KunicMarko\SonataImporterBundle\Admin\AbstractImportAdmin`, if
that is not possible you will have to use `KunicMarko\SonataImporterBundle\Admin\ImportAdminTrait`
trait in your Admin Class.

### Prepare Controller

Your Admin service definition should have `KunicMarko\SonataImporterBundle\Controller\ImportCRUDController`
as a controller.

```yaml
# config/services.yaml
services:
    App\Admin\CategoryAdmin:
        arguments:
            - ~
            - App\Entity\Category
            - KunicMarko\SonataImporterBundle\Controller\ImportCRUDController
        tags:
            - { name: sonata.admin, manager_type: orm, group: admin, label: Category }
```

If that is not possible you will `KunicMarko\SonataImporterBundle\Controller\ImportActionTrait`
trait in your controller and make sure you call the method `setImporterFactory`
in your service definition:

```xml
<service id="KunicMarko\SonataImporterBundle\Controller\ImportCRUDController" public="true">
    <call method="setImporterFactory">
        <argument type="service" id="KunicMarko\Importer\ImporterFactory" />
    </call>
</service>
```

### Autoconfigure ImportClasses

To be able to autoconfigure your ImportClasses they will have to implement
`KunicMarko\SonataImporterBundle\SonataImport` and configure `format` and `adminClass` methods
along with other methods.

That will look like:

```php
class CategoryCSVImport implements SonataImport
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function adminClass(): string
    {
        return CategoryAdmin::class;
    }

    public static function format(): string
    {
        return 'csv';
    }

    public function map(array $item, array $additionalData)
    {
        $category = new Category();

        $category->setName($item[0]);

        $this->entityManager->persist($category);
    }

    public function save(array $items, array $additionalData): void
    {
        $this->entityManager->flush();
    }
}
```
