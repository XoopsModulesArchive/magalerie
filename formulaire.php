<?php

//id:magalerie/appercu.php
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
include 'header.php';

$accept = accept();

if (!empty($accept)) {
    require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
} else {
    require_once XOOPS_ROOT_PATH . '/modules/magalerie/class/xoopstree.php';
}
if ('0' == $xoopsModuleConfig['validup']) {
    redirect_header('index.php', 1, '' . _NOPERM . '');

    exit();
}
if (!$xoopsUser) {
    if ('0' == $xoopsModuleConfig['anonymes']) {
        redirect_header('index.php', 1, '' . _NOPERM . '');

        exit();
    }
}

include $xoopsConfig['root_path'] . 'header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

$catname = multiname($uid);
[$pathimg, $cat_uid] = linkretour($uid);
if ('' != $pathimg) {
    $pathimg .= '/';
}
$cat = '' . $pathimg . '' . $catname . '';
$photomax1 = $xoopsModuleConfig['photomax'] / 1024;

$form = $mytree = new XoopsTree($xoopsDB->prefix('magalerie_cat'), 'id', 'cid');
ob_start();
$mytree->makeMySelBox('cat', 'id', 0, 1, 'uid', 'submit()');
$selbox = ob_get_contents();
ob_end_clean();
$fromcat = new XoopsThemeForm('Changer de catégorie', '', $PHP_SELF);
$fromcat->addElement(new XoopsFormLabel(_CHOISIRCAT, $selbox));
$fromcat->addElement(new XoopsFormHidden('op', 'test'));
$fromcat->display();
echo '<br>';
$form = new XoopsThemeForm(_AJOUTER . $cat, 'imageupload', 'Addimg.php');
$form->setExtra('enctype="multipart/form-data"');
$form->addElement(new XoopsFormLabel(_LIMIT, $xoopsModuleConfig['photomax'] . sprintf(' => %.2f ko', $photomax1)));
$form->addElement(new XoopsFormFile(_IMGFILE, 'image', $xoopsModuleConfig['photomax']), true);
$form->addElement(new XoopsFormDhtmlTextArea(_DESCRIP . _FACULT, 'descript', '', 15, 60), true);
$form->addElement(new XoopsFormHidden('op', 'image_upload'));
$form->addElement(new XoopsFormHidden('cat', $cat));
$form->addElement(new XoopsFormHidden('uid', $uid));
$form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
$form->display();

include '../../footer.php';
