<?php
/**
* @version $Id$
* @package Joomla! 1.0.x, Jumi plugin
* @copyright (c) 2006-2009 Martin Hajek
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
* Usage: {jumi stored_code_source}code_written{/jumi}
*/ 
defined( '_VALID_MOS' ) or die( 'Restricted access' );
$_MAMBOTS->registerFunction( 'onPrepareContent', 'plugJumi' );

function plugJumi( $published, &$row, &$params, $page=0  ) {
	global $mainframe, $mosConfig_absolute_path, $database;

	//get plugin parameters
	$query = "SELECT id FROM #__mambots WHERE element = 'plugin_jumi' AND folder = 'content'";
	$database->setQuery( $query );
	$id = $database->loadResult();
	$mambot = new mosMambot( $database );
	$mambot->load( $id );
	$pluginParams =& new mosParameters( $mambot->params );

 	// expression to search for
 	$regex = '/{(jumi)\s*(.*?)}/i';

	// if clear_code then replace jumi syntax codes with an empty string
 	if ($pluginParams->get( 'clear_code') == 1 ) {
		$row->text = preg_replace( $regex, '', $row->text );
		return;
	}

	// find all instances of mambot and put in $matches
	$matches = array();
	preg_match_all( $regex, $row->text, $matches, PREG_SET_ORDER );

	$continuesearching = true;
  while ($continuesearching){  //Nesting loop
	
		// find all instances of $regex (i.e. jumi) in an article and put them in $matches
		$matches = array();
		$matches_found = preg_match_all( $regex, $row->text, $matches, PREG_SET_ORDER );
		if ($matches_found) {
			// cycle through all jumi instancies. Put text into $dummy[2]
			foreach ($matches as $dummy) {
				//read arguments contained in [] from $dummy[2] and put them into the array $jumi
				$mms=array();
				$jumi="";
				preg_match_all('/\[.*?\]/', $dummy[2], $mms);
				if ($mms) { //at the least one argument found
					foreach ($mms as $i=>$mm) {
						$jumi = preg_replace("/\[|]/", "", $mm);
					}
				}
				
		    //Following syntax {jumi [storage_source][arg1]...[argN]}
		    $storage_source = trim(array_shift($jumi)); //filepathname or record id or ""
				if ($storage_source!=""){
					if ($id = substr(strchr($storage_source,"*"),1)) { //if record id return it
						$storage_source = (int)$id;
					} else { // else return filepathname
						$storage_source = $pluginParams->def('default_absolute_path',$mosConfig_absolute_path).'/'.$storage_source;
					}
				} 		    
				$output = ''; // Jumi output
				$debug	= $pluginParams->def('debug_mode', '0');
	
				if($storage_source == '') { //if nothing to show
					$output = ($debug == 0) ? 'dbgerr' : '<div style="color:#FF0000;background:#FFFF00;">Jumi is working but there is <b>nothing to be shown</b>.<br />Specify the source of the code (first square brackets)</div>';
				} else { // buffer output
					ob_start();
					if(is_int($storage_source)){ //it is record id
						$user = $mainframe->getUser();
						$database->setQuery("select custom_script from #__jumi where id = '{$storage_source}' and access <= {$user->gid} and published = 1");
						$code_stored = $database->loadResult(); //returns code stored in the database or null.
	      		if($code_stored != null){ //include custom script written
							eval ('?>'.$code_stored);
	      		} else {
							$output = ($debug == 0) ? 'dbgerr' : '<div style="color:#FF0000;background:#FFFF00;">ERR: Record ID:<b>'.$storage_source.'</b> does not exist, or is not published, or you have got insufficient rights to read it!</div>';
	      		}
	      	} else { //it is file
	      		if(is_readable($storage_source)) {
							include($storage_source); //include file
	      		} else {
							$output = ($debug == 0) ? 'dbgerr' : '<div style="color:#FF0000;background:#FFFF00;">ERR: The file<br /><b>'.$storage_source.'</b><br />does not exist or is not readable!</div>';
	      		}
					}
		  	if ($output == ''){ //if there are no errors
		  		$output = str_replace( '$' , '\$' , ob_get_contents()); //fixed joomla bug
		  		$output = ob_get_contents();
		  	} elseif ($output == 'dbgerr'){
			  		$output = '';
			  }
				ob_end_clean();
			}
			
			// final replacement of $regex (i.e. {jumi [][]}) in $row->text by $output
				$row->text = preg_replace($regex, $output, $row->text, 1); 
			}
			if ($pluginParams->get('nested_replace') == 0) {
				$continuesearching = false;
			}
		} else {
 		  $continuesearching = false;
		}
	}
	return true;
}
?>
