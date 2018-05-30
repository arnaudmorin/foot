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
if (isset($_SESSION['user'])){
    echo "<div class='generic-small-label'>"
            ."<div id='date-switch-local-time' class='invisible'>".$lang['date-switch-local-time']."</div>"
            ."<div id='date-switch-timezone-time' class='invisible'>".$lang['date-switch-timezone-time']."</div>"
            ."<a href='javascript:switchTime(\"local\")' id='date-switch-message'>"
                .$lang['date-switch-local-time']
            ."</a>"
         ."</div>";
}

echo    "<div class='generic-line-header'>"
            ."<div class='matches-results-line-match'>"
                .$lang['matches-results-line-match']
            ."</div>"
            ."<div class='matches-results-line-date'>"
                .$lang['matches-results-line-date']
            ."</div>"
            ."<div class='matches-results-line-score'>"
                .$lang['matches-results-line-score']
            ."</div>";
if (isset($_SESSION['user'])){
    echo     "<div class='matches-results-line-your-bet'>"
                .$lang['matches-results-line-your-bet']
            ."</div>"
            ."<div class='matches-results-line-your-points'>"
                .$lang['matches-results-line-your-points']
            ."</div>"
            ."<div class='matches-results-line-stats'>"
                .$lang['matches-results-line-stats']
            ."</div>";
            
            
}
echo    "</div>";

// Get all played matches
$matches = getAllPlayedMatches();

// Foreach match
foreach ($matches as $match){
    // Get bet if exist and if user connected
    $bet = false;
    if (isset($_SESSION['user']) && isset($_SESSION['friendlist'])){
        echo "<div class='invisible'>"
                ."<div id='date_".$match['id_match']."_local'>".date($lang['date-format-full'], strtotime($match['date']) - date('Z') + $_SESSION['user']->timeOffsetSec)."</div>"
                ."<div id='date_".$match['id_match']."_timezone'>".date($lang['date-format-full'], strtotime($match['date']))."</div>"
            ."</div>";
        $bet = $_SESSION['user']->getBet($match['id_match'], $_SESSION['friendlist']->name);
          
        $points=0;
        $points = computePoints(
            $match['team1_result'],
            $match['team2_result'],
            $bet['team1'],
            $bet['team2'],
            $bet['joker'],
            $match['round_name']
        );
    }
    
    // Print match
    echo "<div class='matches-results-line'>"
            ."<div class='matches-results-line-match'>"
                ."<a href='printBetMatch.php?matchID="
                    .$match['id_match']
                ."&friendlist="
                    .$_SESSION['friendlist']->name
                ."' target='goodBets'>"
                    .$lang['teams-'.$match['team1_name']].$lang['matches-results-versus'].$lang['teams-'.$match['team2_name']]
                ."</a>"
            ."</div>"
            ."<div class='matches-results-line-date'>"
                ."<span name='date_".$match['id_match']."'>".date($lang['date-format-full'], strtotime($match['date']))."</span>"
            ."</div>"
            ."<div class='matches-results-line-score'>"
                .$match['team1_result']." - ".$match['team2_result']
            ."</div>";
    
    if (isset($_SESSION['user'])){
        echo "<div class='matches-results-line-your-bet'>";
        if ($bet){
            echo $bet['team1']." - ".$bet['team2'];
        }
        echo "</div>";
        echo "<div class='matches-results-line-your-points'>";
            echo $points;
        echo "</div>";
        echo "<div class='matches-results-line-stats'>";
            echo getStatsBetPerMatch($match['id_match'])."%";
        echo "</div>";
    }
    
    echo "</div>";
}

require_once("footer.php");

?>
