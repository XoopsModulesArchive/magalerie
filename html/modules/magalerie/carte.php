<?php

//id:magalerie/carte.php
##################################################################################
# Magalerie Version 1.2b                                                         #                                                                                #
# Projet du 30/05/2002          dernière modification: 25/09/2002                #
# Scripts Home:                 http://www.lespace.org                           #              							                 #                                                                              #
# auteur           :            bidou                                            #
# email            :            bidou@lespace.org                                #
# Site web         :		http://www.lespace.org                           #
# licence          :            Gpl                                              #
##################################################################################
# Merci de laisser cette entête en place.                                        #
##################################################################################
include 'header.php';
if ('0' == $ecard) {
    redirect_header('index.php', 1, '' . _NOPERM . '');

    exit();
}
if (!$xoopsUser) {
    if ('0' == $anonymes) {
        redirect_header('index.php', 1, '' . _NOPERM . '');

        exit();
    }
}

include '../../header.php';
$size = getimagesize((string)$image);
$requete = $GLOBALS['xoopsDB']->queryF('select img from ' . $xoopsDB->prefix('magalerie') . " WHERE id = '$id' ");
while (false !== ($ligne = $xoopsDB->fetchArray($requete))) {
    $image = "galerie/$cat/$ligne[img]";
}
?>
<script language="JavaScript">
    <!--
    function MM_findObj(n, d) { //v4.0
        var p, i, x;
        if (!d) d = document;
        if ((p = n.indexOf("?")) > 0 && parent.frames.length) {
            d = parent.frames[n.substring(p + 1)].document;
            n = n.substring(0, p);
        }
        if (!(x = d[n]) && d.all) x = d.all[n];
        for (i = 0; !x && i < d.forms.length; i++) x = d.forms[i][n];
        for (i = 0; !x && d.layers && i < d.layers.length; i++) x = MM_findObj(n, d.layers[i].document);
        if (!x && document.getElementById) x = document.getElementById(n);
        return x;
    }

    function MM_validateForm() { //v4.0
        var i, p, q, nm, test, num, min, max, errors = '', args = MM_validateForm.arguments;
        for (i = 0; i < (args.length - 2); i += 3) {
            test = args[i + 2];
            val = MM_findObj(args[i]);
            if (val) {
                nm = val.name;
                if ((val = val.value) != "") {
                    if (test.indexOf('isEmail') != -1) {
                        p = val.indexOf('@');
                        if (p < 1 || p == (val.length - 1)) errors += '- ' + nm + ' must contain an e-mail address.\n';
                    } else if (test != 'R') {
                        if (isNaN(val)) errors += '- ' + nm + ' must contain a number.\n';
                        $result
                        if (test.indexOf('inRange') != -1) {
                            p = test.indexOf(':');
                            min = test.substring(8, p);
                            max = test.substring(p + 1);
                            if (val < min || max < val) errors += '- ' + nm + ' must contain a number between ' + min + ' and ' + max + '.\n';
                        }
                    }
                } else if (test.charAt(0) == 'R') errors += '- ' + nm + ' is required.\n';
            }
        }
        if (errors) alert('The following error(s) occurred:\n' + errors);
        document.MM_returnValue = (errors == '');
    }

    //-->
</script>
<?php

echo '<b>' . $xoopsConfig['sitename'] . " Cartes virtuelles: $cat</b><br>";
echo '<div align="center">';
echo '<br>';
include 'navig_cat_carte.php';
echo "<br><img border=\"0\" src=\"$image\" $size[3]><br>";
echo '<br>';
include 'navig_cat_carte.php';
echo '<form method="POST" name="formulaire" action="appercu.php">' . "<input type=\"hidden\" name=\"image\" value=\"$image\">";

echo '</div>';

