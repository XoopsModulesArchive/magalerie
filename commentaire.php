<?php

//id:magalerie/commentaire.php
##################################################################################
# Magalerie Version 1.2b                                                         #                                                                                #
# Projet du 30/05/2002          dernière modification: 25/09/2002                #
# Scripts Home:                 http://www.lespace.org                           #                                                                                       #                                                                              #
# auteur           :            bidou                                            #
# email            :            bidou@lespace.org                                #
# Site web         :                http://www.lespace.org                           #
# licence          :            Gpl                                              #
##################################################################################
# Merci de laisser cette entête en place.                                        #
##################################################################################
include 'header.php';
if ('0' == $magcoment) {
    redirect_header('index.php', 1, '' . _NOPERM . '');

    exit();
}
if (!$xoopsUser) {
    if ('0' == $anonymes) {
        redirect_header('index.php', 1, '' . _NOPERM . '');

        exit();
    }
}

//function commentaire () {
global $xoopsConfig, $xoopsDB, $xoopsModule, $xoopsUser, $xoopsTheme, $lid, $start, $index, $allowhtml, $allowsmileys, $allowbbcode;
include '../../header.php';

if ($xoopsUser) {
    $userid = $xoopsUser->uid();
} else {
    $userid = '0';
}

$nb = 10; //nombre de messages par page
if (!$start) {
    $start = 0;
}

$nombre = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('magalerie_comments') . " WHERE lid=$lid");
$row = $GLOBALS['xoopsDB']->fetchRow($nombre);
if ($row[0] > 1) {
    $s = '' . _PLURIEL1 . '';

    $ent = '' . _PLURIEL2 . '';

    $x = '' . _PLURIEL3 . '';
} else {
    $s = '';

    $ent = '';

    $x = '';
}

echo '<div align="center">';
echo '<br>';
echo '<b>' . _COMMENTAIRE . ": $row[0] " . _ENTRE . '' . $s . "</b></div>\n";
echo "<br><table border=\"0\" cellspacing=\"1\" cellpadding=\"10\" align=\"center\" class=\"bg2\">\n" . "<tr class=\"bg3\">\n" . "<td class=\"bg3\">\n";

echo "<form method=\"post\" action=\"comment2.php?op=ajouter\">\n";
echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">\n";
echo "<input type=\"hidden\" name=\"uid\" value=\"$userid\">\n";
echo '<div align="center"><b>' . _COMMAJOUT . "</b></div>\n";
echo '<br><b>' . _NOM . ":&nbsp;</b>\n";
if ($xoopsUser) {
    $username = $xoopsUser->uname();

    echo "<b>$username</b>\n";
} else {
    echo "<b>$xoopsConfig[anonymous]</b>\n";
}
echo "<b><br><br>*Titel des Kommentars:</b> <input type=\"text\" name=\"titre\">\n";
echo '</td><td>';
$req = $GLOBALS['xoopsDB']->queryF('SELECT cat,img FROM ' . $xoopsDB->prefix('magalerie') . " where id='$lid'");
while (false !== ($ligne = $GLOBALS['xoopsDB']->fetchObject($req))) {
    echo "<input type=\"hidden\" name=\"cat\" value=\"$ligne->cat\">\n";

    echo "<input type=\"hidden\" name=\"img\" value=\"$ligne->img\">\n";

    echo '<div align="center">';

    $image = "galerie/$ligne->cat/$ligne->img-ppm";

    $size = getimagesize((string)$image); //recherche du format de l'image

    if (file_exists((string)$image)) {
        echo "<img src=\"$image\" $size[3]>";
    } else {
        echo '<img src="images/vignette.gif" width="90" height="70">';
    }

    echo '</div>';
}
echo '</td></tr><tr class="bg3"><td colspan="2">';
$test = 'texte';
require_once '../../include/xoopscodes.php';
if (1 == $allowbbcode) {
    xoopsCodeTarea((string)$test, $cols = 50, $rows = 10);
} else {
    echo "<textarea id='texte' name='' wrap='virtual' cols='50' rows='10'></textarea><br>";
}
if (1 == $allowsmileys) {
    xoopsSmilies('texte');
}

echo " <p align=\"center\">\n" . ' <input type="submit" name="Submit" value="' . _ENVOITURE . "\">\n" . "</p>\n" . "</form>\n" . "</td>\n" . "</tr>\n" . "</table>\n";

include '../../footer.php';
//}//Fin de la function commentaire
