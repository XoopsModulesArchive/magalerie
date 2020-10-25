<?php

//id:magalerie/functions.php
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

function accept()
{
    global $xoopsDB, $xoopsUser, $memberHandler, $xoopsModuleConfig;

    if ($xoopsUser) {
        //$g_id=chgroup("magalerie");

        $g_id = $xoopsModuleConfig['groupname'];

        $nom = $xoopsUser->uname();

        $members = $memberHandler->getUsersByGroup($g_id, true);

        //$mlist= array();

        $mcount = count($members);

        for ($i = 0; $i < $mcount; $i++) {
            //$mlist[$members[$i]->getVar('uid')] = $members[$i]->getVar('uname');

            $verif = $members[$i]->getVar('uname');

            if ($nom == $verif) {
                return true;
            }

            return false;
        }
    }
}

/*
function chgroup($groupname){
global $xoopsDB;

$query=$xoopsDB->query("SELECT groupid FROM ".$xoopsDB->prefix("groups")." WHERE name='$groupname' ");
    list($g_id)=$xoopsDB->fetchrow($query);
    //$result .= "'$g_id', ";

    return $g_id;
}
*/

function removeaccents($texte)
{
    $string = strtr(
        $texte,
        'ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ',
        'aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn'
    );

    return $string;
}

function removespace($string, $string_depart, $rep)
{
    $compteur_mot = explode(' ', $string);

    $compteur = count($compteur_mot);

    if ($compteur > 1) {
        if (2 == $compteur) {
            $array = [$compteur_mot[0], $compteur_mot[1]];
        }

        if (3 == $compteur) {
            $array = [$compteur_mot[0], $compteur_mot[1], $compteur_mot[2]];
        }

        if (4 == $compteur) {
            $array = [$compteur_mot[0], $compteur_mot[1], $compteur_mot[2], $compteur_mot[3]];
        }

        if (5 == $compteur) {
            $array = [$compteur_mot[0], $compteur_mot[1], $compteur_mot[2], $compteur_mot[3], $compteur_mot[4]];
        }

        if (6 == $compteur) {
            $array = [$compteur_mot[0], $compteur_mot[1], $compteur_mot[2], $compteur_mot[3], $compteur_mot[4], $compteur_mot[5]];
        }

        $separation = implode('_', $array);

        $string = $separation;
    }

    if (($string != $string_depart) or (empty($separation))) {
        rename('' . $rep . '' . $string_depart . '', '' . $rep . '' . $string . '') or die(
            '<br><font color="RED">Pas buenos!</font> impossible de renommer le fichier <font color="#0000FF"> '
            . $string_depart
            . '</font> en <font color="#0000FF">'
            . $string
            . '</font> <p>Faites-le manuellement et recommencez</p>'
        );
    }

    return $string;
}

function cherchscat($ch_id, $table, $where)
{
    global $xoopsDB;

    $resul = [];

    $result = '';

    $query = $xoopsDB->query("SELECT $ch_id FROM " . $xoopsDB->prefix('' . $table . '') . " WHERE $where  ");

    while (list($test) = $xoopsDB->fetchRow($query)) {
        $result .= "'$test', ";
    }

    return $result;
}

function validcat($ch_id, $table, $where)
{
    global $xoopsDB;

    $resul = [];

    $result = '';

    $query = $xoopsDB->query("SELECT $ch_id FROM " . $xoopsDB->prefix('' . $table . '') . " WHERE $where ");

    while (list($test) = $xoopsDB->fetchRow($query)) {
        $result .= "'$test', ";
    }

    return $result;
}

function countimg($ch_id, $table)
{
    global $xoopsDB;

    $resul = [];

    $result = '';

    $query = $xoopsDB->query("SELECT $ch_id FROM " . $xoopsDB->prefix('' . $table . '') . " WHERE valid='1' ");

    while (list($test) = $xoopsDB->fetchRow($query)) {
        $result .= "'$test', ";
    }

    return $result;
}

function vignette_magick($exec, $imgname, $hauteur, $largeur, $image_path)
{
    $size = getimagesize($image_path . $imgname);

    $convert = $exec;

    $convert .= ' -size ' . $size[0] . 'x' . $size[1] . ' ' . $image_path . $imgname . '';

    $convert .= ' -resize ' . $hauteur . 'x' . $largeur . '';

    $convert .= ' -profile ' . $image_path . $imgname . '';

    $convert .= ' ' . $image_path . $imgname . '-ppm';

    $result = exec($convert);
}

