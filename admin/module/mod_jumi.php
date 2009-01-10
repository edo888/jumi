<?php
/**
* @version $Id$
* @package Joomla! 1.5
* @copyright (c) 2006 - 2009 Martin Hajek
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the functions only once
require_once(dirname(__FILE__).DS.'helper.php');

$debug	= $params->get('debug_mode', '0');
$code_written   = modJumiHelper::getCodeWritten($params); //code written or ""
$storage_source = modJumiHelper::getStorageSource($params); //filepathname or record id or ""

if(is_int($storage_source)) { //it is record id
	$code_stored = modJumiHelper::getCodeStored($storage_source); //code or null(error]
}

require(JModuleHelper::getLayoutPath('mod_jumi'));
?>
