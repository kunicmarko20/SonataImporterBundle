<?php

namespace KunicMarko\SonataImporterBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
abstract class AbstractImportAdmin extends AbstractAdmin implements AdminWithImport
{
    use ImportAdminTrait;
}
