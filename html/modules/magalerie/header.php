<?php

//id:magalerie/header.php
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

include '../../mainfile.php';
if (file_exists($xoopsConfig['root_path'] . 'modules/magalerie/language/' . $xoopsConfig['language'] . '/main.php')) {
    include $xoopsConfig['root_path'] . 'modules/magalerie/language/' . $xoopsConfig['language'] . '/main.php';
} else {
    include $xoopsConfig['root_path'] . 'modules/magalerie/language/french/main.php';
}
include 'functions.php';
include 'cache/config.php';
