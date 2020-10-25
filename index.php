<?php

//id:magalerie/index.php
#################################################
# Magalerie Version 1.8		for xoops 2xx
# Projet du --/--/2002          dernière modification: 06/09/2003
# Scripts Home:                 http://www.lespace.org
# auteur           :            bidou
# email            :            bidou@lespace.org
# Site web         :	http://
# licence          :            Gpl
##################################################
#traduction anglaise par	Romuald
#email 			trans@freesurf.ch
#
##################################################
# Merci de laisser cette entête en place.
##################################################
include 'header.php';

require XOOPS_ROOT_PATH . '/modules/magalerie/class/xoopspagenav.php';

$accept = accept();

if (!empty($accept)) {
    require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';

    $perm = '';
} else {
    require_once XOOPS_ROOT_PATH . '/modules/magalerie/class/xoopstree.php';

    $perm = ' and valid=1';
}

$uid_form = 0;
if (!empty($_GET['uid'])) {
    $uid_form = $_GET['uid'];
}

$path = multiname($uid_form);
$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
$mytree = new XoopsTree($xoopsDB->prefix('magalerie_cat'), 'id', 'cid');
$GLOBALS['xoopsOption']['template_main'] = 'magalerie_index.html';
require XOOPS_ROOT_PATH . '/header.php';
$title = '<a href="index.php" title="Retour index"><b>' . _MA_TITRE . "</b></a> <font size=+1>$path</font>";
$xoopsTpl->assign('barre_titre', $title);

if (1 == $xoopsModuleConfig['listcat']) {
    $navig_form = '<form method="post" action="galerie.php">';

    $navig_form .= '<b>' . _NAVIGATION . '</b>: ';

    ob_start();

    $mytree->makeMySelBox('cat', 'id', 0, 1, 'uid', 'submit()');

    $navig_form .= ob_get_contents();

    ob_end_clean();

    $navig_form .= '</form>';
} else {
    $navig_form = '&nbsp;';
}

$xoopsTpl->assign('barre_navig', $navig_form);

$temcount = 1;
$start = 0;
if (!empty($_GET['start'])) {
    $start = $_GET['start'];
}
$nombre = $xoopsDB->query('SELECT COUNT(id) FROM ' . $xoopsDB->prefix('magalerie_cat') . " WHERE cid = '$uid_form' $perm ");
$row = $xoopsDB->fetchRow($nombre);
$message = $row[0];

if (0 == $uid_form) {
    $nombrescat = $xoopsDB->query('SELECT COUNT(id) FROM ' . $xoopsDB->prefix('magalerie_cat') . " WHERE cid != '0' $perm");

    $rowsc = $xoopsDB->fetchRow($nombrescat);

    $messagescat = $rowsc[0];

    if (empty($accept)) {
        $nbscat2 = validcat('id', 'magalerie_cat', "valid !='0'");

        $query = $xoopsDB->query('SELECT COUNT(id) FROM ' . $xoopsDB->prefix('magalerie') . " WHERE uid IN ($nbscat2'') $perm");
    } else {
        $query = $xoopsDB->query('SELECT COUNT(id) FROM ' . $xoopsDB->prefix('magalerie') . ' WHERE  valid=1 ');
    }

    $solde = $xoopsDB->fetchRow($query);
} else {
    $nbscat = cherchscat('id', 'magalerie_cat', "cid='$uid_form'");

    $query = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('magalerie') . " WHERE uid IN ($nbscat'') ");

    $rowsc = $xoopsDB->fetchRow($query);
}

if ($row[0] > 1) {
    $s1 = '' . _PLURIEL1 . '';
} else {
    $s1 = '';
}

if ($rowsc[0] > 1) {
    $s = '' . _PLURIEL1 . '';
} else {
    $s = '';
}

if (0 == $uid_form) {
    $nomb_galerie = $row[0] . ' ' . _CAT . '' . $s1;
} else {
    $nomb_galerie = $row[0] . ' ' . _SOUSCAT . '' . $s1;
}

if (0 == $uid_form && $rowsc[0] >= 1) {
    $row_cat = ", $messagescat " . _SOUSCAT . (string)$s;

    $row_img = "$solde[0] " . _MG_IMG . (string)$s;
} else {
    $row_img = "$rowsc[0] " . _MG_IMG . (string)$s;

    $row_cat = '';
}
$xoopsTpl->assign('result_gal', $nomb_galerie);
$xoopsTpl->assign('nomb_souscat', $row_cat);
$xoopsTpl->assign('nomb_img', $row_img);

$pagenav = new XoopsPageNav($message, $xoopsModuleConfig['nb'], $start, 'start', 'uid=' . $uid_form . '');

