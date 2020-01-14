<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class AdminImportExtension extends AbstractAdminExtension
{
    /**
     * @var array
     */
    private $templates;

    public function __construct(array $templates)
    {
        $this->templates = $templates;
    }

    public function configureRoutes(AdminInterface $admin, RouteCollection $collection): void
    {
        if (!$admin instanceof AdminWithImport) {
            return;
        }

        $collection->add('import');
    }

    public function configureActionButtons(AdminInterface $admin, $list, $action, $object): array
    {
        if (!$admin instanceof AdminWithImport) {
            return parent::configureActionButtons($admin, $list, $action, $object);
        }

        $list['import']['template'] = $this->templates['action_button'];

        return $list;
    }

    public function configureDashboardActions(AdminInterface $admin, $actions): array
    {
        $actions['import']['template'] = $this->templates['dashboard_action'];

        return $actions;
    }
}
