<?php
/**
* @version   $Id$
* @package   Jumi
* @copyright Copyright (C) 2008 Edvard Ananyan. All rights reserved.
* @license   GNU/GPL, see LICENSE.php
*/

defined("_JEXEC") or die("Restricted access");

// installing module
$module_installer = new JInstaller;
if($module_installer->install(dirname(__FILE__).DS.'module'))
    echo 'Module install success', '<br />';
else
    echo 'Module install failed', '<br />';

// installing plugin
$cont_plugin_installer = new JInstaller;
if($cont_plugin_installer->install(dirname(__FILE__).DS.'plugin_content'))
    echo 'Content plugin install success', '<br />';
else
    echo 'Content plugin install failed', '<br />';

// installing plugin
$xtd_plugin_installer = new JInstaller;
if($xtd_plugin_installer->install(dirname(__FILE__).DS.'plugin_editor-xtd'))
    echo 'Editor-xtd  plugin install success', '<br />';
else
    echo 'Editor-xtd plugin install failed', '<br />';