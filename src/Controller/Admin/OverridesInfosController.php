<?php

declare(strict_types=1);

namespace PrestaShop\Module\Ps_overridesinfos\Controller\Admin;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use Symfony\Component\HttpFoundation\Response;
use PrestaShopBundle\Controller\Admin\Improve\Modules\ModuleAbstractController;
use PrestaShop\PrestaShop\Core\Addon\AddonsCollection;

/**
 * Responsible of "Improve > Modules > Modules & Services > verrides" page display.
 */
class OverridesInfosController extends ModuleAbstractController
{
    /**
     * @AdminSecurity("is_granted('read', request.get('_legacy_controller'))")
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render(
            '@Modules/ps_overridesinfos/views/templates/admin/card_list_overrides.html.twig',
            $this->getOverridesPageData()
        );
    }
    
    protected function getOverridesPageData()
    {
        $modulePresenter = $this->get('prestashop.adapter.presenter.module');
        $modulesPresenterCallback = function (AddonsCollection &$modules) use ($modulePresenter) {
            return $modulePresenter->presentCollection($modules);
        };

        $moduleManager = $this->get('ps_overridesinfos.module.manager');
        $modules = $moduleManager->getModulesWithOverrides($modulesPresenterCallback);

        return [
            'enableSidebar' => true,
            'layoutHeaderToolbarBtn' => $this->getToolbarButtons(),
            'layoutTitle' => $this->trans('Module notifications', 'Admin.Modules.Feature'),
            'help_link' => $this->generateSidebarLink('AdminModules'),
            'modules' => $modules->overrides,
            'requireAddonsSearch' => false,
            'requireBulkActions' => false,
            'requireFilterStatus' => false,
            'level' => $this->authorizationLevel($this::CONTROLLER_NAME),
            'errorMessage' => $this->trans('You do not have permission to add this.', 'Admin.Notifications.Error'),
        ];
    }
}
