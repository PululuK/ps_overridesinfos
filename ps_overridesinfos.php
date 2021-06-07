<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Ps_overridesinfos extends Module
{
    protected $config_form = false;

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
    }

    public function install()
    {
        return parent::install();
    }

    public function uninstall()
    {
        return parent::uninstall();
    }
}
