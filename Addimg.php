<?php

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

if (0 == $xoopsModuleConfig['validup']) {
    redirect_header('index.php', 1, '' . _NOPERM . '');

    exit();
}

if (!$xoopsUser) {
    if ('0' == $xoopsModuleConfig['anonymes']) {
        redirect_header('index.php', 1, '' . _NOPERM . '');

        exit();
    }

    $userid = '0';
} else {
    $userid = $xoopsUser->uid();
}

if (!empty($HTTP_POST_FILES['image']['name'])) {
    $size_label = $_POST['MAX_FILE_SIZE'];

    require_once XOOPS_ROOT_PATH . '/modules/magalerie/class/fileupload.php';

    $upload = new fileupload();

    $upload->set_upload_dir(XOOPS_ROOT_PATH . "/modules/magalerie/galerie/$cat/", 'image');

    $upload->set_accepted('gif|jpg|png', 'image');

    $upload->set_max_image_height(0, 'image');

    $upload->set_max_image_width(0, 'image');

    $upload->set_overwrite(1, 'image');

    $result = $upload->upload();

    $newname = $result['image']['filename'];

    if (empty($result)) {
        include '../../header.php';

        $upload->errors(1);

        echo '<br><input type="button" value="' . _PREC . '" onclick="history.go(-1)"></div>';

        include '../../footer.php';
    } else {
        $rep = XOOPS_ROOT_PATH . "/modules/magalerie/galerie/$cat/";

        if (0 == $xoopsModuleConfig['funcvignette']) {
            echo '';
        }

        if (1 == $xoopsModuleConfig['funcvignette']) {
            if (!eregi('.gif', $string)) {
                vignette($newname, 75, $xoopsModuleConfig['hauteur'], $xoopsModuleConfig['largeur'], $rep, $rep, '-ppm');
            }
        }

        if (2 == $xoopsModuleConfig['funcvignette']) {
            vignette_magick($xoopsModuleConfig['exec'], $newname, $xoopsModuleConfig['largeur'], $xoopsModuleConfig['hauteur'], $rep);
        }

        if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
            $confirmup = 1;
        } else {
            $confirmup = $xoopsModuleConfig['confirmup'];
        }

        $date = time();

        if (empty($descript)) {
            $q = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('magalerie') . " (uid, userid, img, valid, date) values ( '$uid', '$userid', '$newname', '$confirmup', '$date')");

            $qr = $xoopsDB->query('SELECT id FROM ' . $xoopsDB->prefix('magalerie') . " WHERE uid ='$uid' AND  img = '$newname'");

            [$newid] = $xoopsDB->fetchRow($qr);
        } else {
            $myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object

            $descript = $myts->addSlashes($descript);

            $q = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('magalerie') . " (uid, userid, img, valid, date, description) values ( '$uid', '$userid', '$newname', '$confirmup', '$date', '$descript')");

            $qr = $xoopsDB->query('SELECT id FROM ' . $xoopsDB->prefix('magalerie') . " WHERE uid ='$uid' AND  img = '$newname'");

            [$newid] = $xoopsDB->fetchRow($qr);
        }

        if (1 == $xoopsModuleConfig['sendmail']) {
            if (!$xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
                $subject = '' . $xoopsConfig['sitename'] . ' ' . _MODULE_NAME . '';

                $xoopsMailer = getMailer();

                $xoopsMailer->useMail();

                $xoopsMailer->setToEmails($xoopsConfig['adminmail']);

                $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);

                $xoopsMailer->setFromName('' . $xoopsConfig['sitename'] . ' ' . _MODULE_NAME . '');

                $xoopsMailer->setSubject($subject);

                if (0 == $xoopsModuleConfig['confirmup']) {
                    $xoopsMailer->setBody(_NEWIMAGE . "\n\n" . XOOPS_URL . '/modules/magalerie/admin/index.php');
                } else {
                    $xoopsMailer->setBody(_NEWIMAGE_ADDED . "\n\n" . XOOPS_URL . "/modules/magalerie/show.php?id=$newid");
                }

                $xoopsMailer->send();
            }
        }

        include '../../header.php';

        if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
            redirect_header(XOOPS_URL . "/modules/magalerie/formulaire.php?uid=$uid", 0, 'OK');

            exit();
        }

        echo "<table cellpadding=\"6\"><tr><td><img src=\"galerie/$cat/$newname-ppm\"></td>\n" . '<td>';

        if (0 == $xoopsModuleConfig['confirmup']) {
            echo '' . _REVALID . '';
        } else {
            echo '' . _OK_REVALID . '';

            echo '<div align="center"><br><a href="' . XOOPS_URL . "/modules/magalerie/show.php?id=$newid\">" . XOOPS_URL . "/modules/magalerie/show.php?id=$newid</a></div>";
        }

        echo "<br><div align=\"right\"><a href=\"index.php\">Retour galerie</a></div></td></tr></table>\n";

        include '../../footer.php';
    }
}