$result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('magalerie_cat') . "  WHERE cid='$uid_form' $perm ORDER BY valid limit " . (int)$start . ',' . $xoopsModuleConfig['nb']);
while (list($id, $cid, $cat, $strimg, $coment, $clic, $alea, $valid) = $xoopsDB->fetchRow($result)) {
    $ramdom = $xoopsDB->query('SELECT img FROM ' . $xoopsDB->prefix('magalerie') . " WHERE uid='$id' and valid >= '1' ORDER BY RAND()");

    $row = $xoopsDB->getRowsNum($ramdom);

    [$img] = $xoopsDB->fetchRow($ramdom);

    $path = multiname($uid_form);

    if ('' != $path) {
        $path = '' . $path . '/';
    }

    $rowimage = 'galerie/' . $path . '' . $cat . "/$img-ppm";

    $rowcat = "<b><a href=\"galerie.php?uid=$id\">$cat</a></b> ($row)";

    $coment = $myts->displayTarea($coment);

    if (1 != $alea) {
        if (file_exists((string)$strimg)) {
            $size = getimagesize((string)$strimg);

            $td = (string)$size[0];

            $monimage = "<a href=\"galerie.php?uid=$id\" title=\"" . _CAT . " $cat\"><img src=\"$strimg\" $size[3] alt=\"" . _CAT . " $cat\"></a>";
        }
    } else {
        if (file_exists('galerie/' . $path . '' . $cat . "/$img")) {
            if (file_exists((string)$rowimage)) {
                $size = getimagesize((string)$rowimage);

                $td = (string)$size[0];

                $monimage = "<a href=\"galerie.php?uid=$id\" title=\"" . _CAT . " $cat\"> <img src=\"$rowimage\" $size[3] alt=\"$rowimage\"></a>";
            } else {
                $monimage = "<a href=\"galerie.php?uid=$id\" title=\"" . _CAT . " $cat\">" . _NO_VIGNETTE . '</a>';

                $td = '140';
            }
        }
    }

    $option = $xoopsDB->query('SELECT id, cid, cat FROM ' . $xoopsDB->prefix('magalerie_cat') . " WHERE cid='$id' $perm ");

    $nmbcat = $xoopsDB->getRowsNum($option);

    if ($nmbcat > 1) {
        $souscatlink = '';

        $souscatindex = "<a href=\"index.php?uid=$id\">(index)</a>";
    } else {
        $souscatindex = '';

        $souscatlink = '';
    }

    if ($nmbcat > 3) {
        $souscatlink = "$nmbcat " . _SOUSCAT . '' . $s . '';

        $souscatindex = "<a href=\"index.php?uid=$id\">(index)</a>";

        $marquee = '<Marquee Behavior="Scroll" Direction="left" Height="20" ScrollAmount="2" ScrollDelay="100" onMouseOver="this.stop()" onMouseOut="this.start()">';

        $mfin = '</marquee>';
    } else {
        $marquee = '';

        $mfin = '';
    }

    $souscat = (string)$marquee;

    while (false !== ($ligne = $GLOBALS['xoopsDB']->fetchObject($option))) {
        $catid = $ligne->id;

        $pathrep = $ligne->cid;

        $souscatname = $ligne->cat;

        $nbcat = $xoopsDB->query('SELECT COUNT(id) FROM ' . $xoopsDB->prefix('magalerie') . " WHERE uid = '$catid' and valid=1 ");

        $rowscat = $xoopsDB->fetchRow($nbcat);

        $tscat = $rowscat[0];

        $souscat .= "<a href=\"galerie.php?uid=$catid&rep=$pathrep\">$souscatname</a> ($tscat) &nbsp;";
    }

    $souscat .= (string)$mfin;

    $colonne = $xoopsModuleConfig['themepage'];

    $xoopsTpl->assign('colonne', $colonne);

    $temcount2 = $temcount;

    if ($temcount == $xoopsModuleConfig['themepage']) {
        $temcount -= $xoopsModuleConfig['themepage'];
    }

    $temcount++;

    //$fortune=exec("/usr/games/fortune fr");

    //$fortune = htmlspecialchars($fortune);

    $message = htmlspecialchars(exec('/usr/games/fortune fr'), ENT_QUOTES | ENT_HTML5);

    $xoopsTpl->append('file', ['cat' => $rowcat, 'td' => $td, 'image' => $monimage, 'descript' => $coment, 'themecount' => $temcount2, 'souscat' => $souscat, 'nombsouscat' => $souscatlink, 'souscatindex' => $souscatindex]);
}

//*******Pagination**********
if ($message > $xoopsModuleConfig['nb']) {
    $pagenav = $pagenav->renderNav();

    $xoopsTpl->assign('pagenav', $pagenav);
}

if (file_exists('galerie_footer.php')) {
    //include "galerie_footer.php";
}

include 'galerie_footer.php';
include '../../footer.php';
