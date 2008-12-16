<?php
/**
* @version $Id$
* @package Joomla! 1.5.x, Jumi editors-xtd plugin
* @copyright (c) 2008 Martin Hajek
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/

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
	{ //just replaces & to &amp; so that &xxx; is seen by a browser as an entity
		return str_replace('&', '&amp;', $source);
	}
	
	function cleanRubbish($source)
	{ // cleans from the $source (encoded code in an article) possible rubbish brought by wysiwyg
		$cleaningTab = array(	"<br>" => "\n", "<br />" => "\n",	"<p>" => "\n", "</p>" => "\n", "&nbsp;" => " ");
		foreach ($cleaningTab as $key => $cleaningTab) {
    	$source = str_replace($key, $cleaningTab[$key], $source);
		}
		return $source;
	}	
}

?>
