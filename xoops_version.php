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
$modversion['name'] = _MAGALERIE_NAME;
$modversion['version'] = 1.7;
$modversion['description'] = _MAGALERIE_DESC;
$modversion['credits'] = 'Magalerie v1.7 pour xoops v2xxx';
$modversion['author'] = 'bidou<br>bidou@lespace.org - http://www.lespace.org/ <br>traduction english par<br>Romuald trans@freesurf.ch';
$modversion['help'] = '';
$modversion['license'] = 'GPL see LICENSE';
$modversion['official'] = 0;
$modversion['image'] = 'images/galerie.png';
$modversion['dirname'] = 'magalerie';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = 'magalerie';
$modversion['tables'][1] = 'magalerie_cat';

// Menu
$modversion['hasMain'] = 1;

//Blocks
$modversion['blocks'][1]['file'] = 'bloc-top.php';
$modversion['blocks'][1]['name'] = _MAGALERIE_BNAME;
$modversion['blocks'][1]['description'] = _MAG_BLOC_CC;
$modversion['blocks'][1]['show_func'] = 'b_magalerie_show';

$modversion['blocks'][2]['file'] = 'bloc-simple.php';
$modversion['blocks'][2]['name'] = _MAGALERIE_BNAME;
$modversion['blocks'][2]['description'] = _MAG_2BLOC_S;
$modversion['blocks'][2]['show_func'] = 'bs_magalerie_show';

// Comments
$modversion['hasComments'] = 1;
$modversion['comments']['pageName'] = 'show.php';
$modversion['comments']['itemName'] = 'id';
// Comment callback functions
$modversion['comments']['callbackFile'] = 'include/comment_functions.php';
$modversion['comments']['callback']['approve'] = 'magalerie_com_approve';
$modversion['comments']['callback']['update'] = 'magalerie_com_update';

$modversion['templates'][1]['file'] = 'magalerie_index.html';
$modversion['templates'][1]['description'] = '';

$modversion['templates'][2]['file'] = 'magalerie_galerie.html';
$modversion['templates'][2]['description'] = '';

$modversion['templates'][3]['file'] = 'magalerie_show.html';
$modversion['templates'][3]['description'] = '';

$modversion['templates'][4]['file'] = 'magalerie_index_skyn.html';
$modversion['templates'][4]['description'] = '';

$modversion['templates'][5]['file'] = 'magalerie_galerie_skyn.html';
$modversion['templates'][5]['description'] = '';
// Config Settings (only for modules that need config settings generated automatically)

// name of config option for accessing its specified value. i.e. $xoopsModuleConfig['storyhome']
$modversion['config'][1]['name'] = 'nb';
$modversion['config'][1]['title'] = '_NBVIGN';
$modversion['config'][1]['description'] = '';
$modversion['config'][1]['formtype'] = 'select';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 12;
$modversion['config'][1]['options'] = ['4' => 4, '6' => 6, '9' => 9, '10' => 10, '12' => 12, '14' => 14, '15' => 15];

$modversion['config'][2]['name'] = 'themepage';
$modversion['config'][2]['title'] = '_NBPPAGE';
$modversion['config'][2]['description'] = '';
$modversion['config'][2]['formtype'] = 'select';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = 3;
$modversion['config'][2]['options'] = ['1' => 1, '2' => 2, '3' => 3];

$modversion['config'][3]['name'] = 'listcat';
$modversion['config'][3]['title'] = '_LISTCAT';
$modversion['config'][3]['description'] = '';
$modversion['config'][3]['formtype'] = 'yesno';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 1;
////////
$modversion['config'][4]['name'] = 'navigcat';
$modversion['config'][4]['title'] = '_NAVIGCAT';
$modversion['config'][4]['description'] = '';
$modversion['config'][4]['formtype'] = 'yesno';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 0;

$modversion['config'][5]['name'] = 'anonymes';
$modversion['config'][5]['title'] = '_ANONPOST';
$modversion['config'][5]['description'] = '';
$modversion['config'][5]['formtype'] = 'yesno';
$modversion['config'][5]['valuetype'] = 'int';
$modversion['config'][5]['default'] = 1;

