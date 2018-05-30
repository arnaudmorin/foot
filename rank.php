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

if ($detect->isMobile()) {
    require_once("header.php");
}
else{
    // Modif to include JS code for graph
    require_once("headerRank.php");
}

// Check that the user is identified
require_once("checkAuth.php");

// Check that the user is in a friendlist
require_once("checkFriendlist.php");

echo    "<div class='generic-line-header'>"
#              ."<div class='rank-line-place'>"
                .$lang['rank-line-place']
#            ."</div>"
            ."<div class='rank-line-name'>"
                .$lang['rank-line-name']
            ."</div>"
            ."<div class='rank-line-points'>"
                .$lang['rank-line-points']
            ."</div>";
// Temporarly disable gift
// Disable gift for default friendlist
/*if (strtolower($_SESSION['friendlist']->name) != strtolower(_DEFAULT_FRIENDLIST)){
    echo    "<div class='rank-line-gift'>"
                .$lang['rank-line-gift']
            ."</div>";
}
*/
echo    "</div>";

// Evolution
$rankEvolution=array();
$rankEvolution=getRankPointsProgression($_SESSION['friendlist']->name, true);
//print_r($rankEvolution);

global $sql;
   
// Get the list of days and times off matches with bets
$dayTimeMatches = $sql->fetchAssoc(
                    $sql->doQuery(
                        "SELECT date 
                         FROM 
                            matches
                         WHERE
                            status = 'PLAYED'
                            ORDER BY DATE ASC   
                            "
                    )
                );

// Detect the last 2 days
$lastDay='';
$previousDay='';
foreach ($dayTimeMatches as $dayTimeMatch) {
    $fields=explode (" ", $dayTimeMatch['date']);
    $day=$fields[0];

// if (!isset($days[$day])) $days[$day]=$day;
    if ($day!=$lastDay) {
        $previousDay=$lastDay;
        $lastDay=$day;
    }    
}



//$rankEvolution['member']['day']['place']
 
// =====================================================================
// To print the place
$place=0;
$nb=0;
$previousPoints=100000;

$rank = $_SESSION['user']->getRank($_SESSION['friendlist']->name);
foreach ($rank as $login => $points){
    // Create user with autobuild
    $user = new User($login);
    
    $nb++;
    
    if ($points!=$previousPoints){
        $previousPoints=$points;
        $place=$nb;
    }
    
    $lastRank=$rankEvolution[$login][$lastDay]['rank'];
    $previousRank=$rankEvolution[$login][$previousDay]['rank'];
    
    $evolution=strval($previousRank)-strval($lastRank);
    if ($evolution==0){
        $evolution_string=' (=)';
        $image_evolution="http://www.lfp.fr/images/ico_prog_st.png";
    }
    elseif ($evolution>0){
        $evolution_string=' (+'.$evolution.')';
        $image_evolution="http://www.lfp.fr/images/ico_prog_plus.png";
    }
    elseif ($evolution<0){
        $evolution_string=' ('.$evolution.')';
        $image_evolution="http://www.lfp.fr/images/ico_prog_moins.png";
    }   
    echo "<div class='rank-line'>"
#            ."<div class='rank-line-place'>"
                ."<img src='".$image_evolution."'/>&nbsp;&nbsp;"
                .strval($place)
                .'.'
                .$evolution_string
#            ."</div>"  
            ."<div class='rank-line-name'>"
            ."<a href='printBetUser.php?user="
                .$user->login
            ."&friendlist="
                .$_SESSION['friendlist']->name
             ."' target='bets'>"
                .$user->login
            ."</a>"
            ."</div>"
            ."<div class='rank-line-points'>"
                .$points
            ."</div>";
   
   // Temporarly suppress gift for CSS
   
    // Disable gift for default friendlist
/*    if (strtolower($_SESSION['friendlist']->name) != strtolower(_DEFAULT_FRIENDLIST)){
        echo "<div class='rank-line-gift'>"
                .$user->gift
            ."</div>";
    }
*/    
    echo "</div>";
}
// =====================================================================


