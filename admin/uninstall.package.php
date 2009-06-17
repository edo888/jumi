<?php
defined("_VALID_MOS") or die("Restricted access");

function com_uninstall() {
    global $database, $mainframe;

    require_once($mainframe->getPath('installer_class', 'module'));
    require_once($mainframe->getPath('installer_class', 'mambot'));

    // rename xml files to xml- for further deletion
    rename($mainframe->getCfg('absolute_path').'/administrator/components/com_jumi/module/mod_jumi.xml', $mainframe->getCfg('absolute_path').'/administrator/components/com_jumi/module/mod_jumi.xml-');
    rename($mainframe->getCfg('absolute_path').'/administrator/components/com_jumi/plugin/plugin_jumi.xml', $mainframe->getCfg('absolute_path').'/administrator/components/com_jumi/plugin/plugin_jumi.xml-');

    // uninstalling jumi module
    $database->setQuery("select id from #__modules where title = 'Jumi'");
    $database->loadObject($jumi_module);

    $module_uninstaller = new mosInstallerModule;
    if($module_uninstaller->uninstall($jumi_module->id, ''))
        echo 'Module uninstall success', '<br />';
    else
        echo 'Module uninstall failed', '<br />';

    // uninstalling jumi plugin
    $database->setQuery("select id from #__mambots where name = 'Jumi'");
    $database->loadObject($jumi_plugin);

    $plugin_uninstaller = new mosInstallerMambot;
    if($plugin_uninstaller->uninstall($jumi_plugin->id, ''))
        echo 'Plugin uninstall success', '<br />';
    else
        echo 'Plugin uninstall failed', '<br />';
}