echo '<table width="80%" border="0" cellspacing="2" cellpadding="2" align="center" class="bg2">'
     . '<tr>'
     . '<td width="25%" class="bg4"><b>Sujet:</b></td>'
     . '<td width="25%" class="bg4" align="center">'
     . "<input type=\"text\" name=\"sujet\" size=\"20\" value=\"$sujet\">"
     . '</td>'
     . '<td rowspan="6" align="center" width="39%" class="bg4">'
     . '<p> <br>'
     . '<b>Message</b> <br>'
     . '<textarea rows="6" name="message" cols="30"></textarea>'
     . '<br>'
     . '</p>'
     . '</td>'
     . '</tr>'
     . '<tr>'
     . '<td width="25%" class="bg4"><b>Votre nom:&nbsp;</b></td>'
     . '<td width="25%" class="bg4" align="center">'
     . "<input type=\"text\" name=\"nom1\" size=\"20\" value=\"$nom1\">"
     . '</td>'
     . '</tr>'
     . '<tr>'
     . '<td width="25%" class="bg4"><b>Votre Email:</b></td>'
     . '<td width="25%" class="bg4" align="center">'
     . "<input type=\"text\" name=\"email1\" size=\"20\" value=\"$email1\">"
     . '</td>'
     . '</tr>'
     . '<tr>'
     . '<td width="25%" class="bg4"><b>Nom du destinataire :</b></td>'
     . '<td width="25%" class="bg4" align="center">'
     . "<input type=\"text\" name=\"nom2\" size=\"20\" value=\"$nom2\">"
     . '</td>'
     . '</tr>'
     . '<tr>'
     . '<td width="25%" class="bg4"><b>E-mail du destinataire:&nbsp;</b></td>'
     . '<td width="25%" class="bg4" align="center">'
     . "<input type=\"text\" name=\"email2\" size=\"20\" value=\"$email2\">"
     . '</td>'
     . '</tr>'
     . '<tr>'
     . '<td width="25%" class="bg4"><b>Musique:</b></td>'
     . '<td width="25%" class="bg4" align="center">';
?>
<select name="music">
    <option>Aucune</option>
    <option>----Cinema----</option>
    <option value="Cinema/bladerunner.mid">bladerunner</option>
    <option value="Cinema/jamesbond.mid">jamesbond</option>
    <option value="Cinema/madaboutyou.mid">madaboutyou</option>
    <option value="Cinema/startrek.mid">startrek</option>
    <option value="Cinema/starwars.mid">starwars</option>
    <option value="Cinema/superman.mid">superman</option>
    <option value="Cinema/thesimpson.mid">thesimpson</option>
    <option value="Cinema/titanic.mid">titanic</option>
    <option value="Cinema/wonderyears.mid">wonderyears</option>
    <option value="Cinema/xfile.mid">xfile</option>
    <option></option>
    <option>----Classique----</option>
    <option value="Classique/Bach_aria.mid">Bach_aria</option>
    <option value="Classique/Claudio_Monteverdi_balleto.mid">balleto</option>
    <option value="Classique/Handel_aleluia.mid">Handel_aleluia</option>
    <option value="Classique/Handel_hornpipe.mid">Handel_hornpipe</option>
    <option value="Classique/Villa_Lobos_estudo_em_e_menor.mid">Villa_Lobos</option>
    <option value="Classique/Wagner_cavalgada_das_valquirias.mid">Wagner</option>
    <option></option>
    <option>----Electronique----</option>
    <option value="Electronique/Edwin_van_Veldhoven_eurydice.mid">van_Veldhoven</option>
    <option value="Electronique/Edwin_van_Veldhoven_tonatiuh.mid">van_Veldhoven</option>
    <option></option>
    <option>----Evenements----</option>
    <option value="Evenement/happy.mid">happy</option>
    <option value="Evenement/happy2.mid">happy2</option>
    <option value="Evenement/xmas_1.mid">xmas1</option>
    <option value="Evenement/xmas_wewish.mid">xmas wewish</option>
    <option></option>
    <option>----Pop Rock----</option>
    <option value="Pop_Rock/Beatles_dont_let_me_down.mid">Beatles</option>
    <option value="Pop_Rock/Dire_Straits_dire_straits.mid">Dire Straits</option>
    <option value="Pop_Rock/Dire_Straits_sultans_of_swing.mid">Dire Straits2</option>
    <option value="Pop_Rock/Elton_John_can_you_feel_the_love_tonigh.mid">Elton
        John
    </option>
    <option value="Pop_Rock/Elton_John_dont_let_the_sun.mid">Elton John2</option>
    <option value="Pop_Rock/Enya_lothrien.mid">Enya lothrien</option>
    <option value="Pop_Rock/John_Lennon_imagine.mid">John Lennon</option>
    <option value="Pop_Rock/Led_Zepelin_stairway_to_heaven.mid">Led Zepelin</option>
    <option value="Pop_Rock/Louis_Armstrong_wonderful_world.mid">Louis Armstrong</option>
    <option value="Pop_Rock/Men_at_Work_way_down_under.mid">Men at Work</option>
    <option value="Pop_Rock/Pachelbel_canon_em_d.mid">Pachelbel</option>
    <option value="Pop_Rock/Phil_Collins_against_all_odds.mid">Phil Collins</option>
    <option value="Pop_Rock/Tears_for_Fears_everybody_wants_to_tule_the_world.mid">Tears
        for Fears
    </option>
    <option value="Pop_Rock/U2_where_the_streets_have_no_name.mid">U2</option>
    <option value="Pop_Rock/Van_Halen_dreams.mid">Van Halen</option>
    <option value="Pop_Rock/Van_Halen_right_now.mid">Van_Halen2</option>
    <option value="Pop_Rock/Villa_Lobos_estudo_em_e_menor.mid">Villa Lobos</option>
