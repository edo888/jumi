<?php
/**
* @version $Id: info.php 92 2009-02-15 17:08:02Z martin2hajek $
* @package Joomla! 1.5.x, Jumi plugin
* @copyright (c) 2009 Martin Hajek
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
* Usage: in xml files write
* <param name="@info" type="info" default="" label="" description="" myvar="" />
* 
*/ 
defined('_JEXEC') or die( 'Restricted access' );

class JElementInfo extends JElement
{
	
	function fetchTooltip( $label, $description, &$node, $control_name, $name )
	{ //clears tooltip column
		return;
	}

	function fetchElement( $name, $value, &$node, $control_name )
	{		
		$info	= ( isset( $node->_attributes['label'] ) ) ? $node->_attributes['label'] : '';
		$description	= ( isset( $node->_attributes['description'] ) ) ? $node->_attributes['description'] : '';
		$myvar = ( isset( $node->_attributes['myvar'] ) ) ? $node->_attributes['myvar'] : '';
		
		if ( $info ) {
			$info = html_entity_decode( JText::_( $info ) );
		}
		if ( $description ) {
			$description = html_entity_decode( JText::_( $description ) );
		}			
		if ( $myvar ) { //the second line harcoded
			$myvar = html_entity_decode( JText::_( $myvar ) );
			$myvar = '<br /><strong>'.$myvar.'</strong>: '.$GLOBALS['_JUMI_ROOT'];
		}		

		//final paramater output				
		$html = '<div style="padding: 2px 5px;">';
		$html .= '<h4 style="margin: 0px;">'.$info.'</h4>';
		if ( $description ) { $html .= $description; }
		if ( $myvar ) { $html .= $myvar; }
		$html .= '<div style="clear: both;"></div></div>';
		return $html;
	}

}
