<?php

#################################################
# Magalerie Version 1.7		E-xoops
# Projet du --/--/2002          dernière modification: 19/06/2003
# Scripts Home:                 http://www.lespace.org
# auteur           :            bidou
# email            :            bidou@lespace.org
# Site web         :		http://www.lespace.org
# licence          :            Gpl
##################################################
#traduction anglaise par	Romuald
#email 			trans@freesurf.ch
#
##################################################
# Merci de laisser cette entête en place.
##################################################
include 'admin_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
$ModName = 'magalerie';

$gop = 'index';

if (!empty($_GET['gop'])) {
    $gop = $_GET['gop'];
}

if ('index' == $gop) {
    xoops_cp_header();

    OpenTable();

    require XOOPS_ROOT_PATH . '/modules/magalerie/class/xoopspagenav.php';

    echo '<a href="index.php"><b>Admin index</b></a><br><br>';

    echo '<br><table width="100%" cellspacing="0" cellpadding="0" border="0">' . '<tr><td width="20%">';

    echo "<form method=\"post\" action=\"$PHP_SELF\">";

    echo '<b>' . _CHOICAT . '</b>: ';

    $myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object

    $mytree = new XoopsTree($xoopsDB->prefix('magalerie_cat'), 'id', 'cid');

    $mytree->makeMySelBox('cat', 'id', 0, 1, 'uid', 'submit()');

    echo '</form>';

    $row = $xoopsDB->query('SELECT id FROM ' . $xoopsDB->prefix('magalerie') . ' ');

    $nbt = $xoopsDB->getRowsNum($row);

    if ($row > 1) {
        $s = '' . _PLURIEL1 . '';
    }

    echo '</td><td width="60%" align="center">' . _NBT . ' ' . $nbt . ' ' . _IMG . '' . $s . '</td>' . '<td width="20%">';

    $req = $xoopsDB->query('SELECT id FROM ' . $xoopsDB->prefix('magalerie') . " where valid='0' ");

    $nbval = $xoopsDB->getRowsNum($req);

    if ($nbval > 1) {
        $s = '' . _PLURIEL1 . '';
    } else {
        $s = '';
    }

    echo "$nbval " . _ATTENTE . '' . $s . '<br>';

    while (list($valid) = $xoopsDB->fetchRow($req)) {
        echo '<a href="edit.php?gop=showedit&id=' . $valid . '"><img src="../images/admin_edit.gif" border=0 title=' . _EDIT . '></a>&nbsp;';
    }

    echo '</td>' . '</tr></table>';

    if (!empty($uid)) {
        $temcount = 1;

        $start = 0;

        if (!empty($_GET['start'])) {
            $start = $_GET['start'];
        }

        $orderby = 'dateD';

        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }

        $mysqlorderby = convertorderbyin($orderby);

        $orderbyTrans = convertorderbytrans($mysqlorderby);

        $linkorderby = convertorderbyout($orderby);

        $nblist = 10;

        $nombre = $xoopsDB->query('SELECT COUNT(id) FROM ' . $xoopsDB->prefix('magalerie') . " WHERE uid='$uid' ");

        $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('magalerie') . " WHERE uid='$uid' ORDER BY $mysqlorderby limit " . (int)$start . ',' . $nblist);

        $nbresult = $xoopsDB->fetchRow($nombre);

        $message = $nbresult[0];

        $pagenav = new XoopsPageNav($message, $nblist, $start, 'start', 'uid=' . $uid . '&orderby=' . $orderby . '');

        $catname = multiname($uid);

        [$pathimg, $valid] = link_catname($uid);

        if (1 == $valid) {
            $voir = '<img src=../images/vert.png width="10" height="10">';
        } else {
            $voir = '<img src=../images/rouge.png width="10" height="10">';
        }

        echo "<div align=\"center\"><b>$nbresult[0]</b> " . _RESULT . " <b>$pathimg</b>&nbsp; $voir<br><br>";

        echo '' . _MD_SORTBY . '&nbsp;&nbsp;

              ' . _MD_DATE . " (<a href='$PHP_SELF?uid=" . $uid . "&orderby=dateA'><img src=\"../images/up.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a><a href='$PHP_SELF?uid=$uid&orderby=dateD'><img src=\"../images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)
              " . _MD_RATING . " (<a href='$PHP_SELF?uid=$uid&orderby=noteA'><img src=\"../images/up.gif\" width=\"11\" height=\"14\"  border=\"0\" align=\"middle\"></a><a href='$PHP_SELF?uid=$uid&orderby=noteD'><img src=\"../images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)
              " . _MD_POPULARITY . " (<a href='$PHP_SELF?uid=$uid&orderby=hitsA'><img src=\"../images/up.gif\" width=\"11\" height=\"14\"  border=\"0\" align=\"middle\"></a><a href='$PHP_SELF?uid=$uid&orderby=hitsD'><img src=\"../images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)
              	";

        echo '<b><br>';

        printf(_MD_CURSORTEDBY, (string)$orderbyTrans);

        echo '</b></div>';

        if (!$xoopsDB->getRowsNum($result)) { //echo ""._CHOICAT."\n";
            echo '<br>';
        }

        echo '<br><table border="0" cellspacing="0" cellpadding="0" width="100%"><tr>';

        while (false !== ($val = $GLOBALS['xoopsDB']->fetchBoth($result))) {
            $image = '../galerie/' . $pathimg . "/$val[img]";

            if (file_exists((string)$image)) {
                $size = getimagesize((string)$image);
            }

            if (file_exists("$image-ppm")) {
                $size2 = getimagesize("$image-ppm");
            }

            echo '<td align="center" valign="top">'
                 . "<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" class=\"bg2\" width=\"100%\">\n"
                 . "<tr class=bg3>\n"
                 . "<td align=\"center\"><b> $val[img]</b></td>\n"
                 . "<td align=center><b><a href='javascript:openWithSelfMain(\"../show-pop.php?id=$val[id]\",\"image\",$size[0],$size[1]);' title=\""
                 . _SEE
                 . "\"><img src=\"../images/admin_show.gif\" border=0 alt=Voir></a></b></td>\n"
                 . '<td align=center><a href="edit.php?gop=showedit&id='
                 . $val['id']
                 . "&uid=$uid\" title=\""
                 . _DIT
                 . "\"><img src=\"../images/admin_edit.gif\" border=0 alt=editer></a></td>\n"
                 . '<td align=center><a href="delete.php?op=confirm_img&id='
                 . $val['id']
                 . "&uid=$uid&image=$image\"><img src=\"../images/admin_del.gif\" border=\"0\" alt="
                 . _SUPP
                 . "></a></td>\n"
                 . "</tr>\n";

            echo "<tr class=\"bg4\">\n" . "<td width=\"170\" align=\"center\"><img src=\"$image-ppm\"></td>\n";

            echo '<td colspan="4" align="left" valign="top" class="bg4">';

            echo "<form method=\"post\" action=\"$PHP_SELF?gop=transf&imgid=" . $val['id'] . '">';

            echo '' . _MOVECAT . '<br>';

            //$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object

            $mytree = new XoopsTree($xoopsDB->prefix('magalerie_cat'), 'id', 'cid');

            $mytree->makeMySelBox('cat', 'id', 0, 1, 'newuid', 'submit()');

            echo '</form>';

            echo '<b>' . _CAT . ":</b> $catname<br>" . '<b>id:</b> ' . $val['id'] . '<br>' . '<b>' . _HITS . ":</b>$val[clic]<br>" . '<b>' . _VOT . ":</b>$val[note]<br>";

            if (1 == $val['valid']) {
                $valid = _YES;
            } else {
                $valid = _NO;
            }

            echo '<b>' . _VISIB . ":</b> $valid<br>";

            $comments = $val['comments'];

            echo '<b>' . _IMG_COMMENT . "</b> $comments<br>";

            if ('' != $val['description']) {
                $desc = _YES;
            } else {
                $desc = _NO;
            }

            echo '<b>' . _DESCRIP . "</b>&nbsp;$desc<br>";

            $desc = htmlspecialchars((string)$val[description], ENT_QUOTES | ENT_HTML5);

            echo "<textarea name=\"desc\" style=\"width: 180px;\">$desc</textarea>";

            echo '</td></tr>';

            echo "</table><br></td>\n";

            if (2 == $temcount) {
                echo '</tr><tr>';

                $temcount -= 2;
            }

            $temcount++;
        }

        echo "</tr></table>\n";

        echo '<div align=center><br>' . $pagenav->renderNav() . '</div>';
    }

    CloseTable();

    xoops_cp_footer();
}

