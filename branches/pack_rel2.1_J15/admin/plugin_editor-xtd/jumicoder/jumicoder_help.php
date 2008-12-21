<?php
/**
* @version $Id$
* @package Joomla! 1.5.x, Jumi content plugin, Jumi editors-xtd plugin
* @copyright (c) 2008 Martin Hajek
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/

define( '_JEXEC', 1 );
define('JPATH_BASE', '../../../' );
define('DS', DIRECTORY_SEPARATOR);
// Jumi content plugin is required!!!
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Jumi coder</title>
<style type="text/css">
<!--
body {font-family:Verdana, Arial, Helvetica, sans-serif;font-size:10px;color:#000000;background-color:#F9F9F9;}
.jumicoder {text-align: left;}
.jumicoder .warn {color: #FF0000}
.jumicoder .back {text-align: right;font-weight: bold;}
-->
</style>
</head>
<body>
<div class="jumicoder">
<p class="back"><a href="jumicoder_form.php" title="Jumi coder">Back to Jumi coder</a></p>
<image src="jumicoder_help.png" alias="Help image"/>
<p>1. You can copy/paste encoded code to Joomla! wysiwig or nowysiwyg editor.</p>
<p>2. You can copy/paste code from Joomla! wysiwig or nowysiwyg editor.</p>
<p>3. It is recommended to edit the code in upper (decoded) area of Jumi coder only. If edited in Joomla! editor you can encounter mistakes.</p>
<p class="warn">4. If there is a "clearing entities" option in your Joomla! wysiwyg editor disable it! If enabled Joomla! editor can clear the code or the part of it!</p>
<p>For more help see <a href="http://jumi.vedeme.cz">Jumi Guides</a> or <a href="http://edo.webmaster.am/jumi">Jumi Tips and Tricks</a> or <a href="http://forum.joomla.org/viewtopic.php?f=470&t=349124&start=0">Jumi support forum thread</a> at Joomla.org</p>
<p class="back"><a href="jumicoder_form.php" title="Jumi coder" class="back">Back to Jumi coder</a></p>
</div>
</body>
</html>
