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
//require_once("header.php");

echo "<html>
<head>
<meta http-equiv='Content-Type' content='text/html;' charset='utf8'>
<meta name='description' content='".$lang['html-description']."'> 
<meta name='author' lang='".$lang['html-currentlanguage']."' content='Emerginov Team'> 
<meta name='keywords' content='".$lang['html-keywords']."'>
<title>".$lang['html-title']."</title>";

echo "<link href='CSS/styles.css' type='text/css' rel='stylesheet' />";
echo "<link rel='stylesheet' href='CSS/smoothness/jquery-ui-1.8.16.custom.css' />";
echo "<script src='javascript/jquery-1.6.2.min.js'></script>";
echo "<script src='javascript/jquery-ui-1.8.16.custom.min.js'></script>";

echo "</head>        <body><div class='background'></div><div class='conteneur'>";

?>


<?php

$userLogin=$_GET['user'];
$friendlistName=$_GET['friendlist'];

global $sql;

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

echo     "<div class='matches-results-line-your-bet'>"
            .$lang['matches-results-line-your-bet']
        ."</div>"
        ."<div class='matches-results-line-your-points'>"
            .$lang['matches-results-line-your-points']
        ."</div>"
        ."<div class='matches-results-line-stats'>"
            .$lang['matches-results-line-stats']
        ."</div>";
            
            

echo    "</div>";

// Get all played matches
$matches = getAllPlayedMatches();

// Foreach match
foreach ($matches as $match){
    // Get bet if exist and if user connected
    $bet = false;
    
    $matchID=$match['id_match'];

    echo "<div class='invisible'>"
            ."<div id='date_".$match['id_match']."_local'>".date($lang['date-format-full'], strtotime($match['date']))."</div>"
            ."<div id='date_".$match['id_match']."_timezone'>".date($lang['date-format-full'], strtotime($match['date']))."</div>"
        ."</div>";
        
    // sql request to get the bet       
    $bet = $sql->doQuery(
        "SELECT *
         FROM
            pronostics
         WHERE
            members_login = '".mysql_real_escape_string($userLogin)."' AND
            matches_id_match = '".mysql_real_escape_string($matchID)."' AND
            friendlist_name = '".mysql_real_escape_string($friendlistName)."'
        ",
        true
    );
      
    $points=0;
    if ($bet) 
        $points = computePoints(
            $match['team1_result'],
            $match['team2_result'],
            $bet['team1'],
            $bet['team2'],
            $bet['joker'],
            $match['round_name']
        );
    
    // Print match
    echo "<div class='matches-results-line'>"
            ."<div class='matches-results-line-match'>"
                .$lang['teams-'.$match['team1_name']].$lang['matches-results-versus'].$lang['teams-'.$match['team2_name']]
            ."</div>"
            ."<div class='matches-results-line-date'>"
                ."<span name='date_".$match['id_match']."'>".date($lang['date-format-full'], strtotime($match['date']))."</span>"
            ."</div>"
            ."<div class='matches-results-line-score'>"
                .$match['team1_result']." - ".$match['team2_result']
            ."</div>";
    
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
    
    
    echo "</div>";
}

//require_once("footer.php");

?>
