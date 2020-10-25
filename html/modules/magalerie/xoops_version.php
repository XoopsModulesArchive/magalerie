<?php

$modversion['name'] = _MAGALERIE_NAME;
$modversion['version'] = 1.20;
$modversion['description'] = _MAGALERIE_DESC;
$modversion['credits'] = 'Magalerie v1.2b pour xoop (version-rc3)';
$modversion['author'] = 'bidou<br>bidou@lespace.org - http://www.lespace.org/';
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
$modversion['tables'][2] = 'magalerie_comments';

// Menu
$modversion['hasMain'] = 1;

$modversion['blocks'][1]['file'] = 'bloc.php';
$modversion['blocks'][1]['name'] = _MAGALERIE_NAME;
$modversion['blocks'][1]['description'] = 'Le bloc ma galerie';
$modversion['blocks'][1]['show_func'] = 'b_magalerie_show';
