<?php
/**
* @version $Id$
* @package Joomla! 1.0.x
* @copyright (c) 2006 - 2009 Martin Hajek
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/ 
defined( '_VALID_MOS' ) or die( 'Restricted access' );

//Getting $code_written, $storage_source, $code_stored, $debug
global $database, $mosConfig_absolute_path; 
$code_written = trim($params->get( 'code_written' )); //code written or ""
$storage_source = trim($params->get('source_code_storage')); //filepathname or record id or ""
	if ($storage_source!=""){
		if ($id = substr(strchr($storage_source,"*"),1)) { //if record id return it
			$storage_source = (int)$id;
		} else { // else return filepathname
			$storage_source = $params->def('default_absolute_path',$mosConfig_absolute_path).'/'.$storage_source;
		}
	}
if(is_int($storage_source)) { //it is record id
	$user   = $mainframe->getUser(); //returns code stored in the database or null.
	$database->setQuery("select custom_script from #__jumi where id = '{$storage_source}' and access <= {$user->gid} and published = 1");
	$code_stored = $database->loadResult(); //code or null(error)
}
$debug	= $params->def('debug_mode', '0');

//Output of custom scripts
if($code_written.$storage_source != '') { //something to show
  if($code_written != '') //if code written
    eval ('?>'.$code_written); //include custom script written
  
  if($storage_source != '') { // if record id or filepathname
    if(is_int($storage_source)) { //it is record id
      if($code_stored != null) {
				eval ('?>'.$code_stored); //include custom script written
      } else {
      	if ($debug != 0){
					echo '<div style="color:#FF0000;background:#FFFF00;">'.'ERR: Record ID:<b>'.$storage_source.'</b> does not exist, or is not published, or you have got insufficient rights to read it!</div>';
				}
      }
    } else { //it is file
      if(is_readable($storage_source)) {
				include($storage_source); //include file
      } else {
      	if ($debug != 0){
					echo '<div style="color:#FF0000;background:#FFFF00;">'.'ERR: The file<br /><b>'.$storage_source.'</b><br />does not exist or is not readable!</div>';
				}
      }
    }
  }
} else { //nothing to show
	if ($debug != 0){
  	echo '<div style="color:#FF0000;background:#FFFF00;">'.'ERR: Jumi is working but there is <b>nothing to be shown</b>.<br />Write the code and/or specify the nonempty source of the code.'.'</div>';
  }
} 
?>
