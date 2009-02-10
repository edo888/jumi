<?php
/**
* @version $Id$
* @package Joomla! 1.5.x, Jumi plugin
* @copyright (c) 2009 Martin Hajek
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
* Usage: {jumi stored_code_source}code_written{/jumi}
*/ 

defined('_JEXEC') or die( "Direct Access Is Not Allowed" );
// Import library dependencies
jimport('joomla.event.plugin');
require_once( dirname( __FILE__ ).DS.'jumi'.DS.'class.jumicoder.php' );

class plgContentJumi extends JPlugin
{
	function plgContentJumi( &$subject )
	{
	  parent::__construct( $subject );
	  // load plugin parameters and language file
	  $this->_plugin = JPluginHelper::getPlugin( 'content', 'jumi' );
	  $this->_params = new JParameter( $this->_plugin->params );
	  JPlugin::loadLanguage('plg_content_jumi', JPATH_ADMINISTRATOR);
	}

	function onPrepareContent(&$article, &$params, $limitstart)
	{
	  // just startup
	  global $mainframe;
	  $plugin =& JPluginHelper::getPlugin('content', 'jumi');
	  $pluginParams = new JParameter( $plugin->params );
	  $debug = $pluginParams->get( 'debug_mode');
	  // expression to search for
	  $regex = '%\{jumi\b[^}]?(\S*?)\}([\S\s]*?)\{/jumi\}%';
		// if hide_code then replace jumi syntax codes with an empty string
		if ( $pluginParams->get( 'clear_code') == 1 ) {
			$article->text = preg_replace( $regex, '', $article->text );
			return true;
		}

		$continuesearching = true;
		//Nesting loop. NO {jumi}{/jumi} in code_written please!
    while ($continuesearching){
			// find all instances of $regex (i.e. jumi syntax) in an article and put them in $result
			$result = array();
			$matches_found = preg_match_all( $regex, $article->text, $result, PREG_SET_ORDER );
			if ($matches_found) {
				// cycle through all jumi instancies.
				for ($matchi = 0; $matchi < count($result); $matchi++) {
			    //Following syntax {jumi stored_code_source}code_written{/jumi}. NO {jumi}{/jumi} in code_written please!
					$storage_source = $this->getStorageSource(trim($result[$matchi][1]), $pluginParams->def('default_absolute_path',JPATH_ROOT)); //filepathname or record id or ""
					$code_written = $result[$matchi][2]; //raw code written or ""
					$output = ''; // Jumi output
					if($code_written == '' && $storage_source == '') { //if nothing to show
					  $output = ($debug == 0) ? 'dbgerr' : '<div style="color:#FF0000;background:#FFFF00;">'.JText::_('ERROR_CONTENT').'</div>';
					} else { // buffer output
						ob_start();
						if($code_written != ''){ //if code written
							$code_written = JumiCoder::cleanRubbish($code_written);
							$code_written = JumiCoder::decode($code_written, 0);
			    		eval ("?>".$code_written); //include code written
						}
			  		if($storage_source != ''){ //if record id or filepathname
							if(is_int($storage_source)){ //if record id
			    		  $code_stored = $this->getCodeStored($storage_source);
			      		if($code_stored != null){
									eval ('?>'.$code_stored);//include record
			      		} else {
									$output = ($debug == 0) ? 'dbgerr' : '<div style="color:#FF0000;background:#FFFF00;">'.JText::sprintf('ERROR_RECORD', $storage_source).'</div>';
			      		}
			      	} else { //if file
			      		if(is_readable($storage_source)) {
									include($storage_source); //include file
			      		} else {
									$output = ($debug == 0) ? 'dbgerr' : '<div style="color:#FF0000;background:#FFFF00;">'.JText::sprintf('ERROR_FILE', $storage_source).'</div>';
			      		}
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
				// final replacement of $regex (i.e. {jumi}) in $article->text by $output
				$article->text = preg_replace($regex, $output, $article->text, 1);
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

	function getCodeStored($source)
	{ //returns code stored in the database or null.
		$database  = &JFactory::getDBO();
		$user = &JFactory::getUser();
		$database->setQuery("select custom_script from #__jumi where id = '{$source}' and access <= {$user->gid} and published = 1");
		//$database->setQuery("select custom_script from #__jumi where id = $source"); //all records, all users
		return $database->loadResult();
	}
	
	function getStorageSource($source, $abspath)
	{ //returns filepathname or a record id or ""
  	$storage=trim($source);
  	if ($storage!=""){
			if ($id = substr(strchr($storage,"*"),1)) { //if record id return it
  			return (int)$id;
  		} else { // else return filepathname
  		return $abspath.DS.$storage;
  		}
  	}	else { // else return ""
  	return '';
		}
	}

}
?>
