<?php
/**
 * @version $Id$
 * @package Jumi
 * @author Martin Hájek
 * @copyright 2008
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

class JumiCoderForm
{
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
		echo '<form name="form1" action="" method="post" class="jumicoder">';
			echo '<label><strong>Decoded</strong> (code editor):</label><br />';
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
			echo '<label>Encoding into wysiwyg:</label>';
			echo '&nbsp;&nbsp;<input type="radio" name="wysiwyg" value="1" '.$wis_checked.'>Yes';
			echo '<input type="radio" name="wysiwyg" value="0" '.$nowis_checked.'>No';
			echo '&nbsp;&nbsp;<input type="submit" value="Encode" name="encode" />';
		echo '</form>';			
		echo '<form name="form2" action="" method="post" class="jumicoder">';
			echo '<label>Decoding from wysiwyg:</label>';
			echo '<input type="radio" name="wysiwyg" value="1" '.$wis_checked.'>Yes';
			echo '<input type="radio" name="wysiwyg" value="0" '.$nowis_checked.'>No';
			echo '&nbsp;&nbsp;<input type="submit" value="Decode" name="decode" /><br />';
			echo '<label><strong>Encoded</strong> copy/paste to/from Joomla!:</label><br />';
			echo '<textarea rows="'.$rows.'" cols="'.$cols.'" name="encoded">';
				if (isset($_POST['encode'])){
						$encoded=JumiCoder::encode($_POST['decoded']);
				}
				echo (($wis_checked == 'checked') ? $encoded : JumiCoder::viewEntities($encoded));
			echo '</textarea>';
		echo '</form>';	
	}
}

?>