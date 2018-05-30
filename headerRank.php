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
/*

HTML headers - menu, background, etc.

*/

echo "<html>
<head>
<meta http-equiv='Content-Type' content='text/html;' charset='utf8'>
<meta name='description' content='".$lang['html-description']."'> 
<meta name='author' lang='".$lang['html-currentlanguage']."' content='Emerginov Team'> 
<meta name='keywords' content='".$lang['html-keywords']."'>
<title>".$lang['html-title']."</title>";

?>
<script>
	function changeLang(lang){
        // Set the lang
        $('#lang').val( lang );
        langForm.submit();
    }
</script>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

<?php
    
$friendlistName=$_SESSION['friendlist']->name;
$friendlist = new Friendlist($friendlistName);
    
$rankPointsProgression=array();
$rankPointsProgression=getRankPointsProgression($friendlistName);
 
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

// Create the array of days 
$days=array();
foreach ($dayTimeMatches as $dayTimeMatch) {
    $fields=explode (" ", $dayTimeMatch['date']);
    $day=$fields[0];
    if (!isset($days[$day])) $days[$day]=$day;
}
       
// fill with empty value if there is not yet results
if (count($days)==0){
    $days['0']='0';
    foreach ($friendlist->members as $member){ 
        $rankPointsProgression[$member]['0']['points']=0;
        $rankPointsProgression[$member]['0']['rank']=0;
    }
}       
       
// create the first chart : display the point evolutions
$chart=   "function drawChart() {".
          "var data = new google.visualization.arrayToDataTable([['day'";
    
foreach ($friendlist->members as $member){
    $user = new User($member);
    $chart=$chart.",'".$user->login."'";
}
$chart=$chart."]";
    
foreach ($days as $day) {           
    $chart=$chart.",['".$day."'";
    foreach ($friendlist->members as $member){   
        $chart=$chart.",".$rankPointsProgression[$member][$day]['points'];
    }
    $chart=$chart."]";
}

$chart=$chart."]);";

// Chart options
$chart=$chart."var options = {backgroundColor:'#322f2a',width: 660, height: 400,title: 'Evolution Points',\n\r"
    ."\thAxis:{textStyle:{color: 'white', fontName: 'Arial', fontSize: 10}},\n\r"
    ."\tvAxis:{textStyle:{color: 'white', fontName: 'Arial', fontSize: 10}},\n\r"
    ."\tlegend:{textStyle:{color: 'white', fontName: 'Arial', fontSize: 10}},\n\r"
    ."\ttitleTextStyle:{color: 'white', fontName: 'Arial', fontSize: 14}};\n\r";
$chart=$chart."var chart = new google.visualization.LineChart(document.getElementById('chart_div1'));";
$chart=$chart."chart.draw(data, options);";

// display the chart
echo $chart;   

// create the second chart : display the rank evolutions
$chart2=   "var data2 = new google.visualization.arrayToDataTable([['day'";                     
foreach ($friendlist->members as $member){
    $user = new User($member);
    $chart2=$chart2.",'".$user->login."'";
}
$chart2=$chart2."]";
foreach ($days as $day) {           
    $chart2=$chart2.",['".$day."'";
    foreach ($friendlist->members as $member){   
        $chart2=$chart2.",".$rankPointsProgression[$member][$day]['rank'];
    }
    $chart2=$chart2."]";
}
$chart2=$chart2."]);";
$chart2=$chart2."var options2 = {backgroundColor:'#322f2a',width: 660, height: 400,title: 'Evolution Place',\n\r"
    ."\thAxis:{textStyle:{color: 'white', fontName: 'Arial', fontSize: 10}},\n\r"
    ."\tvAxis:{maxValue: ".count($friendlist->members) .", minValue:1, direction : -1,textStyle:{color: 'white', fontName: 'Arial', fontSize: 10}},\n\r"
    ."\tlegend:{textStyle:{color: 'white', fontName: 'Arial', fontSize: 10}},\n\r"
    ."\ttitleTextStyle:{color: 'white', fontName: 'Arial', fontSize: 14}};\n\r";
$chart2=$chart2."var chart = new google.visualization.AreaChart(document.getElementById('chart_div2'));";
$chart2=$chart2."chart.draw(data2, options2);";
$chart2=$chart2."}";

echo $chart2;  
echo "</script>";

$pageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

if ($detect->isMobile()) {
    // any mobile platform
    echo "<link href='CSS/styles_mobile.css' type='text/css' rel='stylesheet' />";
    echo "<meta name='viewport' content='width=320,user-scalable=false' />";
}
else{
    echo "<link href='CSS/styles.css' type='text/css' rel='stylesheet' />";
}
echo "<link rel='stylesheet' href='CSS/smoothness/jquery-ui-1.8.16.custom.css' />";
echo "<script src='javascript/jquery-1.6.2.min.js'></script>";
echo "<script src='javascript/jquery-ui-1.8.16.custom.min.js'></script>";

echo "</head>
        <body>
            <div class='background'></div>
            <div class='conteneur'>
                <div class='background_left'></div>

                <div class='background_top'>
                    <form method='post' action='$pageName' name='langForm'>
                        <input type='hidden' name='lang' value='' id='lang'/>
                        <a href='javascript:changeLang(\"ENGLISH\");'><img src='CSS/flag_english.png' class='flag' width='40px'/></a>
                        <a href='javascript:changeLang(\"FRENCH\");'><img src='CSS/flag_french.png' class='flag' width='40px' /></a>
                    </form>
                    <h1>".$lang['html-title']."</h1>
                </div>
                <div class='background_right'></div>";
                
// Load menus.php
if (!$detect->isMobile()) {
    require_once('menus.php');
}

echo "          <div class='Contenu_Texte'>";

?>
