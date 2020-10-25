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
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
$ModName = 'magalerie';
xoops_cp_header();
OpenTable();

echo '<table width="100%" border="0"><tr><td width="70%">';
echo '<h3>' . MG_ADMIN . '</h3></td><td>';

$req = $xoopsDB->query('SELECT id FROM ' . $xoopsDB->prefix('magalerie') . " where valid='0' ");
$nbval = $xoopsDB->getRowsNum($req);

if ($nbval > 1) {
    $s = '' . _PLURIEL1 . '';
} else {
    $s = '';
}
echo "$nbval " . _ATTENTE . '' . $s . '<br>';
while (list($valid) = $xoopsDB->fetchRow($req)) {
    echo '<a href="edit.php?gop=showedit&id=' . $valid . '"><img src="../images/admin_edit.gif" border=0 title=' . _EDIT . '></a>&nbsp;';
}
echo '</td></tr></table>';

echo '<p><a href="menuconf.php">' . MG_CONFIG . '</a></p>';

echo '<table width="100%"><tr><td width="200">';
echo '' . MG_LISTIMG . '</td><td>';
echo '';

echo '<form method="post" action="listing.php">';
echo '<b>' . _CHOICAT . '</b><br> ';
//$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
$mytree = new XoopsTree($xoopsDB->prefix('magalerie_cat'), 'id', 'cid');
$mytree->makeMySelBox('cat', 'id', 0, 1, 'uid', 'submit()');
echo '</form> ';
echo '</td></tr></table>';

echo '<table width="100%"><tr><td width="200">';
echo '' . _EDITCAT . ' </td><td>';
echo '<form method="post" action="edit.php">';
echo '<b>' . _CHOICAT . '</b>:<br> ';
$mytree = new XoopsTree($xoopsDB->prefix('magalerie_cat'), 'id', 'cid');
$mytree->makeMySelBox('cat', 'id', 0, 1, 'id', 'submit()');
echo '</form>';
echo '</td></tr></table>';
echo '<p><a href="charge.php">' . MG_ADD_ELEMENT . '</a></p>';
echo '<table width="100%"><tr><td width="200">';
echo '' . MG_EDITIMG . '</td><td>';
echo '<form method="post" action="edit.php?gop=showedit">';
echo '<b>id:</b> <input type="text" name="id"style="width:30px">&nbsp;<input type="submit" value="go">';
echo '</form>';
echo '</td></tr></table><br>';
CloseTable();
xoops_cp_footer();
