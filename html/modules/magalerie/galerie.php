<?php

//id:magalerie/galerie.php
##################################################################################
# Magalerie Version 1.2b                                                         #                                                                                #
# Projet du 30/05/2002		dernière modification: 25/09/2002                #
# Scripts Home:                 http://www.lespace.org                           #              							                 #                                                                              #
# auteur        :		bidou                                            #
# email         :		bidou@lespace.org                                #
# Site web	:		http://www.lespace.org                           #
# licence	:		Gpl                                              #
##################################################################################
# Merci de laisser cette entête en place.                                        #
##################################################################################
include 'header.php';
$this_date = time();
$this_date = formatTimestamp($this_date, 'ms');

include '../../header.php';
require XOOPS_ROOT_PATH . '/modules/magalerie/class/xoopspagenav.php';
//openTable();

?>
<script language="JavaScript">
    function OuvrirFenetre(url, nom, details) {
        window.open(url, nom, details)
    }
</script>
<?php

echo '<table width="98%" border="0"><tr><td><a href="index.php"><b>' . _TITRE . '</b</a></td><td align="right">';
if ('1' == $listcat) {
    list_cat($PHP_SELF);
} else {
    echo '&nbsp;';
}
echo '</td></tr></table>';

if ('1' == $navigcat) {
    include 'navig.php';
}

$temcount = 1;
if (!$start) {
    $start = 0;
}
//Récupération et affichage des données
$nombre = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(*)  as img FROM ' . $xoopsDB->prefix('magalerie') . " WHERE cat='$cat' and valid='1'");
$row = $GLOBALS['xoopsDB']->fetchRow($nombre);
$message = $row[0];
//Récupération et affichage des données
if (!$orderby) {
    $orderby = 'dateD';
}
$mysqlorderby = convertorderbyin($orderby);
$orderbyTrans = convertorderbytrans($mysqlorderby);
$linkorderby = convertorderbyout($orderby);
$pagenav = new XoopsPageNav($message, $nb, $start, 'start', "cat=$cat");
$result = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $xoopsDB->prefix('magalerie') . " WHERE cat='$cat' and valid='1' ORDER BY $mysqlorderby limit " . (int)$start . ',' . $nb);

if ($row[0] > 1) {
    $s = '' . _PLURIEL1 . '';

    $ent = '' . _PLURIEL2 . '';

    $x = '' . _PLURIEL3 . '';
} else {
    $s = '';

    $ent = '';

    $x = '';
}
echo '<br><div align="center"><b>' . _CAT . ": $cat </b>" . $row[0] . ' ' . _IMG . '' . $s . '';

