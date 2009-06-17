<?php
/**
* @version $Id: jumi.php 92 2009-02-15 17:08:02Z martin2hajek $
* @package Joomla! 1.5.x, Jumi plugin
* @copyright (c) 2009 Martin Hajek
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
* Usage: {jumi stored_code_source}code_written{/jumi}
*/ 

defined('_JEXEC') or die( "Direct Access Is Not Allowed" );
// Import library dependencies
jimport( 'joomla.plugin.plugin' );
require_once( dirname( __FILE__ ).DS.'jumi'.DS.'class.jumicoder.php' );

class plgSystemJumi extends JPlugin
{
	var $regex = '%\{jumi\b[^}]?(\S*?)\}([\S\s]*?)\{/jumi\}%';
	var $debug;
	var $pluginParams;
	
  function plgSystemJumi( &$subject, $config ) //constuctor
  {
  	global $mainframe, $_JUMI_ROOT;
  	parent::__construct($subject, $config);
			//parent::__construct( $subject );
		 $this->loadLanguage( );
			//$option	= JRequest::getCmd( 'option' );
  	JPlugin::loadLanguage('plg_system_jumi', JPATH_ADMINISTRATOR);
  	JPlugin::loadLanguage('plg_system_jumi');
		$this->_plugin = JPluginHelper::getPlugin( 'system', 'jumi' );
		$this->pluginParams = new JParameter( $this->_plugin->params );
		$this->debug = $this->pluginParams->get( 'debug_mode');
		//Jumi root for files inclusion is GLOBAL for all Jumi extensions
		$_JUMI_ROOT = $this->pluginParams->def('jumi_root', JPATH_ROOT);
  }
  
  function onPrepareContent( &$article ) //Articles, Sections desc., Categories desc.
	{
		global $mainframe, $_JUMI_ROOT;
	  $nested = $this->pluginParams->get('nested_replace');

		//Clear the Jumi code and syntax from the article in the frontend? If yes then clear and end
		$aagid = (!isset($article->usertype)) ? $this->getGroupIdFromType('Administrator') : $this->getGroupIdFromType($article->usertype); //for Sections and Categories desc article author is set to Admin by default
		if ($this->getClearing( $this->pluginParams->get( 'clear_code'), $aagid )) {
			$article->text = preg_replace( $this->regex, '', $article->text );
			return true;
		}
	  
		$continuesearching = true; //Nesting loop. NO {jumi}{/jumi} in code_written please!
    while ($continuesearching){
			// find all instances of $regex (i.e. jumi syntax) in an article and put them in $result
			$result = array();
			$matches_found = preg_match_all( $this->regex, $article->text, $result, PREG_SET_ORDER );
			if ($matches_found) {
				for ($matchi = 0; $matchi < count($result); $matchi++) { //cycle through all jumi instancies.
			    //Sewing code written and code stored together to output
					$storage_source = $this->getStorageSource(trim($result[$matchi][1])); //filepathname or record id or ""
					$code_written = $result[$matchi][2]; //raw code written or ""
					$output = $this->getOutput($code_written, $storage_source, $this->debug);
					//Final replacement of $regex (i.e. {jumi ...}...{/jumi}) in $article->text by eval $output
					ob_start();
					eval("?>".$output);
					$output = str_replace( '$' , '\$' , ob_get_contents()); //fixed joomla bug
					$output = str_replace( '\0' , '\\\\0' , ob_get_contents()); //fixed php bug. Not sure if there is no side effect of the fix.
					ob_end_clean();
					$article->text = preg_replace($this->regex, $output, $article->text, 1);
				}
				if ($nested == 0) {
		  		$continuesearching = false;
		  	}
			} else {
   		  $continuesearching = false;
			}
		}
		return true;
	}
	