////////////////////////

if ('transf' == $gop) {
    $imgid = $_GET['imgid'];

    $newuid = $_POST['newuid'];

    $modif = 'select uid, img from ' . $xoopsDB->prefix('magalerie') . " where id='$imgid' ";

    $real = $xoopsDB->query($modif);

    while (list($uid, $img) = $xoopsDB->fetchRow($real)) {
        [$old_pathimg, $valid_cat] = linkretour($uid);

        if ('' != $old_pathimg) {
            $old_pathimg .= '/';
        }

        $old_cat = multiname($uid);

        [$new_pathimg, $valid_cat] = linkretour($newuid);

        if ('' != $new_pathimg) {
            $new_pathimg .= '/';
        }

        $new_cat = multiname($newuid);

        if (!$newuid || !$imgid) {
            redirect_header($GLOBALS['HTTP_REFERER'], 3, _NOPERM);

            exit();
        } elseif (!$xoopsDB->query('UPDATE ' . $xoopsDB->prefix('magalerie') . " SET  uid='$newuid' where id ='$imgid'")) {
            die('update de magalerie impossible!<br>' . $xoopsDB->errno() . ': ' . $xoopsDB->error());
        }

        $photo = XOOPS_ROOT_PATH . '/modules/magalerie/galerie/' . $old_pathimg . '' . $old_cat . "/$img";

        $destination = XOOPS_ROOT_PATH . '/modules/magalerie/galerie/' . $new_pathimg . '' . $new_cat . "/$img";

        rename((string)$photo, $destination);

        $photo2 = XOOPS_ROOT_PATH . '/modules/magalerie/galerie/' . $old_pathimg . '' . $old_cat . "/$img-ppm";

        $destination2 = XOOPS_ROOT_PATH . '/modules/magalerie/galerie/' . $new_pathimg . '' . $new_cat . "/$img-ppm";

        rename((string)$photo2, $destination2);

        redirect_header("listing.php?uid=$newuid", 0, "<b>$img " . MG_MAJ . '</b>');

        exit();
    }
}
