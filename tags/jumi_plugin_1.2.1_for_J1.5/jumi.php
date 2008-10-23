<?php
/**
* @version $Id$
* @package Joomla! 1.5
* @copyright (c) 2008 Martin Hajek
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
* Usage
* {jumi [pathname] [arg1] [arg2] ... [argN]}
* where pathname is the pathname of the file to be included and [arg1] ... [argN] are optional arguments.
* It depends on your included php file if it handles jumi argumets, that can be accessed by $jumi[] array in a php script.
* 
* There is also Jumi module. You can find it, as well as demo, manuals, tips and tricks at http://jumi.vedeme.cz
*/ 

defined('_JEXEC') or die( "Direct Access Is Not Allowed" );
// Import library dependencies
jimport('joomla.event.plugin');

class plgContentJumi extends JPlugin
{
   //Constructor
    function plgContentJumi( &$subject )
    {
      parent::__construct( $subject );
      // load plugin parameters
      $this->_plugin = JPluginHelper::getPlugin( 'content', 'jumi' );
      $this->_params = new JParameter( $this->_plugin->params );
    }

   // Plugin method with the same name as the event will be called automatically.
   function onPrepareContent(&$article, &$params, $limitstart)
   {
      // just startup
      global $mainframe;
      $plugin =& JPluginHelper::getPlugin('content', 'jumi');
      $pluginParams = new JParameter( $plugin->params );
      // expression to search for
      $regex = '/{(jumi)\s*(.*?)}/i';
   	// if hide_code then output empty string
   	if ( $pluginParams->get( 'hide_code') == 1 ) {
   		$article->text = preg_replace( $regex, '', $article->text );
   		return true;
   	}
   	// find all instances of $regex (i.e. jumi) in an article and put them in $matches
   	$matches = array();
   	preg_match_all( $regex, $article->text, $matches, PREG_SET_ORDER );      
   	// cycle through all instancies. Put text into $dummy[2]
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
         //The first $jumi argument must be the file pathname
   		$incl_file=trim(array_shift($jumi));
   		$output = "You must supply the file pathname into the <b>first Jumi argument at the least!</b>"; 			
   		if ( $incl_file ) { //if the string $incl_file is nonempty try to include the file else $output "You must supply ...
      		$incl_file = $pluginParams->def('default_absolute_path',JPATH_ROOT).DS.$incl_file;
    			if (is_readable($incl_file)) { // if the file is readable then include it else $output "The file ...	
   				ob_start();
   				include($incl_file);
               $output = str_replace( '$' , '\$' , ob_get_contents()); //fixed joomla bug 
   				ob_end_clean();
   			} else {
   				$output = "The file <b>".$incl_file."</b> cannot be included!<br />It does not exist or is not readable."; 
   			}
   		}
   		// final replacing of $regex (i.e. jumi) in $article->text by $output 
   		$article->text = preg_replace($regex, $output, $article->text, 1);
   	}
   	return true;   
   }
}
?>
