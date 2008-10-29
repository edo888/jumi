<?php
/**
* @version $Id$
* @package Joomla! 1.5
* @copyright (c) 2008 Martin Hajek
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

function getCodeStored($source){ //returns code stored in the database or null.
	$database  = &JFactory::getDBO();
	//$user      = &JFactory::getUser();
	//$database->setQuery("select custom_script from #__jumi where id = '{$source}' and access <= {$user->gid} and published = 1");
	$database->setQuery("select custom_script from #__jumi where id = $source");
	return $database->loadResult();
}

if ($code_written.$storage_source != ""){ //something to show
  if ($code_written != ""){ //if code written
		eval ('?>'.$code_written); //include custom script written
	}
	if ($storage_source != ""){ // if record id or filepathname
		if (is_int($storage_source)){ //it is record id
			$code_written = getCodeStored($storage_source);
			if ($code_written != null) {
				eval ('?>'.$code_written); //include custom script written
			}
			else {
				echo '<div style="color:#FF0000;background:#FFFF00;">'.JText::sprintf('ERROR_RECORD',$storage_source).'</div>';
			}
		}
		else { //it is file
			if (is_readable($storage_source)) {
				include($storage_source); //include file
			}
			else {
			  echo '<div style="color:#FF0000;background:#FFFF00;">'.JText::sprintf('ERROR_FILE',$storage_source).'</div>';
			}
		}
	}
}
else { //nothing to show
	echo '<div style="color:#FF0000;background:#FFFF00;">'.JText::sprintf('ERROR_CONTENT').'</div>';
}
?>