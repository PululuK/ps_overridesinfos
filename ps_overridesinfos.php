<?php


declare(strict_types=1);

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class Ps_overridesinfos extends Module
{    
    protected $config_form = true;
    public $tabs;

    public function __construct()
    {
        $this->name = 'ps_overridesinfos';
        $this->tab = 'analytics_stats';
        $this->version = '1.0.0';
        $this->author = 'PululuK';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Prestashop overrides informations');
        $this->description = $this->l('Information on modules which contain overrides or which have been overridden.');

        $this->confirmUninstall = $this->l('');

        $this->ps_versions_compliancy = [
            'min' => '1.7', 
            'max' => _PS_VERSION_
        ];
        
        $this->tabs = [$this->getTabsInfos()];
    }

    public function install()
    {
        return $this->manuallyInstallTab() && parent::install();
    }

    public function uninstall()
    {
        return parent::uninstall();
    }
    
    public function getContent()
    {
        $tabInfos = $this->getTabsInfos();
        
        Tools::redirectAdmin(
            $this->context->link->getAdminLink($tabInfos['class_name'])
        );
    }
    
    public function getTabsInfos(): array
    {
        
        $tabNames = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tabNames[$lang['id_lang']] = $this->trans(
                'Overrides', 
                [], 
                'Modules.Ps_overridesinfos.Admin', 
                $lang['iso_code']
            );
        }

        return [
            'name' => $tabNames,
            'class_name' => 'AdminOverridesInfosController',
            'parent_class_name' => 'AdminModulesSf',
            'wording' => $tabNames,
            'visible' => true,
            'active' => 1,
            'icon' => '',
            'wording_domain' => 'Modules.Ps_overridesinfos.Admin',
            'route_name' => 'ps_overridesinfos_list',
        ];
    }

    /**
     * @return bool
     */
    private function manuallyInstallTab(): bool
    {
        $tabInfos = $this->getTabsInfos();

        $tabId = (int) Tab::getIdFromClassName($tabInfos['class_name']);
        $tabParentId =  (int) Tab::getIdFromClassName($tabInfos['parent_class_name']);
        if (!$tabId) {
            $tabId = null;
        }

        $tab = new Tab($tabId);
        $tab->active = $tabInfos['active'];;
        $tab->class_name = $tabInfos['class_name'];
        $tab->route_name = $tabInfos['route_name'];
        $tab->name = $tabInfos['name'];
        $tab->active = $tabInfos['active'];
        $tab->id_parent = $tabParentId;
        $tab->module = $this->name;

        return (bool) $tab->save();
    }
}
