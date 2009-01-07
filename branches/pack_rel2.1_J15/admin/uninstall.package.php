<?php
/**
* @version   $Id$
* @package   Jumi
* @copyright Copyright (C) 2008 Edvard Ananyan. All rights reserved.
* @license   GNU/GPL, see LICENSE.php
*/

defined("_JEXEC") or die("Restricted access");

$uninstaller = new InstallerController;

// uninstalling jumi module
$db->setQuery("select id from #__modules where title = 'Jumi'");
$jumi_module = $db->loadObject();
$module_uninstaller = new JInstaller;
if($module_uninstaller->uninstall('module', $jumi_module->id))
    echo 'Module uninstall success', '<br />';
else
    echo 'Module uninstall failed', '<br />';

// uninstalling jumi content plugin
$db->setQuery("select id from #__plugins where name = 'Jumi'");
$jumi_plugin = $db->loadObject();
$cont_plugin_uninstaller = new JInstaller;
if($cont_plugin_uninstaller->uninstall('plugin', $jumi_plugin->id))
    echo 'Content plugin uninstall success', '<br />';
else
    echo 'Content plugin uninstall failed', '<br />';
    
// uninstalling jumi editor-xtd plugin
$db->setQuery("select id from #__plugins where name = 'Editor Button - Jumicoder'");
$jumi_plugin = $db->loadObject();
$xtd_plugin_uninstaller = new JInstaller;
if($xtd_plugin_uninstaller->uninstall('plugin', $jumi_plugin->id))
    echo 'Editor-xtd plugin uninstall success', '<br />';
else
    echo 'Editor-xtd plugin uninstall failed', '<br />';