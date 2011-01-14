<?php
/**
* @version   $Id$
* @package   Jumi
* @copyright (C) 2008 - 2011 Edvard Ananyan
* @license   GNU/GPL v3 http://www.gnu.org/licenses/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

$fileid    = JRequest::getInt('fileid');
$database  = &JFactory::getDBO();
$user      = &JFactory::getUser();
$document  = &JFactory::getDocument();
$mainframe = &JFactory::getApplication();

$database->setQuery("select * from #__jumi where id = '{$fileid}' and access <= {$user->gid} and published = 1");
$appl = $database->loadObject();

if(!is_object($appl))
    JError::raiseError(404, JText::_("The Jumi Application is Unpublished or Removed"));

$document->setTitle($appl->title);

eval('?>'.$appl->custom_script);

if(!empty($appl->path))
    if(is_file($appl->path))
        require($appl->path);
    elseif(is_file($mainframe->getCfg('absolute_path').DS.$appl->path))
        require $mainframe->getCfg('absolute_path').DS.$appl->path;
    else
        JError::raiseError(404, JText::_("Couldn't find page"));