if ('1' == $validup) {
    if ($xoopsUser) {
        echo "&nbsp;(<a href=\"formulaire.php?cat=$cat\">" . _ADDIMG . '</a>)';
    } else {
        if ('1' == $anonymes) {
            echo "&nbsp;(<a href=\"formulaire.php?cat=$cat\">" . _ADDIMG . '</a>)';
        }
    }
}
echo '<br><br>' . _MD_SORTBY . '&nbsp;&nbsp;
              ' . _MD_DATE . " (<a href='galerie.php?cat=$cat&orderby=" . dateA . "'><img src=\"images/up.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a><a href='galerie.php?cat=$cat&orderby=dateD'><img src=\"images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)
              " . _MD_RATING . " (<a href='galerie.php?cat=$cat&orderby=voteA'><img src=\"images/up.gif\" width=\"11\" height=\"14\"  border=\"0\" align=\"middle\"></a><a href='galerie.php?cat=$cat&orderby=voteD'><img src=\"images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)
              " . _MD_POPULARITY . " (<a href='galerie.php?cat=$cat&orderby=hitsA'><img src=\"images/up.gif\" width=\"11\" height=\"14\"  border=\"0\" align=\"middle\"></a><a href='galerie.php?cat=$cat&orderby=hitsD'><img src=\"images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)
              	";
echo '<b><br>';
printf(_MD_CURSORTEDBY, (string)$orderbyTrans);
echo '</div><br>';
echo '<table border="0" align=center width="99%"><tr>';
while (false !== ($ligne = $GLOBALS['xoopsDB']->fetchObject($result))) {
    $id = $ligne->id;

    $img = $ligne->img;

    $cat = $ligne->cat;

    $note = $ligne->note;

    $vote = $ligne->vote;

    //$coment ="".$ligne->coment."";

    $image = "galerie/$cat/" . $img . '-ppm';

    $date = formatTimestamp($ligne->date, 's');

    $count = 7;

    $startdate = (time() - (86400 * $count));

    if ($startdate < $ligne->date) {
        $nouveau = '&nbsp;<img src="' . XOOPS_URL . '/modules/magalerie/images/new.gif" whidth="28" height="11" title="' . _MD_NEWTHISWEEK . '"><br>';
    }

    echo '<td align="center" width="33%">';

    if ($ligne->clic > 1) {
        $s1 = '' . _PLURIEL1 . '';
    } else {
        $s1 = '';
    }

    if ($ligne->vote > 1) {
        $s2 = '' . _PLURIEL1 . '';
    } else {
        $s2 = '';
    }

    echo '<table border="0" cellspacing="1" cellpadding="2">' . '<tr>' . "<td colspan=\"2\" align=\"left\">$nouveau <b>Ajoutée le:</b> $date";

    $count = 7;

    $startdate = (time() - (86400 * $count));

    if ($startdate < $date) {
        if (1 == $status) {
            echo '&nbsp;<img src="' . XOOPS_URL . '/modules/mydownloads/images/newred.gif" alt="' . _MD_NEWTHISWEEK . '">';
        }
    }

    echo '</td>' . '</tr><tr>' . '<td colspan="2" align="center">';

    if (file_exists((string)$image)) {
        $size = getimagesize((string)$image);

        if ('.gif' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4) || '.GIF' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 5), 5)) {
            echo "<a href=\"show.php?cat=$cat&img=$img\" title=\"" . _ALT2 . '"><img src="images/vignette.gif" width="90" height="70" title="' . _ALT2 . '">';
        } else {
            echo "<a href=\"show.php?id=$id\" title=\"" . _ALT2 . "\"><img src=\"$image\" $size[3]></a></td>";
        }
    } else {
        echo "<a href=\"show.php?cat=$cat&img=$img\" title=\"" . _ALT2 . '"><img src="images/vignette.gif" width="90" height="70" title="' . _ALT2 . '"></a></td>';
    }

    echo '</tr><tr>' . '<td>' . $ligne->clic . ' ' . _CLICS . '' . $s1 . '</td>';

    $diff = floor(($note / $vote));

    if ($diff < 5) {
        $rank_img = 'images/blanc.gif';
    }

    if ($diff > 4) {
        $rank_img = '../../images/ranks/star.gif';
    }

    if ($diff > 5) {
        $rank_img = '../../images/ranks/stars2.gif';
    }

    if ($diff > 6) {
        $rank_img = '../../images/ranks/stars3.gif';
    }

    if ($diff > 7) {
        $rank_img = '../../images/ranks/stars4.gif';
    }

    if ($diff > 8) {
        $rank_img = '../../images/ranks/stars5.gif';
    }

    $rank_size = getimagesize((string)$rank_img);

    //echo "&nbsp;<img src=\"$rank_img\" $rank_size[3] title=\"$ligne->vote Votes $ligne->note "._POINTS."\">";

    echo '<td>';

    if (0 != $vote) {
        echo "<img src=\"$rank_img\" $rank_size[3] title=\"$ligne->vote Votes $ligne->note " . _POINTS . '"></td>';
    } else {
        echo '&nbsp;';
    }

    echo '</tr><tr>' . '<td colspan="2">';

    $solde = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('magalerie_comments') . " where lid='$id'");

    $total = $GLOBALS['xoopsDB']->fetchRow($solde);

    if ($total[0] > 1) {
        $sn = '' . _PLURIEL1 . '';
    } else {
        $sn = '';
    }

    echo '' . $total[0] . ' ' . _COMMENTAIRE . '' . $sn . '</td>' . '</tr>' . '</table><br>';

    echo '</td><td>';

    if ($temcount == $themepage) {
        echo '</tr><tr>';

        $temcount -= $themepage;
    }

    $temcount++;
}
echo '</td></tr></table>';
echo '<div align=center><br>' . $pagenav->renderNav() . '<div>';
if (file_exists('galerie_footer.php')) {
    include 'galerie_footer.php';
}
//closeTable();
include '../../footer.php';
?>
