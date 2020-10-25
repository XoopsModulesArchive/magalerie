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

function b_linkretour($uid)
{
    global $db;

    $demande = 'select cid from ' . $db->prefix('magalerie_cat') . " where id='$uid' ";

    $req = $db->query($demande);

    while (list($cid) = $db->fetch_row($req)) {
        if (0 != $cid) {
            $link = b_multiname($cid);

            $aref = "$link/";
        } else {
            $aref = '';
        }
    }

    return $aref;
}

function b_multiname($name)
{
    global $db, $cid;

    $rete = 'select cat from ' . $db->prefix('magalerie_cat') . " where id='$name'";

    $result = $db->query($rete);

    while (list($rowcat) = $db->fetch_row($result)) {
        return $rowcat;
    }
}

function b_magalerie_show()
{
    //require_once (XOOPS_ROOT_PATH."/modules/magalerie/functions.php");

    require_once XOOPS_ROOT_PATH . '/modules/magalerie/language/french/modinfo.php';

    global $xoopsConfig, $db, $xoopsUser;

    $block = [];

    $block['title'] = _MAGALERIE_BNAME;

    $block['title'] .= "<br><div align='center'><font color='#CD6531'><b>Sélections aléatoires</b></font></div>";

    $block['content'] = "<table border='0' cellpadding='5' width='100%' align='center'><tr><td width='30%' align='center'> ";

    $ramdom = $db->query('SELECT id, uid, img FROM ' . $db->prefix('magalerie') . " WHERE valid >= '1' ORDER BY RAND()");

    $row = $db->num_rows($ramdom);

    [$id, $uid, $img] = $db->fetch_row($ramdom);

    $pathimg = b_linkretour($uid);

    $catname = b_multiname($uid);

    $rowimage = $xoopsConfig['root_path'] . 'modules/magalerie/galerie/' . $pathimg . '' . $catname . '/' . $img . '-ppm';

    if (file_exists((string)$rowimage)) {
        $size = getimagesize((string)$rowimage);

        $block['content'] .= "catégorie: <a href='" . XOOPS_URL . "/modules/magalerie/galerie.php?uid=$uid' title='visiter la catégorie $catname'>" . $catname . '</a>';

        $block['content'] .= '<br><a href="' . $xoopsConfig['xoops_url'] . "/modules/magalerie/show.php?id=$id\">
	<img src=\"" . $xoopsConfig['xoops_url'] . '/modules/magalerie/galerie/' . $pathimg . '' . $catname . '/' . $img . "-ppm\" $size[3] title=\"voir cette image en détail\" style=\"padding: 5px\"></a>";
    }

    $block['content'] .= "</td><td width='30%' align='center'>";

    //////////////////////////////////////////

    [$id, $uid, $img] = $db->fetch_row($ramdom);

    $pathimg = b_linkretour($uid);

    $catname = b_multiname($uid);

    $rowimage = $xoopsConfig['root_path'] . 'modules/magalerie/galerie/' . $pathimg . '' . $catname . '/' . $img . '-ppm';

    ////////////////////////

    if (file_exists((string)$rowimage)) {
        $size = getimagesize((string)$rowimage);

        $block['content'] .= "catégorie: <a href='" . XOOPS_URL . "/modules/magalerie/galerie.php?uid=$uid' title='visiter la catégorie $catname'>" . $catname . '</a>';

        $block['content'] .= '<br><a href="' . $xoopsConfig['xoops_url'] . "/modules/magalerie/show.php?id=$id\">
	<img src=\"" . $xoopsConfig['xoops_url'] . '/modules/magalerie/galerie/' . $pathimg . '' . $catname . '/' . $img . "-ppm\" $size[3] title=\"voir cette image en détail\" style=\"padding: 5px\"></a>";
    }

    $block['content'] .= "</td><td width='30%' align='center'>";

    ////////////////////////////////

    [$id, $uid, $img] = $db->fetch_row($ramdom);

    $pathimg = b_linkretour($uid);

    $catname = b_multiname($uid);

    $rowimage = $xoopsConfig['root_path'] . 'modules/magalerie/galerie/' . $pathimg . '' . $catname . '/' . $img . '-ppm';

    //////////////////////////////

    if (file_exists((string)$rowimage)) {
        $size = getimagesize((string)$rowimage);

        $block['content'] .= "catégorie: <a href='" . XOOPS_URL . "/modules/magalerie/galerie.php?uid=$uid' title='visiter la catégorie $catname'>" . $catname . '</a>';

        $block['content'] .= '<br><a href="' . $xoopsConfig['xoops_url'] . "/modules/magalerie/show.php?id=$id\">
	<img src=\"" . $xoopsConfig['xoops_url'] . '/modules/magalerie/galerie/' . $pathimg . '' . $catname . '/' . $img . "-ppm\" $size[3] title=\"voir cette image en détail\" style=\"padding: 5px\"></a>";
    }

    $block['content'] .= '</td></tr></table>';

    //serie bloc fixe

    $block['content'] .= "<br><div align='center'><font color='#CD6531'><b>Les tops de la galerie</b></font></div>";

    $block['content'] .= "<table border='0' cellpadding='5' width='100%' align='center'><tr><td width='30%' align='center'> ";

    $d_ramdom = $db->query('SELECT id, uid, img, date FROM ' . $db->prefix('magalerie') . " WHERE valid >= '1' ORDER BY date DESC");

    $d_row = $db->num_rows($d_ramdom);

    [$d_id, $d_uid, $d_img, $d_date] = $db->fetch_row($d_ramdom);

    $d_pathimg = b_linkretour($d_uid);

    $d_catname = b_multiname($d_uid);

    $d_rowimage = $xoopsConfig['root_path'] . 'modules/magalerie/galerie/' . $d_pathimg . '' . $d_catname . '/' . $d_img . '-ppm';

    if (file_exists((string)$d_rowimage)) {
        $d_size = getimagesize((string)$d_rowimage);

        $block['content'] .= "catégorie: <a href='" . XOOPS_URL . "/modules/magalerie/galerie.php?uid=$d_uid' title='visiter la catégorie $d_catname'>" . $d_catname . '</a>';

        $block['content'] .= '<br><a href="' . $xoopsConfig['xoops_url'] . "/modules/magalerie/show.php?id=$d_id\">
	<img src=\"" . $xoopsConfig['xoops_url'] . '/modules/magalerie/galerie/' . $d_pathimg . '' . $d_catname . '/' . $d_img . "-ppm\" $d_size[3] title=\"voir cette image en détail\" style=\"padding: 5px\"></a>";
    }

    $d_date = formatTimestamp($d_date, 's');

    $block['content'] .= '<br>Dernière en date<br>le ' . $d_date . '.';

    //////////////////////////////////////////////////////

    $block['content'] .= "</td><td width='30%' align='center'>";

    $n_ramdom = $db->query('SELECT id, uid, img, note FROM ' . $db->prefix('magalerie') . " WHERE valid >= '1' ORDER BY note DESC");

    $n_row = $db->num_rows($n_ramdom);

    [$n_id, $n_uid, $n_img, $n_note] = $db->fetch_row($n_ramdom);

    $n_pathimg = b_linkretour($n_uid);

    $n_catname = b_multiname($n_uid);

    $n_rowimage = $xoopsConfig['root_path'] . 'modules/magalerie/galerie/' . $n_pathimg . '' . $n_catname . '/' . $n_img . '-ppm';

    if (file_exists((string)$n_rowimage)) {
        $n_size = getimagesize((string)$n_rowimage);

        $block['content'] .= "catégorie: <a href='" . XOOPS_URL . "/modules/magalerie/galerie.php?uid=$n_uid' title='visiter la catégorie $n_catname'>" . $n_catname . '</a>';

        $block['content'] .= '<br><a href="' . $xoopsConfig['xoops_url'] . "/modules/magalerie/show.php?id=$n_id\">
	<img src=\"" . $xoopsConfig['xoops_url'] . '/modules/magalerie/galerie/' . $n_pathimg . '' . $n_catname . '/' . $n_img . "-ppm\" $n_size[3] title=\"voir cette image en détail\" style=\"padding: 5px\"></a>";
    }

    $block['content'] .= '<br>La mieux notée<br>' . $n_note . ' points.';

    $block['content'] .= "</td><td width='30%' align='center'>";

    $c_ramdom = $db->query('SELECT id, uid, img, clic FROM ' . $db->prefix('magalerie') . " WHERE valid >= '1' ORDER BY clic DESC");

    $c_row = $db->num_rows($c_ramdom);

    [$c_id, $c_uid, $c_img, $c_clic] = $db->fetch_row($c_ramdom);

    $c_pathimg = b_linkretour($c_uid);

    $c_catname = b_multiname($c_uid);

    $c_rowimage = $xoopsConfig['root_path'] . 'modules/magalerie/galerie/' . $c_pathimg . '' . $c_catname . '/' . $c_img . '-ppm';

    if (file_exists((string)$c_rowimage)) {
        $c_size = getimagesize((string)$c_rowimage);

        $block['content'] .= "catégorie: <a href='" . XOOPS_URL . "/modules/magalerie/galerie.php?uid=$c_uid' title='visiter la catégorie $c_catname'>" . $c_catname . '</a>';

        $block['content'] .= '<br><a href="' . $xoopsConfig['xoops_url'] . "/modules/magalerie/show.php?id=$c_id\">
	<img src=\"" . $xoopsConfig['xoops_url'] . '/modules/magalerie/galerie/' . $c_pathimg . '' . $c_catname . '/' . $c_img . "-ppm\" $c_size[3] title=\"voir cette image en détail\" style=\"padding: 5px\"></a>";
    }

    $block['content'] .= '<br>La plus visitée<br>' . $c_clic . ' clics.';

    $block['content'] .= '</td></tr></table><hr>';

    //$block['content'] .= "<img src=\"".$xoopsConfig['xoops_url']."/modules/magalerie/galerie/".$pathimg."".$catname."/".$img."-ppm\" $size[3] title=\"voir cette image en détail\" style=\"padding: 5px\"></a>";

    return $block;
}
