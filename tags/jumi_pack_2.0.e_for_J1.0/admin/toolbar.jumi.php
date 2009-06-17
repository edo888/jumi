<?php
/**
* @version   $Id$
* @package   Jumi
* @copyright Copyright (C) 2008 Edvard Ananyan. All rights reserved.
* @license   GNU/GPL, see LICENSE.php
*/

defined( '_VALID_MOS' ) or die( 'Restricted access' );

require_once($mainframe->getPath('toolbar_html'));

switch($task) {
    case 'edit':
        $cid = mosGetParam($_REQUEST, 'cid', array(0));
        if(!is_array($cid))
            $cid = array(0);
        TOOLBAR_Jumi::_EDIT($cid[0]);
        break;

    case 'add'  :
    case 'editA':
        $id = (int)mosGetParam($_REQUEST, 'id');
        TOOLBAR_Jumi::_EDIT($id);
        break;

    default:
        TOOLBAR_Jumi::_DEFAULT();
        break;
}