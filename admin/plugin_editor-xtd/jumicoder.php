<?php
/**
* @version $Id$
* @package Joomla! 1.5.x, Jumi editors-xtd plugin
* @copyright (c) 2008 Martin Hajek
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

class plgButtonJumicoder extends JPlugin
{
	function onDisplay($name)
	{
	  //button image
		$doc=& JFactory::getDocument();
		$doc->addStyleDeclaration('.button2-left .jumicoder 	{ background: url('.JURI::root().'plugins/editors-xtd/jumicoder/jumicoder.png) 100% 0 no-repeat; }');
		//jumicoder form
		$link = 'plugins/editors-xtd/jumicoder/jumicoder_form.php';
		if (strstr(JURI::base(),'administrator')) { //joomla prepends JURI::base() to $link automatically
			$link = '../'.$link;
		}
		//modal object with jumicoder form
		$button = new JObject();
		$button->set('modal', true);
		$button->set('link', $link); //link to the jumicoder_form.php
		$button->set('text', JText::_('Coder')); //button text
		$button->set('name', 'jumicoder'); //class of the button
		$button->set('options', "{handler: 'iframe', size: {x: 570, y: 675}}");
		return $button;
	}
}
