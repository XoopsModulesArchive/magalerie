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

$op = 'list';

global $xoopsModuleConfig;

if (!empty($_GET['op'])) {
    $op = $_GET['op'];
}

if ('list' == $op) {
    xoops_cp_header();

    OpenTable();

    echo '<a href="index.php"><b>Admin index</b></a><br><br>';

    if (!is_writable(XOOPS_ROOT_PATH . '/modules/magalerie/galerie')) {
        echo " <h3 style='color:#ff0000;'>" . sprintf(_WARNINNOTWRITEABLE . ' ' . XOOPS_ROOT_PATH . '/modules/magalerie/galerie ' . _2WARNINNOTWRITEABLE . '') . '</h3>';
    }

    echo '<div align="center"><b>' . _MODULE . '</b></div><br><br>';

    echo '<form method="post" action="charge.php?op=checkrep">' . '' . _NEWREP . ' <input type="submit" value="' . _ADD . '">' . '</form>';

    echo '<br>';

    list_souscat('charge.php?op=souscat', '----');

    echo '<form method="post" action="charge.php?op=chimg">';

    echo '<br>' . _NEWIMAGE . ' &nbsp;';

    $mytree = new XoopsTree($xoopsDB->prefix('magalerie_cat'), 'id', 'cid');

    $mytree->makeMySelBox('cat', 'cat', 0, 1, '', 'submit()');

    echo '</form>';

    echo '<br>';

    CloseTable();

    xoops_cp_footer();
}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
if ('checkrep' == $op) {
    xoops_cp_header();

    OpenTable();

    echo "<div align=\"center\"><br><a href='charge.php'><b>Retour</b></a><br><hr></div>";

    $path = XOOPS_ROOT_PATH . '/modules/magalerie/galerie';

    $rep = "$path/";

    if (!is_writable((string)$path)) {
        echo " <h3 style='color:#ff0000;'>" . sprintf(_WARNINNOTWRITEABLE . ' CHMOD 0777 <br>' . XOOPS_ROOT_PATH . '/modules/magalerie/galerie') . '</h3>';

        exit;
    }

    $dir = opendir($rep);

    while ($f = readdir($dir)) {
        if (is_dir($rep . $f)) {
            $requete = 'select cat from ' . $xoopsDB->prefix('magalerie_cat') . " where cat='$f'";

            $result = $xoopsDB->query($requete);

            while (list($img) = $xoopsDB->fetchRow($result)) {
                $f = retour($img);
            }

            if ('1' != $f && '.' != $f && '..' != $f) {
                $string = removeaccents($f);

                $string = removeSpace($string, $f, $rep);

                $insert = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('magalerie_cat') . " SET cat='$string', img='', coment='', clic='0', alea='1', valid='1'");

                echo "<b>$string</b> " . _REPOK . "<br> $insert[cat]";
            }
        }
    }

    closedir($dir);

    if (empty($insert)) {
        echo '' . _NOOPREP . '';
    }

    CloseTable();

    xoops_cp_footer();
}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
if ('souscat' == $op) {
    xoops_cp_header();

    OpenTable();

    $cat = multiname($uid);

    echo "<div align=\"center\"><br><a href='charge.php'><b>Retour</b></a><br><hr></div>";

    $rep2 = XOOPS_ROOT_PATH . "/modules/magalerie/galerie/$cat";

    $rep = XOOPS_ROOT_PATH . "/modules/magalerie/galerie/$cat/";

    if (!is_writable((string)$rep2)) {
        echo " <h3 style='color:#ff0000;'>" . sprintf(_WARNINNOTWRITEABLE . ' CHMOD 0777 <br>' . XOOPS_ROOT_PATH . "/modules/magalerie/galerie/$cat") . '</h3>';

        exit;
    }

    $dir = opendir($rep);

    while ($f = readdir($dir)) {
        if (is_dir($rep . $f)) {
            $requete = 'select cat from ' . $xoopsDB->prefix('magalerie_cat') . " where cat='$f'";

            $result = $xoopsDB->query($requete);

            while (list($img) = $xoopsDB->fetchRow($result)) {
                $f = retour($img);
            }

            if ('1' != $f && '.' != $f && '..' != $f) {
                $string = removeaccents($f);

                $string = removeSpace($string, $f, $rep);

                $insert = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('magalerie_cat') . " SET  cid='$uid', cat='$string', img='', coment='', clic='0', alea='1', valid='1'");

                echo "<b>$string</b> " . _REPOK . "<br> $insert[cat]";
            }
        }
    }

    closedir($dir);

    if (empty($insert)) {
        echo '' . _NOOPREP . '';
    }

    CloseTable();

    xoops_cp_footer();
}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
if ('chimg' == $op) {
    xoops_cp_header();

    OpenTable();

    $configHandler = xoops_getHandler('config');

    $xoopsModuleConfig = &$configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    $hauteur = $xoopsModuleConfig['hauteur'];

    $largeur = $xoopsModuleConfig['largeur'];

    $exec = $xoopsModuleConfig['exec'];

    $cat = checkname($id);

    $ramdom = $xoopsDB->query('SELECT cid FROM ' . $xoopsDB->prefix('magalerie_cat') . " WHERE cat='$cat' ");

    $row = $xoopsDB->getRowsNum($ramdom);

    [$cid] = $xoopsDB->fetchRow($ramdom);

    if ('0' !== $cid) {
        $cidname = checkrename($cid);

        $rep = XOOPS_ROOT_PATH . "/modules/magalerie/galerie/$cidname/$cat/";

        $chmod = XOOPS_ROOT_PATH . "/modules/magalerie/galerie/$cidname/$cat";
    } else {
        $cid = '0';

        $rep = XOOPS_ROOT_PATH . "/modules/magalerie/galerie/$cat/";
    }

    echo "<div align=\"center\"><br><a href='charge.php'><b>Retour</b></a><br><hr></div>";

    if ($xoopsUser) {
        $uid = $xoopsUser->uid();
    } else {
        $uid = '0';
    }

    $dir = opendir($rep);

    $i = 0;

    while ($f = readdir($dir)) {
        if (is_file($rep . $f)) {
            $sub = mb_substr(mb_strrchr((string)$f, '.'), 4);

            $extension = mb_substr(mb_strrchr((string)$f, '.'), 1);

            if (!$sub) {
                $requete = 'select img from ' . $xoopsDB->prefix('magalerie') . " WHERE img='$f' and uid=$id";

                $result = $xoopsDB->query($requete);

                $nb = $xoopsDB->getRowsNum($result);

                while (list($img) = $xoopsDB->fetchRow($result)) {
                    $f = retour($img);
                }

                if ('1' != $f) {
                    $string = removeaccents($f);

                    $string = removeSpace($string, $f, $rep);

                    $date = time();

                    $insert = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('magalerie') . " SET uid='$id', userid='1', img='$string', vote='0', valid='1', date='$date' ") or $eh::show('0013');

                    echo "<b>$string</b> " . _YESIMG . '<br> ';

                    if ($insert) {
                        if (0 == $xoopsModuleConfig['funcvignette']) {
                            echo '';
                        }

                        if (1 == $xoopsModuleConfig['funcvignette']) {
                            if (!eregi('.gif', $string)) {
                                vignette($string, 75, $hauteur, $largeur, $rep, $rep, '-ppm');
                            }
                        }

                        if (2 == $xoopsModuleConfig['funcvignette']) {
                            vignette_magick($exec, $string, $largeur, $hauteur, $rep);
                        }
                    }
                }

                $i++;
            }
        }//fin while
    }

    closedir($dir);

    if (empty($insert)) {
        echo '' . _NOIMG . '';
    }

    CloseTable();

    xoops_cp_footer();
}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
