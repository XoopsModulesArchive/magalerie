<?php

include 'admin_header.php';
include 'admin-menu.php';
function Config()
{
    xoops_cp_header();

    OpenTable();

    echo "et mon cul c'est du poulet";

    include '../cache/config.php';

    echo "<form name='frmmessage' action='index.php?op=SaveConfig' method='post'>";

    echo _XTG_NBMSGBYPAGE . " : <input type='text' value='$nb' name='nb'><br>";

    if (1 == $allowbbcode) {
        $allowbbcode1 = "selected='selected'";
    } else {
        $allowbbcode0 = "selected='selected'";
    }

    if (1 == $allowhtml) {
        $allowhtml1 = "selected='selected'";
    } else {
        $allowhtml0 = "selected='selected'";
    }

    if (1 == $allowsmileys) {
        $allowsmileys1 = "selected='selected'";
    } else {
        $allowsmileys0 = "selected='selected'";
    }

    if (1 == $sendmail2webmaster) {
        $sendmail2webmaster1 = "selected='selected'";
    } else {
        $sendmail2webmaster0 = "selected='selected'";
    }

    echo _XTG_ALLOWBBCODE . " : <select name='allowbbcode'><option value='1' " . $allowbbcode1 . '>' . _XTG_YES . "</option><option value='0' " . $allowbbcode0 . '>' . _XTG_NO . '</option></select><br>';

    echo _XTG_ALLOWHTML . " : <select name='allowhtml'><option value='1' " . $allowhtml1 . '>' . _XTG_YES . "</option><option value='0' " . $allowhtml0 . '>' . _XTG_NO . '</option></select><br>';

    echo _XTG_ALLOWSMILEYS . " : <select name='allowsmileys'><option value='1' " . $allowsmileys1 . '>' . _XTG_YES . "</option><option value='0' " . $allowsmileys0 . '>' . _XTG_NO . '</option></select><br>';

    echo _XTG_SENDMAIL2WEBMASTER . " : <select name='sendmail2webmaster'><option value='1' " . $sendmail2webmaster1 . '>' . _XTG_YES . "</option><option value='0' " . $sendmail2webmaster0 . '>' . _XTG_NO . '</option></select><br>';

    echo "<input type='submit' name='submit' value='Ok'></form";

    CloseTable();

    xoops_cp_footer();
}

function SaveConfig($nbmessage, $allowbbcode, $allowhtml, $allowsmileys, $sendmail2webmaster)
{
    global $xoopsDB;

    $fp = fopen('../cache/config.php', 'wb');

    fwrite(
        $fp,
        '<?
$allowbbcode=' . $allowbbcode . ';
$allowhtml=' . $allowhtml . ';
$allowsmileys=' . $allowsmileys . ';
$nbmsgbypage=' . $nbmessage . ';
$sendmail2webmaster=' . $sendmail2webmaster . ';
?>'
    );

    fclose($fp);

    redirect_header('index.php', 1, _XTG_MODIFSAVE);
}

switch ($op) {
    case 'Messagesave':
        Messagesave($idmsg, $_POST['uname'], $_POST['url'], $_POST['email'], $_POST['title'], $_POST['message']);
        break;
    case 'Messageedit':
        Messageedit($_GET['idmsg']);
        break;
    case 'Messagedel':
        Messagedel($_GET['idmsg']);
        break;
    case 'Messagedel1':
        Messagedel1($_GET['idmsg']);
        break;
    case 'Messageshow':
        Messageshow();
        break;
    default:
        Config();
        break;
    case 'SaveConfig':
        SaveConfig($nbmessage, $_POST['allowbbcode'], $_POST['allowhtml'], $_POST['allowsmileys'], $_POST['sendmail2webmaster']);
        break;
    //default:
    //	Choice();
    //	break;
}
