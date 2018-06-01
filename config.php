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

define("_DEBUG",1);                                                     // If 1, debug will be printed instead of log to file
define("_DEBUG_LOGIN", "arnaud");                                       // If all -> debug on all website for anybody
                                                                        // If a login -> debug only when this user is logged in

define("_AUTOCREATE_FRIENDLISTS", 1);                                   // If 1, the Friend lists will automatically be created when asked by users
                                                                        // If 0, the API groups creation will wait an administrator approval
                                                                        // 2014: This is now disabled

define("_ADD_IN_DEFAULT_FRIENDLIST", 1);                                // If 1, the user will be added in the DEFAULT_FRIENDLIST when registering
                                                                        // If 0, nothing happen
define("_DEFAULT_FRIENDLIST", $default_friendlist);                     // Default friendlist (variable defined in passwords.php)
define("_DEFAULT_FRIENDLIST_PASSWORD", $default_friendlist_password);   // Default friendlist password (variable defined in passwords.php)

// Website
define("_WEBSITE_BASEURL", "projects.emerginov.com");
define("_WEBSITE_BASEFOLDER", "/Foot");

// Lang things
define("_LANG_DEFAULT", "french.php");
define("_LANG_ENGLISH", "english.php");
define("_LANG_FRENCH", "french.php");
define("_LANG_DEFAULT_IVR", "Lise");
define("_LANG_FRENCH_IVR", "Lise");
define("_LANG_ENGLISH_IVR", "Bibi");

// Cookie 
define("_COOKIE_DURATION", 30 ); //in days

// Database configuration
define("_DB_NAME", $mysql_db_name);
define("_DB_HOST", $mysql_db_server);
define("_DB_USERNAME", $mysql_db_login);
define("_DB_PWD", $mysql_db_password);

// Notification 
// This is the time before the match to check and notif user if no bet is found. Format is SQL INTERVAL (see http://dev.mysql.com/doc/refman/5.0/fr/date-and-time-functions.html)
// Usuaylly 2 HOUR
define("_NOTIFICATION_NOT_BET", " 2 HOUR ");
// This is the interval to wait before sending resume notification to users with rank and results. Format is SQL INTERVAL (see http://dev.mysql.com/doc/refman/5.0/fr/date-and-time-functions.html)
// Usually it's 1 DAY
define("_NOTIFICATION_RESUME_INTERVAL", " 1 DAY ");
// This is the hour we must wait before sending notification (we consider that results will be entered before this time). 24 Hour format. Local time
define("_NOTIFICATION_RESUME_TIME", 4);

// Matches statuses
define("_PLAYED", "PLAYED");
define("_CANCELED", "CANCELED");
define("_DELAYED", "DELAYED");
define("_PLANNED", "PLANNED");

// Number of jokers when a user just registered
define("_NUMBER_OF_JOKERS", 3);

// Default timezone (usefull for date and time for matches
define("_TIMEZONE", "Europe/Paris");

// Default number of secondes before a match until bets are still open
define("_TIME_BEFORE_BET", 60);

// How points are computed
define("_POINTS_ROUND_groups-1_MAX",    3);
define("_POINTS_ROUND_groups-2_MAX",    3);
define("_POINTS_ROUND_groups-3_MAX",    3);
define("_POINTS_ROUND_eights_MAX",      5);
define("_POINTS_ROUND_quarter_MAX",     7);
define("_POINTS_ROUND_semi_MAX",        10);
define("_POINTS_ROUND_final_MAX",       15);

define("_POINTS_ROUND_groups-1_MIN",    1);
define("_POINTS_ROUND_groups-2_MIN",    1);
define("_POINTS_ROUND_groups-3_MIN",    1);
define("_POINTS_ROUND_eights_MIN",      2);
define("_POINTS_ROUND_quarter_MIN",     3);
define("_POINTS_ROUND_semi_MIN",        5);
define("_POINTS_ROUND_final_MIN",       7);

define("_POINTS_ROUND_groups-1_BONUS",  1);
define("_POINTS_ROUND_groups-2_BONUS",  1);
define("_POINTS_ROUND_groups-3_BONUS",  1);
define("_POINTS_ROUND_eights_BONUS",    2);
define("_POINTS_ROUND_quarter_BONUS",   2);
define("_POINTS_ROUND_semi_BONUS",      3);
define("_POINTS_ROUND_final_BONUS",     3);
?>
