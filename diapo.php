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
include 'header.php';
require XOOPS_ROOT_PATH . '/modules/magalerie/class/xoopspagenav.php';

$start = 0;
if (!empty($_GET['start'])) {
    $start = $_GET['start'];
}
$uid = $_GET['uid'];
$cat = multiname($uid);
[$pathimg, $valid_cat] = linkretour($uid);
if ('' != $pathimg) {
    $pathimg .= '/';
}
$path_image = '' . XOOPS_URL . '/modules/magalerie/galerie/' . $pathimg . '' . $cat . '/';
$nbimages = $xoopsDB->query('SELECT COUNT(id) FROM ' . $xoopsDB->prefix('magalerie') . " WHERE uid='$uid' and valid >= '1' ");
$rowsc = $xoopsDB->fetchRow($nbimages);
$row = $rowsc[0];
$Nmax = 1;
$result = $xoopsDB->query('SELECT id, img FROM ' . $xoopsDB->prefix('magalerie') . "  WHERE uid='$uid' and valid >= '1' limit " . (int)$start . ',' . $Nmax);
[$id, $img] = $xoopsDB->fetchRow($result);

$setclic = $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('magalerie') . " SET clic=clic+1 WHERE id='$id' ");
$pagin = ($start + $Nmax);

if ($start >= $Nmax) {
    $precd = ($start - $Nmax);

    $suite = "<a href='$PHP_SELF?uid=" . $uid . '&start=' . $precd . "'>precedent</a>&nbsp;";
}

error_reporting(E_ALL);

if (!empty($stop)) {
    $stop = $_GET['stop'];
}

if ($pagin <= $row) {
    if (empty($stop)) {
        header('Refresh: 3;diapo.php?uid=' . $uid . '&start=' . $pagin . '');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');             // Date du passé
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // toujours modifié
        header('Cache-Control: no-cache, must-revalidate');           // HTTP/1.1
    }

    echo "<meta http-equiv='Content-Type' content='text/html; charset=" . _CHARSET . "'>\n";

    echo '<html>
	<head>
	<title>Diaporama</title>';

    echo "</head>\n";

    echo '<body style="margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px">';

    echo '<table width="770" height="540" cellspacing="0" cellpadding="0" align="center"><tr><td>';

    $image = '' . $path_image . '' . $img . '';

    $resize = resize($image, '500');

    echo '<center><img src="' . $image . "\" width=\"$resize[w]\" height\"$resize[h]\" border=\"0\" alt=\"$img\"></center>";

    echo '</td></tr></table>';

    $actuel = ($pagin - 1);

    $img_prec = ($pagin - 2);

    $img_suiv = ($pagin);

    echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" width=\"100%\">
	<tr><td width=\"33%\" align=\"center\">$pagin / $row</td><td width=\"33%\">";

    echo '<table border="0" cellspacing="0" cellpadding="0" align="center">';

    if ($start > 0) {
        echo '<td align="left" width="50" align="bottom"><a href="diapo.php?uid=' . $uid . '&start=' . $img_prec . '">' . '<img src="images/back.gif" width="16" height="17" border="0" title="' . _PREC . '"></a></td>';
    } else {
        echo '<td align="center" width="50">&nbsp;</td>';
    }

    echo '<td align="center" width="50"><a href="diapo.php?uid=' . $uid . '&start=' . $actuel . '&stop=1"><img src="images/retour.gif" width="46" heigth="14" border="0" title="Pause"></a></td>';

    if (($start + 2) != $row) {
        echo '<td align="right" width="50"><a href="diapo.php?uid=' . $uid . '&start=' . $img_suiv . '"><img src="images/suiv.gif" width="16" height="17" border="0" title="' . _SUIV . '"></a></td>';
    } else {
        echo '<td align="center" width="50">&nbsp;</td>';
    }

    echo '</tr></table>';

    echo '</td><td width="33%" align="right"><font size="-3px">© 2003 Diaprama &nbsp;&nbsp;</font></td></tr></table>';

    echo '</body></html>';
} else {
    echo '<html><head>';

    echo "</head>\n";

    echo '<body style="margin-top: 200px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px">';

    echo '<br><h1 align="center">Le diporama est termin&eacute;!</h1>';
}

echo '</body></html>';

