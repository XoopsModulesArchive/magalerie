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
//$op = "index";

if (!empty($_GET['op'])) {
    $op = $_GET['op'];
}

if ('confirm_img' == $op) {
    OpenTable();

    xoops_cp_header();

    echo "<form method=\"post\" action=\"$PHP_SELF?op=del_img\">\n";

    //	echo "<input type=\"hidden\" name=\"op\" value=\"del_img\">\n";

    echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";

    echo "<input type=\"hidden\" name=\"uid\" value=\"$uid\">\n";

    echo "<input type=\"hidden\" name=\"image\" value=\"$image\">\n";

    echo '<div align="center"><b>' . _CONFDEL . " $id</b><br>" . _GARDE . "
	<br><br><img src=\"$image-ppm\"><br><br>\n";

    echo '<input type="submit" value="' . _YES . "\">&nbsp\n";

    echo '<input type="button" value="' . _NO . "\" onclick=\"history.go(-1)\"></div>\n";

    echo "</form>\n";

    CloseTable();

    xoops_cp_footer();
}
////////////////////////////////////////////////
if ('confirm_cat' == $op) {
    OpenTable();

    xoops_cp_header();

    echo '' . _C_SUR . '';

    echo "<form method=\"post\" action=\"$PHP_SELF?op=del_cat\">\n";

    echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";

    echo '<input type="submit" value="' . _YES . "\">&nbsp\n";

    echo '<input type="button" value="' . _NO . "\" onclick=\"history.go(-1)\"></div>\n";

    echo "</form>\n";

    CloseTable();

    xoops_cp_footer();
}

///////////////////////////////////////////////////////////
if ('del_img' == $op) {
    $supp = 'DELETE FROM ' . $xoopsDB->prefix('magalerie') . " WHERE id='$id' ";

    $xoopsDB->query($supp);

    $supp_com = 'DELETE FROM ' . $xoopsDB->prefix('xoopscomments') . " WHERE com_itemid ='$id' ";

    $xoopsDB->query($supp_com);

    //echo $xoopsDB->error();

    $Fnm = (string)$image;

    $Fnm2 = "$image-ppm";

    unlink($Fnm);

    unlink($Fnm2);

    redirect_header('listing.php?uid=' . $uid . '', 1, '' . MG_MAJ . '');

    exit();
}

/////////////////////////////
if ('del_cat' == $op) {
    $nbsid = cherchscat('id', 'magalerie', "uid='$id'");

    $supp2 = 'DELETE FROM ' . $xoopsDB->prefix('xoopscomments') . "  WHERE com_itemid IN ($nbsid'')";

    $xoopsDB->query($supp2);

    $supp = 'DELETE FROM ' . $xoopsDB->prefix('magalerie_cat') . " WHERE id='$id'" and '' . $xoopsDB->prefix('magalerie') . " WHERE uid='$id'";

    $xoopsDB->query($supp);

    echo $xoopsDB->error();

    redirect_header('index.php', 1, '' . MG_MAJ . '');

    exit();
}

function confirm_coment()
{
    global $xoopxDB, $id, $lid;

    echo "<form method=\"post\" action=\"commentaire.php\">\n";

    echo "<input type=\"hidden\" name=\"op\" value=\"delete\">\n";

    echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";

    echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">\n";

    echo '<div align="center"><b>' . _CONFIRM . "</b><br><br>\n";

    echo '<input type="submit" value="' . _YES . "\">&nbsp\n";

    echo '<input type="button" value="' . _NO . "\" onclick=\"history.go(-1)\"></div>\n";

    echo "</form>\n";
}//Fin de la function confirm

function delete_coment()
{
    global $xoopsUser, $xoopsDB, $id, $lid;

    if ($xoopsUser) {
        if ($xoopsUser->isAdmin()) {
            $supp = 'DELETE FROM ' . $xoopsDB->prefix('mydownloads_comments') . " WHERE id=$id";

            $xoopsDB->query($supp);

            echo $xoopsDB->error();
        }
    }

    redirect_header("commentaire.php?lid=$lid", 1, _AJOUR);

    exit();
}//Fin de la function delete
