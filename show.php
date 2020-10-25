<?php

//id:magalerie/show.php
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

include '../../header.php';

$GLOBALS['xoopsOption']['template_main'] = 'magalerie_show.html';

$accept = accept();

$id = 0;
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
}
$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
$setclic = $GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('magalerie') . " SET clic = clic+1 WHERE id= $id ") or die('mes couilles');
$requete = $xoopsDB->query('select * from ' . $xoopsDB->prefix('magalerie') . " WHERE id = '$id' ");
while (false !== ($ligne = $GLOBALS['xoopsDB']->fetchObject($requete))) {
    //$img_id = $ligne->id;

    $uid = $ligne->uid;

    $userid = $ligne->userid;

    $img = $ligne->img;

    $clic = $ligne->clic;

    $note = $ligne->note;

    $vote = $ligne->vote;

    $row = $ligne->comments;

    $descript = $myts->displayTarea($ligne->description);

    if ('' == $descript) {
        $descript = '' . _NO_DESCRIPT . '';
    }

    $count = 7;

    $startdate = (time() - (86400 * $count));

    if (empty($accept)) {
        $r = $xoopsDB->query('select valid from ' . $xoopsDB->prefix('magalerie_cat') . " WHERE id = '$uid' ");

        [$auto_view] = $xoopsDB->fetchRow($r);

        if (1 != $auto_view) {
            redirect_header(XOOPS_URL . '/index.php', 3, _SORRY);

            exit();
        }
    }

    if ($startdate < $ligne->date) {
        if (1 == $ligne->valid) {
            $nouveau = '&nbsp;<img src="' . XOOPS_URL . '/modules/magalerie/images/new.gif" title="' . _MD_NEWTHISWEEK . '">';
        }
    } else {
        $nouveau = '';
    }

    $cat = multiname($uid);

    //$pathimg=linkretour($uid);

    [$pathimg, $souscat_id] = linkretour($uid);

    if ('' != $pathimg) {
        $pathimg .= '/';
    }

    $image = 'galerie/' . $pathimg . '' . $cat . '/' . $img . '';

    $xoopsTpl->assign('test', $image);

    $date = formatTimestamp($ligne->date, 's');

    $xoopsTpl->assign('lang_index', _MA_TITRE);

    $xoopsTpl->assign('lang_titre', _MA_TITRE);

    $gal_path = "<b><a href=\"galerie.php?uid=$uid\"> " . $pathimg . '' . $cat . '</a></b>';

    $xoopsTpl->assign('retour_galerie', $gal_path);

    include 'navig_cat_show.php';

    $showmax = $xoopsModuleConfig['showmax'];

    if (file_exists((string)$image)) {
        $size = getimagesize($image);

        $image = resize((string)$image, (string)$showmax);

        $file = "<img src='$image' height='$sm_hauteur' width='$sm_largeur' border='0'>";

        $xoopsTpl->assign('image_file', $file);
    }

    include 'navig_cat_show.php';

    //------------PROPOSE PAR-------------------------------

    if ($userid > 0) {
        $postimg = new XoopsUser($userid);

        $postername = '<a href="../../userinfo.php?uid=' . $postimg->uid() . '"> ' . $postimg->uname() . '</a>';
    } else {
        $postername = (string)$xoopsConfig[anonymous];
    }

    //-----------------------------------------------------------------

    $xoopsTpl->assign('lang_mg_poster', _MGPOSTER);

    $xoopsTpl->assign('lien_poster', $postername);

    $xoopsTpl->assign('description', $descript);

    $xoopsTpl->assign('nom_image', _MG_IMG);

    $nameof = " $img $nouveau";

    $xoopsTpl->assign('nameof_image', $nameof);

    $xoopsTpl->assign('lang_nb_clics', _CLICS);

    if ($clic >= 2) {
        $xoopsTpl->assign('lang_plus', _PLURIEL1);
    } else {
        $xoopsTpl->assign('lang_plus', '');
    }

    $xoopsTpl->assign('nb_clics', $clic);

    if ($clic >= 50) {
        $img_pop = '&nbsp;<img src ="' . XOOPS_URL . '/modules/mydownloads/images/pop.gif">';

        $xoopsTpl->assign('populaire', $img_pop);
    } else {
        $xoopsTpl->assign('populaire', '');
    }

    $xoopsTpl->assign('lang_format', _FORMAT);

    $format = "$size[0] x $size[1]";

    $xoopsTpl->assign('size_format', $format);

    $xoopsTpl->assign('lang_rate', _RATE);

    if ($vote >= 2) {
        $xoopsTpl->assign('plus_rate', _PLURIEL1);
    } else {
        $xoopsTpl->assign('plus_rate', '');
    }

    $xoopsTpl->assign('nb_rate', $vote);

    if (0 != $vote) {
        $diff = floor(($note / $vote));

        if ($diff > 0) {
            $rank_img = 'rank3dbf8e94a6f70.gif';
        }

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
    }

    //$rank_size = getimagesize("$rank_img");

    if (0 != $vote) {
        $image_rank = "&nbsp;<img src=\"images/$rank_img\" whith=\"73\" height=\"13\" title=\"$note " . _POINTS . '"></td>';

        $xoopsTpl->assign('img_rank', $image_rank);
    } else {
        $image_rank = '&nbsp;<img src="images/rank3e632f95e81ca.gif" whith="73" height="13">';

        $xoopsTpl->assign('img_rank', $image_rank);
    }

    $xoopsTpl->assign('lang_cat', _CAT);

    $rootcat = '' . $pathimg . (string)$cat;

    $xoopsTpl->assign('path_cat', $rootcat);

    if (0 != $xoopsModuleConfig['popup']) {
        $popupico = "<a href='javascript:openWithSelfMain(\"show-pop.php?id=$id\",\"popup\",$size[0],$size[1])'><img src=\"images/print.gif\" width=\"14\" height=\"11\" title=\"" . _IMPRIM . '"></a>&nbsp;&nbsp;&nbsp;';

        $xoopsTpl->assign('popup_ico', $popupico);
    }

    if (0 != $xoopsModuleConfig['ecard']) {
        $ecardico = "<a href=\"carte.php?id=$id\"><img src=\"images/friend.gif\" width=\"14\" height=\"11\" title=\"" . _ENVOY . '"></a>';

        $xoopsTpl->assign('ecard_ico', $ecardico);
    }
}
$from_vote = '<form method="post" action="vote.php">';
$from_vote .= "<input type=\"hidden\" name=\"id\" value=\"$id\">";
$from_vote .= '<input type="hidden" name="cat" value="' . $pathimg . "$cat\">";
$from_vote .= '<br><b>'
              . _VOTE
              . "</b> <select name=\"note\" onChange='submit()'>"
              . '<option selected>--</option>'
              . '<option value="1">1</option>'
              . '<option value="2">2</option>'
              . '<option value="3">3</option>'
              . '<option value="4">4</option>'
              . '<option value="5">5</option>'
              . '<option value="6">6</option>'
              . '<option value="7">7</option>'
              . "<option value=\"8\">8</option>\n"
              . "<option value=\"9\">9</option>\n"
              . "<option value=\"10\">10</option>\n"
              . "</select></form>\n";

if ($row > 1) {
    $s = '' . _PLURIEL1 . '';
} else {
    $s = '';
}

$commentaire = '<br><b>' . _COMMENTAIRE . '' . $s . ": $row " . _ENTRE . '' . $s . "</b>\n";

$xoopsTpl->assign('vote_from', $from_vote);
$xoopsTpl->assign('nb_comment', $commentaire);
require XOOPS_ROOT_PATH . '/include/comment_view.php';

require XOOPS_ROOT_PATH . '/footer.php';
