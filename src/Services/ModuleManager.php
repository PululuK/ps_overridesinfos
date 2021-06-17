<?php

declare(strict_types=1);

namespace PrestaShop\Module\Ps_overridesinfos\Services;

use PrestaShop\PrestaShop\Adapter\Module\AdminModuleDataProvider;
use PrestaShop\Module\Ps_overridesinfos\Services\ModulePresenter;
use PrestaShop\PrestaShop\Core\Addon\AddonManagerInterface;
use Module as LegacyModule;
use PrestaShop\PrestaShop\Core\Addon\AddonsCollection;
use PrestaShop\PrestaShop\Core\Addon\Module\ModuleRepository;
use Tools;


class ModuleManager {

    /**
     * Admin Module Data Provider.
     *
     * @var \PrestaShop\PrestaShop\Adapter\Module\AdminModuleDataProvider
     */
    private $adminModuleProvider;

    /**
     * Module Repository.
     *
     * @var \PrestaShop\PrestaShop\Core\Addon\Module\ModuleRepository
     */
    private $moduleRepository;

    private $addonManager;

    /**
     * @param AddonManagerInterface $addonManager
     * @param ModuleRepository $addonManager
     * @param AdminModuleDataProvider $adminModuleProvider
     * 
     */
    public function __construct(
        AddonManagerInterface $addonManager, 
        ModuleRepository $moduleRepository,
        AdminModuleDataProvider $adminModuleProvider
    )
    {
        $this->addonManager = $addonManager;
        $this->moduleRepository = $moduleRepository;
        $this->adminModuleProvider = $adminModuleProvider;
    }
    
    /**
     * Get modules with verrides
     * 
     * @return object
     */
    public function getModulesWithOverrides(callable $modulesPresenter)
    {
        $installedProducts = $this->moduleRepository->getInstalledModules();

        $modules = (object) [
            'overrides' => [],
        ];

        /*
         * @var \PrestaShop\PrestaShop\Adapter\Module\Module
         */
        foreach ($installedProducts as $installedProduct) {
            $installedProduct = new ModulePresenter($installedProduct);
            if (!$installedProduct->hasOverrides()) {
                $modules->overrides[] = (object) $installedProduct;
            }
        }

        $modulesProvider = $this->adminModuleProvider;
        foreach ($modules as $moduleLabel => $modulesPart) {
            $collection = AddonsCollection::createFrom($modulesPart);
            $modulesProvider->generateAddonsUrls($collection, str_replace('to_', '', $moduleLabel));
            $modules->{$moduleLabel} = $modulesPresenter($collection);
        }

        return $modules;
    }
}
