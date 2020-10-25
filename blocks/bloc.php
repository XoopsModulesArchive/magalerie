<?php

function b_magalerie_show()
{
    global $xoopsConfig, $xoopsDB, $xoopsUser;

    $block = [];

    $block['title'] = _MAGALERIE_BNAME;

    $block['content'] = '';

    $ramdom = $xoopsDB->query('SELECT id, cat, img FROM ' . $xoopsDB->prefix('magalerie') . " WHERE valid >= '1' ORDER BY RAND()");

    $row = $xoopsDB->getRowsNum($ramdom);

    [$id, $cat, $img] = $xoopsDB->fetchRow($ramdom);

    $rowimage = $xoopsConfig['root_path'] . 'modules/magalerie/galerie/' . $cat . '/' . $img . '-ppm';

    if (file_exists((string)$rowimage)) {
        $size = getimagesize((string)$rowimage);

        $block['content'] .= '<div align="center"><a href="' . $xoopsConfig['xoops_url'] . "/modules/magalerie/show.php?id=$id\"><img src=\"" . $xoopsConfig['xoops_url'] . '/modules/magalerie/galerie/' . $cat . '/' . $img . "-ppm\" $size[3] title=\"'._CAT.' $cat\"></a></div>";
    }

    return $block;
}
