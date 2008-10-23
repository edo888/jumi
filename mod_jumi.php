<?php
/**
* @version $Id$
* @package Joomla! 1.0.x and Mambo_4.5.X
* @copyright (c) 2006 - 2008 Martin Hajek
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/ 
	defined( '_VALID_MOS' ) or die( 'Restricted access' );
	global $mosConfig_absolute_path;
	$incl_file = $params->def('default_absolute_path',$mosConfig_absolute_path) . '/' . trim($params->get( 'file_pathname' ));
	if (is_readable($incl_file)) {
		$dummy = $params->get( 'php_args' );
		preg_match_all('/\[.*?\]/', $dummy, $matches);
		if ($matches) {
			foreach ($matches as $i=>$match) {
				$jumi = preg_replace("/\[|]/", "", $match);
			}
		}
		include($incl_file);
	}
	else {
		echo "The file <b> ".$incl_file."</b> cannot be included!<br />It does not exist or is not readable.";
	}
?>
