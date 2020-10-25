<?php

//id:magalerie/appercu.php
##################################################################################
# Magalerie Version 1.2b                                                         #
# Projet du --/--/2002          dernière modification: 25/09/2002                #
# Scripts Home:                 http://www.lespace.org                           #
# auteur           :            bidou                                            #
# email            :            bidou@lespace.org                                #
# Site web         :		http://www.lespace.org                           #
# licence          :            Gpl                                              #
##################################################################################
# Merci de laisser cette entête en place.                                        #
##################################################################################
include 'header.php';

$myts = MyTextSanitizer::getInstance();
if ('' != $_POST[(string)$nom1]) {
    $message = htmlspecialchars($_POST[(string)$nom1], ENT_QUOTES | ENT_HTML5);
}
if ('' != $_POST[(string)$email1]) {
    $message = htmlspecialchars($_POST[(string)$email1], ENT_QUOTES | ENT_HTML5);
}
if ('' != $_POST[(string)$nom2]) {
    $message = htmlspecialchars($_POST[(string)$nom2], ENT_QUOTES | ENT_HTML5);
}
if ('' != $_POST[(string)$email2]) {
    $message = htmlspecialchars($_POST[(string)$email2], ENT_QUOTES | ENT_HTML5);
}
if ('' != $_POST[(string)$sujet]) {
    $message = htmlspecialchars($_POST[(string)$sujet], ENT_QUOTES | ENT_HTML5);
}
$sujet = stripslashes((string)$sujet);
if ('' != $_POST['message']) {
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES | ENT_HTML5);
}
$message = stripslashes((string)$message);
$size = getimagesize((string)$image);

echo '<html><head>'
     . "<style type=\"text/css\">font {  font-family: $police; font-size: $taille; color: #$color}"
     . '</style>'
     . '<title>Lespace Cartes virtuelles</title>'
     . '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">'
     . '</head>'
     . "<body bgcolor=\"$bodycolor\" text=\"#000000\">"
     . "<BGSOUND src='Midi/$music' AUTOSTART='true'>"
     . "<embed src='Midi/$music' AUTOSTART='true' HIDDEN='true'></embed>"
     . "<table width=\"95%\" border=\"5\" cellspacing=\"0\" cellpadding=\"4\" align=\"center\" bordercolor=\"#$bordercolor\">"
     . '<tr>'
     . '<td align="center">'
     . '<p>&nbsp;</p>'
     . "<p><img src=\"$image\" $size[3]></p>"
     . '<p>&nbsp;</p>'
     . "<table width=\"80%\" border=\"2\" cellspacing=\"0\" cellpadding=\"8\" bordercolor=\"#$bordercolor\">"
     . '<tr>'
     . '<td>'
     . "<p><font>Pour:<b> $nom2</b></font></p>"
     . '<blockquote>'
     . "<p><font>$message</font></p>"
     . '</blockquote>'
     . "<p align=\"right\"><font>Envoy&eacute; par <b>$nom1</b> email: <a href=\"mailto:$email1\">$email1</a></font></p>"
     . '</td>'
     . '</tr>'
     . '</table>'
     . '<p>&nbsp;</p>'
     . '<p>&nbsp;</p>'
     . '</td>'
     . '</tr>'
     . "</table>\n";

?>

<form method="post" action="maversion.php">
    <input type="hidden" name="image" size="25" value="<?php echo $image; ?>">
    <input type="hidden" name="nom1" size="25" value="<?php echo $nom1; ?>">
    <input type="hidden" name="email1" size="25" value="<?php echo $email1; ?>">
    <input type="hidden" name="nom2" size="25" value="<?php echo $nom2; ?>">
    <input type="hidden" name="email2" size="25" value="<?php echo $email2; ?>">
    <input type="hidden" name="sujet" size="25" value="<?php echo $sujet; ?>">
    <input type="hidden" name="message" size="25" value="<?php echo $message; ?>">
    <input type="hidden" name="music" value="<?php echo $music; ?>">
    <input type="hidden" name="bodycolor" value="<?php echo $bodycolor; ?>">
    <input type="hidden" name="bordercolor" value="<?php echo $bordercolor; ?>">
    <input type="hidden" name="police" value="<?php echo $police; ?>">
    <input type="hidden" name="taille" value="<?php echo $taille; ?>">
    <input type="hidden" name="color" value="<?php echo $color; ?>">
    <p align="center"><input type="submit" name="Submit" value="Envoyer">
        <input type="button" value="Retour" onclick="history.go(-1)"></p>
</form>
</body>
</html>

