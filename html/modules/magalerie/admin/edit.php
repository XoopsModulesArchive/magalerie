<?php

#####################################################
#  Copyright © 2002, bidou lespace.org              #
#  postmaster@lespace.org - http://www.lespace.org  #
#                                                   #
#  Licence : GPL                                    #
#  Merci de laisser ce copyright en place...        #
#####################################################
include 'admin_header.php';

function showedit_cat()
{
    global $xoopsDB, $cat;

    xoops_cp_header();

    OpenTable();

    include 'admin-menu.php';

    echo "<p><b>Editer une cat&eacute;gorie: $cat</b></p>\n";

    list_cat('edit.php');

    $requete = 'select * from ' . $xoopsDB->prefix('magalerie_cat') . " WHERE cat='$cat' ";

    $result = $xoopsDB->query($requete);

    while (list($id, $cat, $img, $coment, $clic, $alea, $valid) = $xoopsDB->fetchRow($result)) {
        echo "<form action=\"$PHP_SELF\" method=\"post\">\n"
             . "<input type=\"hidden\" name=\"gop\" value=\"updatecat\">\n"
             . "<input type=\"hidden\" name=\"id\" value=\"$id\">\n"
             . "<table border=\"0\" cellpadding=\"6\">\n"
             . "<tr><td><b>id:</b></td><td>$id</td></tr>\n"
             . "<tr><td><b>categorie:</b></td><td><input type=hidden name=cat value=\"$cat\" maxlength=50 style=\"width: 200px;\"> $cat</td></tr>\n"

             . "<tr><td rowspan=\"2\" valign=\"top\"><b>image:</b></td>\n";

        if ('1' == $alea) {
            echo "<td><input type=radio name=alea value=\"1\" checked> Sélection aleatoire.</td>\n";

            echo "</tr><tr>\n" . "<td><input type=radio name=alea value=\"0\"> <input type=text name=\"img\" value=\"images/vignettes.gif\"  style=\"width: 175px;\"></p></td>\n";
        } else {
            echo "<td><input type=radio name=alea value=\"1\"> Sélection aleatoire.</td>\n";

            echo "</tr><tr>\n" . "<td><input type=radio name=alea value=\"0\" checked> <input type=text name=\"img\" value=\"$img\"  style=\"width: 175px;\"></p></td>\n";
        }

        echo "$coment</textarea></td></tr>\n" . "<tr><td><b>Visible:</b></td><td>\n";

        if ('1' == $valid) {
            echo "<p><input type=radio name=valid value=\"1\" checked> Oui.\n" . "<input type=radio name=valid value=\"0\"> Non</p>\n";
        } else {
            echo "<p><input type=radio name=valid value=\"1\">  Oui.\n" . "<input type=radio name=valid value=\"0\" checked> Non.</p>\n";
        }

        echo "</td></tr>\n" . "<tr><td colspan=2><input type=submit value=\"Valider\"></td></tr>\n" . "</table>\n" . "</form>\n";
    }

    CloseTable();

    xoops_cp_footer();

    //include ("../../../footer.php");
}

function edit_comment()
{
    global $xoopsDB, $id, $cat, $img;

    xoops_cp_header();

    OpenTable();

    include 'admin-menu.php';

    echo '<br>';

    $image = "../galerie/$cat/$img-ppm";

    $requete = $GLOBALS['xoopsDB']->queryF('select * from ' . $xoopsDB->prefix('magalerie_comments') . " WHERE id='$id' ");

    while (list($id, $lid, $uid, $titre, $texte, $date) = $xoopsDB->fetchRow($requete)) {
        $myts = MyTextSanitizer::getInstance();

        $titre = stripslashes((string)$titre);

        $titre = htmlspecialchars($titre, ENT_QUOTES | ENT_HTML5);

        $texte = stripslashes((string)$texte);

        $texte = htmlspecialchars($texte, ENT_QUOTES | ENT_HTML5);

        $date = formatTimestamp($date, 's');

        echo '<b>Editer un commentaire.</b>';

        echo "<form method=\"post\" action=\"$PHP_SELF\">" . '<input type="hidden" name="gop" value="update_coment">' . "<input type=\"hidden\" name=\"cat\" value=\"$cat\">" . "<input type=\"hidden\" name=\"img\" value=\"$img\">";

        echo '<table cellspacing="2" cellpadding="2" border="0">' . '<tr>';

        echo '<td>id du commentaire: </td>' . "<td><input type=\"hidden\" name=\"id\" value=\"$id\">$id</td><td rowspan=\"4\">";

        if (file_exists((string)$image)) {
            $size = getimagesize($image);

            echo "<a href=\"../show.php?id=$lid\" title=\"" . _GALIMG . "\"><img src=\"$image\" $size[3]></a>";
        }

        echo '</td>';

        echo '</tr><tr>';

        echo '<td>lié avec lid: </td>' . "<td><input type=\"text\" name=\"lid\" value=\"$lid\"></td>";

        echo '</tr><tr>';

        echo '<td>uid: </td>' . "<td><input type=\"text\" name=\"uid\" value=\"$uid\"></td>";

        echo '</tr><tr>';

        echo '<td  colspan="2">&nbsp;</td>';

        echo '</tr><tr>';

        echo '<td>titre: </td>' . "<td  colspan=\"2\"><input type=\"text\" name=\"titre\" value=\"$titre\"></td>";

        echo '</tr><tr>';

        echo '<td>texte: </td>' . "<td  colspan=\"2\"><textarea id='texte' name='texte' wrap='virtual' cols='50' rows='10'>$texte</textarea></td>";

        echo '</tr><tr>';

        echo "<td>date: $date</td>" . '<td  colspan="2"><input type="submit" value="go"></td>';

        echo '</tr></table>';

        echo '</form>';
    }

    CloseTable();

    xoops_cp_footer();
}

function update_coment()
{
    global $xoopsDB, $id, $lid, $uid, $titre, $texte, $date, $cat, $img;

    $myts = MyTextSanitizer::getInstance();

    $titre = addslashes((string)$titre);

    $titre = $myts->addSlashes($titre);

    $texte = addslashes((string)$texte);

    $texte = $myts->addSlashes($texte);

    if (!$titre || !$texte) {
    } elseif (!$GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('magalerie_comments') . " SET lid='$lid', uid='$uid', titre='$titre', texte='$texte' WHERE id='$id' ")) {
        die('update de magalerie impossible!<br>' . $GLOBALS['xoopsDB']->errno() . ': ' . $GLOBALS['xoopsDB']->error());
    } else {
        redirect_header('edit.php?gop=' . edit_comment . '&id=' . $id . '&cat=' . $cat . '&img=' . $img . '', 1, 'La base est mis a jour!');
    }

    exit();
}

//showedit_cat();
function updatecat()
{
    global $xoopsDB, $id, $cat, $img, $coment, $alea, $valid;

    $coment = addslashes((string)$coment);

    if (!$cat || !$img) {
    } elseif (!$GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('magalerie_cat') . " SET cat='$cat', img='$img', alea='$alea', valid='$valid' WHERE id='$id'")) {
        die('update de magalerie impossible!<br>' . $GLOBALS['xoopsDB']->errno() . ': ' . $GLOBALS['xoopsDB']->error());
    } else {
        redirect_header('edit.php?cat=' . $cat . '', 1, 'La base est mis a jour!');
    }

    exit();
}

switch ($gop) {
    case 'update_coment':
        update_coment();
        break;
    case 'edit_comment':
        edit_comment();
        break;
    case 'updatecat':
        updatecat();
        break;
    default:
        showedit_cat();
        break;
}
