<?php

//id:magalerie/index.php
##################################################################################
# Magalerie Version 1.2b                                                         #                                                                                #
# dernière modification: 25/09/2002                				 #
# Scripts Home:                 http://www.lespace.org                           #              							                 #                                                                              #
# auteur           :            bidou                                            #
# email            :            bidou@lespace.org                                #
# Site web         :		http://www.lespace.org                           #
# licence          :            Gpl                                              #
##################################################################################
# Merci de laisser cette entête en place.                                        #
##################################################################################
include 'header.php';

include '../../header.php';
require XOOPS_ROOT_PATH . '/modules/magalerie/class/xoopspagenav.php';
//openTable();

echo '<table width="98%" border="0"><tr><td><a href="index.php"><b>' . _TITRE . '</b</a></td><td align="right">';
if ('1' == $listcat) {
    list_cat('galerie.php');
} else {
    echo '&nbsp;';
}
echo '</td></tr></table>';

$temcount = 1;
if (!$start) {
    $start = 0;
}
$nombre = $xoopsDB->query('SELECT COUNT(id) FROM ' . $xoopsDB->prefix('magalerie_cat') . "  WHERE valid >= '1'");
$row = $GLOBALS['xoopsDB']->fetchRow($nombre);
$message = $row[0];
$query = $xoopsDB->query('SELECT COUNT(id) FROM ' . $xoopsDB->prefix('magalerie') . "  WHERE valid >= '1'");
$solde = $GLOBALS['xoopsDB']->fetchRow($query);

if ($row[0] > 1) {
    $s = '' . _PLURIEL1 . '';

    $ent = '' . _PLURIEL2 . '';

    $x = '' . _PLURIEL3 . '';
} else {
    $s = '';

    $ent = '';

    $x = '';
}

echo '<div align="center"><b>' . _ILYA . ' ' . $row[0] . ' ' . _CAT . '' . $s . '<br>' . _TOTALIMG . ' ' . $solde[0] . ' ' . _IMG . '' . $s . ' </b></div><br>';
echo '<table align=center width=99%><tr align="top">';
//while($ligne=$GLOBALS['xoopsDB']->fetchObject($result)) {
$pagenav = new XoopsPageNav($message, $nb, $start, 'start', "cat=$cat");

$result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('magalerie_cat') . "  WHERE valid >= '1' limit " . (int)$start . ',' . $nb);
while (list($id, $cat, $img, $coment, $clic, $alea, $valid) = $xoopsDB->fetchRow($result)) {
    $image = "galerie/$cat/$img-ppm";

    echo '<td align="center" width="33%">';

    echo '<table>' . '<tr>' . "<td align=\"center\"><b>$cat</b></td>" . '</tr><tr>' . '<td align="center">';

    //<a href=\"galerie.php?cat=$cat\"><img src=\"images/vignette.gif\" width=\"90\" height=\"70\" title=\""._CAT." $cat\"></a>

    if ('1' != $alea) {
        echo "<a href=\"galerie.php?cat=$cat\"><img src=\"$img\" width=\"90\" height=\"70\" title=\"" . _CAT . " $cat\"></a>";
    } else {
        //echo "ffff $img |<a href=\"galerie.php?cat=$cat\"><img src=\"images/vignette.gif\" width=\"90\" height=\"70\" title=\""._CAT." $cat\"></a>";

        $ramdom = $xoopsDB->query('SELECT img FROM ' . $xoopsDB->prefix('magalerie') . " WHERE cat='$cat' and valid >= '1' ORDER BY RAND()");

        $row = $xoopsDB->getRowsNum($ramdom);

        [$img] = $xoopsDB->fetchRow($ramdom);

        $rowimage = "galerie/$cat/$img-ppm";

        if (file_exists((string)$rowimage)) {
            $size = getimagesize((string)$rowimage);

            echo "<a href=\"galerie.php?cat=$cat\"><img src=\"$rowimage\" $size[3] title=\"" . _CAT . " $cat\"></a>";
        }
    }

    echo '</td>' . '</tr><tr>' . "<td align=\"center\">$row " . _IMG . '' . $s . '</td>' . '</tr>' . '</table><br>';

    echo '</td>';

    if ($temcount == $themepage) {
        echo '</tr><tr>';

        $temcount -= $themepage;
    }

    $temcount++;
}
echo '</td></tr></table>';

//*******Pagination**********
echo '<div align=center><br>' . $pagenav->renderNav() . '</div>';

if (file_exists('galerie_footer.php')) {
    include 'galerie_footer.php';
}
//closeTable();
include '../../footer.php';
