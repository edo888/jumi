<?php
defined("_JEXEC") or die("Restricted access");

$uninstaller = new InstallerController;

// uninstalling Jumi module
$db->setQuery("select id from #__modules where title = 'Jumi'");
$jumi_module = $db->loadObject();
$module_uninstaller = new JInstaller;
if($module_uninstaller->uninstall('module', $jumi_module->id))
    echo 'Module uninstall success', '<br />';
else
    echo 'Module uninstall failed', '<br />';

// uninstalling Jumi system plugin
$db->setQuery("select id from #__plugins where name = 'System - Jumi'");
$jumi_plugin = $db->loadObject();
$plugin_uninstaller = new JInstaller;
if($plugin_uninstaller->uninstall('plugin', $jumi_plugin->id))
    echo 'System plugin uninstall success', '<br />';
else
    echo 'System plugin uninstall failed', '<br />';

// uninstalling jumi editor-xtd plugin
$db->setQuery("select id from #__plugins where name = 'Button - Jumicoder'");
$jumi_plugin = $db->loadObject();
$xtd_plugin_uninstaller = new JInstaller;
if($xtd_plugin_uninstaller->uninstall('plugin', $jumi_plugin->id))
    echo 'Editor-xtd plugin uninstall success', '<br />';
else
    echo 'Editor-xtd plugin uninstall failed', '<br />';

// uninstalling Jumi router
$db->setQuery("select id from #__plugins where name = 'System - Jumi Router'");
$jumi_router = $db->loadObject();
$plugin_uninstaller = new JInstaller;
if($plugin_uninstaller->uninstall('plugin', $jumi_router->id))
    echo 'Router uninstall success', '<br />';
else
    echo 'Router uninstall failed', '<br />';