if (!$detect->isMobile()) {
    echo "<br>&nbsp;</br>"
        ."<div id='containerGraphPlace'>"
        ."  <div id='chart_div1'>"
        ."  </div>"
        ."</div><br>&nbsp;</br>"
        ."<div id='containerGraphPlace'>"
        ."  <div id='chart_div2'>"
        ."  </div>"
        ."</div>";
}

debug($_SESSION['user']->getRank($_SESSION['friendlist']->name));

debug("Résultat 1-1");
debug("Bet 0-0 (avec bonus): ".computePoints(1,1,0,0,false,"groups-1")." (".computePoints(1,1,0,0,true,"groups-1").")");
debug("Bet 1-1 (avec bonus): ".computePoints(1,1,1,1,false,"groups-1")." (".computePoints(1,1,1,1,true,"groups-1").")");
debug("Bet 2-2 (avec bonus): ".computePoints(1,1,2,2,false,"groups-1")." (".computePoints(1,1,2,2,true,"groups-1").")");
debug("Bet 3-3 (avec bonus): ".computePoints(1,1,3,3,false,"groups-1")." (".computePoints(1,1,3,3,true,"groups-1").")");
debug("Bet 1-0 (avec bonus): ".computePoints(1,1,1,0,false,"groups-1")." (".computePoints(1,1,1,0,true,"groups-1").")");
debug("Bet 2-0 (avec bonus): ".computePoints(1,1,2,0,false,"groups-1")." (".computePoints(1,1,2,0,true,"groups-1").")");
debug("Bet 3-0 (avec bonus): ".computePoints(1,1,3,0,false,"groups-1")." (".computePoints(1,1,3,0,true,"groups-1").")");
debug("Bet 2-1 (avec bonus): ".computePoints(1,1,2,1,false,"groups-1")." (".computePoints(1,1,2,1,true,"groups-1").")");
debug("Bet 3-1 (avec bonus): ".computePoints(1,1,3,1,false,"groups-1")." (".computePoints(1,1,3,1,true,"groups-1").")");
debug("Bet 1-2 (avec bonus): ".computePoints(1,1,1,2,false,"groups-1")." (".computePoints(1,1,1,2,true,"groups-1").")");
debug("Bet 1-3 (avec bonus): ".computePoints(1,1,1,3,false,"groups-1")." (".computePoints(1,1,1,3,true,"groups-1").")");
debug("Bet 0-1 (avec bonus): ".computePoints(1,1,0,1,false,"groups-1")." (".computePoints(1,1,0,1,true,"groups-1").")");
debug("Bet 0-2 (avec bonus): ".computePoints(1,1,0,2,false,"groups-1")." (".computePoints(1,1,0,2,true,"groups-1").")");
debug("Bet 0-3 (avec bonus): ".computePoints(1,1,0,3,false,"groups-1")." (".computePoints(1,1,0,3,true,"groups-1").")");

debug("");

