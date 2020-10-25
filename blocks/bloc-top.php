<?php

#################################################
# Magalerie Version 1.6		E-xoops
# Projet du --/--/2002          dernière modification: 19/04/2003
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
function b_chgroup($groupname)
{
    global $xoopsDB;

    $query = $xoopsDB->query('SELECT groupid FROM ' . $xoopsDB->prefix('groups') . " WHERE name='$groupname' ");

    [$g_id] = $xoopsDB->fetchRow($query);

    return $g_id;
}

function b_validcat($ch_id, $table, $where)
{
    global $xoopsDB;

    $result = [];

    $result = '';

    $query = $xoopsDB->query("SELECT $ch_id FROM " . $xoopsDB->prefix('' . $table . '') . " WHERE $where ");

    while (list($test) = $xoopsDB->fetchRow($query)) {
        $result .= "'$test', ";
    }

    return $result;
}

function b_linkretour($uid)
{
    global $xoopsDB;

    $demande = 'select cid from ' . $xoopsDB->prefix('magalerie_cat') . " where id='$uid' ";

    $req = $xoopsDB->query($demande);

    while (list($cid) = $xoopsDB->fetchRow($req)) {
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
    global $xoopsDB, $cid;

    $rete = 'select cat from ' . $xoopsDB->prefix('magalerie_cat') . " where id='$name'";

    $result = $xoopsDB->query($rete);

    while (list($rowcat) = $xoopsDB->fetchRow($result)) {
        return $rowcat;
    }
}

function b_magalerie_show()
{
    global $xoopsConfig, $xoopsDB, $xoopsUser, $memberHandler;

    if ($xoopsUser) {
        $g_id = b_chgroup('magalerie');

        $nom = $xoopsUser->uname();

        $members = $memberHandler->getUsersByGroup($g_id, true);

        //$mlist= array();

        $mcount = count($members);

        for ($i = 0; $i < $mcount; $i++) {
            //$mlist[$members[$i]->getVar('uid')] = $members[$i]->getVar('uname');

            $test = $members[$i]->getVar('uname');

            if ($nom == $test) {
                $accept = true;
            } else {
                $accept = false;
            }
        }
    }

    if (empty($accept)) {
        $nbscat = b_validcat('id', 'magalerie_cat', "valid !='0'");

        $d_ramdom = $xoopsDB->query('SELECT id, uid, img, date FROM ' . $xoopsDB->prefix('magalerie') . " WHERE uid IN ($nbscat'') ORDER BY date DESC");
    } else {
        $d_ramdom = $xoopsDB->query('SELECT id, uid, img, date FROM ' . $xoopsDB->prefix('magalerie') . " WHERE valid >= '1' ORDER BY date DESC");

        $d_ramdom = $xoopsDB->query('SELECT id, uid, img, date FROM ' . $xoopsDB->prefix('magalerie') . " WHERE valid >= '1' ORDER BY date DESC");
    }

    $d_row = $xoopsDB->getRowsNum($d_ramdom);

    [$d_id, $d_uid, $d_img, $d_date] = $xoopsDB->fetchRow($d_ramdom);

    $d_pathimg = b_linkretour($d_uid);

    $d_catname = b_multiname($d_uid);

    $d_rowimage = $xoopsConfig['root_path'] . 'modules/magalerie/galerie/' . $d_pathimg . '' . $d_catname . '/' . $d_img . '-ppm';

    if (file_exists((string)$d_rowimage)) {
        $d_size = getimagesize((string)$d_rowimage);

        $block = [];

        $block['title'] = _MAGALERIE_BNAME;

        $block['content'] = '<div align=center>' . _CAT . ": <a href='" . XOOPS_URL . "/modules/magalerie/galerie.php?uid=$d_uid' title='" . _VISIT_CAT . " $d_catname'>" . $d_catname . '</a>';

        $block['content'] .= '<br><a href="' . $xoopsConfig['xoops_url'] . "/modules/magalerie/show.php?id=$d_id\" title=\"" . _VOIR_DETAIL . '">
	<img src="' . $xoopsConfig['xoops_url'] . '/modules/magalerie/galerie/' . $d_pathimg . '' . $d_catname . '/' . $d_img . "-ppm\" $d_size[3] alt=\"" . $d_img . '" style="padding: 5px"></a>';
    }

    $d_date = formatTimestamp($d_date, 's');

    $block['content'] .= '<br>' . _IMG_NEW . '<br>le ' . $d_date . '. <br><br>';

    //////////////////////////////////////////////////////

    //	$block['content'] .= "</td><td width='30%' align='center'>";

    if (empty($accept)) {
        $n_ramdom = $xoopsDB->query('SELECT id, uid, img, note FROM ' . $xoopsDB->prefix('magalerie') . " WHERE uid IN ($nbscat'') ORDER BY note DESC");
    } else {
        $n_ramdom = $xoopsDB->query('SELECT id, uid, img, note FROM ' . $xoopsDB->prefix('magalerie') . " WHERE valid >= '1' ORDER BY note DESC");
    }

    $n_row = $xoopsDB->getRowsNum($n_ramdom);

    [$n_id, $n_uid, $n_img, $n_note] = $xoopsDB->fetchRow($n_ramdom);

    $n_pathimg = b_linkretour($n_uid);

    $n_catname = b_multiname($n_uid);

    $n_rowimage = $xoopsConfig['root_path'] . 'modules/magalerie/galerie/' . $n_pathimg . '' . $n_catname . '/' . $n_img . '-ppm';

    if (file_exists((string)$n_rowimage)) {
        $n_size = getimagesize((string)$n_rowimage);

        $block['content'] .= '' . _CAT . ": <a href='" . XOOPS_URL . "/modules/magalerie/galerie.php?uid=$n_uid' title='" . _VISIT_CAT . " $n_catname'>" . $n_catname . '</a>';

        $block['content'] .= '<br><a href="' . $xoopsConfig['xoops_url'] . "/modules/magalerie/show.php?id=$n_id\" title=\"" . _VOIR_DETAIL . '">
	<img src="' . $xoopsConfig['xoops_url'] . '/modules/magalerie/galerie/' . $n_pathimg . '' . $n_catname . '/' . $n_img . "-ppm\" $n_size[3] alt=\"" . $n_img . '" style="padding: 5px"></a>';
    }

    $block['content'] .= '<br>' . _NOTE . '<br>' . $n_note . ' ' . _RANG_NOTE . '. <br><br>';

    //	$block['content'] .= "</td><td width='30%' align='center'>";

    if (empty($accept)) {
        $c_ramdom = $xoopsDB->query('SELECT id, uid, img, clic FROM ' . $xoopsDB->prefix('magalerie') . " WHERE uid IN ($nbscat'') ORDER BY clic DESC");
    } else {
        $c_ramdom = $xoopsDB->query('SELECT id, uid, img, clic FROM ' . $xoopsDB->prefix('magalerie') . " WHERE valid >= '1' ORDER BY clic DESC");
    }

    $c_row = $xoopsDB->getRowsNum($c_ramdom);

    [$c_id, $c_uid, $c_img, $c_clic] = $xoopsDB->fetchRow($c_ramdom);

    $c_pathimg = b_linkretour($c_uid);

    $c_catname = b_multiname($c_uid);

    $c_rowimage = $xoopsConfig['root_path'] . 'modules/magalerie/galerie/' . $c_pathimg . '' . $c_catname . '/' . $c_img . '-ppm';

    if (file_exists((string)$c_rowimage)) {
        $c_size = getimagesize((string)$c_rowimage);

        $block['content'] .= '' . _CAT . ": <a href='" . XOOPS_URL . "/modules/magalerie/galerie.php?uid=$c_uid' title='" . _VISIT_CAT . " $c_catname'>" . $c_catname . '</a>';

        $block['content'] .= '<br><a href="' . $xoopsConfig['xoops_url'] . "/modules/magalerie/show.php?id=$c_id\" title=\"" . _VOIR_DETAIL . '">
	<img src="' . $xoopsConfig['xoops_url'] . '/modules/magalerie/galerie/' . $c_pathimg . '' . $c_catname . '/' . $c_img . "-ppm\" $c_size[3] alt=\"" . $c_img . '" title="' . _VOIR_DETAIL . '" style="padding: 5px"></a>';
    }

    $block['content'] .= '<br>' . _VISIT . '<br>' . $c_clic . ' ' . _CLIC . '. </div>';

    //$block['content'] .= "</td></tr></table>";

    return $block;
}
