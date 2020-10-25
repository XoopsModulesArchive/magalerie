<?php

//id:magalerie/maversion.php
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
$myts = MyTextSanitizer::getInstance();
$nom1 = $myts->addSlashes($nom1);
$email1 = $myts->addSlashes($email1);
$nom2 = $myts->addSlashes($nom2);
$email2 = $myts->addSlashes($email2);
$sujet = stripslashes((string)$sujet);
$sujet = $myts->addSlashes($sujet);
$message = stripslashes((string)$message);
$message = $myts->addSlashes($message);
$codehtml = '<html><head>'
            . "<style type=\"text/css\">font {  font-family: $police; font-size: $taille; color: #$color}"
            . '</style>'
            . '<title>Cartes virtuelles</title>'
            . '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">'
            . '</head>'
            . "<body bgcolor=\"$bodycolor\" text=\"#000000\">"
            . "<BGSOUND src='"
            . $xoopsConfig['xoops_url']
            . "/modules/magalerie/Midi/$music' AUTOSTART='true'>"
            . "<embed src='"
            . $xoopsConfig['xoops_url']
            . "/modules/magalerie/Midi/$music' AUTOSTART='true' HIDDEN='true'></embed>"
            . "<table width=\"95%\" border=\"5\" cellspacing=\"0\" cellpadding=\"4\" align=\"center\" bordercolor=\"#$bordercolor\">"
            . '<tr>'
            . '<td align="center">'
            . '<p>&nbsp;</p>'
            . "<p><img src='"
            . $xoopsConfig['xoops_url']
            . "/modules/magalerie/$image'></p>"
            . '<p>&nbsp;</p>'
            . "<table width=\"80%\" border=\"2\" cellspacing=\"0\" cellpadding=\"8\" bordercolor=\"#$bordercolor\">"
            . '<tr>'
            . '<td>'
            . '<p><font>'
            . _POUR
            . "<b> $nom2</b></font></p>"
            . '<blockquote>'
            . "<p><font>$message</font></p>"
            . '</blockquote>'
            . '<p align="right"><font>'
            . _POSTER
            . " <b>$nom1</b> "
            . _MAIL
            . ": <a href=\"mailto:$email1\">$email1</a></font></p>"
            . '</td>'
            . '</tr>'
            . '</table><p>&nbsp;</p>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '<p align="center">'
            . $xoopsConfig['sitename']
            . ' Module Magalerie<br><a href="'
            . $xoopsConfig['xoops_url']
            . '"> '
            . $xoopsConfig['xoops_url']
            . '</a></p>'
            . '</body>'
            . "</html>\n";

mail((string)$email2, (string)$sujet, $codehtml, "From: $email1\nReply-To: $email1\nContent-Type: text/html; charset=\"iso-8859-1\"\n");

redirect_header('index.php', 3, '' . _MAILTO . " $nom2, $email2<br><b>" . _BINGO . '</B>');
exit();