	function onAfterDispatch() //Feeds
	{
		global $mainframe;
		$docu	=& JFactory::getDocument();
		$docuType = $docu->getType(); //feed, html, pdf
		if ( $docuType == 'feed' && isset( $docu->items ) ) { // if feed then replace it with empty string
			for ( $i = 0; $i <= count( $docu->items ); $i++ ) {
				if ( isset( $docu->items[$i]->description ) ) {
					$docu->items[$i]->description = preg_replace( $this->regex, '', $docu->items[$i]->description, 1 );
				}
			}
		}		
	}
	/////////////////////custom methods //////////////////////////////
	function getCodeStored($source)
	{ //returns code stored in the database or null.
		$database  = &JFactory::getDBO();
		$user = &JFactory::getUser();
		$database->setQuery("select custom_script from #__jumi where id = '{$source}' and access <= {$user->gid} and published = 1");
		//$database->setQuery("select custom_script from #__jumi where id = $source"); //all records, all users
		return $database->loadResult();
	}
	
	function getStorageSource($source)
	{ //returns filepathname or a record id or ""
  	global $_JUMI_ROOT;
  	$storage=trim($source);
  	if ($storage!=""){
			if ($id = substr(strchr($storage,"*"),1)) { //if record id return it
  			return (int)$id;
  		} else { // else return filepathname
  		return $GLOBALS['_JUMI_ROOT'].DS.$storage;
  		}
  	}	else { // else return ""
  	return '';
		}
	}
	
	function getGroupIdFromType($type) 
	{ //returns user group id from its type or null
		$database	=& JFactory::getDBO();
		$database->setQuery( 'SELECT id FROM #__core_acl_aro_groups WHERE name = "'.$type.'"' );
		return $database->loadResult();
	}
	
	function getOutput($code_written, $storage_source, $debug) 
	{ //returns Jumi $output
		$output = ''; // Jumi output
		if($code_written == '' && $storage_source == '') { //if nothing to show
		  $output = ($debug == 0) ? '' : '<div style="color:#FF0000;background:#FFFF00;">'.JText::_('ERROR_CONTENT').'</div>';
		} else { // buffer code to $output
			if($code_written != ''){ //if code written
				$code_written = JumiCoder::cleanRubbish($code_written);
				$code_written = JumiCoder::decode($code_written, 0);
    		$output .= $code_written; //include code written
			}
  		if($storage_source != ''){ //if record id or filepathname
				if(is_int($storage_source)){ //if record id
    		  $code_stored = $this->getCodeStored($storage_source);
      		if($code_stored != null){
						$output .= $code_stored; //include record
      		} else {
						$output = ($debug == 0) ? '' : '<div style="color:#FF0000;background:#FFFF00;">'.JText::sprintf('ERROR_RECORD', $storage_source).'</div>';
      		}
      	} else { //if file
      		if(is_readable($storage_source)) {
						$output .= file_get_contents($storage_source); //include file
      		} else {
						$output = ($debug == 0) ? '' : '<div style="color:#FF0000;background:#FFFF00;">'.JText::sprintf('ERROR_FILE', $storage_source).'</div>';
      		}
				}
  		}
  	}
		return $output;
	}
	
	function getClearing($clear_switch, $aagid)
	{ //decides wheather clear (filter out) Jumi syntax from the article or not
		//aagid: article autor group id
		switch ($clear_switch) {
			case '0':
				$clearing = true;
				$config	= JComponentHelper::getParams( 'com_content' );
				$filterGroups	=  $config->get( 'filter_groups' ); //$params->_registry[_default][data]->filter_groups;
				$filterType		= $config->get( 'filter_type' ); //$params->_registry[_default][data]->filter_type;
				if ((is_array($filterGroups) && in_array( $aagid, $filterGroups )) || (!is_array($filterGroups) && $aagid == $filterGroups)) {
					if ($filterType == 'WL') {
						$clearing = false;
					}
				} else {
					if ($filterType != 'WL') {
						$clearing = false;
					}
				}
			break;
			case '1':
				$clearing = true;
			break;
			case '2':
				$clearing = false;
			break;
			default:
				$clearing = false; 
		}	
		return $clearing;
	}

}
?>