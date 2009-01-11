<?php
/**
* @version   $Id$
* @package   Jumi
* @copyright Copyright (C) 2008 Edvard Ananyan. All rights reserved.
* @license   GNU/GPL, see LICENSE.php
*/

defined("_VALID_MOS") or die("Restricted access");

class HTML_Jumi {
    function showApplications(&$rows,&$pageNav,$option,&$lists) {
        mosCommonHTML::loadOverlib();
        ?>
        <form action="index2.php?option=com_jumi" method="post" name="adminForm">

        <table class="adminheading">
            <tr>
                <th rowspan="2" nowrap="nowrap">
                    Jumi Applications Manager
                </th>
                <td align="right" rowspan="2" valign="top">
                    <?php echo $lists['state']; ?>
                </td>
            </tr>
            <tr>
                <td align="right">
                    Filter:
                    <input type="text" name="search" id="search" value="<?php echo $lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
                    <button onclick="this.form.submit();">Go</button>
                    <button onclick="document.getElementById('search').value='';this.form.submit();">Reset</button>
                </td>
            </tr>
        </table>

        <table class="adminlist">
            <tr>
                <th width="1%">
                    #
                </th>
                <th width="2%">
                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
                </th>
                <th align="center">
                    Title
                </th>
                <th width="30%" align="center">
                    Path
                </th>
                <th width="8%" align="center">
                    Published
                </th>
                <th width="12%" align="center">
                    Access
                </th>
                <th width="1%" nowrap="nowrap">
                    ID
                </th>
            </tr>
            <?php
            $k = 0;
            for($i=0, $n=count($rows); $i < $n; $i++) {
                $row = &$rows[$i];

                $link       = 'index2.php?option=com_jumi&task=edit&hidemainmenu=1&cid[]='. $row->id;
                $access     = mosCommonHTML::AccessProcessing( $row, $i );
                $checked    = mosCommonHTML::CheckedOutProcessing( $row, $i );
                ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td>
                        <?php echo $pageNav->rowNumber( $i ); ?>
                    </td>
                    <td>
                        <?php echo $checked; ?>
                    </td>
                    <td>
                        <a href="<?php echo $link; ?>" title="Edit Application"><?php echo $row->title; ?></a>
                    </td>
                    <td align="center">
                        <?php echo $row->path; ?>
                    </td>
                    <td align="center">
                        <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->published == '0' ? 'publish' : 'unpublish'; ?>')">
                            <img src="images/<?php echo $row->published == '0' ? 'publish_x.png' : 'publish_g.png'; ?>" width="12" height="12" border="0" alt="<?php echo $row->published == '0' ? 'Unpublished' : 'Published'; ?>" />
                        </a>
                    </td>
                    <td align="center">
                        <?php echo $access; ?>
                    </td>
                    <td align="center">
                        <?php echo $row->id; ?>
                    </td>
                </tr>
                <?php
                $k = 1 - $k;
            }
            ?>
        </table>

        <?php echo $pageNav->getListFooter(); ?>

        <input type="hidden" name="option" value="com_jumi" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="hidemainmenu" value="0" />
        <input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
        </form>
        <?php
    }

    function editApplication(&$row) {
        mosMakeHtmlSafe($row);

        mosCommonHTML::loadOverlib();
        ?>
        <script language="javascript" type="text/javascript">
        function submitbutton(pressbutton) {
            var form = document.adminForm;
            if(pressbutton == 'cancel') {
                submitform(pressbutton);
                return;
            }

            // validation
            if(form.title.value == "")
                alert( "NEEDTITLE" );
            else if(form.custom_script.value == "" && form.path.value == "")
                alert( "NEEDSCRIPT" );
            else
                submitform(pressbutton);
        }
        </script>
        <form action="index2.php?option=com_jumi" method="post" name="adminForm">

        <table class="adminheading">
            <tr>
                <th class="edit">
                    Application Manager: <small><small>[ <?php echo $row->title ? $row->title : 'New'; ?> ]</small></small>
                </th>
            </tr>
        </table>

        <table class="adminform" width="100%">
            <tr>
                <td width="200" class="key">
                    <label for="title">
                        TITLE:
                    </label>
                </td>
                <td>
                    <input class="inputbox" type="text" name="title" id="title" size="60" value="<?php echo @$row->title; ?>" />
                </td>
            </tr>
            <tr>
                <td width="200" class="key">
                    <label for="alias">
                        Alias:
                    </label>
                </td>
                <td>
                    <input class="inputbox" type="text" name="alias" id="alias" size="60" value="<?php echo @$row->alias; ?>" />
                </td>
            </tr>
            <tr>
                <td class="key">
                    <label for="custom_script">
                        <span onMouseOver="return overlib('<table><tr><td>CUSTOMSCRIPT<td></tr></table>', CAPTION, 'Custom Script', BELOW, RIGHT);" onMouseOut="return nd();">Custom Script:</span>
                    </label>
                </td>
                <td>
                    <p><textarea name="custom_script" id="custom_script" cols="80" rows="10"><?php echo @$row->custom_script; ?></textarea></p>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <label for="path">
                        <span onMouseOver="return overlib('<table><tr><td>INCLFILE<td></tr></table>', CAPTION, 'Pathname', BELOW, RIGHT);" onMouseOut="return nd();">Pathname:</span>
                    </label>
                </td>
                <td>
                    <input class="inputbox" type="text" name="path" id="path" size="60" value="<?php echo @$row->path; ?>" />
                </td>
            </tr>
        </table>

        <input type="hidden" name="option" value="com_jumi" />
        <input type="hidden" name="cid" value="<?php echo @$row->id; ?>" />
        <input type="hidden" name="mask" value="0" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="images" value="" />
        <input type="hidden" name="hidemainmenu" value="0" />
        <input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
        </form>
        <?php
    }
}