<?php
/**
* @version   $Id$
* @package   Jumi
* @copyright Copyright (C) 2008 Edvard Ananyan. All rights reserved.
* @license   GNU/GPL, see LICENSE.php
*/

defined("_VALID_MOS") or die("Restricted access");

if(!($acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'all') | $acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'com_jumi')))
    mosRedirect('index2.php', _NOT_AUTH);

require_once($mainframe->getPath('admin_html'));

switch ($task) {
    case 'add':
    case 'edit': editApplication(); break;
    case 'save':
    case 'apply': saveApplication(); break;
    case 'remove': removeApplication(); break;
    case 'publish':
    case 'unpublish': publishApplications(); break;
    case 'cancel': cancelApplication(); break;
    case 'accesspublic': accessApplication(0); break;
    case 'accessregistered': accessApplication(1); break;
    case 'accessspecial': accessApplication(2); break;
    default: showApplications(); break;
}

function showApplications() {
    global $database, $mainframe, $option;

    $filter_state  = $mainframe->getUserStateFromRequest("$option.filter_state",'filter_state','*');
    $search        = $mainframe->getUserStateFromRequest("$option.search",'search','');
    $search        = $database->getEscaped(trim(strtolower($search)));
    $limit         = (int)mosGetParam($_REQUEST, 'global.list.limit', $mainframe->getCfg('list_limit'));
    $limitstart    = $mainframe->getUserStateFromRequest($option.'limitstart','limitstart',0);

    $where = array();

    if($filter_state) {
        if($filter_state == 'P')
            $where[] = 'm.published = 1';
        elseif($filter_state == 'U')
            $where[] = 'm.published = 0';
    }

    if($search)
        $where[] = 'LOWER(m.title) LIKE "%'.$search.'%"';

    $where   = (count($where) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
    $orderby = ' ORDER BY m.id';

    $query = 'SELECT COUNT(m.id) FROM #__jumi as m left join #__groups as g on (m.access = g.id) '.$where;
    $database->setQuery($query);
    $total = $database->loadResult();

    require_once($mainframe->getCfg('absolute_path').'/administrator/includes/pageNavigation.php');
    $pageNav = new mosPageNav($total, $limitstart, $limit);

    $query = 'SELECT m.*, g.name as groupname FROM #__jumi as m left join #__groups as g on (m.access = g.id)  '.$where.' '.$orderby;
    $database->setQuery($query,$pageNav->limitstart,$pageNav->limit);
    $rows = $database->loadObjectList();

    if($database->getErrorNum()) {
        echo $database->stderr();
        return false;
    }

    // state filter
    //$lists['state'] = mosHTML::selectList( $authors, 'filter_authorid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'created_by', 'name', $filter_authorid );
    //JHTML::_('grid.state',$filter_state);

    // search filter
    $lists['search'] = $search;

    HTML_Jumi::showApplications($rows, $pageNav, $option, $lists);
}

function editApplication() {
    global $database, $option;

    $cid = mosGetParam($_REQUEST, 'cid', array(0));
    $uid = (int) @$cid[0];

    $query = 'SELECT * FROM #__jumi WHERE id = '.$uid;
    $database->setQuery($query);
    $database->loadObject($row);

    HTML_Jumi::editApplication($row);
}

function saveApplication() {
    global $database, $task;

    $post = $_POST;
    $cid  = mosGetParam($_REQUEST, 'cid', array(0));
    $applid = (int) @$cid[0];

    $title         = $database->Quote($post['title']);
    $alias         = $database->Quote($post['alias']);
    $custom_script = $database->Quote(stripslashes($_POST['custom_script']));
    $path          = $database->Quote($post['path']);
    if($applid == 0) {
        $query = "insert into #__jumi (title, alias, custom_script, path) values($title,$alias,$custom_script,$path)";
        $database->setQuery($query);
        $database->query();
    } else {
        $query = "update #__jumi set title = $title, alias = $alias, custom_script = $custom_script, path = $path where id = $applid";
        $database->setQuery($query);
        $database->query();
    }

    switch($task) {
        case 'apply':
            $msg = 'Changes to Application saved';
            $link = 'index2.php?option=com_jumi&task=edit&cid[]='. $applid .'';
            break;

        case 'save':
        default:
            $msg = 'Application saved';
            $link = 'index2.php?option=com_jumi';
            break;
    }

    mosRedirect($link, $msg);
}

function removeApplication() {
    global $database, $option;

    $cid  = mosGetParam($_REQUEST, 'cid', array(0));
    $msg = '';

    for($i=0, $n=count($cid); $i < $n; $i++) {
        $msg .= "Application with id: $cid[$i] successfully deleted";
        $query = "delete from #__jumi where id = $cid[$i]";
        $database->setQuery($query);
        $database->query();
    }

    mosRedirect('index2.php?option='.$option, $msg);
}

function publishApplications() {
    global $database, $option, $task;

    $cid  = mosGetParam($_REQUEST, 'cid', array(0));

    $publish = ( $task == 'publish' ? 1 : 0 );

    if(count($cid) < 1) {
        $action = $publish ? 'publish' : 'unpublish';
        echo "<script>alert('Select an item to $action'); window.history.go(-1);</script>";
        exit();
    }

    mosArrayToInts($cid);
    $cids = implode(',',$cid);

    $query = "UPDATE #__jumi SET published = ".intval($publish)." WHERE id in ($cids)";
    $database->setQuery($query);
    if (!$database->query()) {
        echo "<script>alert('".$database->getErrorMsg()."'); window.history.go(-1);</script>";
        exit();
    }

    mosRedirect( 'index2.php?option='. $option, count($cid) . ' item(s) '.$task.'ed successfully' );
}

function accessApplication($access) {
    global $database, $option;

    $cid  = mosGetParam($_REQUEST, 'cid', array(0));
    $applid = (int) @$cid[0];

    $query = "update #__jumi set access=$access where id=$applid";
    $database->setQuery($query);
    if (!$database->query()) {
        echo "<script>alert('".$database->getErrorMsg()."'); window.history.go(-1);</script>";
        exit();
    }

    mosRedirect( 'index2.php?option='. $option );
}

function cancelApplication() {
    global $database, $option;

    $id   = mosGetParam($_REQUEST, 'id', 0);
    //$row  =& JTable::getInstance('poll', 'Table');

    //$row->checkin( $id );
    mosRedirect( 'index2.php?option='. $option );
}