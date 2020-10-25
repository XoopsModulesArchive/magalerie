<?php

//id:magalerie/appercu.php
##################################################################################
# Magalerie Version 1.2b                                                         #                                                                                #
# Projet du 30/05/2002		dernière modification: 25/09/2002                #
# Scripts Home:                 http://www.lespace.org                           #              							                 #                                                                              #
# auteur        :		bidou                                            #
# email         :		bidou@lespace.org                                #
# Site web	:		http://www.lespace.org                           #
# licence	:		Gpl                                              #
##################################################################################
# Merci de laisser cette entête en place.                                        #
##################################################################################
include 'header.php';

if ('0' == $validup) {
    redirect_header('index.php', 1, '' . _NOPERM . '');

    exit();
}
if (!$xoopsUser) {
    if ('0' == $anonymes) {
        redirect_header('index.php', 1, '' . _NOPERM . '');

        exit();
    }
}

//function ajoutimg(){

//global $xoopsUser, $xoopsDB, $xoopsConfig, $xoopsTheme, $catid, $cat, $photomax, $allowsmileys, $allowbbcode;
include $xoopsConfig['root_path'] . 'header.php';

$photomax1 = $photomax / 1024;

//OpenTable();

echo '<div align="center"><b>' . _AJOUTER . "</b></div>\n";
echo "<br>\n";
echo "<table align=\"center\" cellspacing=\"2\" cellpadding=\"4\" border=\"0\" width=\"90%\" class=\"bg2\">\n"
     . "<tr>\n"
     . '<td colspan="2" class="bg3" width="100%"><b>'
     . _COMPL
     . "</b></td>\n"
     . "</tr>\n"
     . "<tr>\n"
     . '<td width="30%" class="bg3">'
     . _CHOISIRCAT
     . "</td>\n"
     . "<td width=\"70%\" class=\"bg3\">\n";
list_cat('formulaire.php');
echo "<form method=\"post\" action=\"Addimg.php\" ENCTYPE=\"multipart/form-data\" NAME=\"add\">\n";
//."<input type=\"hidden\" name=\"go\" value=\"Addimg\">\n";
echo "</td>\n" . "</tr>\n" . "<tr class=\"bg3\">\n" . '<td>' . _CHOISIPAYS . "</td>\n" . "<td><input type=\"hidden\" name=\"cat\" value=\"$cat\"><b>" . $cat . "</b>\n";
echo "</td></tr>\n" . "<tr class=\"bg3\">\n" . '<td>' . _IMGFILE . ':<br>' . _LIMIT . '<br>';
printf('%.2f ko', $photomax1);
echo "</td>\n" . '<td><INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="10000000"><input type=file name="photo">';
echo "</td>\n";
echo "</tr>\n";
echo '</table>';
echo '<br><div align="center"><b>Ajouter un commentaire</b> (facultatif)</div><br>';
echo '<table align="center" cellspacing="2" cellpadding="4" border="0" width="90%" class="bg2">';
echo "<tr class=\"bg3\">\n" . '<td>' . _NOM . "</td>\n" . '<td>';
if ($xoopsUser) {
    $nom = $xoopsUser->uname();

    echo "$nom</td>";
} else {
    $nom = 'Anonyme';

    echo "$nom <a href=\"../../register.php\"> (" . _INSCRIPT . ")</a></td>\n";
}
echo "</tr>\n";
echo "<tr class=\"bg3\">\n" . '<td>' . _NOMCAM . "</td>\n" . "<td><input type=\"text\" name=\"titre\"></td>\n";

echo "</tr>\n" . "<tr class=\"bg3\">\n" . '<td valign="top">' . _DESCRIPTCAM . "</td>\n" . "<td><br>\n";
include '../../include/xoopscodes.php';
if (1 == $allowbbcode) {
    xoopsCodeTarea('coment', $cols = 50, $rows = 10);
} else {
    echo "<textarea id='coment' name='coment' wrap='virtual' cols='50' rows='10'></textarea><br>";
}
if (1 == $allowsmileys) {
    xoopsSmilies('coment');
}
echo "</td>\n";
echo "</tr>\n";

echo "</table>\n";
echo '<div align="center"><br><input type="submit" name="add" value="' . _ENVOITURE . "\">&nbsp;&nbsp;</div>\n";
echo "</form>\n";
echo "<br><br>\n";
echo '<br>';

//CloseTable();
include '../../footer.php';
//}//fin validup
//}//fin function
