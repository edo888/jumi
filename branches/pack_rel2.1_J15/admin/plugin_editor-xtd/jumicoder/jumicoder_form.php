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
require_once(JPATH_BASE .'plugins'.DS.'content'.DS.'jumi'.DS.'class.jumicoder.php');

function JumiCoderForm()
{
	$encoded = '';
	$decoded = '';
	$cols = 60;
	$rows = 15;
	//Radio buttons wysiwyg or no wysiwyg
	$wis_checked = 'checked';
	$nowis_checked = '';
	if (isset($_POST['wysiwyg']) && ($_POST['wysiwyg']=='0')){
		$wis_checked = '';
		$nowis_checked = 'checked';
	}
	//The form
	echo '<form name="form1" action="" method="post">';
		echo '<label><strong>Decoded</strong><br />(code editor)</label><br />';
		echo '<textarea rows="'.$rows.'" cols="'.$cols.'" name="decoded">';
			if (isset($_POST['decode'])){
				$decoded = $_POST['encoded'];
				if ($nowis_checked == 'checked'){
					$decoded = JumiCoder::cleanRubbish($decoded); //cleans possible rubbish
				}
				$decoded = JumiCoder::decode($decoded);
			}
			echo JumiCoder::viewEntities($decoded);
		echo '</textarea><br />';
		echo '<label class="l">Encoding into Wysiwyg:</label>';
		echo '&nbsp;&nbsp;<input type="radio" name="wysiwyg" value="1" '.$wis_checked.'>Yes';
		echo '<input type="radio" name="wysiwyg" value="0" '.$nowis_checked.'>No';
		echo '&nbsp;&nbsp;<input type="submit" value="Encode" name="encode" />';
	echo '</form>';
	echo '<form name="form2" action="" method="post">';
		echo '<br /><br /><label>Decoding from Wysiwyg:</label>';
		echo '<input type="radio" name="wysiwyg" value="1" '.$wis_checked.'>Yes';
		echo '<input type="radio" name="wysiwyg" value="0" '.$nowis_checked.'>No';
		echo '&nbsp;&nbsp;<input type="submit" value="Decode" name="decode" /><br />';
		echo '<label><strong>Encoded</strong><br />(copy/paste the code to/from Joomla!)</label><br />';
		echo '<textarea rows="'.$rows.'" cols="'.$cols.'" name="encoded">';
			if (isset($_POST['encode'])){
					$encoded=JumiCoder::encode($_POST['decoded']);
			}
			echo (($wis_checked == 'checked') ? $encoded : JumiCoder::viewEntities($encoded));
		echo '</textarea>';
	echo '</form>';
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Jumi coder</title>
<style type="text/css">
<!--
body {font-family:Verdana, Arial, Helvetica, sans-serif;font-size:10px;color:#000000;background-color:#F9F9F9;}
.jumicoder {text-align: center;}
.jumicoder .warn {color: #FF0000; font-size:9px;}
-->
</style>
</head>
<body>
<div class="jumicoder">
	<?php JumiCoderForm(); ?>
	<span class="warn">If there is a "clearing entities" option in your Joomla! wysiwyg editor disable it!</span>
</div>
</body>
</html>
