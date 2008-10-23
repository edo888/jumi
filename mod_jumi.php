<?php
/**
* @version   $Id: mod_jumi.php 1.2.0
* @package   Joomla 1.5, Jumi module for Joomla 1.5
* @copyright Copyright (c) 2008 Martin Hájek. All rights reserved.
* @license   GNU/GPL, see LICENSE.php
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

$jumi = modJumiHelper::getJumiArray($params);
$incl_file = modJumiHelper::getFilePathname($params);
require(JModuleHelper::getLayoutPath('mod_jumi'));
