<?php
defined("_JEXEC") or die("Restricted access");

// installing module
$module_installer = new JInstaller;
if($module_installer->install(dirname(__FILE__).DS.'module'))
    echo 'Module install success', '<br />';
else
    echo 'Module install failed', '<br />';

// installing system plugin
$plugin_installer = new JInstaller;
if($plugin_installer->install(dirname(__FILE__).DS.'plugin_system'))
    echo 'System plugin install success', '<br />';
else
    echo 'System plugin install failed', '<br />';

// installing editor-xtd plugin
$plugin_installer = new JInstaller;
if($plugin_installer->install(dirname(__FILE__).DS.'plugin_editor-xtd'))
    echo 'Editor-xtd plugin install success', '<br />';
else
    echo 'Editor-xtd plugin install failed', '<br />';

// installing router
$plugin_installer = new JInstaller;
if($plugin_installer->install(dirname(__FILE__).DS.'router'))
    echo 'Router install success', '<br />';
else
    echo 'Router install failed', '<br />';

// enabling system plugin
$db =& JFactory::getDBO();
$db->setQuery('update #__plugins set published = 1 where element = "jumi" and folder = "system"');
$db->query();

// enabling editor-xtd plugin
$db =& JFactory::getDBO();
$db->setQuery('update #__plugins set published = 1 where element = "jumicoder" and folder = "editors-xtd"');
$db->query();

// enabling router
$db->setQuery('update #__plugins set published = 1, ordering = 100 where element = "jumirouter" and folder = "system"');
$db->query();