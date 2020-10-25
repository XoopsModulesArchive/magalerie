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
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
$gop = 'showedit_cat';

if (!empty($_GET['gop'])) {
    $gop = $_GET['gop'];
}

if (!empty($_POST['gop'])) {
    $gop = $_POST['gop'];
}

$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object

/////////////  SHOWEDIT //////////////////
if ('showedit' == $gop) {
    xoops_cp_header();

    OpenTable();

    echo '<a href="index.php"><b>Admin index</b></a>';

    if (!empty($uid)) {
        echo " , <a href=\"listing.php?uid=$uid\"><b>Retour</b></a><br><br>";
    } else {
        echo '<br><br>';
    }

    echo '<table width="100%" border="0"><tr><td width="70%">';

    echo '<h3>' . MG_ADMIN . '</h3></td><td>';

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

    echo '</td></tr></table>';

    $val = 'SELECT * FROM ' . $xoopsDB->prefix('magalerie') . " WHERE id='$id' ";

    $result = $xoopsDB->query($val);

    echo '<table width="100%"><tr><td>';

    echo '&nbsp;</td><td align="right">';

    echo "<form method=\"post\" action=\"listing.php?gop=transf&imgid=$id\">";

    echo '' . _MOVECAT . ' ';

    $mytree = new XoopsTree($xoopsDB->prefix('magalerie_cat'), 'id', 'cid');

    $mytree->makeMySelBox('cat', 'id', 0, 1, 'newuid', 'submit()');

    echo '</form>';

    echo '</td></tr></table>';

    while (list($id, $uid, $userid, $img, $clic, $note, $vote, $valid, $date, $comments, $descript) = $xoopsDB->fetchRow($result)) {
        $catname = multiname($uid);

        [$pathimg, $gal_valid] = link_catname($uid);

        $image = '../galerie/' . $pathimg . "/$img";

        $size = getimagesize((string)$image);

        $size_ppm = getimagesize("$image-ppm");

        $date = formatTimestamp($date, 's');

        $form_img = new XoopsThemeForm(_EDIMG . '', '', $PHP_SELF);

        $form_img->addElement(new XoopsFormHidden('gop', 'update'));

        $image = "<a href='javascript:openWithSelfMain(\"../show-pop.php?id=$id\",\"popup\", $size[0], $size[1]);'><img src=\"$image-ppm\" $size_ppm[3]></a>";

        $form_img->addElement(new XoopsFormLabel(_ID . ':&nbsp;' . $id . '<br> le: ' . $date, $image));

        $form_img->addElement(new XoopsFormHidden('id', $id));

        $form_img->addElement(new XoopsFormHidden('date', $date));

        $form_img->addElement(new XoopsFormHidden('uid', $userid));

        if (0 != $userid) {
            $poster = new XoopsUser($userid);

            $uname = '' . $poster->uname() . '';
        } else {
            $uname = (string)$xoopsConfig[anonymous];
        }

        $uname = "<a href=\"../../../userinfo.php?uid=$userid\"> $uname</a>";

        $form_img->addElement(new XoopsFormLabel(_NOM, $uname));

        if (1 == $gal_valid) {
            $voir = '<img src=../images/vert.png width="10" height="10">';
        } else {
            $voir = '<img src=../images/rouge.png width="10" height="10">';
        }

        $form_img->addElement(new XoopsFormLabel(_CAT, $pathimg . '&nbsp;&nbsp;' . $voir));

        $form_img->addElement(new XoopsFormLabel(_IMG, $img));

        $descript = $myts->addSlashes($descript);

        $form_img->addElement(new XoopsFormDhtmlTextArea(_DESCRIP, 'descript', $descript, 15, 60), true);

        $form_img->addElement(new XoopsFormText(_HITS, 'clic', 5, 5, $value = (string)$clic));

        $form_img->addElement(new XoopsFormText(_NOT, 'note', 5, 5, $note));

        $form_img->addElement(new XoopsFormText(_VOT, 'vote', 5, 5, $vote));

        $form_img->addElement(new XoopsFormLabel(_IMG_COMMENT, $comments));

        if (0 == $valid) {
            $form_img->addElement(new XoopsFormRadioYN(_VALIDEMAIL, 'validemail', 0, $yes = _YES, $no = _NO));
        }

        $form_img->addElement(new XoopsFormRadioYN(_VISIB, 'valid', $valid, $yes = _YES, $no = _NO));

        $form_img->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

        $form_img->display();
    }

    CloseTable();

    xoops_cp_footer();
}

