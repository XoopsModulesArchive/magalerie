<?php

#####################################################
#  Auteur, bidou http://www.lespace.org  © 2002     #
#  postmaster@lespace.org - http://www.lespace.org  #
#                                                   #
#  Licence : GPL                                    #
#  Merci de laisser ce copyright en place...        #
#####################################################
include 'admin_header.php';
$ModName = 'magalerie';

function index()
{
    global $xoopsDB, $xoopstheme, $id, $cat, $orderby, $nb, $start;

    xoops_cp_header();

    OpenTable();

    require XOOPS_ROOT_PATH . '/modules/magalerie/class/xoopspagenav.php';

    include 'admin-menu.php';

    echo '<br><table width="100%" cellspacing="0" cellpadding="0" border="0">' . '<tr><td width="20%">';

    list_cat();

    $row = $GLOBALS['xoopsDB']->queryF('SELECT id FROM ' . $xoopsDB->prefix('magalerie') . ' ');

    $nbt = $xoopsDB->getRowsNum($row);

    //$message=$nbt[0];

    if ($row > 1) {
        $s = '' . _PLURIEL1 . '';
    }

    echo '</td><td width="60%" align="center">' . _NBT . ' ' . $nbt . ' ' . _IMG . '' . $s . '</td>' . '<td width="20%">';

    $req = $GLOBALS['xoopsDB']->queryF('SELECT id FROM ' . $xoopsDB->prefix('magalerie') . " where valid='0' ");

    $nbval = $xoopsDB->getRowsNum($req);

    if ($nbval > 1) {
        $s = '' . _PLURIEL1 . '';
    } else {
        $s = '';
    }

    echo "$nbval " . _ATTENTE . '' . $s . '<br>';

    while (list($valid) = $xoopsDB->fetchRow($req)) {
        echo "<a href=\"$PHP_SELF?&id=" . $valid . '"><img src="../images/admin_edit.gif" border=0 title=' . _EDIT . '></a>&nbsp;';
    }

    echo '</td>' . '</tr></table>';

    function showedit()
    {
        global $xoopsDB, $xoopstheme, $top, $id, $cat, $orderby, $nb, $start;

        $val = 'SELECT * FROM ' . $xoopsDB->prefix('magalerie') . " WHERE id='$id' ";

        $result = $xoopsDB->query($val);

        echo '<b>' . _EDIMG . '</b>';

        echo " <form action=\"$PHP_SELF\" method=\"post\">";

        while (list($id, $uid, $thecat, $img, $clic, $note, $vote, $valid, $date) = $xoopsDB->fetchRow($result)) {
            $size = getimagesize("../galerie/$cat/$img");

            $size_ppm = getimagesize("../galerie/$cat/$img-ppm");

            $date = formatTimestamp($date, 's');

            echo '<input type="hidden" name="gop" value="update">' . "<input type=\"hidden\" name=\"id\" value=\"$id\">" . "<input type=\"hidden\" name=\"date\" value=\"$date\">";

            echo '<table border="0" cellspacing="2" cellpadding="2" align="center">'
                 . '<tr><td rowspan=9 valign="top" align="center">'
                 . _ID
                 . " $id<br><a href='javascript:openWithSelfMain(\"../show-pop.php?cat="
                 . $thecat
                 . '&img='
                 . $img
                 . "\",\"popup\", $size[0], $size[1]);'><img src=\"../galerie/$thecat/$img-ppm\" $size_ppm[3]></a><br>$date</td>"
                 . '<td><b>'
                 . _NOM
                 . ':</b></td><td>';

            if ('0' == $uid) {
                echo 'Anonyme';
            } else {
                $query = $GLOBALS['xoopsDB']->queryF('SELECT uname FROM ' . $xoopsDB->prefix('users') . " where uid='$uid' ");

                while (list($name) = $xoopsDB->fetchRow($query)) {
                    echo "<a href=\"../../../userinfo.php?uid=$uid\" target=\"new\"> $name</a>";
                }
            }

            echo "<input type=hidden name=uid value=\"$uid\" maxlength=50></td></tr>"
                 . '<tr><td><b>'
                 . _CAT
                 . ":</b></td><td><input type=text name=cat value=\"$thecat\" maxlength=50 style=\"width: 200px;\"></td></tr>"
                 . '<tr><td><b>'
                 . _IMG
                 . ":</b></td><td><input type=text name=img value=\"$img\" maxlength=255 style=\"width: 200px;\"></td></tr>"
                 //."<tr><td><b>"._DESCRIP.":</b></td><td><textarea name=coment style=\"width: 200px; height: 50px;\">$coment</textarea></td></tr>"
                 . '<tr><td><b>'
                 . _HITS
                 . ":</b></td><td><input type=text name=clic value=\"$clic\" maxlength=50 style=\"width: 30px;\"></td></tr>"
                 . '<tr><td><b>'
                 . _NOT
                 . ":</b></td><td><input type=text name=note value=\"$note\" maxlength=50 style=\"width: 30px;\"></td></tr>"
                 . '<tr><td><b>'
                 . _VOT
                 . ":</b></td><td><input type=text name=vote value=\"$vote\" maxlength=50 style=\"width: 30px;\"></td></tr>"
                 . '<tr><td><b>'
                 . _VISIB
                 . ":</b></td><td>\n";

            if ('1' == $valid) {
                echo '<p><input type=radio name=valid value="1" checked> ' . _YES . '&nbsp;' . '<input type=radio name=valid value="0"> ' . _NO . '</p>';
            } else {
                echo '<input type="checkbox" name="validemail" value="1"> ' . _VALIDEMAIL . ' ';

                echo '<p><input type=radio name=valid value="1">  ' . _YES . '&nbsp;' . '<input type=radio name=valid value="0" checked> ' . _NO . '</p>';
            }

            echo '</td></tr>' . '</table>';

            echo '<div align="center"><input type=submit value="Update">' . "<a href=\"$PHP_SELF?cat=$cat\"> " . _CLOSE . '</a>' . '<a href="delete.php?op=confirm_img&cat=' . $cat . '&img=' . $img . '&id=' . $id . '"> <img src="../images/admin_del.gif"></a></div>' . '</form>';
        }
    }

    $temcount = 1;

    if (!$start) {
        $start = 0;
    }

    if (!$orderby) {
        $orderby = 'dateD';
    }

    $mysqlorderby = convertorderbyin($orderby);

    $orderbyTrans = convertorderbytrans($mysqlorderby);

    $linkorderby = convertorderbyout($orderby);

    $nblist = 10;

    $nombre = $xoopsDB->query('SELECT COUNT(id) FROM ' . $xoopsDB->prefix('magalerie') . " WHERE cat='$cat' ");

    $result = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $xoopsDB->prefix('magalerie') . " WHERE cat='$cat' ORDER BY $mysqlorderby limit " . (int)$start . ',' . $nblist);

    $nbresult = $GLOBALS['xoopsDB']->fetchRow($nombre);

    $message = $nbresult[0];

    $pagenav = new XoopsPageNav($message, $nblist, $start, 'start', 'cat=' . $cat . '&orderby=' . $orderby . '');

    echo "<div align=\"center\"><b>$nbresult[0]</b> " . _RESULT . " <b>$cat</b><br><br>";

    if ($id) {
        showedit();
    }

    echo '' . _MD_SORTBY . '&nbsp;&nbsp;

              ' . _MD_DATE . " (<a href='index.php?cat=" . $cat . '&orderby=' . dateA . "'><img src=\"../images/up.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a><a href='index.php?cat=$cat&orderby=dateD'><img src=\"../images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)
              " . _MD_RATING . " (<a href='index.php?cat=$cat&orderby=voteA'><img src=\"../images/up.gif\" width=\"11\" height=\"14\"  border=\"0\" align=\"middle\"></a><a href='index.php?cat=$cat&orderby=voteD'><img src=\"../images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)
              " . _MD_POPULARITY . " (<a href='index.php?cat=$cat&orderby=hitsA'><img src=\"../images/up.gif\" width=\"11\" height=\"14\"  border=\"0\" align=\"middle\"></a><a href='index.php?cat=$cat&orderby=hitsD'><img src=\"../images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)
              	";

    echo '<b><br>';

    printf(_MD_CURSORTEDBY, (string)$orderbyTrans);

    echo '</b></div>';

    if (!$GLOBALS['xoopsDB']->getRowsNum($result)) {
        echo '' . _CHOICAT . "\n";
    }

    echo '<br>';

    echo '<table border="0" cellspacing="0" cellpadding="0" width="100%"><tr>';

    while (false !== ($val = $GLOBALS['xoopsDB']->fetchBoth($result))) {
        $image = "../galerie/$val[cat]/$val[img]";

        $size = getimagesize((string)$image);

        $size2 = getimagesize("$image-ppm");

        echo "<td align=\"center\"><table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" class=\"bg2\">\n" . "<tr class=bg3>\n"
             //."<td><b>Id</b></td>\n"
             //."<td><b>"._NOM."</b></td>\n"
             //."<td><b>"._CAT."</b></td>\n"
             //."<td><b>"._TITLE."</b></td>\n"
             . "<td align=\"center\"><b>$val[img]</b></td>\n" . "<td><b>&nbsp;&nbsp;</b></td>\n" . '<td align=center><b>' . _SEE . "</b></td>\n" . '<td align=center><b>' . _DIT . "</b></td>\n" . '<td align=center><b>' . _SUPP . "</b></td>\n" //."<td align=center><b>"._HITS."</b></td>\n"
             . "</tr>\n";

        echo "<tr class=\"bg4\">\n"
             //."<td>$val[id]</td>\n"
             //."<td >$val[uid]</td>\n"
             //."<td>$val[cat]</td>\n"
             . "<td rowspan=\"2\"><img src=\"$image-ppm\"></td>\n"

             . "<td rowspan=\"2\" valign=\"top\">&nbsp;</td>\n"
             . '<td align="center" valign="top">'
             . "&nbsp;<a href='javascript:openWithSelfMain(\"../show-pop.php?cat="
             . $val['cat']
             . '&img='
             . $val['img']
             . "\",\"image\",$size[0],$size[1]);'><img src=\"../images/admin_show.gif\" border=0 alt=Voir></a>"
             . "</td>\n"
             . "<td align=\"center\" valign=\"top\"><a href=\"$PHP_SELF?cat="
             . $val['cat']
             . '&id='
             . $val[id]
             . '">'
             . "<img src=\"../images/admin_edit.gif\" border=0 alt=editer></a></td>\n";

        echo '<td align="center" valign="top">';

        echo "<form method=\"post\" action=\"delete.php\">\n";

        echo "<input type=\"hidden\" name=\"op\" value=\"confirm_img\">\n";

        echo '<input type="hidden" name="id" value="' . $val[id] . "\">\n";

        echo '<input type="hidden" name="cat" value="' . $val['cat'] . "\">\n";

        echo '<input type="hidden" name="img" value="' . $val['img'] . "\">\n";

        echo '<input type="image" src="../images/admin_del.gif" border="0" alt="Effacer">';

        echo "</form>\n";

        echo "</td>\n" //."<td align=\"center\" valign=\"top\">$val[clic]</td>\n"
             . "</tr>\n";

        echo "<tr><td colspan=\"4\" valign=\"top\" class=\"bg4\"><b>id:</b> $val[id]<br>\n" . '<b>' . _CAT . ":</b> $val[cat]<br>" . "<b>uid:</b> $val[uid]<br>\n"

             . '<b>' . _HITS . ":</b>$val[clic]<br>" . '<b>' . _VOT . ":</b>$val[note]<br>";

        if ('1' == $val[valid]) {
            $valid = _YES;
        } else {
            $valid = _NO;
        }

        echo '<b>' . _VISIB . ":</b> $valid</td></tr>";

        //echo "</table>\n";

        echo "</table><br></td>\n";

        if (2 == $temcount) {
            echo '</tr><tr>';

            $temcount -= 2;
        }

        $temcount++;
    }

    echo "</tr></table>\n";

    echo '<div align=center><br>' . $pagenav->renderNav() . '</div>';

    CloseTable();

    xoops_cp_footer();

    include '../../../footer.php';
}

function update()
{
    global $xoopsUser, $xoopsConfig, $xoopsDB, $validemail, $id, $uid, $cat, $img, $clic, $note, $vote, $valid, $date, $nb;

    //echo "<input type=\"text\" name=\"date\" value=\"$date\">";

    //$date = formatTimestamp($date,"s");

    if (1 == $validemail) {
        $result = $GLOBALS['xoopsDB']->queryF('SELECT email FROM ' . $xoopsDB->prefix('users') . " WHERE uid='$uid' ");

        while (list($email) = $xoopsDB->fetchRow($result)) {
            if ('0' == !$uid) { //echo "$email";
                $subject = $xoopsConfig['sitename'] . ' - ' . _GALIMG;
            }

            $xoopsMailer = getMailer();

            $xoopsMailer->useMail();

            $xoopsMailer->setToEmails($email);

            $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);

            $xoopsMailer->setFromName($xoopsConfig['sitename']);

            $xoopsMailer->setSubject($subject);

            $xoopsMailer->setBody(_NEW_IMG_COMENT . "\n" . XOOPS_URL . "/modules/magalerie/show.php?id=$id\n\n" . _CONTEST . _IFERREUR);

            $xoopsMailer->send();
        }
    }

    if (!$cat || !$img) {
        redirect_header($GLOBALS['HTTP_REFERER'], 3, _ORDIE);

        exit();
    } elseif (!$GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('magalerie') . " SET  uid='$uid', cat='$cat', img='$img', clic='$clic', note='$note', vote='$vote', valid='$valid' where id ='$id'")) {
        die('update de magalerie impossible!<br>' . $GLOBALS['xoopsDB']->errno() . ': ' . $GLOBALS['xoopsDB']->error());
    }

    redirect_header($GLOBALS['HTTP_REFERER'], 1, "<b>$img Mis a jour!</b>");

    exit();
}

switch ($gop) {
    case 'update':
        update();
        break;
    default:
        index();
        break;
}
