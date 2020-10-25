<?php

//Les scripts a bidou
include 'admin_header.php';
xoops_cp_header();
OpenTable();
include 'admin-menu.php';

if (!is_writable(XOOPS_ROOT_PATH . '/modules/magalerie/galerie')) {
    echo " <h3 style='color:#ff0000;'>" . sprintf(_WARNINNOTWRITEABLE, XOOPS_ROOT_PATH . '/modules/magalerie/galerie') . '</h3>';
}

function checkrep()
{
    global $xoopsDB, $coment, $largeur, $hauteur;

    $rep = XOOPS_ROOT_PATH . '/modules/magalerie/galerie/';

    if (!is_writable((string)$rep)) {
        echo " <h3 style='color:#ff0000;'>" . sprintf(_WARNINNOTWRITEABLE, XOOPS_ROOT_PATH . '/modules/magalerie/galerie') . '</h3>';
    }

    $dir = opendir($rep);

    while ($f = readdir($dir)) {
        if (is_dir($rep . $f)) {
            if ('.' != $f) {
                $sub;
            }

            if ('..' != $f) {
                $sub;
            }

            if (!$sub) {
                $requete = 'select cat from ' . $xoopsDB->prefix('magalerie_cat') . " where cat='$f'";

                $result = $xoopsDB->query($requete);

                while (list($img) = $xoopsDB->fetchRow($result)) {
                    $f = retour($img);
                }
            }

            if ('1' != $f && '.' != $f && '..' != $f) {
                $insert = $GLOBALS['xoopsDB']->queryF('INSERT INTO ' . $xoopsDB->prefix('magalerie_cat') . " SET cat='$f', img='$img', coment='$coment', clic='0', alea='1', valid='1'");

                echo "<b>$f</b> " . _REPOK . '<br> ';
            }
        }
    }

    if (!$insert) {
        echo '' . _NOOPREP . '';
    }

    closedir($dir);
}//fin checkrep

echo '<br>';
echo '<table cellspacing="2" cellpadding="6" border="0"><tr>
<td>' . _NEWREP . '</td>';
echo '<td>' . _NEWIMAGE . '</td>';
echo '</tr><tr>';
echo "<td align=\"center\"><form method=\"post\" action=\"$PHP_SELF\">" . '<input type="hidden" name="op" value="checkrep">' . '<input type="submit" value="' . _ADD . '">' . '</form></td><td align="center">';
list_cat('charge.php?op=chimg');
echo '</td></tr></table>';
echo '<br>';

function chimg()
{
    global $xoopsDB, $xoopsUser, $showcoment, $cat, $f, $hauteur, $largeur;

    if ($xoopsUser) {
        $uid = $xoopsUser->uid();
    } else {
        $uid = '0';
    }

    $rep = XOOPS_ROOT_PATH . "/modules/magalerie/galerie/$cat/";

    if (!is_writable((string)$rep)) {
        echo " <h3 style='color:#ff0000;'>" . sprintf(_WARNINNOTWRITEABLE . ' ' . $rep . '' . _2WARNINNOTWRITEABLE . '') . '</h3>';
    }

    $dir = opendir($rep);

    $i = 0;

    while ($f = readdir($dir)) {
        if (is_file($rep . $f)) {
            $sub = mb_substr(mb_strrchr((string)$f, '.'), 4);

            $extension = mb_substr(mb_strrchr((string)$f, '.'), 1);

            if (!$sub) {
                $requete = 'select img from ' . $xoopsDB->prefix('magalerie') . " WHERE img='$f' ";

                $result = $xoopsDB->query($requete);

                $nb = $xoopsDB->getRowsNum($result);

                while (list($img) = $xoopsDB->fetchRow($result)) {
                    $f = retour($img);
                }

                if ('1' != $f) {
                    $date = time();

                    $insert = $GLOBALS['xoopsDB']->queryF('INSERT INTO ' . $xoopsDB->prefix('magalerie') . " SET uid='$uid', cat='$cat', img='$f', vote='0', valid='1', date='$date' ") or die('' . _AD_MA_EDITIMGTXT1 . " $img " . _AD_MA_EDITERRORTXT1 . '<br>' . $GLOBALS['xoopsDB']->errno() . ': ' . $GLOBALS['xoopsDB']->error());

                    echo "<b>$f</b> " . _YESIMG . '<br> ';

                    if ($insert) {
                        vignette($f, 75, $hauteur, $largeur, $rep, $rep, '-ppm');
                    }
                }

                $i++;
            }
        }//fin while
    }

    closedir($dir);

    if (!$insert) {
        echo '' . _NOIMG . '';
    }
}//fin

switch ($op) {
    case 'chimg':
        chimg();
        break;
    case 'checkrep':
        checkrep();
        break;
}

CloseTable();
xoops_cp_footer();
include '../footer.php';
