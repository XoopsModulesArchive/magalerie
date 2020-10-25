<?php

function bs_chgroup($groupname)
{
    global $xoopsDB;

    $query = $xoopsDB->query('SELECT groupid FROM ' . $xoopsDB->prefix('groups') . " WHERE name='$groupname' ");

    [$g_id] = $xoopsDB->fetchRow($query);

    return $g_id;
}

function bs_validcat($ch_id, $table, $where)
{
    global $xoopsDB;

    $resul = [];

    $result = '';

    $query = $xoopsDB->query("SELECT $ch_id FROM " . $xoopsDB->prefix('' . $table . '') . " WHERE $where ");

    while (list($test) = $xoopsDB->fetchRow($query)) {
        $result .= "'$test', ";
    }

    return $result;
}

function bs_linkretour($uid)
{
    global $xoopsDB;

    $demande = 'select cid from ' . $xoopsDB->prefix('magalerie_cat') . " where id='$uid' ";

    $req = $xoopsDB->query($demande);

    while (list($cid) = $xoopsDB->fetchRow($req)) {
        if (0 != $cid) {
            $link = bs_multiname($cid);

            $aref = "$link/";
        } else {
            $aref = '';
        }
    }

    return $aref;
}

function bs_multiname($name)
{
    global $xoopsDB, $cid;

    $rete = 'select cat from ' . $xoopsDB->prefix('magalerie_cat') . " where id='$name'";

    $result = $xoopsDB->query($rete);

    while (list($rowcat) = $xoopsDB->fetchRow($result)) {
        return $rowcat;
    }
}

function bs_magalerie_show()
{
    //require_once (XOOPS_ROOT_PATH."/modules/magalerie/functions.php");

    //require_once (XOOPS_ROOT_PATH."/modules/magalerie/language/french/modinfo.php");

    global $xoopsConfig, $xoopsDB, $xoopsUser, $memberHandler;

    ///////////////////

    if ($xoopsUser) {
        $g_id = bs_chgroup('magalerie');

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

    ///////////////////

    $block = [];

    $block['title'] = _MAGALERIE_BNAME;

    $block['content'] = '';

    if (empty($accept)) {
        $nbscat = bs_validcat('id', 'magalerie_cat', "valid !='0'");

        $ramdom = $xoopsDB->query('SELECT id, uid, img FROM ' . $xoopsDB->prefix('magalerie') . " WHERE uid IN ($nbscat'') ORDER BY RAND()");
    } else {
        $ramdom = $xoopsDB->query('SELECT id, uid, img FROM ' . $xoopsDB->prefix('magalerie') . " WHERE valid >= '1' ORDER BY RAND()");
    }

    $row = $xoopsDB->getRowsNum($ramdom);

    [$id, $uid, $img] = $xoopsDB->fetchRow($ramdom);

    $pathimg = bs_linkretour($uid);

    $catname = bs_multiname($uid);

    $rowimage = $xoopsConfig['root_path'] . 'modules/magalerie/galerie/' . $pathimg . '' . $catname . '/' . $img . '-ppm';

    if (file_exists((string)$rowimage)) {
        $size = getimagesize((string)$rowimage);

        $block['content'] .= '<div align="center"><a href="'
                             . $xoopsConfig['xoops_url']
                             . "/modules/magalerie/show.php?id=$id\"><img src=\""
                             . $xoopsConfig['xoops_url']
                             . '/modules/magalerie/galerie/'
                             . $pathimg
                             . ''
                             . $catname
                             . '/'
                             . $img
                             . "-ppm\" $size[3] title=\""
                             . $pathimg
                             . ''
                             . $catname
                             . '"></a></div>';
    }

    return $block;
}
