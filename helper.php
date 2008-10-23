<?php
/**
* @version   $Id: helper.php 1.2.0
* @package   Joomla 1.5, Jumi module for Joomla 1.5
* @copyright Copyright (c) 2008 Martin Hájek. All rights reserved.
* @license   GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class modJumiHelper
{
   function getJumiArray(&$params) //gets $jumi[] array
   {
      $dummy = $params->get( 'php_args' );
      preg_match_all('/\[.*?\]/', $dummy, $matches);
      if ($matches) {
         foreach ($matches as $i=>$match) {
            $jumi = preg_replace("/\[|]/", "", $match);
         }
      }
      return $jumi;
	}
	function getFilePathname(&$params) //gets pathname of the file to be included
	{
      return $params->def('default_absolute_path',JPATH_ROOT).DS.trim($params->get('file_pathname'));
   }
}



