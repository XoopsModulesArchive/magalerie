<?php

//id:magalerie/galerie.php
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

$GLOBALS['xoopsOption']['template_main'] = 'magalerie_galerie.html';

include '../../header.php';
require XOOPS_ROOT_PATH . '/modules/magalerie/class/xoopspagenav.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';

//OpenTable();
$accept = accept();
if (empty($accept)) {
    $r = $xoopsDB->query('select valid from ' . $xoopsDB->prefix('magalerie_cat') . " WHERE id = '$uid' ");

    [$auto_view] = $xoopsDB->fetchRow($r);

    if (1 != $auto_view) {
        redirect_header(XOOPS_URL . '/index.php', 3, _SORRY);

        exit();
    }
}

$this_date = time();
$this_date = formatTimestamp($this_date, 'ms');
$setclic = $GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('magalerie_cat') . " SET clic=clic+1 WHERE id='$uid' ");
$catname = multiname($uid);

[$pathimg, $souscat_id] = linkretour($uid);
$title = '<a href="index.php"><b>' . _MA_TITRE . '</b></a>';
if (!empty($pathimg)) {
    $title .= "&nbsp;<span style=\"font-weight:bold;\">&raquo;</span>&nbsp;<a href=\"index.php?uid=$souscat_id\"><b>$pathimg</b></a>";
}
$title .= '&nbsp;<span style="font-weight:bold;">&raquo;</span>&nbsp;<b><font size="+1">' . $catname . '</font></b>';

$xoopsTpl->assign('barre_titre', $title);
$mytree = new XoopsTree($xoopsDB->prefix('magalerie_cat'), 'id', 'cid');
$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object

if ('1' == $xoopsModuleConfig['listcat']) {
    $navig_form = '<form method="post" action="galerie.php">';

    $navig_form .= '<b>' . _NAVIGATION . '</b>: ';

    ob_start();

    $mytree->makeMySelBox('cat', 'id', 0, 1, 'uid', 'submit()');

    $navig_form .= ob_get_contents();

    ob_end_clean();

    $navig_form .= '</form>';
} else {
    $navig_form .= '&nbsp;';
}

$xoopsTpl->assign('barre_navig', $navig_form);
$temcount = 1;
//Récupération du nombre des données
$nombre = $xoopsDB->query('SELECT COUNT(*) as img FROM ' . $xoopsDB->prefix('magalerie') . " WHERE uid='$uid' and valid='1'");
$row = $xoopsDB->fetchRow($nombre);
$message = $row[0];

//ordre de classement par defaut
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

$result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('magalerie') . " WHERE uid='$uid' and valid='1' ORDER BY $mysqlorderby limit " . (int)$start . ',' . $xoopsModuleConfig['nb']);
$pagenav = new XoopsPageNav($message, $xoopsModuleConfig['nb'], $start, 'start', 'uid=' . $uid . '&orderby=' . $orderby . '');

if ($row[0] > 1) {
    $s = '' . _PLURIEL1 . '';

    $ent = '' . _PLURIEL2 . '';

    $x = '' . _PLURIEL3 . '';
} else {
    $s = '';

    $ent = '';

    $x = '';
}

if ('' == $pathimg) {
    $nomdecat = _CAT;
} else {
    $nomdecat = _SOUSCAT;
}

$row_cat = '' . $row[0] . ' ' . _MG_IMG . '' . $s . '';
$xoopsTpl->assign('nomb_souscat', $row_cat);

//OPTION LOCALE je laisse ce lien désactivé (très gourmand en ressource) si vous souhaitez l'utiliser en local décommenté la ligne.
//Le diaporama est réglé sur 3 secondes (j'insiste c'est une option locale) pour le régler editer le fichier diapo.php et rechercher: header("Refresh: 3;) a la ligne 39 env.
//echo "<br>[<a href='javascript:openWithSelfMain(\"diapo.php?uid=".$uid."&id=".$row[0]."\",\"diapo\",780,580);' title='Diaporama de la catégorie $cat'>"._MORE."</a>]";

if ('1' == $xoopsModuleConfig['validup']) {
    if ($xoopsUser) {
        $up_link = "&nbsp;<b>(<a href=\"formulaire.php?uid=$uid\">" . _ADDIMG . '</a>)</b>';
    } else {
        if ('1' == $xoopsModuleConfig['anonymes']) {
            $up_link = "&nbsp;&nbsp;(<a href=\"formulaire.php?uid=$uid\">" . _ADDIMG . '</a>)';
        }
    }

    $xoopsTpl->assign('ajoutimage', $up_link);
}

