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
require_once("loader.php");
require_once("header.php");

// Check that the user is identified
require_once("checkAuth.php");

// Check that the user is an admin
require_once("checkAdmin.php");

// Get back all matchs not already played
$matches = $sql->fetchAssoc($sql->doQuery("SELECT * FROM matches WHERE status = '". _PLANNED ."' ORDER BY date"));

echo "<h3>".$lang['setresult-title']."</h3>\n
    <form method='POST' action='set_result.php'>\n";

// For each match
foreach ($matches as $match){
    // Check if we need to set results
    if (   isset($_REQUEST['submitter'])
        && isset($_REQUEST['team1_'.$match['id_match']]) && is_numeric($_REQUEST['team1_'.$match['id_match']])
        && isset($_REQUEST['team2_'.$match['id_match']]) && is_numeric($_REQUEST['team2_'.$match['id_match']])){
        
        $req = "UPDATE matches
                SET
                    team1_result = '".mysqli_real_escape_string($_REQUEST['team1_'.$match['id_match']])."',
                    team2_result = '".mysqli_real_escape_string($_REQUEST['team2_'.$match['id_match']])."',
                    status = '"._PLAYED."'
                WHERE
                    id_match = '".mysqli_real_escape_string($match['id_match'])."'
                ";
        $res = $sql->doQuery($req);
        if (!$res){
            error($lang['setresult-cant-set']);
            debug(mysql_error());
            debug($req);
        }
        else{
            info(   $lang['setresult-set-success'] . "</br>" .
                    $lang['teams-'.$match['team1_name']] . " 
                    <b>".$_REQUEST['team1_'.$match['id_match']]."</b>
                     - 
                    <b>".$_REQUEST['team2_'.$match['id_match']]."</b> " .
                    $lang['teams-'.$match['team2_name']]
                );
            continue;
        }
    }
    
    echo "<div class='setresult-line'>\n";
    echo "  <div class='setresult-team1'>".$lang['teams-'.$match['team1_name']]."</div>\n";
    echo "  <div class='setresult-inputdiv'><input type='number' class='setresult-input' name='team1_".$match['id_match']."' size='4' min='0' max='99'/></div>\n";
    echo " - ";
    echo "  <div class='setresult-inputdiv'><input type='number' class='setresult-input' name='team2_".$match['id_match']."' size='4' min='0' max='99'/></div>\n";
    echo "  <div class='setresult-team2'>".$lang['teams-'.$match['team2_name']]."</div>\n";
    echo "  <div class='setresult-actions'>\n"; // TODO
    echo "      <div class='setresult-cancel'></div>\n";
    echo "      <div class='setresult-delay'></div>\n";
    echo "      <div class='setresult-delete'></div>\n";
    echo "  </div>\n";
    echo "</div>\n";
}

echo "  <p></p><div class='setresult-line'><input type='submit' value='".$lang['setresult-go']."' name='submitter'></div>
    </form>";
    
?>
