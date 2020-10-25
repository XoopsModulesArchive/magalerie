<?php

//id:magalerie/show.php
##################################################################################
# Magalerie Version 1.2b                                                         #                                                                                #
# Projet du 30/05/2002		dernière modification: 25/09/2002                #
# Scripts Home:                 http://www.lespace.org                           #              							                 #                                                                              #
# auteur        :		bidou                                            #
# email         :		bidou@lespace.org                                #
# Site web	:		http://www.lespace.org                           #
# licence	:		Gpl                                              #
##################################################################################
# Merci de laisser cette entête en place.                                        #
##################################################################################
include 'header.php';
//function voirfiche(){
//global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsTheme, $id, $cat, $img, $allowbbcode, $allowhtml, $allowsmileys, $magcoment, $anonymes, $start, $ecard, $nbms, $popup;
require XOOPS_ROOT_PATH . '/header.php';
//OpenTable();
$requete = $GLOBALS['xoopsDB']->queryF('select * from ' . $xoopsDB->prefix('magalerie') . " WHERE id = '$id' ");
while (false !== ($ligne = $GLOBALS['xoopsDB']->fetchObject($requete))) {
    $img_id = $ligne->id;

    $uid = $ligne->uid;

    $img = $ligne->img;

    $cat = $ligne->cat;

    $clic = $ligne->clic;

    $row_clic = $clic += '1';

    $note = $ligne->note;

    $vote = $ligne->vote;

    $desc = $ligne->coment;

    $count = 7;

    $startdate = (time() - (86400 * $count));

    if ($startdate < $ligne->date) {
        if (1 == $ligne->valid) {
            $nouveau = '&nbsp;<img src="' . XOOPS_URL . '/modules/magalerie/images/new.gif" alt="' . _MD_NEWTHISWEEK . '">';
        }
    }

    $image = "galerie/$cat/$img";

    $date .= formatTimestamp($ligne->date, 's');

    $GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('magalerie') . " SET clic = $row_clic WHERE id='$img_id' ");
}
echo '<b><a href="index.php">' . _TITRE . ":</a><a href=\"galerie.php?cat=$cat\"> $cat</a></b>";

include 'navig_cat_show.php';
if (file_exists((string)$image)) {
    $size = getimagesize((string)$image);

    echo " <p align=\"center\"><img src=\"$image\" $size[3] border=\"0\"></p>\n";
}

include 'navig_cat_show.php';
echo '<br>';
echo '<table width="380" border="0" cellspacing="1" cellpadding="2" align="center" class="bg2">' . '<tr>' . '<td class="bg3"><b>' . _IMG . ":</b> $img $nouveau</td>";
echo '<td class="bg3" width="40%"><b>' . _CLICS . ":</b> $clic $grade";
if ($clic >= 50) {
    echo '&nbsp;<img src ="' . XOOPS_URL . '/modules/mydownloads/images/pop.gif" alt="' . _MD_POPULAR . '">';
}
echo '</td>' //	."<td rowspan=\"4\" valign=\"top\" class=\"bg3\"><center><b>"._DESCRIP.":</b></center> &nbsp;&nbsp;$desc</td>"
     . '</tr>' . '<tr>' . '<td class="bg3"><b>' . _FORMAT . ":</b> $size[0] x $size[1]</td>" . '<td class="bg3"><b>' . _RATE . ":</b> $vote ";