$barre_order = '' . _MD_SORTBY . '
' . _MD_DATE . " (<a href='galerie.php?uid=$uid&orderby=dateA'><img src=\"images/up.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a><a href='galerie.php?uid=$uid&orderby=dateD'><img src=\"images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)
" . _MD_RATING . " (<a href='galerie.php?uid=$uid&orderby=noteA'><img src=\"images/up.gif\" width=\"11\" height=\"14\"  border=\"0\" align=\"middle\"></a><a href='galerie.php?uid=$uid&orderby=noteD'><img src=\"images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)
" . _MD_POPULARITY . " (<a href='galerie.php?uid=$uid&orderby=hitsA'><img src=\"images/up.gif\" width=\"11\" height=\"14\"  border=\"0\" align=\"middle\"></a><a href='galerie.php?uid=$uid&orderby=hitsD'><img src=\"images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)";

$xoopsTpl->assign('barre_order', $barre_order);
$xoopsTpl->assign('lang_ordre', sprintf(_MD_CURSORTEDBY, $orderbyTrans));
while (false !== ($ligne = $GLOBALS['xoopsDB']->fetchObject($result))) {
    $imgid = $ligne->id;

    $userid = $ligne->userid;

    $img = $ligne->img;

    $note = $ligne->note;

    $vote = $ligne->vote;

    $total = $ligne->comments;

    $descript = $myts->displayTarea($ligne->description);

    $date = formatTimestamp($ligne->date, 's');

    $count = 7;

    $startdate = (time() - (86400 * $count));

    if ($startdate < $ligne->date) {
        $nouveau = '&nbsp;<img src="' . XOOPS_URL . '/modules/magalerie/images/new.gif" whidth="28" height="11" alt="' . _MD_NEWTHISWEEK . '">';
    } else {
        $nouveau = '';
    }

    if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
        $editer = '<a href="' . XOOPS_URL . "/modules/magalerie/admin/edit.php?gop=showedit&id=$imgid&uid=\" title=\"\"><img src=\"" . XOOPS_URL . '/images/icons/edit.gif" alt="edit.gif"></a>';
    } else {
        $editer = '';
    }

    if ($ligne->clic > 1) {
        $s1 = '' . _PLURIEL1 . '';
    } else {
        $s1 = '';
    }

    if ($ligne->vote > 1) {
        $s2 = '' . _PLURIEL1 . '';
    } else {
        $s2 = '';
    }

    $added = '<b>' . _ADDED . "</b> $date";

    //------------PROPOSE PAR-------------------------------

    if (0 != $userid) {
        $postimg = new XoopsUser($userid);

        $postername = '<b>Par:</b><a href="../../userinfo.php?uid=' . $postimg->uid() . '"> ' . $postimg->uname() . '</a>';
    } else {
        $postername = (string)$xoopsConfig[anonymous];
    }

    if ('' != $pathimg) {
        $pathimg .= '/';
    }

    $image = 'galerie/' . $pathimg . '' . $catname . '/' . $img . '-ppm';

    if (file_exists('galerie/' . $pathimg . '' . $catname . '/' . $img . '')) {
        if (!file_exists((string)$image)) {
            $monimage = "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . '">' . _NO_VIGNETTE . '</a>';
        } else {
            $size = getimagesize((string)$image);

            $monimage = "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . "\"><img src=\"$image\" $size[3] alt=\"$img\"></a>";
        }
    }

    $nombclic = '' . $ligne->clic . ' ' . _CLICS . '' . $s1 . '';

    if (0 != $vote) {
        $diff = floor(($note / $vote));

        if ($diff > 2) {
            $rank_img = 'rank3dbf8e9e7d88d.gif';
        }

        if ($diff > 4) {
            $rank_img = 'rank3dbf8ea81e642.gif';
        }

        if ($diff > 6) {
            $rank_img = 'rank3dbf8eb1a72e7.gif';
        }

        if ($diff > 8) {
            $rank_img = 'rank3dbf8edf15093.gif';
        }

        if ($diff > 9) {
            $rank_img = 'rank3dbf8ee8681cd.gif';
        }

        $rankimg = "<img src=\"images/$rank_img\" whith=\"73\" height=\"16\" title=\"$note " . _POINTS . '">';
    } else {
        $rankimg = "<img src=\"images/rank3dbf8e94a6f70.gif\" whith=\"73\" height=\"16\" title=\"$note " . _POINTS . '">';
    }

    $colonne = $xoopsModuleConfig['themepage'];

    $xoopsTpl->assign('colonne', $colonne);

    $temcount2 = $temcount;

    if ($temcount == $xoopsModuleConfig['themepage']) {
        $temcount -= $xoopsModuleConfig['themepage'];
    }

    $temcount++;

    if ($total > 1) {
        $total_com = "$total " . _COMMENTAIRE . '' . $s . '';
    } else {
        $total_com = "$total " . _COMMENTAIRE . '';
    }

    $xoopsTpl->append('file', ['themecount' => $temcount2, 'image' => $monimage, 'date' => $added, 'if_new' => $nouveau, 'imgrank' => $rankimg, 'postername' => $postername, 'nomb_clic' => $nombclic, 'comments' => $total_com, 'description' => $descript, 'editer' => $editer]);
}

if ($message > $xoopsModuleConfig['nb']) {
    $xoopsTpl->assign('pagenav', $pagenav->renderNav());
}

include 'galerie_footer.php';
include '../../footer.php';
