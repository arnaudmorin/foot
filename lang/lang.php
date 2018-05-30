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

/***********************************************************************
 * Configuration & Variables
 **********************************************************************/

// Preconfigure the default language
include(_LANG_DEFAULT);
$langDefault = $langArray;

/***********************************************************************
 * Functions
 **********************************************************************/

/*
 * Functions loadLanguage is used to load a language array from the file
 * and append it to the default array defined here
 */
function loadLanguage($file = ""){
    global $langDefault;
    $lang = Array();
    
    // If file is empty, then try to determine the language with one of:
    // lang in GET or POST request
    // cookie CAN_betting_prefered_lang value
    // phone number
    // 
    if ($file == ""){
        // Check if lang change requested (HTTP GET or POST)
        if (isset($_REQUEST['lang']) && constant("_LANG_".$_REQUEST['lang']) != ""){
            $file = constant("_LANG_".$_REQUEST['lang']);
            // We prefer to use a cookie to share this information among all servers
            setcookie ( 'CAN_betting_prefered_lang', $_REQUEST['lang'], time()+60*60*24*_COOKIE_DURATION);
            // Also set in $_SESSION['user'] if cookie not working on client side
            if (isset($_SESSION['user']->language)) $_SESSION['user']->language = constant("_LANG_".$_REQUEST['lang']);
        }
        // Try with cookie
        else if(isset($_REQUEST['CAN_betting_prefered_lang']) && constant("_LANG_".$_REQUEST['CAN_betting_prefered_lang'])  != "" ){
            $file = constant("_LANG_".$_REQUEST['CAN_betting_prefered_lang']);
        }
        // Try with phone number saved in session (did on user identification)
        else if (isset($_SESSION['user']->language)){
            $file = $_SESSION['user']->language;
        }
        // Nothing found
        else{
            $file = _LANG_DEFAULT;
        }
    }
    
    // Add default language to lang
    $lang = $langDefault;
    
    // Now append specific language to lang
    include($file);
    foreach ($langArray as $key => $value){
        $lang[$key] = $value;
    }
    return $lang;
}
?>
