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

// TODO: gerer les jokers!!!

require_once("loader.php");
require_once("header.php");

// Check that the user is identified
require_once("checkAuth.php");

// Check that the user is in a friendlist
require_once("checkFriendlist.php");

if (isset($_REQUEST['submit'])){
    // We want to bet for matches
    // For all data in $_REQUEST
    foreach ($_REQUEST as $name => $value){
        if (preg_match("/^team1_([0-9]+)/", $name, $res)){
            // TODO: joker
            $joker = false;
            // Match ID is in $res[1]
            $matchID = $res[1];
            
            // Do we have a bet for team1 and team2
            // and is those values numeric?
            if (is_numeric($value) && is_numeric($_REQUEST['team2_'.$matchID])){
                // Yes, convert these numeric values to int
                $team1 = intval($value);
                $team2 = intval($_REQUEST['team2_'.$matchID]);
                
                // Is this bet valid for all friendlists?
                // If yes, $friendlists will be empty and doBet will apply on all 
                // user's friendlists
                $friendlists = array();
                if (!(isset($_REQUEST['default_'.$matchID]) && $_REQUEST['default_'.$matchID] == true)){
                    // No, add only current friendlist in $friendlists array
                    $friendlists[] = $_SESSION['friendlist']->name;
                }
                
                // Let's do the bet!
                $_SESSION['user']->doBet(
                    $matchID, 
                    $team1, 
                    $team2,
                    $friendlists,
                    $joker //TODO!
                );
            }
        }
    }
}

?>
<script>
	function switchTime(switchToTime){
        if (switchToTime == 'local'){
            switchFromTime = 'timezone';
        }
        else{
            switchFromTime = 'local';
        }
        
        // Switch the message
        $('#date-switch-message').html( $('#date-switch-' + switchFromTime + '-time').html() );
        // Switch the href of the message link
        $('#date-switch-message').attr('href', 'javascript:switchTime("' + switchFromTime + '")');
        
        // Switch the date spans
        $('span[name^="date_"]').each(
            function(index){
                $(this).html($('#' + $(this).attr('name') + '_' + switchToTime).html());
            });
        // Switch the time spans
        $('span[name^="time_"]').each(
            function(index){
                $(this).html($('#' + $(this).attr('name') + '_' + switchToTime).html());
            });
    }
</script>
<?php
// Show title
echo "<h3>".$lang['dobet-title']."</h3>"
     ."<form method='POST' action='do_bet.php'>"
     ."<div class='generic-small-label'>"
        ."<div id='date-switch-local-time' class='invisible'>".$lang['date-switch-local-time']."</div>"
        ."<div id='date-switch-timezone-time' class='invisible'>".$lang['date-switch-timezone-time']."</div>"
        ."<a href='javascript:switchTime(\"local\")' id='date-switch-message'>"
            .$lang['date-switch-local-time']
        ."</a>"
     ."</div>";

// Get all matches with bets done for this guy
$matches = $_SESSION['user']->getAllMatchesWithBets($_SESSION['friendlist']->name);

