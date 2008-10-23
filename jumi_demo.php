<?php
/**
* @version $Id$
* @package Joomla! 1.0.X or Joomla! 1.5.X or Mambo_4.5.X, mod_jumi or plugin_jumi
* @copyright (C) 2008 Martin Hajek
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/  
defined('_JEXEC') OR defined('_VALID_MOS') OR die( "Direct Access Is Not Allowed" );
?>

<!-- Intro -->
<p><strong>Hello in the world of Jumi!</strong></p>
<p>This is the default Jumi demo file "jumi_demo.php".</p>
<p>For quick introduction to Jumi read <a href="http://jumi.vedeme.cz/index.php?option=com_content&view=article&id=39&Itemid=37" title="Concise guide">Concise guide to Jumi</a>.</p>
<!-- Argument values passinbg -->
<p>The real strength and great flexibility of Jumi lies in its ability to pass practically any number of argument values into your php scripts very easily. Values can be referrenced by $jumi[] array in your code.</p>
<p>Your current content of $jumi[] array is:</p>
<?php
   if (!empty($jumi)){
      echo "<ul>\n";
      foreach ($jumi as $key => $value) {
   	  echo '<li>$jumi['.$key.'] = '.$value.'</li>';
   	}
   	echo "</ul>\n";
	}
	else {
		echo "<p>empty</p>";
	}
?>
<!-- End -->
<p>For another Jumi extensions, demo files, tips and tricks visit<p>
<div align="center"><a href="http://jumi.vedeme.cz" title="Jumi website"><strong>jumi.vedeme.cz</strong></a></div>
