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


// Check if we need to add a match
if (    isset($_REQUEST['team1']) && $_REQUEST['team1'] != ""
        && isset($_REQUEST['team2']) && $_REQUEST['team2'] != ""
        && isset($_REQUEST['round']) && $_REQUEST['round'] != ""
        && isset($_REQUEST['group']) && $_REQUEST['group'] != ""
        && isset($_REQUEST['date']) && $_REQUEST['date'] != ""
        && isset($_REQUEST['hours']) && $_REQUEST['hours'] != ""
        && isset($_REQUEST['minutes']) && $_REQUEST['minutes'] != ""
    ){
    $req = "INSERT INTO
                matches(
                    team1_name, 
                    team2_name,
                    round_name,
                    group_name,
                    status,
                    date
                )
                VALUES(
                    '".mysql_real_escape_string($_REQUEST['team1'])."',
                    '".mysql_real_escape_string($_REQUEST['team2'])."',
                    '".mysql_real_escape_string($_REQUEST['round'])."',
                    '".mysql_real_escape_string($_REQUEST['group'])."',
                    '"._PLANNED."',
                    '".$_REQUEST['date']." ".$_REQUEST['hours'].":".$_REQUEST['minutes']."'
                )
            ";
    $res = $sql->doQuery($req);
    if (!$res){
        error($lang['addmatch-cant-add']);
        debug(mysql_error());
        debug($req);
    }
    else{
        info(   $lang['addmatch-add-success'] . "</br>" .
                $lang['teams-'.$_REQUEST['team1']] . " vs " . $lang['teams-'.$_REQUEST['team2']] . " - " . $_REQUEST['date'] . " - " . $_REQUEST['hours'] . ":" . $_REQUEST['minutes']);
    }
}


// Get back all teams
$teams = $sql->fetchAssoc($sql->doQuery("SELECT * FROM teams"));

// Get back all rounds
$rounds = $sql->fetchAssoc($sql->doQuery("SELECT * FROM rounds"));

// Get back all groups
$groups = $sql->fetchAssoc($sql->doQuery("SELECT * FROM groups"));

?>
<script>
	$(function() {
        // Date picker
        $( "#datepicker" ).datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function(dateText, inst) {
                $( "#date" ).val(dateText);
            }
        });
        
        // Hours slider
        $( "#slider-hours" ).slider({
			//orientation: "vertical",
			range: "min",
			min: 0,
			max: 23,
			value: 17,
            step: 1,
			slide: function( event, ui ) {
				$( "#hours" ).val( ('0' + ui.value).slice(-2) );
                $( "#hours-indicator" ).html( ('0' + ui.value).slice(-2) );
			}
		});
        
        // Minutes slider
		$( "#hours" ).val( ('0' + $( "#slider-hours" ).slider( "value" )).slice(-2)  );
        $( "#hours-indicator" ).html( ('0' + $( "#slider-hours" ).slider( "value" )).slice(-2) );
        $( "#slider-minutes" ).slider({
			//orientation: "vertical",
			range: "min",
			min: 0,
			max: 45,
			value: 0,
            step: 15,
			slide: function( event, ui ) {
				$( "#minutes" ).val( ('0' + ui.value).slice(-2) );
                $( "#minutes-indicator" ).html( ('0' + ui.value).slice(-2) );
			}
		});
		$( "#minutes" ).val( ('0' + $( "#slider-minutes" ).slider( "value" )).slice(-2)  );
		$( "#minutes-indicator" ).html( ('0' + $( "#slider-minutes" ).slider( "value" )).slice(-2) );
    });
</script>
<?php

echo "<h3>".$lang['addmatch-title']."</h3>
    <form method='POST' action='add_match.php'>
        <p><span class='addmatch-labels'>".$lang['addmatch-team1']."</span><select name='team1'>\n";

// All teams
foreach ($teams as $team){
    echo "<option value='".$team['name']."'>".$lang["teams-".$team['name']]."</option>\n";
}

echo "</select></p>\n";
echo "  <p><span class='addmatch-labels'>".$lang['addmatch-team2']."</span><select name='team2'>\n";

// All teams
foreach ($teams as $team){
    echo "<option value='".$team['name']."'>".$lang["teams-".$team['name']]."</option>\n";
}
echo "</select></p>\n";

echo "  <p><span class='addmatch-labels'>".$lang['addmatch-round']."</span><select name='round'>\n";

// All rounds
foreach ($rounds as $round){
    echo "<option value='".$round['name']."'>".$lang["rounds-".$round['name']]."</option>\n";
}
echo "</select></p>\n";

echo "  <p><span class='addmatch-labels'>".$lang['addmatch-group']."</span><select name='group'>\n";

// All groups
foreach ($groups as $group){
    echo "<option value='".$group['name']."'>".$lang["groups-".$group['name']]."</option>\n";
}
echo "</select></p>\n";

echo "  <input type='hidden' name='date' id='date' value='".date("Y-m-d")."'>
        <div class='addmatch-datepickerContainer'>
            <div class='addmatch-labels'>".$lang['addmatch-date']."</div>
            <div id='datepicker'></div>
        </div>";

echo "  <input type='hidden' name='hours' id='hours'>
        <input type='hidden' name='minutes' id='minutes'>
        <div class='addmatch-timeContainer'>
            <div class='addmatch-labels'>".$lang['addmatch-time']."</div>
            <div class='addmatch-hours-slider'>
                <div id='slider-hours'></div>
            </div>
            <div class='addmatch-minutes-slider'>
                <div id='slider-minutes'></div>
            </div>
            <div class='addmatch-hours-indicator addmatch-indicator' id='hours-indicator'></div>
            <div class='addmatch-hours-indicator-h addmatch-indicator' id='hours-indicator'>h</div>
            <div class='addmatch-minutes-indicator addmatch-indicator' id='minutes-indicator'></div>
            <div class='addmatch-minutes-indicator-m addmatch-indicator' id='hours-indicator'>min</div>
        </div>";

echo "  <p>".$lang['addmatch-timezone-warning']."</p>";

echo "  <p><input type='submit' value='".$lang['addmatch-go']."'></p>
    </form>";

/*
echo "

Date: <div id='slider-vertical-hours'></div>

<input type='text' name='hours' id='hours' />
Date: <div id='slider-vertical-minutes'></div>

<input type='text' name='minutes' id='minutes' />";
*/
require_once("footer.php");
?>