$modversion['config'][6]['name'] = 'validup';
$modversion['config'][6]['title'] = '_UPFILE';
$modversion['config'][6]['description'] = '';
$modversion['config'][6]['formtype'] = 'yesno';
$modversion['config'][6]['valuetype'] = 'int';
$modversion['config'][6]['default'] = 1;

$modversion['config'][7]['name'] = 'confirmup';
$modversion['config'][7]['title'] = '_COMFIRMUP';
$modversion['config'][7]['description'] = '';
$modversion['config'][7]['formtype'] = 'yesno';
$modversion['config'][7]['valuetype'] = 'int';
$modversion['config'][7]['default'] = 0;

$modversion['config'][8]['name'] = 'photomax';
$modversion['config'][8]['title'] = '_MAXUP';
$modversion['config'][8]['description'] = '';
$modversion['config'][8]['formtype'] = 'textbox';
$modversion['config'][8]['valuetype'] = 'int';
$modversion['config'][8]['default'] = 100000;

$modversion['config'][9]['name'] = 'funcvignette';
$modversion['config'][9]['title'] = '_CHOIX_PROG';
$modversion['config'][9]['description'] = '';
$modversion['config'][9]['formtype'] = 'select';
$modversion['config'][9]['valuetype'] = 'int';
$modversion['config'][9]['default'] = 1;
$modversion['config'][9]['options'] = ['aucun(null)' => 0, 'GD' => 1, 'ImageMagick' => 2];

$modversion['config'][10]['name'] = 'exec';
$modversion['config'][10]['title'] = '_IF_MAGICK';
$modversion['config'][10]['description'] = '';
$modversion['config'][10]['formtype'] = 'textbox';
$modversion['config'][10]['valuetype'] = 'char';
$modversion['config'][10]['default'] = '/user/bin/convert';

$modversion['config'][11]['name'] = 'hauteur';
$modversion['config'][11]['title'] = '_MAXHAUT';
$modversion['config'][11]['description'] = '';
$modversion['config'][11]['formtype'] = 'textbox';
$modversion['config'][11]['valuetype'] = 'init';
$modversion['config'][11]['default'] = 160;

$modversion['config'][12]['name'] = 'largeur';
$modversion['config'][12]['title'] = '_MAXLARG';
$modversion['config'][12]['description'] = '';
$modversion['config'][12]['formtype'] = 'textbox';
$modversion['config'][12]['valuetype'] = 'init';
$modversion['config'][12]['default'] = 160;

$modversion['config'][13]['name'] = 'showmax';
$modversion['config'][13]['title'] = '_SHOWMAX';
$modversion['config'][13]['description'] = '_SHOWMAX_EX';
$modversion['config'][13]['formtype'] = 'textbox';
$modversion['config'][13]['valuetype'] = 'init';
$modversion['config'][13]['default'] = 750;

$modversion['config'][14]['name'] = 'groupname';
$modversion['config'][14]['title'] = '_MAG_GROUP';
$modversion['config'][14]['description'] = '_MAG_GROUP_CONF';
$modversion['config'][14]['formtype'] = 'group';
$modversion['config'][14]['valuetype'] = 'init';
$modversion['config'][14]['default'] = '';

$modversion['config'][15]['name'] = 'sendmail';
$modversion['config'][15]['title'] = '_SENDMAIL';
$modversion['config'][15]['description'] = '';
$modversion['config'][15]['formtype'] = 'yesno';
$modversion['config'][15]['valuetype'] = 'init';
$modversion['config'][15]['default'] = 1;

$modversion['config'][16]['name'] = 'ecard';
$modversion['config'][16]['title'] = '_CARDPOST';
$modversion['config'][16]['description'] = '_CARDPOST_EX';
$modversion['config'][16]['formtype'] = 'yesno';
$modversion['config'][16]['valuetype'] = 'init';
$modversion['config'][16]['default'] = 1;

$modversion['config'][17]['name'] = 'popup';
$modversion['config'][17]['title'] = '_MIDI';
$modversion['config'][17]['description'] = '';
$modversion['config'][17]['formtype'] = 'yesno';
$modversion['config'][17]['valuetype'] = 'init';
$modversion['config'][17]['default'] = 1;
