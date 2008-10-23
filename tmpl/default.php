<?php
/**
* @version   $Id: default.php 1.2.0
* @package   Joomla 1.5, Jumi module for Joomla 1.5
* @copyright Copyright (c) 2008 Martin Hájek. All rights reserved.
* @license   GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
//if file is readable then include it
if (is_readable($incl_file)) {
   include($incl_file);
}
else {
   echo "The file <b>".$incl_file."</b> cannot be included!<br />It does not exist or is not readable.";
}
?>



