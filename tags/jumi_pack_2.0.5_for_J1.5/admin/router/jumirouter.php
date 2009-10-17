<?php
/**
* @version   $Id: jumirouter.php 7 2009-02-22 15:59:03Z edo888 $
* @package   Jumi
* @copyright Copyright (C) 2008 Edvard Ananyan. All rights reserved.
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * JumiRouter plugin
 *
 */
class  plgSystemJumiRouter extends JPlugin {
    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @access protected
     * @param  object $subject The object to observe
     * @param  array  $config  An array that holds the plugin configuration
     * @since  1.0
     */
    function plgSystemJumiRouter(& $subject, $config) {
        // check to see if we are on frontend to execute plugin
        global $mainframe;
        if($mainframe->isAdmin())
            return;

        parent::__construct($subject, $config);
    }

    /**
     * Routes URLs
     *
     * @access public
     */
    function onAfterInitialise() {
        global $mainframe;

        $uri    =& JURI::getInstance();
        $router =& $mainframe->getRouter();

        $router->attachParseRule('parseJumiRouter');

    }
}

/**
 * SEF url parser
 *
 * @access public
 * @static
 * @param $router object of JRouter class
 * @param $uri object of JURI class
 */
function parseJumiRouter(& $router, & $uri) {
    if($router->getMode() == JROUTER_MODE_RAW)
        return array();

    $db =& JFactory::getDBO();
    $db->setQuery('select id, title, alias from #__jumi where published = 1');
    $apps = $db->loadRowList();
    $alias = array();
    foreach($apps as $i=>$app) {
        if(empty($app[2]))
            $apps[$i][2] = JFilterOutput::stringURLSafe($app[1]);
        $alias[$i] = $apps[$i][2];
    }

    $segments = explode('/', $uri->getPath());
    foreach($segments as $i => $segment)
        if(($j = array_search($segment, $alias)) !== false) {
            unset($segments[$i]);
            $uri->setVar('option', 'com_jumi');
            $uri->setVar('fileid', $apps[$j][0]);
        }

    $uri->setPath(implode('/', $segments));

    return array();
}