</select>


</td>
</tr>
<tr>
    <td width="31%" class="bg4"><b>Texte:</b></td>
    <td width="42%" class="bg4" align="center">
        <?php

        $sizearray = ['xx-small', 'x-small', 'small', 'medium', 'large', 'x-large', 'xx-large'];
        echo "<select name='taille' onchange='setVisible(\"hiddenText\");setElementSize(\"hiddenText\",this.options[this.selectedIndex].value);'>\n";
        echo "<option value='SIZE'>" . _SIZE . "</option>\n";
        foreach ($sizearray as $size) {
            echo "<option value='$size'>$size</option>\n";
        }
        echo "</select>\n";

        $fontarray = ['Arial', 'Courier', 'Georgia', 'Helvetica', 'Impact', 'Verdana'];
        echo "<select name='police' onchange='setVisible(\"hiddenText\");setElementFont(\"hiddenText\",this.options[this.selectedIndex].value);'>\n";
        echo "<option value='FONT'>" . _FONT . "</option>\n";
        foreach ($fontarray as $font) {
            echo "<option value='$font'>$font</option>\n";
        }
        echo "</select>\n";

        $colorarray = ['00', '33', '66', '99', 'CC', 'FF'];
        echo "<select name='color' onchange='setVisible(\"hiddenText\");setElementColor(\"hiddenText\",this.options[this.selectedIndex].value);'>\n";
        echo "<option value='COLOR'>" . _COLOR . "</option>\n";
        foreach ($colorarray as $color1) {
            foreach ($colorarray as $color2) {
                foreach ($colorarray as $color3) {
                    echo "<option value='" . $color1 . $color2 . $color3 . "' style='background-color:#" . $color1 . $color2 . $color3 . ';color:#' . $color1 . $color2 . $color3 . ";'>#" . $color1 . $color2 . $color3 . "</option>\n";
                }
            }
        }

        ?>
    </td>
    <td rowspan="3" align="center" width="27%" class="bg4">
        <?php
        echo "</select><span id='hiddenText'>" . _EXAMPLE . "</span>\n";
        ?>
    </td>
</tr>
<tr>
    <td width="31%" class="bg4"><b>Couleur du fond:</b></td>
    <td width="42%" class="bg4" align="center">
        <?php
        $colorarray = ['00', '33', '66', '99', 'CC', 'FF'];
        echo "<select name='bodycolor' >\n";
        echo "<option value='#FFFFFF'>" . _COLOR . "</option>\n";
        foreach ($colorarray as $color1) {
            foreach ($colorarray as $color2) {
                foreach ($colorarray as $color3) {
                    echo "<option value='" . $color1 . $color2 . $color3 . "' style='background-color:#" . $color1 . $color2 . $color3 . ';color:#' . $color1 . $color2 . $color3 . ";'>#" . $color1 . $color2 . $color3 . "</option>\n";
                }
            }
        }
        echo '</select>';
        ?>
    </td>
</tr>
<tr>
    <td width="31%" class="bg4"><b>Couleur des bordures:</b></td>
    <td width="42%" class="bg4" align="center">
        <?php
        $colorarray = ['00', '33', '66', '99', 'CC', 'FF'];
        echo "<select name='bordercolor' >\n";
        echo "<option value='COLOR'>" . _COLOR . "</option>\n";
        foreach ($colorarray as $color1) {
            foreach ($colorarray as $color2) {
                foreach ($colorarray as $color3) {
                    echo "<option value='" . $color1 . $color2 . $color3 . "' style='background-color:#" . $color1 . $color2 . $color3 . ';color:#' . $color1 . $color2 . $color3 . ";'>#" . $color1 . $color2 . $color3 . "</option>\n";
                }
            }
        }

        echo '</select>';
        ?>
    </td>
</tr>
</table>
<p align="center">
    <input type="submit" value="Appercu" name="B1" onClick="MM_validateForm('nom1','','R');MM_validateForm('email1','','RisEmail');MM_validateForm('email2','','RisEmail');MM_validateForm('sujet','','R','email1','','R','message','','R');return document.MM_returnValue">
    <input type="reset" value="Annuler" name="B2"></p>
</form>
<?php
include '../../footer.php';
?>

