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
include 'header.php';
$requete = $xoopsDB->query('select id, uid, img from ' . $xoopsDB->prefix('magalerie') . " WHERE id = '$id' ");
while (false !== ($ligne = $GLOBALS['xoopsDB']->fetchObject($requete))) {
    $uid = $ligne->uid;

    $img = $ligne->img;

    $cat = multiname($uid);

    [$pathimg, $cat_uid] = linkretour($uid);

    $pathimg .= '/';

    $img = 'galerie/' . $pathimg . $cat . '/' . $img . '';
}
$GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('magalerie') . " SET clic=clic+1 WHERE id='$id'");

echo "<html>\n" . " <head><title>Galerie d_images</title></head>\n" . " <body style=\"background-color: #FFFFFF; vertical-align: middle; text-align: center; margin: 0;\">\n" . "  <center>\n" . "<img src=\"$img\">\n" . "  </center>\n" . " </body>\n" . "</html>\n";
