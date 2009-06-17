<?php
defined("_VALID_MOS") or die("Restricted access");

function com_install() {
    global $database, $mainframe;

    require_once($mainframe->getPath('installer_class', 'module'));
    require_once($mainframe->getPath('installer_class', 'mambot'));

    // change default icon
    $database->setQuery("UPDATE #__components set admin_menu_img = '../administrator/components/com_jumi/images/jumi.png' where admin_menu_link = 'option=com_jumi'");
    $database->query();

    //rename xml- files to xml to not conflict with component xml file when installing
    rename($mainframe->getCfg('absolute_path').'/administrator/components/com_jumi/module/mod_jumi.xml-', $mainframe->getCfg('absolute_path').'/administrator/components/com_jumi/module/mod_jumi.xml');
    rename($mainframe->getCfg('absolute_path').'/administrator/components/com_jumi/plugin/plugin_jumi.xml-', $mainframe->getCfg('absolute_path').'/administrator/components/com_jumi/plugin/plugin_jumi.xml');

    // installing module
    $module_installer = new mosInstallerModule;
    if($module_installer->install(dirname(__FILE__).'/module'))
        echo 'Module install success', '<br />';
    else
        echo 'Module install failed', '<br />';

    // installing plugin
    $plugin_installer = new mosInstallerMambot;
    if($plugin_installer->install(dirname(__FILE__).'/plugin'))
        echo 'Plugin install success', '<br />';
    else
        echo 'Plugin install failed', '<br />';
}