$diff = floor(($note / $vote));
if ($diff > 0) {
    $rank_img = 'rank3dbf8e94a6f72.gif';
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
echo "&nbsp;<img src=\"../../uploads/$rank_img\" title=\"$note " . _POINTS . '">';
echo '</td>' . '</tr>' . '<tr>' . '<td  class="bg3"><b>' . _CAT . ":</b> $cat</td>" . '<td class="bg3" align="center">';
if (0 != $popup) {
    echo "<a href='javascript:openWithSelfMain(\"show-pop.php?cat=$cat&img=$img\",\"popup\",$size[0],$size[1])'><img src=\"images/print.gif\" width=\"14\" height=\"11\" title=\"" . _IMPRIM . '"></a>&nbsp;&nbsp;&nbsp;';
}
if (0 != $ecard) {
    echo "<a href=\"carte.php?cat=$cat&id=$id\"><img src=\"images/friend.gif\" width=\"14\" height=\"11\" title=\"" . _ENVOY . '"></a>';
}
echo '</td></tr>' . '</table>';

echo '<div align="center"><form method="post" action="vote.php">';
//."<input type=\"hidden\" name=\"op\" value=\"vote\">\n";
echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
echo "<input type=\"hidden\" name=\"cat\" value=\"$cat\">";
echo "<br><b>Noter cette image:</b> <select name=\"note\" onChange='submit()'>"
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
     . "</select>\n";

$nombre = $GLOBALS['xoopsDB']->queryF('SELECT count(*) as id from ' . $xoopsDB->prefix('magalerie_comments') . " WHERE lid=$id");
$row = $GLOBALS['xoopsDB']->fetchRow($nombre);

if ('1' == $magcoment) {
    if ($xoopsUser) {
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><a href=\"commentaire.php?lid=$id\" title=\"" . _COMMAJOUT . '">' . _COMMENTAIRE . '' . $s . "</a>: $row[0] " . _ENTRE . '' . $s . "</b>\n";
    } else {
        if ('1' == $anonymes) {
            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><a href=\"commentaire.php?lid=$id\" title=\"" . _COMMAJOUT . '">' . _COMMENTAIRE . '' . $s . "</a>: $row[0] " . _ENTRE . '' . $s . "</b>\n";
        }
    }
}
echo "</form><br><br>\n";

if (0 != $magcoment && 0 != $row[0]) {
    require XOOPS_ROOT_PATH . '/modules/magalerie/class/xoopspagenav.php';

    //openThread($width="98%");

    openComment();

    $message = $row[0];

    if (!isset($start)) {
        $start = 0;
    }

    $pagenav = new XoopsPageNav($message, $nbms, $start, 'start', "id=$img_id");

    $q = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $xoopsDB->prefix('magalerie_comments') . " WHERE lid=$id limit " . (int)$start . ',' . $nbms);

    while (false !== ($ligne = $GLOBALS['xoopsDB']->fetchObject($q))) {
        if (0 != $ligne->uid) {
            $query = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $xoopsDB->prefix('users') . " where uid='$ligne->uid' ");

            while (false !== ($gal = $xoopsDB->fetchArray($query))) {
                $uname = (string)$gal[uname];
            }

            $poster = new XoopsUser($ligne->uid);

            if (!$poster->isActive()) {
                $poster = 0;
            }
        } else {
            $uname = (string)$xoopsConfig[anonymous];

            $poster = 0;
        }

        if ('' != $rank['image']) {
            $rank['image'] = "<img src='" . XOOPS_URL . '/uploads/' . $rank['image'] . "' alt=''>";
        }

        if ($poster) {
            $reg_date = _JOINED;

            $reg_date .= formatTimestamp($poster->user_regdate(), 's');

            $posts = _POSTS;

            $posts .= $poster->posts();

            $user_from = _FROM;

            $user_from = $poster->user_from();

            $rank = $poster->rank();

            if ('' != $rank['image']) {
                $rank['image'] = "<img src='" . XOOPS_URL . '/uploads/' . $rank['image'] . "' alt=''>";
            }

            $avatar_image = "<img src='" . XOOPS_URL . '/uploads/' . $poster->user_avatar() . "' alt=''>";

            if ($poster->isOnline()) {
                $online_image = "<span style='color:#ee0000;font-weight:bold;'>" . _ONLINE . '</span>';
            } else {
                $online_image = '';
            }

            $profile_image = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $poster->uid() . "'><img src='" . XOOPS_URL . "/images/icons/profile.gif' alt='" . _PROFILE . "'></a>";

            if ($xoopsUser) {
                $pm_image = "<a href=\"j$qavascript:openWithSelfMain('" . XOOPS_URL . '/pmlite.php?send2=1&amp;to_userid=' . $poster->uid() . "','pmlite',360,300);\"><img src='" . XOOPS_URL . "/images/icons/pm.gif' alt='" . sprintf(_SENDPMTO, $poster->uname()) . "'></a>";
            } else {
                $pm_image = '';
            }

            if ('' != $gal['email']) {
                $email_image = "<a href='mailto:" . $gal['email'] . "'><img src='" . XOOPS_URL . "/images/icons/email.gif' alt='" . sprintf(_SENDEMAILTO, $poster->uname()) . "'></a>";
            } else {
                $email_image = '';
            }

            $posterurl = $poster->url();

            if ('' != $poster->url()) {
                $www_image = "<a href='$posterurl' t$uidarget='_blank'><img src='" . XOOPS_URL . "/images/icons/www.gif' alt='" . _VISITWEBSITE . "' target='_blank'></a>";
            } else {
                $www_image = '';
            }

            if ('' != $poster->user_icq()) {
                $icq_image = "<a href='http://wwp.icq.com/scripts/search.dll?to=" . $poster->user_icq() . "'><img src='" . XOOPS_URL . "/images/icons/icq_add.gif' alt='" . _ADDTOLIST . "'></a>";
            } else {
                $icq_image = '';
            }

            if ('' != $poster->user_aim()) {
                $aim_image = "<a href='aim:goim?screenname=" . $poster->user_aim() . '&message=Hi+' . $poster->user_aim() . "+Are+you+there?'><img src='" . XOOPS_URL . "/images/icons/aim.gif' alt='aim'></a>";
            } else {
                $aim_image = '';
            }

            if ('' != $poster->user_yim()) {
                $yim_image = "<a href='http://edit.yahoo.com/config/send_webmesg?.target=" . $poster->user_yim() . "&.src=pg'><img src='" . XOOPS_URL . "/images/icons/yim.gif' alt='yim'></a>";
            } else {
                $yim_image = '';
            }

            if ('' != $poster->user_msnm()) {
                $msnm_image = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $poster->uid() . "'><img src='" . XOOPS_URL . "/images/icons/msnm.gif' alt='msnm'></a>";
            } else {
                $msnm_image = '';
            }
        }

        if ($xoopsUser) {
            $adminview = $xoopsUser->isAdmin();
        } else {
            $adminview = 0;
        }

        if ($adminview) {
            $delete_image = "<a href='comment2.php?op=confirm&id=" . $ligne->id . '&lid=' . $img_id . "'><img src='" . XOOPS_URL . "/images/icons/delete.gif' alt='" . _XTG_DELETEPOST . "'></a>";

            $edit_image = "<a href='admin/edit.php?gop=edit_comment&id=" . $ligne->id . '&cat=' . $cat . '&img=' . $img . "'><img src='" . XOOPS_URL . "/images/icons/edit.gif' border='0'></a>";
        }

        $myts = MyTextSanitizer::getInstance();

        $titre = stripslashes((string)$ligne->titre);

        $titre = htmlspecialchars($titre, ENT_QUOTES | ENT_HTML5);

        $texte = stripslashes((string)$ligne->texte);

        $texte = $myts->displayTarea($texte, $allowhtml, $allowsmileys, $allowbbcode);

        $date = formatTimestamp($ligne->date, 's');

        if (1 == $color_num) {
            $color_num = 2;
        } else {
            $color_num = 1;
        }

        //$texte = stripslashes("$ligne->texte");

        showComment(
            $color_num,
            $subject_image,
            $titre,
            $texte,
            $date,
            '',
            $reply_image,
            $edit_image,
            $delete_image,
            $uname,
            $rank['title'],
            $rank['image'],
            $avatar_image,
            $reg_date,
            $posts,
            $user_from,
            $online_image,
            $profile_image,
            $pm_image,
            $email_image,
            $www_image,
            $icq_image,
            $aim_image,
            $yim_image,
            $msnm_image
        );
    }

    closeComment();

    echo '<div align=center><br>' . $pagenav->renderNav() . '<div>';
}// fin du$magcoment!=0

echo '<br>';

if (file_exists('galerie_footer.php')) {
    include 'galerie_footer.php';
}

//CloseTable();
require XOOPS_ROOT_PATH . '/footer.php';
