<?php

declare(strict_types=1);

namespace PrestaShop\Module\Ps_overridesinfos\Services;

use PrestaShop\PrestaShop\Core\Addon\Module\ModuleInterface;
use Module as LegacyModule;
use PrestaShop\PrestaShop\Core\Addon\AddonsCollection;
use Tools;


class ModuleManager {

    private $addonManager;

    /**
     * @param AddonManagerInterface $addonManager
     */
    public function __construct(AddonManagerInterface $addonManager)
    {
        $this->addonManager = $addonManager;
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
