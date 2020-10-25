<?php

echo '<table border="0" cellspacing="0" cellpadding="1" align="center">' . '<tr>';
$nav = 'SELECT cat FROM ' . $xoopsDB->prefix('magalerie_cat') . '';
$result = $GLOBALS['xoopsDB']->queryF($nav);
$Nmax = 50; // nombre par page
$Ncur = 0; // n° de la fiche courante
$Ndeb = 0; // 1ère fiche transmise par l'URL
if (isset($num)) {
    $Ndeb = (int)$num;
}
$temcount = 1;
// tant qu'il y a des fiches
while (($val = $GLOBALS['xoopsDB']->fetchBoth($result))
       && ($Ncur < $Nmax + $Ndeb)) {
    if ($Ncur >= $Ndeb) {
        $lien = '' . $val['cat'] . '';

        if ($temcount > 1) {
            echo '<td align="center"><img src="images/centre.gif">';
        }

        echo '<td align="center"><a href="' . $xoopsConfig['xoops_url'] . "/modules/magalerie/galerie.php?cat=$lien\" style=\"font-size:14px\"><b> $lien</b></a>";

        echo '</td>';
    }

    if (6 == $temcount) {
        echo '</tr><tr>';

        $temcount -= 6;

        //      echo"</td></tr><tr>";
    }

    $temcount++;

    $Ncur++;
}
echo '<br></tr></table>';
