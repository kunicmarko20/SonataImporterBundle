<?php

namespace KunicMarko\SonataImporterBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class ImportCRUDController extends CRUDController
{
    use ImportActionTrait;
}
