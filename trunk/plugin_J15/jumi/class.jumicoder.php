<?php
/**
 * @version $Id$
 * @package Jumi
 * @author Martin Hájek
 * @copyright 2008
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die( "Direct Access Is Not Allowed" );

class JumiCoder
{
	function encode($source)
	{
		return htmlspecialchars(stripslashes($source), ENT_NOQUOTES);
	}
	
	function decode($source)
	{
		if (!function_exists("htmlspecialchars_decode")) {
			return strtr(stripcslashes($source), array_flip(get_html_translation_table(HTML_SPECIALCHARS, ENT_NOQUOTES))); //for PHP 4
    } else {
    	return htmlspecialchars_decode(stripcslashes($source), ENT_NOQUOTES);
    }
	}
		
	function viewEntities($source)
	{ //just replaces & to &amp; for &xxx; be visible in a browser as entities
		return str_replace('&', '&amp;', $source);
	}
	
	function cleanRubbish($source)
	{ // cleans from no wysiwyg possible rubbish brought by wysiwyg
		$cleaningTab = array(	"<br>" => "\n", "<br />" => "\n",	"<p>" => "\n", "</p>" => "\n");
		foreach ($cleaningTab as $key => $cleaningTab) {
    	$source = str_replace($key, $cleaningTab[$key], $source);
		}
		return $source;
	}	
}

?>