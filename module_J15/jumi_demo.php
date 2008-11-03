<?php
/**
* @version $Id$
* @package Jumi
* @copyright (C) 2008 Martin Hajek
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/  
defined('_JEXEC') OR die( "Direct Access Is Not Allowed" );
function sitename() { //gets sitename
 $config = new JConfig();
 return $config->sitename;
}
?>

<!-- Intro -->
<p><strong>Hello in the world of Jumi!</strong></p>
<p>This is the default Jumi demo file presented on <b><?php echo sitename(); ?></b>.</p>
<p>Your php codes can be made fully reusable like functions with Jumi extensions. E.g. values of variables of your stored codes can be set up in an appropriate "code" area in Jumi module administration.</p>
<p>You can also try Jumi component and plugin that are compatible with this module.</p>
<p>Jumi resources: <a href="http://jumi.vedeme.cz">Downloads & guides</a>, <a href="http://edo.webmaster.am/jumi">Tips & tricks</a>.</p>
