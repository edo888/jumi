<?php
/**
* @version $Id: helper.php 92 2009-02-15 17:08:02Z martin2hajek $
* @package Joomla! 1.5
* @copyright (c) 2008 Martin Hajek
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class modJumiHelper
{
	function getCodeWritten(&$params){ //returns code written or ""
		return trim($params->get( 'code_written' ));
	}
	function getStorageSource(&$params){ //returns filepathname or a record id or ""
    $storage=trim($params->get('source_code_storage'));
    if ($storage!=""){
  		if ($id = substr(strchr($storage,"*"),1)) { //if record id return it
    		return (int)$id;
    	}
    	else { // else return filepathname
    		return isset($GLOBALS['_JUMI_ROOT']) ? $GLOBALS['_JUMI_ROOT'].DS.$storage : JPATH_ROOT.DS.$storage;
    	}
    }
    else {
    	return "";
    }
	}	
	function getCodeStored($source){ //returns code stored in the database or null.
		$database  = &JFactory::getDBO();
		$user      = &JFactory::getUser();
		$database->setQuery("select custom_script from #__jumi where id = '{$source}' and access <= {$user->gid} and published = 1");
		//$database->setQuery("select custom_script from #__jumi where id = $source"); //all records, all users
		return $database->loadResult();
	}
}
?>
