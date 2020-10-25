<?php

$result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('magalerie') . " WHERE valid >= '1' and cat = '$cat'");
$x = 1;
$menge = $GLOBALS['xoopsDB']->getRowsNum($result);
while (false !== ($val = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $bild[$x] = $val['id'];

    $y = $x;

    $x++;
}

for ($x = 1; $x < ($y + 1); $x++) {
    if ($bild[$x] == $id) {
        $aktuell = $x;

        $img_ret = $bild[$x - 1];

        $img_next = $bild[$x + 1];
    }
}

echo '<table border="0" cellspacing="0" cellpadding="0" width="150" align="center">';
if ($aktuell > 1) {
    echo '<td align="left" width="50"><a href="carte.php?cat=' . $cat . '&id=' . $img_ret . '">' . '<img src="images/back.gif" width="16" height="17" border="0" title="' . _PREC . '"></a></td>';
} else {
    echo '<td align="center" width="50">&nbsp;</td>';
}
echo '<td align="center" width="50"><a href="galerie.php?cat=' . $cat . '"><img src="images/retour.gif" width="46" heigth="14" border="0" title="' . _LERETOUR . ' ' . _CHOISIPAYS . " $cat\"></a></td>";
if ($aktuell < $menge) {
    echo '<td align="right" width="50"><a href="carte.php?cat=' . $cat . '&id=' . $img_next . '"><img src="images/suiv.gif" width="16" height="17" border="0" title="' . _SUIV . '"></a></td>';
} else {
    echo '<td align="center" width="50">&nbsp;</td>';
}
echo '</table>';