debug("Résultat 2-1");
debug("Bet 0-0 (avec bonus): ".computePoints(2,1,0,0,false,"groups-1")." (".computePoints(2,1,0,0,true,"groups-1").")");
debug("Bet 1-1 (avec bonus): ".computePoints(2,1,1,1,false,"groups-1")." (".computePoints(2,1,1,1,true,"groups-1").")");
debug("Bet 2-2 (avec bonus): ".computePoints(2,1,2,2,false,"groups-1")." (".computePoints(2,1,2,2,true,"groups-1").")");
debug("Bet 3-3 (avec bonus): ".computePoints(2,1,3,3,false,"groups-1")." (".computePoints(2,1,3,3,true,"groups-1").")");
debug("Bet 1-0 (avec bonus): ".computePoints(2,1,1,0,false,"groups-1")." (".computePoints(2,1,1,0,true,"groups-1").")");
debug("Bet 2-0 (avec bonus): ".computePoints(2,1,2,0,false,"groups-1")." (".computePoints(2,1,2,0,true,"groups-1").")");
debug("Bet 3-0 (avec bonus): ".computePoints(2,1,3,0,false,"groups-1")." (".computePoints(2,1,3,0,true,"groups-1").")");
debug("Bet 2-1 (avec bonus): ".computePoints(2,1,2,1,false,"groups-1")." (".computePoints(2,1,2,1,true,"groups-1").")");
debug("Bet 3-1 (avec bonus): ".computePoints(2,1,3,1,false,"groups-1")." (".computePoints(2,1,3,1,true,"groups-1").")");
debug("Bet 1-2 (avec bonus): ".computePoints(2,1,1,2,false,"groups-1")." (".computePoints(2,1,1,2,true,"groups-1").")");
debug("Bet 1-3 (avec bonus): ".computePoints(2,1,1,3,false,"groups-1")." (".computePoints(2,1,1,3,true,"groups-1").")");
debug("Bet 0-1 (avec bonus): ".computePoints(2,1,0,1,false,"groups-1")." (".computePoints(2,1,0,1,true,"groups-1").")");
debug("Bet 0-2 (avec bonus): ".computePoints(2,1,0,2,false,"groups-1")." (".computePoints(2,1,0,2,true,"groups-1").")");
debug("Bet 0-3 (avec bonus): ".computePoints(2,1,0,3,false,"groups-1")." (".computePoints(2,1,0,3,true,"groups-1").")");

debug("");

debug("Résultat 2-0");
debug("Bet 0-0 (avec bonus): ".computePoints(2,0,0,0,false,"groups-1")." (".computePoints(2,0,0,0,true,"groups-1").")");
debug("Bet 1-1 (avec bonus): ".computePoints(2,0,1,1,false,"groups-1")." (".computePoints(2,0,1,1,true,"groups-1").")");
debug("Bet 2-2 (avec bonus): ".computePoints(2,0,2,2,false,"groups-1")." (".computePoints(2,0,2,2,true,"groups-1").")");
debug("Bet 3-3 (avec bonus): ".computePoints(2,0,3,3,false,"groups-1")." (".computePoints(2,0,3,3,true,"groups-1").")");
debug("Bet 1-0 (avec bonus): ".computePoints(2,0,1,0,false,"groups-1")." (".computePoints(2,0,1,0,true,"groups-1").")");
debug("Bet 2-0 (avec bonus): ".computePoints(2,0,2,0,false,"groups-1")." (".computePoints(2,0,2,0,true,"groups-1").")");
debug("Bet 3-0 (avec bonus): ".computePoints(2,0,3,0,false,"groups-1")." (".computePoints(2,0,3,0,true,"groups-1").")");
debug("Bet 2-1 (avec bonus): ".computePoints(2,0,2,1,false,"groups-1")." (".computePoints(2,0,2,1,true,"groups-1").")");
debug("Bet 3-1 (avec bonus): ".computePoints(2,0,3,1,false,"groups-1")." (".computePoints(2,0,3,1,true,"groups-1").")");
debug("Bet 1-2 (avec bonus): ".computePoints(2,0,1,2,false,"groups-1")." (".computePoints(2,0,1,2,true,"groups-1").")");
debug("Bet 1-3 (avec bonus): ".computePoints(2,0,1,3,false,"groups-1")." (".computePoints(2,0,1,3,true,"groups-1").")");
debug("Bet 0-1 (avec bonus): ".computePoints(2,0,0,1,false,"groups-1")." (".computePoints(2,0,0,1,true,"groups-1").")");
debug("Bet 0-2 (avec bonus): ".computePoints(2,0,0,2,false,"groups-1")." (".computePoints(2,0,0,2,true,"groups-1").")");
debug("Bet 0-3 (avec bonus): ".computePoints(2,0,0,3,false,"groups-1")." (".computePoints(2,0,0,3,true,"groups-1").")");

require_once("footer.php");
?>
