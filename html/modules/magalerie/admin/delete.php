<?php

include 'admin_header.php';

function confirm_img()
{
    global $xoopsDB, $id, $cat, $img;

    OpenTable();

    xoops_cp_header();

    echo "<form method=\"post\" action=\"$PHP_SELF\">\n";

    echo "<input type=\"hidden\" name=\"op\" value=\"del_img\">\n";

    echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";

    echo "<input type=\"hidden\" name=\"cat\" value=\"$cat\">\n";

    echo "<input type=\"hidden\" name=\"img\" value=\"$img\">\n";

    echo '<div align="center"><b>' . _CONFDEL . '</b><br>' . _GARDE . "<br><br><img src=\"../galerie/$cat/$img-ppm\"><br><br>\n";

    echo '<input type="submit" value="' . _YES . "\">&nbsp\n";

    echo '<input type="button" value="' . _NO . "\" onclick=\"history.go(-1)\"></div>\n";

    echo "</form>\n";

    CloseTable();

    xoops_cp_footer();
}

function confirm_cat()
{
    global $xoopsDB, $id, $cat, $img;

    OpenTable();

    xoops_cp_header();

    CloseTable();

    xoops_cp_footer();
}

function del_img()
{
    global $xoopsDB, $id, $cat, $img;

    $supp = 'DELETE FROM ' . $xoopsDB->prefix('magalerie') . " WHERE id='$id' ";

    $GLOBALS['xoopsDB']->queryF($supp);

    $supp_com = 'DELETE FROM ' . $xoopsDB->prefix('magalerie_comments') . " WHERE lid='$id' ";

    $GLOBALS['xoopsDB']->queryF($supp_com);

    echo $GLOBALS['xoopsDB']->error();

    $Fnm = "../galerie/$cat/$img";

    $Fnm2 = "../galerie/$cat/$img-ppm";

    unlink($Fnm);

    unlink($Fnm2);

    redirect_header('index.php?cat=' . $cat . '', 1, 'La base est mis a jour!');

    exit();
}

function del_cat()
{
    global $xoopsDB, $id, $img;

    $supp = 'DELETE FROM ' . $xoopsDB->prefix('magalerie_cat') . " WHERE ID='$id' ";

    $GLOBALS['xoopsDB']->queryF($supp);

    echo $GLOBALS['xoopsDB']->error();

    redirect_header($GLOBALS['HTTP_REFERER'], 1, 'La base est mis a jour');

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

            $GLOBALS['xoopsDB']->queryF($supp);

            echo $GLOBALS['xoopsDB']->error();
        }
    }

    redirect_header("commentaire.php?lid=$lid", 1, _AJOUR);

    exit();
}//Fin de la function delete

switch ($op) {
    case 'confirm_coment':
        confirm_coment();
        break;
    case 'delete_coment':
        delete_coment();
        break;
    case 'del_img':
        del_img();
        break;
    default:
        confirm_img();
        break;
}
