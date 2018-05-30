<?php
/*
    Copyright 2011 Emerginov Team <admin@emerginov.org>
    
    This file is part of Emerginov Scripts.

    This script is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    This script is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this script.  If not, see <http://www.gnu.org/licenses/>.

*/
/*

PHP libraries loaders, includes, etc.

*/

// Load all files
require_once('passwords.php');
require_once('config.php');
require_once('lang/lang.php');
require_once('includes/miscFunctions.php');
require_once('Emerginov.php');
require_once('includes/User.class.php');
require_once('includes/Friendlist.class.php');
require_once('includes/SqlConnector.class.php');
require_once('includes/Mobile_Detect.php');
require_once('includes/simple_html_dom.php');

// Start session
session_start();

// Load language
$lang = loadLanguage();

// Create an new SQL connector object
$sql = new SqlConnector();

$detect = new Mobile_Detect();

//debug(date_default_timezone_get());
//debug(date('Y-m-d H:i:s'));
//debug(date('Z'));
// Set the default timezone
date_default_timezone_set(_TIMEZONE);
//debug(date_default_timezone_get());
//debug(date('Y-m-d H:i:s'));
//debug(date('Z'));
if (_DEBUG) ini_set('display_errors', 'On');
if (_DEBUG) {
    error_reporting(E_ALL);
}
else {
    error_reporting(0);
}

// Friendlist change - was in menus, is now here to prevent ugly reloading page hack
if (isset($_SESSION['user']) && isset($_SESSION['friendlist']) && count($_SESSION['user']->friendlists) > 1 && isset($_REQUEST['change-friendlist-requested'])){
    // If user is involved in this friendlist
    if (in_array($_REQUEST['change-friendlist-requested'],$_SESSION['user']->friendlists)){
        // Populate session variable for all the website
        $_SESSION['friendlist'] = new Friendlist($_REQUEST['change-friendlist-requested']);
    }
}
?>
