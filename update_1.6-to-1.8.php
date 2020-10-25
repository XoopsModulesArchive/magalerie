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

include '../../mainfile.php';
include '../../header.php';

if (!is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid())) {
    exit('Access Denied');
}

$module_id = $xoopsModule->mid();

$req = ' ALTER TABLE ' . $xoopsDB->prefix('magalerie') . ' DROP INDEX img ';
@$GLOBALS['xoopsDB']->queryF($req) or print('<br>Impossible de modifier la table magalerie<br>si le caractère unique existe, Faites le manuellement<br>Si neccéssaire.<br>');

$req2 = 'ALTER TABLE ' . $xoopsDB->prefix('magalerie') . ' ADD comments INT (11) UNSIGNED AFTER date , ADD description TEXT AFTER comments ';
@$GLOBALS['xoopsDB']->queryF($req2) or die('<br>Impossible de ajouter les champs comments et description<br>dans la tale magalerie.<br><br>');

echo '<h1 align="center">OK</h1>';

$result1 = $xoopsDB->query('SELECT lid FROM ' . $xoopsDB->prefix('magalerie_comments') . ' ');
while (list($lid) = $xoopsDB->fetchRow($result1)) {
    @$GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('magalerie') . " SET comments=comments+1 WHERE id='$lid'") or die('niquer');
}

$result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('magalerie_comments') . ' ');
while (list($id, $lid, $uid, $titre, $texte, $date) = $xoopsDB->fetchRow($result)) {
    $new_id = $GLOBALS['xoopsDB']->getInsertId();

    $nex_id = $new_id + 1;

    $res = @$GLOBALS['xoopsDB']->queryF("INSERT INTO xoops_xoopscomments VALUES ('', '', '$nex_id', '$module_id', '$lid', '0', '$date', '0', '$uid', '', '$titre', '$texte', '0', '2', '', '0', '1', '1', '1', '1')") or die('Un problème est survenu pendant la mise a jour des commentaires<br><br>');
}

echo '<h1 align="center">OK</h1>';
include '../../footer.php';
