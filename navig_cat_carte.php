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
$result = $xoopsDB->query('SELECT id FROM ' . $xoopsDB->prefix('magalerie') . " WHERE valid >= '1' and uid = '$uid'");
$x = 1;
$menge = $xoopsDB->getRowsNum($result);
while (false !== ($val = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $bild[$x] = $val['id'];

    $y = $x;

    $x++;
}
echo '<br>';

for ($x = 1; $x < ($y + 1); $x++) {
    if ($bild[$x] == $id) {
        $aktuell = $x;

        if ($aktuell > 1) {
            $img_ret = $bild[$x - 1];
        }

        if ($aktuell < $menge) {
            $img_next = $bild[$x + 1];
        }
    }
}

echo '<table border="0" cellspacing="0" cellpadding="0" align="center" style="width:150px">';
if ($aktuell > 1) {
    echo '<td align="left" width="50"><a href="' . $PHP_SELF . '?id=' . $img_ret . '">' . '<img src="images/back.gif" width="16" height="17" border="0" title="' . _PREC . '"></a></td>';
} else {
    echo '<td align="center" width="50">&nbsp;</td>';
}
echo '<td align="center" width="50"><a href="galerie.php?uid=' . $uid . '"><img src="images/retour.gif" width="46" heigth="14" border="0" title="' . _LERETOUR . ' ' . _CHOISIPAYS . " $cat\"></a></td>";
if ($aktuell < $menge) {
    echo '<td align="right" width="50"><a href="' . $PHP_SELF . '?id=' . $img_next . '"><img src="images/suiv.gif" width="16" height="17" border="0" title="' . _SUIV . '"></a></td>';
} else {
    echo '<td align="center" width="50">&nbsp;</td>';
}
echo '</table>';
