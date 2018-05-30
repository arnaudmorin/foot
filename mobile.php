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

// Load PHP require mecanism
require_once("loader.php");

// Load HTML headers
require_once("header.php");

// all menus configuration
$menu_non_aut=array(    #$lang['menu-index'] => "index.php",
                        $lang['menu-matches-results'] => "matches_results.php",
                        $lang['menu-help'] => "help.php",
                        "<span class='menu_highlight'>".$lang['menu-register']."</span>"=> "register.php",
                        "<span class='menu_highlight'>".$lang['menu-connect']."</span>"=> "identification.php",
                        );

$menu_ins=array(    #$lang['menu-index'] => "index.php",
                    #$lang['menu-account'] => "account.php",
                    $lang['menu-dobet'] => "do_bet.php",
//                    $lang['menu-inbox'] => "inbox.php",
                    $lang['menu-matches-results'] => "matches_results.php",
                    $lang['menu-ranking'] => "rank.php",
                    $lang['menu-help'] => "help.php",
                    "<span class='menu_highlight'>".$lang['menu-disconnect']."</span>" => "deconnexion.php",
                    );

$menu_web=array(    #"<span class='menu_highlight'>".$lang['menu-add-match']."</span>" => "add_match.php",
                    "<span class='menu_highlight'>".$lang['menu-add-result']."</span>" => "set_result.php",
                    #$lang['menu-index'] => "index.php",
                    #$lang['menu-account'] => "account.php",
                    $lang['menu-dobet'] => "do_bet.php",
//                    $lang['menu-inbox'] => "inbox.php",
                    $lang['menu-matches-results'] => "matches_results.php",
                    $lang['menu-ranking'] => "rank.php",
                    $lang['menu-help'] => "help.php",
                    "<span class='menu_highlight'>".$lang['menu-disconnect']."</span>" => "deconnexion.php",
                    );


// Check what menu should be loaded for this user (authenticated or not)
$menu = $menu_non_aut;
if (isset($_SESSION['user'])) $menu = $menu_ins;
if (isset($_SESSION['user']) && $_SESSION['user']->isAdmin) $menu = $menu_web;

// Start menu div
echo "<div class='menu'>\n";
echo "<h1>".$lang['menu-title-mobile']."</h1>\n";
echo "<ul>\n";

// Friendlist selector
if (isset($_SESSION['user']) && isset($_SESSION['friendlist']) && count($_SESSION['user']->friendlists) > 1){
    $pageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
    echo "<form method='post' action='$pageName'>";
    echo "<div class='menu-select-friendlist-div'><select name='change-friendlist-requested' class='menu-select-friendlist' onchange='submit();'>";
    foreach ($_SESSION['user']->friendlists as $friendlistName){
        $s = "";
        if (isset($_REQUEST['change-friendlist-requested'])){
            if ($_REQUEST['change-friendlist-requested'] == $friendlistName){
                // This friendlist must be selected in the selector
                $s = "selected='selected'";
                
                // Populate session variable for all the website
                $_SESSION['friendlist'] = new Friendlist($friendlistName);
            }
        }
        else{
            if ($_SESSION['friendlist']->name == $friendlistName) $s = "selected='selected'";
        }
        echo "<option value='$friendlistName' class='menu-select-options' $s>$friendlistName</option>";
    }
    echo "</select></div></form>";
}

// Each menu for this user
foreach($menu as $titres=>$sstitres){
    echo "<li><a href='$sstitres'>$titres</a></li>\n";
    }

// End of menu
echo "</ul>\n";
echo "</div>\n";

require_once("footer.php");
?>
