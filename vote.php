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
include '../../mainfile.php';

$xoopsDB->query('UPDATE ' . $xoopsDB->prefix('magalerie') . " SET vote=vote+1 WHERE id='$id'");
$xoopsDB->query('UPDATE ' . $xoopsDB->prefix('magalerie') . " SET note=note+$note WHERE id='$id'");

redirect_header("show.php?id=$id", 1, '' . _VOTEMERCI . '');
exit();