foreach ($matches as $matchArray){
    $match = $matchArray['match'];
    $bet = $matchArray['bet'];
    
    // Prepare team1 and team2 bets already done
    $betTeam1 = "";
    $betTeam2 = "";
    if ($bet){
        $betTeam1 = $bet['team1'];
        $betTeam2 = $bet['team2'];
    }
    
    // Check if bet still possible
    $inputDisabled = checkIfBetStillPossible($match['date']) ? "" : "disabled='disabled'";
    
    // Get pronostics for this match
    $pronostics = getPronosticsMatch($match['id_match']);
    debug($pronostics);
    
    // Determine where to position the cursor
    $xStart = 0.49;
    $xEnd = 0.51;
    if (!($pronostics['team1'] == $pronostics['team2'])){
        $x = $pronostics['team2'] / ($pronostics['team1'] + $pronostics['team2']);
        $xStart = $x - 0.01;
        $xEnd = $x + 0.01;
        
        if ($xStart<0) $xStart=0;
        if ($xEnd>100) $xEnd=100;
    }
    
    echo "<div class='dobet-betContainer'>"
            ."<div class='invisible'>"
                ."<div id='date_".$match['id_match']."_local'>".date($lang['date-format-date'], strtotime($match['date']) - date('Z') + $_SESSION['user']->timeOffsetSec)."</div>"
                ."<div id='date_".$match['id_match']."_timezone'>".date($lang['date-format-date'], strtotime($match['date']))."</div>"
                ."<div id='time_".$match['id_match']."_local'>".date($lang['date-format-time'], strtotime($match['date']) - date('Z') + $_SESSION['user']->timeOffsetSec)."</div>"
                ."<div id='time_".$match['id_match']."_timezone'>".date($lang['date-format-time'], strtotime($match['date']))."</div>"
            ."</div>"
            ."<div class='dobet-label'>"
                .$lang['dobet-date-1'] // On
                ."<b><span name='date_".$match['id_match']."'>".date($lang['date-format-date'], strtotime($match['date']))."</span></b>" // November, 30
                .$lang['dobet-date-2'] // at
                ."<b><span name='time_".$match['id_match']."'>".date($lang['date-format-time'], strtotime($match['date']))."</span></b>" // 8.00 pm
                .$lang['dobet-versus-1'] // , the team
                ."<b>".$lang['teams-'.$match['team1_name']]."</b>" // Angola
                .$lang['dobet-versus-2'] // will play against
                ."<b>".$lang['teams-'.$match['team2_name']].".</b>" // Sudan
            ."</div>"
            ."<div class='dobet-whatisyourbet'>";
    echo        $bet ? $lang['dobet-your-bet-is'] : $lang['dobet-what-is-your-bet'];
    echo    "</div>"
            ."<div class='dobet-line'>"
                ."<div class='dobet-team1'>"
                    ."<b>".$lang['teams-'.$match['team1_name']]."</b>" // Angola
                ."</div>"
                ."<div class='dobet-inputdiv'>"
                    ."<input type='number' class='dobet-input' min='0' max='99' name='team1_".$match['id_match']."' size='4' value='$betTeam1' $inputDisabled />"
                ."</div>"
                ." - "
                ."<div class='dobet-inputdiv'>"
                    ."<input type='number' class='dobet-input' min='0' max='99' name='team2_".$match['id_match']."' size='4' value='$betTeam2' $inputDisabled />"
                ."</div>"
                ."<div class='dobet-team2'>"
                    ."<b>".$lang['teams-'.$match['team2_name']]."</b>" // Sudan
                ."</div>"
            ."</div>"
            ."<div class='dobet-line'>"
                ."<img src='http://chart.apis.google.com/chart?chf=a,s,000000DC|bg,lg,0,EFEFEF00,0,000000,0.5,00000000,1&chbh=a&chs=300x5&cht=bhs&chd=s0:A&chm=r,FFFF00,0,$xStart,$xEnd,1' width='250' height='5' alt='tendance' />"
            ."</div>";
            
    // If user has many friendlists
    if (count($_SESSION['user']->friendlists)>1){
        echo "<div class='dobet-line'>"
                ."<div class='generic-small-label'>"
                    ."<input type='checkbox' name='default_".$match['id_match']."' id='default_".$match['id_match']."' value='1' checked $inputDisabled>"
                    ."<label for='default_".$match['id_match']."'>"
                        .$lang['dobet-default-for-all-friendlists']
                    ."</label>"
                ."</div>"
            ."</div>";
    }
    
    echo "</div>";
}

echo "<p></p><div class='dobet-line'>"
        ."<input type='submit' value='".$lang['dobet-go']."' name='submit' />"
     ."</div>";

debug($matches);
?>	