function vignette($fichier_image, $scale, $max_v, $max_h, $source, $destination, $prefixe)
{
    // MAX_V = HAUTEUR -- MAX_H = LARGEUR

    // le nom de l'image "scalée" commencera par ti_ et le nom du fichier original

    $ti_fichier_image = $fichier_image . $prefixe;

    global $nomfichier;

    $im = imagecreatefromjpeg((string)$source . (string)$fichier_image);

    $v = imagesy($im); // $v prend la hauteur
    $h = imagesx($im); // $h prend la largeur
    //Floor Arrondi à l'entier inférieur

    //ON GERE LA HAUTEUR
    if ($v > $max_v) { // Si la hauteur Img, est plus grand que le max, on reduit
        $taux_hauteur = $v / $max_v;    // On recupere le taux necessaire pour retrecir
        $ti_v = (int)floor($max_v); // ti_v = taille final de la hauteur
        $ti_h = (int)floor($h / $taux_hauteur); // ti_h = taille final de la largeur
    } else {
        $ti_v = $v;
    } // Sinon on fixe la hauteur

    // Si il n'a pas deja subbit une modification de la taille

    if ('' != $ti_h) {
        $h_comp = $ti_h;
    } else {
        $h_comp = $h;
    }

    if ('' != $ti_v) {
        $v_comp = $ti_v;
    } else {
        $v_comp = $v;
    }

    //ON GERE LA LARGEUR

    if ($h_comp > $max_h) {
        $taux_largeur = $h_comp / $max_h;

        $ti_h = (int)floor($max_h);

        $ti_v = (int)floor($v_comp / $taux_largeur);
    } else {
        $ti_h = $h_comp;
    }

    //ON FABRIQUE LA VIGNETTE
    $ti_im = imagecreatetruecolor($ti_h, $ti_v); //soit ImageCreate ou ImageCreateTrueColor
    imagecopyresized($ti_im, $im, 0, 0, 0, 0, $ti_h, $ti_v, $h, $v);

    imagejpeg($ti_im, (string)$destination . (string)$ti_fichier_image, $scale);

    $nomfichier = $destination . $ti_fichier_image;
}

/*
Exemple pour creer une miniature :
vignette($fic,75,100,200,$path_source,$path_minia,"_st");
 Exemple pour creer une image reduite depassant pas les 500 :
($fic,75,500,500,$path_source,$path_dest_photo,"");
*/

function resize($image, $sm)
{
    // Permet de réduire une image juste à l'affichage

    // Auteur: Lageon Bruno

    // Email:  flashpassion@yahoo.fr

    // Web:    http://www.flashpassion.com

    global $image, $sm_largeur, $sm_hauteur;

    // obtenir la taille de l'image

    $arr = getimagesize((string)$image);

    //$arr = getimagesize("$path$image.$type");

    // initialisation de la variable largeur

    $largeur = $arr[0];

    // initialisation de la variable hauteur

    $hauteur = $arr[1];

    $facteur = ($largeur / $sm);

    // Vérifie si l'image est plus petite que $sm

    if ($largeur < $sm) {
        // si vrai retourne les mêmes valeurs d'origine de l'image

        $sm_largeur = $largeur;

        $sm_hauteur = $hauteur;

    // sinon affecte des nouvelles valeurs
    } else {
        $sm_largeur = ($largeur / $facteur);

        $sm_hauteur = ($hauteur / $facteur);
    }

    return $image;
}

function retour($img)
{
    if ($img) {
        $cond = 1;
    }

    return $cond;
}

function linkretour($uid)
{
    global $xoopsDB;

    $demande = 'select cid from ' . $xoopsDB->prefix('magalerie_cat') . " where id='$uid' ";

    $req = $xoopsDB->query($demande);

    while (list($cid) = $xoopsDB->fetchRow($req)) {
        if (0 != $cid) {
            $link = multiname($cid);

            $aref = (string)$link;

            $souscat_id = $cid;
        } else {
            $aref = '';

            $souscat_id = '';
        }
    }

    return [$aref, $souscat_id];
}

function link_catname($img_uid)
{
    global $xoopsDB;

    $rete = 'select cid,cat,valid from ' . $xoopsDB->prefix('magalerie_cat') . " where id='$img_uid'";

    $result = $xoopsDB->query($rete);

    [$cidcat, $rowcat, $accept] = $xoopsDB->fetchRow($result);

    if (0 == $cidcat) {
        $path_img = $rowcat;

        return [$path_img, $accept];
    }

    $rete2 = 'select cat from ' . $xoopsDB->prefix('magalerie_cat') . " where id='$cidcat'";

    $result2 = $xoopsDB->query($rete2);

    [$repsup] = $xoopsDB->fetchRow($result2);

    $path_img = "$repsup/$rowcat";

    return [$path_img, $accept];
}