////////////// SOWEDIT_CAT ////////////////////
if ('showedit_cat' == $gop) {
    xoops_cp_header();

    OpenTable();

    echo '<a href="index.php"><b>Admin index</b></a><br><br>';

    //echo "<p><b>"._EDITCAT."</b>&nbsp;<a href='javascript:openWithSelfMain(\"../docs/Edit_imag.html\",\"popup\", 480, 400);'>?</a></p>\n";

    $aide = "&nbsp;<a href='javascript:openWithSelfMain(\"../docs/Edit_imag.html\",\"popup\", 480, 400);'>" . _EDITCAT . ' ?</a>';

    $form_cat = new XoopsThemeForm(_EDITCAT . $aide, '', $PHP_SELF);

    echo "<form method=\"post\" action=\"$PHP_SELF\">";

    echo '<b>' . _CHOICAT . '</b>: ';

    //$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object

    $mytree = new XoopsTree($xoopsDB->prefix('magalerie_cat'), 'id', 'cid');

    $mytree->makeMySelBox('cat', 'id', 0, 1, 'id', 'submit()');

    echo '</form>';

    if (!empty($id)) {
        $catname = multiname($id);

        $requete = 'select * from ' . $xoopsDB->prefix('magalerie_cat') . " WHERE id='$id' ";

        $result = $xoopsDB->query($requete);

        $form_cat = new XoopsThemeForm(_EDITCAT, '', $PHP_SELF);

        while (list($id, $cid, $cat, $img, $coment, $clic, $alea, $valid) = $xoopsDB->fetchRow($result)) {
            $form_cat->addElement(new XoopsFormHidden('gop', 'updatecat'));

            $form_cat->addElement(new XoopsFormHidden('id', $id));

            $form_cat->addElement(new XoopsFormHidden('cid', $cid));

            $form_cat->addElement(new XoopsFormLabel('id', $id));

            if (1 == $valid) {
                $img_valid = '<img src="../images/vert.png">';
            } else {
                $img_valid = '<img src="../images/rouge.png">';
            }

            $form_cat->addElement(new XoopsFormLabel(_CAT, $cat . '&nbsp;' . $img_valid));

            $form_cat->addElement(new XoopsFormLabel(_IMG, 'img'));

            $box = new XoopsFormRadio(_IMG, 'alea', $alea);

            $box->addOption(1, '&nbsp;' . _ALEASELECT . '<br><br>');

            $input_box2 = "&nbsp;<input type=text name=\"img\" value=\"$img\"  style=\"width: 175px;\">";

            $box->addOption(0, $input_box2);

            $form_cat->addElement($box);

            $coment = htmlspecialchars((string)$coment, ENT_QUOTES | ENT_HTML5);

            $form_cat->addElement(new XoopsFormDhtmlTextArea(_DESCRIP, 'coment', $coment, 15, 60), true);

            if (0 == $cid) {
                $form_cat->addElement(new XoopsFormRadioYN(_VISIB, 'valid', $valid, $yes = _YES, $no = _NO));
            } else {
                $form_cat->addElement(new XoopsFormLabel('Public', _CAUSE_CAT));

                $form_cat->addElement(new XoopsFormHidden('valid', $valid));
            }

            $kill = "<a href=\"delete.php?op=confirm_cat&id=$id\">" . _DELETE_CAT . '</a>';

            $form_cat->addElement(new XoopsFormLabel('Destroy()', $kill));

            $form_cat->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

            $form_cat->display();
        }
    }//fin du if empty

    CloseTable();

    xoops_cp_footer();
}

