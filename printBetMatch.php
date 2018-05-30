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

$matchBet=$_GET['matchID'];
$friendlistName=$_GET['friendlist'];



// SQL request to fetch the name of the teams
global $sql;
$req = "SELECT * FROM matches WHERE id_match='".mysql_real_escape_string($matchBet)."'";
$match = $sql->doQuery($req, true);
$goodBets=getGoodBetPerMatch($matchBet, $friendlistName);

$title='Stats entre '.$lang['teams-'.$match['team1_name']].' contre '. $lang['teams-'.$match['team2_name']]." dans le groupe ".$friendlistName;


echo "<html>
<head>
<meta http-equiv='Content-Type' content='text/html;' charset='utf8'>
<meta name='description' content='".$lang['html-description']."'>
<meta name='author' lang='".$lang['html-currentlanguage']."' content='Emerginov Team'>
<meta name='keywords' content='".$lang['html-keywords']."'>
<title>".$lang['html-title']."</title>
<link href='CSS/styles.css' type='text/css' rel='stylesheet' />
<link rel='stylesheet' href='CSS/smoothness/jquery-ui-1.8.16.custom.css' />
</head>";

echo "<body><div class='background'></div><div id='pie'></div>";  

echo "
<script src='js/jquery.js'></script>
<script src='js/d3.min.js'></script>
<script src='js/d3pie.js'></script>

<script>
    var pie = new d3pie('pie', {
		header: {
			title: {
				text: '";
                
echo $title;                
echo "',
				fontSize: 14
			}
		},
		size: {
			pieInnerRadius: '10%'
		},
		data: {
			content: [";
            
$stats=getStatsPerMatch($matchBet,$friendlistName);
$start=true;

foreach ($stats as $score => $members){
    if (!$start) echo ",";
    echo "{ label: '".$score."', value: ".count($members).", members:'";
    
    $start=false;
    
    $startMember=true;

    foreach ($members as $member){

        if (!$startMember) echo ",";
        echo $member;
        $startMember=false;
    }
 
    echo  "'}";
}            
            
            

//				{ label: '3-3', value: 264131, color:'#00aa00', pronos :'eric, toto ' },




echo " 			
            ]
		},
		
		labels: {
		outer: {
			format: 'label',
			hideWhenLessThanPercentage: null,
			pieDistance: 30
		},
		inner: {
			format: 'percentage',
			hideWhenLessThanPercentage: null
		},
		mainLabel: {
			color: '#333333',
			font: 'arial',
			fontSize: 16
		},
		percentage: {
			color: '#dddddd',
			font: 'arial',
			fontSize: 12,
			decimalPlaces: 0
		},
		value: {
			color: '#cccc44',
			font: 'arial',
			fontSize: 16
		},
		lines: {
			enabled: true,
			style: 'curved',
			color: 'segment' // segment or a hex color
		}
		},
		effects: {
			load: {
				effect: 'default', // none / default
				speed: 1000
			},
			pullOutSegmentOnClick: {
				effect: 'bounce', // none / linear / bounce / elastic / back
				speed: 300,
				size: 10
			},
			highlightSegmentOnMouseover: true,
			highlightLuminosity: -0.2
		},
		
		misc: {
		colors: {
			background: null, // transparent
			segments: [
				'#2484c1', '#65a620', '#7b6888', '#a05d56', '#961a1a',
				'#d8d23a', '#e98125', '#d0743c', '#635222', '#6ada6a',
				'#0c6197', '#7d9058', '#207f33', '#44b9b0', '#bca44a',
				'#e4a14b', '#a3acb2', '#8cc3e9', '#69a6f9', '#5b388f',
				'#546e91', '#8bde95', '#d2ab58', '#273c71', '#98bf6e',
				'#4daa4b', '#98abc5', '#cc1010', '#31383b', '#006391',
				'#c2643f', '#b0a474', '#a5a39c', '#a9c2bc', '#22af8c',
				'#7fcecf', '#987ac6', '#3d3b87', '#b77b1c', '#c9c2b6',
				'#807ece', '#8db27c', '#be66a2', '#9ed3c6', '#00644b',
				'#005064', '#77979f', '#77e079', '#9c73ab', '#1f79a7'
                ],
			segmentStroke: '#ffffff'
		},
		gradient: {
			enabled: false,
			percentage: 95,
			color: '#000000'
		},
		canvasPadding: {
			top: 5,
			right: 5,
			bottom: 5,
			left: 5
		},
		pieCenterOffset: {
			x: 0,
			y: 0
		},
		cssPrefix: null
		},
		
		callbacks: {
		onClickSegment: function(e) {
			alert(e.data.members);
		}
	}
		
	});
</script>";


/*
echo "<ul>";
foreach ($goodBets as $goodBet){
    echo "<li>".$goodBet;
}
echo "</ul>";

$stats=getStatsPerMatch($matchBet,$friendlistName);

foreach ($stats as $score => $members){
    echo $score;
    echo "<ul>";
    foreach ($members as $member){
        echo "<li>".$member;
    }
    echo "</ul>";
}
*/

//require_once("footer.php");

?>