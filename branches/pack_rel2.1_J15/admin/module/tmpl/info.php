<?php
/**
* @version $Id$
* @package Joomla! 1.5.x, Jumi plugin
* @copyright (c) 2009 Martin Hajek
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
* Usage: in xml files write
* <param name="name visible" type="info" description="tooltip" myvar="anything" />
* 
*/ 
defined('_JEXEC') or die( 'Restricted access' );

class JElementInfo extends JElement
{
	function fetchElement( $name, $value, &$node, $control_name )
	{	//Just display Jumi Root	
		$myvar = ( isset( $node->_attributes['myvar'] ) ) ? $node->_attributes['myvar'] : '';
		if ( !$myvar ) {
			return '';
		}		
		return isset($GLOBALS['_JUMI_ROOT']) ? $GLOBALS['_JUMI_ROOT'].DS : JPATH_ROOT.DS;
	}
}