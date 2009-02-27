<?php
defined("_JEXEC") or die("Restricted access");

// installing module
$module_installer = new JInstaller;
if($module_installer->install(dirname(__FILE__).DS.'module'))
    echo 'Module install success', '<br />';
else
    echo 'Module install failed', '<br />';

// installing plugin
$plugin_installer = new JInstaller;
if($plugin_installer->install(dirname(__FILE__).DS.'plugin'))
    echo 'Plugin install success', '<br />';
else
    echo 'Plugin install failed', '<br />';

// installing router
$plugin_installer = new JInstaller;
if($plugin_installer->install(dirname(__FILE__).DS.'router'))
    echo 'Router install success', '<br />';
else
    echo 'Router install failed', '<br />';

// enabling plugin
$db =& JFactory::getDBO();
$db->setQuery('update #__plugins set published = 1 where element = "jumi" and folder = "content"');
$db->query();

// enabling router
$db->setQuery('update #__plugins set published = 1, ordering = 100 where element = "jumirouter" and folder = "system"');
$db->query();