////////////////////////////////////////////////////////////////////////////////////
if ('update' == $gop) {
    if (!empty($validemail)) {
        if (1 == $validemail) {
            $result = $xoopsDB->query('SELECT email FROM ' . $xoopsDB->prefix('users') . " WHERE uid='$uid' ");

            while (list($email) = $xoopsDB->fetchRow($result)) {
                if (0 != $valid) { //echo "$email";
                    $subject = $xoopsConfig['sitename'] . ' - ' . _GALIMG;
                }

                $xoopsMailer = getMailer();

                $xoopsMailer->useMail();

                $xoopsMailer->setToEmails($email);

                $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);

                $xoopsMailer->setFromName($xoopsConfig['sitename']);

                $xoopsMailer->setSubject(_NOTIFICATION);

                $xoopsMailer->setBody(_NEW_IMG_COMENT . "\n" . XOOPS_URL . "/modules/magalerie/show.php?id=$id\n\n" . _CONTEST . _IFERREUR);

                $xoopsMailer->send();
            }
        }
    }

    if (!empty($descript)) {
        $descript = $myts->addSlashes($descript);
    }

    if (!$id) {
        redirect_header($GLOBALS['HTTP_REFERER'] . "&id=$id", 3, _NOPERM);

        exit();
    } elseif (!$xoopsDB->query('UPDATE ' . $xoopsDB->prefix('magalerie') . " SET  clic='$clic', note='$note', vote='$vote', valid='$valid', description='$descript' where id ='$id'")) {
        die('update de magalerie impossible!<br>' . $xoopsDB->errno() . ': ' . $xoopsDB->error());
    }

    redirect_header('' . $PHP_SELF . "?gop=showedit&id=$id", 0, '<b> Mis a jour!</b>');

    exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//showedit_cat();
if ('updatecat' == $gop) {
    if ('' != $coment) {
        $coment_up = $myts->addSlashes($coment);
    }

    if (0 == $cid) {
        $nbscat = validcat('id', 'magalerie_cat', "cid ='$id'");

        $xoopsDB->query(' UPDATE ' . $xoopsDB->prefix('magalerie_cat') . " SET valid='$valid' WHERE id IN ($nbscat'') ") or die('update de magalerie impossible!<br>' . $xoopsDB->errno() . ' ' . $xoopsDB->error() . ' ');
    }

    if (!$id) {
    } elseif (!$xoopsDB->query('UPDATE ' . $xoopsDB->prefix('magalerie_cat') . " SET img='$img', coment='$coment_up', alea='$alea', valid='$valid' WHERE id='$id'")) {
        die('update de magalerie impossible!<br>' . $xoopsDB->errno() . ': ' . $xoopsDB->error());
    } else {
        redirect_header('edit.php?id=' . $id . '', 0, 'La base est mis a jour!');
    }

    exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ('update_descript' == $gop) {
    $orderby = $_POST['orderby'];

    $start = $_POST['start'];

    $desc = $_POST['desc'];

    $uid = $_POST['uid'];

    if (!empty($desc)) {
        $desc = $myts->addSlashes($desc);

        if (!$desc) {
        } elseif (!$xoopsDB->query('UPDATE ' . $xoopsDB->prefix('magalerie') . " SET description='$desc' WHERE id = $id ")) {
            die('update de magalerie impossible!<br>' . $xoopsDB->errno() . ': ' . $xoopsDB->error());
        } else {
            redirect_header("listing.php?uid=$uid&orderby=$orderby&start=$start", 0, 'La base est mis a jour!');
        }

        exit();
    }

    redirect_header("listing.php?uid=$uid&orderby=$orderby&start=$start", 0, '???');

    exit();
}
