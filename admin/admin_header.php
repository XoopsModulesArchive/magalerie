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
include '../../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';
require XOOPS_ROOT_PATH . '/include/cp_functions.php';
if ($xoopsUser) {
    $xoopsModule = XoopsModule::getByDirname('magalerie');

    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL . '/', 3, _NOPERM);

        exit();
    }
} else {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);

    exit();
}
if (file_exists(XOOPS_ROOT_PATH . '/modules/magalerie/language/' . $xoopsConfig['language'] . '/admin.php')) {
    require XOOPS_ROOT_PATH . '/modules/magalerie/language/' . $xoopsConfig['language'] . '/admin.php';
} else {
    require XOOPS_ROOT_PATH . '/modules/magalerie/language/french/admin.php';
}
require_once XOOPS_ROOT_PATH . '/modules/magalerie/include/functions.php';
//include (XOOPS_ROOT_PATH."/modules/magalerie/cache/config.php");
