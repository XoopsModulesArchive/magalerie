<?php

include '../../mainfile.php';
global $xoopsConfig, $xoopsDB;

$GLOBALS['xoopsDB']->fetchBoth($GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $xoopsDB->prefix('magalerie') . " WHERE img=$img"));
$GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('magalerie') . " SET clic=clic+1 WHERE id='$id'");
echo "<html>\n" . " <head><title>$name</title></head>\n" . " <body style=\"background-color: black; vertical-align: middle; text-align: center; margin: 0;\">\n" . "  <center>\n" . "<img src=\"galerie/$cat/$img\">\n" . "  </center>\n" . " </body>\n" . "</html>\n";
