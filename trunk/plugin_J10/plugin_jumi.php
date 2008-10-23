<?php
/**
* @version $Id$
* @package Joomla! 1.0.X or Mambo_4.5.X
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

//do not remove this line
defined( '_VALID_MOS' ) or die( 'Restricted access' );

$_MAMBOTS->registerFunction( 'onPrepareContent', 'plugJumi' );

function plugJumi( $published, &$row, &$params, $page=0  ) {
	global $mosConfig_absolute_path, $database;

 	// expression to search for
 	$regex = '/{(jumi)\s*(.*?)}/i';

	// if not publish then output empty string
 	if (!$published ) {
		$row->text = preg_replace( $regex, '', $row->text );
		return;
	}

	// find all instances of mambot and put in $matches
	$matches = array();
	preg_match_all( $regex, $row->text, $matches, PREG_SET_ORDER );
	
	// cycle through all bots. Bot text will be in $dummy[2]
	foreach ($matches as $dummy) {

		//read arguments from bot text and put them into the array $jumi 
		$mms=array();
		$jumi="";
		preg_match_all('/\[.*?\]/', $dummy[2], $mms);
		if ($mms) { //at the least one argument found
			foreach ($mms as $i=>$mm) {
				$jumi = preg_replace("/\[|]/", "", $mm);
			}
		}
		//The first argument must be the file pathname
		$incl_file=array_shift($jumi);
		$output = "You must supply the file pathname into the <b>first Jumi argument at the least!</b>"; 			
		if ( $incl_file ) { //if the string $incl_file is nonempty try to include the file else $output "You must supply ...
         //get plugin parameters
         $query = "SELECT id FROM #__mambots WHERE element = 'plugin_jumi' AND folder = 'content'";
         $database->setQuery( $query );
         $id = $database->loadResult();
         $mambot = new mosMambot( $database );
         $mambot->load( $id );
         $param =& new mosParameters( $mambot->params );  
			$incl_file = $param->def('default_absolute_path',$mosConfig_absolute_path) . '/' . $incl_file;
         if (is_readable($incl_file)) {// if the file is readable then include it else $output "The file ...	
				ob_start();
				include($incl_file);
				$output = str_replace( '$' , '\$' , ob_get_contents()); //fixed joomla bug
				ob_end_clean();
			} else {
				$output = "The file <b>".$incl_file."</b> cannot be included!<br />It does not exist or is not readable."; 
			}
		}
		// final replacing of $regex (i.e. jumi) in $row->text by $output 
		$row->text = preg_replace($regex, $output, $row->text, 1);
	}
	return true;
}
?>
