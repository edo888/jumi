<?php
/**
* @version   $Id$
* @package   Jumi
* @copyright Copyright (C) 2008 Edvard Ananyan. All rights reserved.
* @license   GNU/GPL, see LICENSE.php
*/

defined("_VALID_MOS") or die("Restricted access");

global $database, $mainframe;

$fileid = mosGetParam($_REQUEST, 'fileid', '');
$user   = $mainframe->getUser();

$database->setQuery("select * from #__jumi where id = '{$fileid}' and access <= {$user->gid} and published = 1");
$database->loadObject($appl);

if(!is_object($appl))
   echo "The Jumi Application is Unpublished or Removed";

$mainframe->setPageTitle($appl->title);

eval("?>".$appl->custom_script);

if(!empty($appl->path))
    if(is_file($appl->path))
        require($appl->path);
    elseif(is_file($mainframe->getCfg('absolute_path').DS.$appl->path))
        require $mainframe->getCfg('absolute_path').DS.$appl->path;
    else
        echo "Couldn't find page";