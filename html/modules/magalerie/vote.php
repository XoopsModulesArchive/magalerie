<?php

include '../../mainfile.php';
//global $xoopsConfig, $xoopsDB, $id, $cat, $img, $vote, $note;
$GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('magalerie') . " SET vote=vote+1 WHERE id='$id'");
$GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('magalerie') . " SET note=note+$note WHERE id='$id'");

redirect_header("show.php?id=$id", 1, '' . _VOTEMERCI . '');
exit();