function list_cat_form($action)
{
    global $xoopsDB, $cat;

    echo "<form method=\"post\" name=\"$cat\" action=\"$action\">";

    $demande = 'select cat from ' . $xoopsDB->prefix('magalerie_cat') . ' ';

    $req = $xoopsDB->query($demande);

    echo "<select name=\"cat\" onChange='submit()'>";

    echo "<option>$cat</option>";

    while (list($cat) = $xoopsDB->fetchRow($req)) {
        echo "<option value=\"$cat\">" . $cat . '</option>';
    }

    echo '</select>';

    echo '</form>';
}

function multiname($name)
{
    global $xoopsDB, $cid;

    $rete = 'select cat from ' . $xoopsDB->prefix('magalerie_cat') . " where id='$name'";

    $result = $xoopsDB->query($rete);

    while (list($rowcat) = $xoopsDB->fetchRow($result)) {
        return $rowcat;
    }
}

function checkrename()
{
    global $xoopsDB, $cid;

    $rete = 'select cat from ' . $xoopsDB->prefix('magalerie_cat') . " where id='$cid'";

    $result = $xoopsDB->query($rete);

    while (list($rowcat) = $xoopsDB->fetchRow($result)) {
        //echo "$rowcat";

        return $rowcat;
    }
}

function checkname()
{
    global $xoopsDB, $id;

    $rete = 'select cat from ' . $xoopsDB->prefix('magalerie_cat') . " where id='$id'";

    $result = $xoopsDB->query($rete);

    while (list($rowcat) = $xoopsDB->fetchRow($result)) {
        //echo "$rowcat";

        return $rowcat;
    }
}

function list_souscat($action, $texte)
{
    global $xoopsDB;

    echo "<form method=\"post\" name=\"uid\" action=\"$action\">";

    echo '' . _NEWSOUSREP . ' &nbsp;';

    $demande = 'select id, cat from ' . $xoopsDB->prefix('magalerie_cat') . " where cid='0' ";

    $req = $xoopsDB->query($demande);

    echo "<select name=\"uid\" onChange='submit()'>";

    echo "<option value=\"#\">$texte</option>";

    while (list($id, $cat) = $xoopsDB->fetchRow($req)) {
        if (0 != $cid) {
            $cat = "&nbsp;&nbsp;--$cat";
        }

        echo "<option value=\"$id\">" . $cat . '</option>';
    }

    echo '</select>';

    echo '</form>';
}

function list_cat($action, $texte)
{
    global $xoopsDB;

    echo "<form method=\"post\" name=\"cat\" action=\"$action\">";

    $demande = 'select cat from ' . $xoopsDB->prefix('magalerie_cat') . " where cid='0' ";

    $req = $xoopsDB->query($demande);

    echo "<select name=\"cat\" onChange='submit()'>";

    echo "<option>$texte</option>";

    while (list($cat) = $xoopsDB->fetchRow($req)) {
        echo "<option value=\"$cat\">" . $cat . '</option>';
    }

    echo '</select>';

    echo '</form>';
}

function convertorderbyin($orderby)
{
    if ('dateA' == $orderby) {
        $orderby = 'date ASC';
    }

    if ('hitsA' == $orderby) {
        $orderby = 'clic ASC';
    }

    if ('noteA' == $orderby) {
        $orderby = 'note ASC';
    }

    if ('dateD' == $orderby) {
        $orderby = 'date DESC';
    }

    if ('hitsD' == $orderby) {
        $orderby = 'clic DESC';
    }

    if ('noteD' == $orderby) {
        $orderby = 'note DESC';
    }

    return $orderby;
}

function convertorderbytrans($orderby)
{
    if ('clic ASC' == $orderby) {
        $orderbyTrans = '' . _MD_POPULARITYLTOM . '';
    }

    if ('clic DESC' == $orderby) {
        $orderbyTrans = '' . _MD_POPULARITYMTOL . '';
    }

    if ('date ASC' == $orderby) {
        $orderbyTrans = '' . _MD_DATEOLD . '';
    }

    if ('date DESC' == $orderby) {
        $orderbyTrans = '' . _MD_DATENEW . '';
    }

    if ('note ASC' == $orderby) {
        $orderbyTrans = '' . _MD_RATINGLTOH . '';
    }

    if ('note DESC' == $orderby) {
        $orderbyTrans = '' . _MD_RATINGHTOL . '';
    }

    return $orderbyTrans;
}

function convertorderbyout($orderby)
{
    if ('date ASC' == $orderby) {
        $orderby = 'dateA';
    }

    if ('clic ASC' == $orderby) {
        $orderby = 'clicA';
    }

    if ('note ASC' == $orderby) {
        $orderby = 'noteA';
    }

    if ('date DESC' == $orderby) {
        $orderby = 'dateD';
    }

    if ('clic DESC' == $orderby) {
        $orderby = 'clicD';
    }

    if ('note DESC' == $orderby) {
        $orderby = 'noteD';
    }

    return $orderby;
}
