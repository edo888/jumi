<?php
/**
* @version   $Id$
* @package   Jumi
* @copyright Copyright (C) 2008 Edvard Ananyan. All rights reserved.
* @license   GNU/GPL, see LICENSE.php
*/

defined( '_VALID_MOS' ) or die( 'Restricted access' );

class TOOLBAR_Jumi {
    function _EDIT($applid) {
        $cid = mosGetParam($_REQUEST, 'cid', array(0));
        $text = $cid[0] ? 'Edit' : 'New';

        mosMenuBar::startTable();
        mosMenuBar::save('save');
        mosMenuBar::spacer();
        if($text != 'New') { mosMenuBar::apply('apply'); mosMenuBar::spacer(); }
        if($cid[0])        { mosMenuBar::cancel('cancel', 'Close'); mosMenuBar::spacer(); }
        else               { mosMenuBar::cancel('cancel'); mosMenuBar::spacer(); }
        mosMenuBar::help('screen.jumi.edit');
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }

    function _DEFAULT() {
        mosMenuBar::startTable();
        mosMenuBar::publishList('publish');
        mosMenuBar::spacer();
        mosMenuBar::unpublishList('unpublish');
        mosMenuBar::spacer();
        mosMenuBar::deleteList('remove');
        mosMenuBar::spacer();
        mosMenuBar::editListX('edit');
        mosMenuBar::spacer();
        mosMenuBar::addNewX('add');
        mosMenuBar::spacer();
        mosMenuBar::help('screen.jumi');
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }
}