<?php

declare(strict_types=1);

namespace PrestaShop\Module\Ps_overridesinfos\Adapter;

use PrestaShop\PrestaShop\Core\Addon\Module\ModuleInterface;
use Module as LegacyModule;
use Tools;


class Module{

    private $moduleAdapter;
    private $moduleName = null;
    
    /**
     * @param ModuleInterface $moduleAdapter
     */
    public function __construct(ModuleInterface $moduleAdapter)
    {
        $this->moduleAdapter = $moduleAdapter;        

        if($this->moduleAdapter->getInstance() instanceof LegacyModule) {
            $this->moduleName = $moduleAdapter->attributes->get('name');
            $this->moduleAdapter->attributes->set(
                'overrideInfos',
                $this->getOverrideInfos()
            );

            $this->moduleAdapter->attributes->set(
                'hasOverrides',
                $this->hasOverrides()
            );
        }
    }

    public function getModule(){
        return $this->moduleAdapter;
    }
    
    /*
     * Check if module contains overrides or has been overrided
     * 
     * @return bool
     */
    public function hasOverrides(): bool {
        $overridesFiles = array_filter($this->getOverrideInfos());
        return !empty($overridesFiles);
    }
    
    public function getOverrideInfos(): array
    {        
        return [
            'contains_overrides' => $this->getOverridesList(),
            'core_overrides' =>  $this->getCoreOverridesList(),
            'theme_overrides' => $this->getThemeOverridesList(),
        ];
    }
    
    /**
     * Get module theme override
     * 
     * @return array
     */
    public function getThemeOverridesList(): array
    {
        $overridesList = [];
        $overrideDir = _PS_THEME_DIR_ . 'modules/';

        if(is_dir($overrideDir . $this->moduleName)) {
            $overridesList = array_filter(Tools::scandir($overrideDir , '', $this->moduleName, true), function ($file) {
                $extention = pathinfo($file, PATHINFO_EXTENSION);
                return basename($file) != 'index.php' && !empty($extention);
            });
        }

        return $overridesList;
        
    }
    
    /**
     * Get module core override
     * 
     * @return array
     */
    public function getCoreOverridesList(): array
    {
        $overridesList = [];
        $overrideDir = _PS_OVERRIDE_DIR_ . 'modules/';

        if(is_dir($overrideDir . $this->moduleName)) {
            $overridesList = array_filter(Tools::scandir($overrideDir , 'php', $this->moduleName, false), function ($file) {
                return basename($file) != 'index.php';
            });
        }

        return $overridesList;
        
    }

    /**
     * Get module overrides
     * 
     * @return array
     */
    public function getOverridesList(): array
    {
        $overridesList = [];

        return [];
        $overrideDir = $this->getLocalPath() . 'override';

        if(is_dir($overrideDir)) {
            $overridesList = array_filter(Tools::scandir($overrideDir, 'php', '', true), function ($file) {
                return basename($file) != 'index.php';
            });
        }

        return $overridesList;
        
    }
}
