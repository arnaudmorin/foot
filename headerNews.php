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

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<!-- BEGIN TimelineJS -->
<script type="text/javascript" src="js/storyjs-embed.js"></script>

<script>
    $(document).ready(function() {
        createStoryJS({
            type:       'timeline',
            width:      '680',
            height:     '600',
            embed_id:   'timeline', 
            start_at_end:true, 
            source:     'timeline.json',
            lang:        'fr'    
            });
        });
</script>

<?php
    
// test if friendlistName boulot
// to be changed to include friendlist

function generateTimeLineFile($fileName,$friendlistName){
    global $sql;  
        
    // start file
    $startFile="{".
    "\"timeline\":".
    "{".
        "\"headline\":\"World Cup 2014\",".
        "\"type\":\"default\",".
        "\"text\":\"\",".
        "\"startDate\":\"2014,6,5\",".
        "\"date\": [";
    
    // SQL request to fetch the list of news
    $req= "SELECT * FROM `news`";

    $resource = $sql->doQuery($req);
    $news = $sql->fetchAssoc($resource);

    $nbNews=count($news);
    $jsonNews=$startFile;
    
        
    for ($i=0; $i<$nbNews; $i++) {
        $formatedNews['startDate']       = str_replace("-", ",", $news[$i]['date'] );
        $formatedNews['startDate'][10]   =','; 
        $formatedNews['headline']        = $news[$i]['headline'];
        $formatedNews['text']            = $news[$i]['text'];
        $formatedNews['asset']['media']  = $news[$i]['media'];
        $formatedNews['asset']['credit'] = $news[$i]['credit'];
        $formatedNews['tag']             = $news[$i]['tag'];
        
        //echo "News:".$formatedNews['tag'].":Group:".$friendlistName.":";
       
        // include only tag concerning the friendlist : hypothesis global tag begins with 'g' => to be changed
        if (($formatedNews['tag'][0]=='g') || ($formatedNews['tag']==$friendlistName)){
            if ($i>0)
                $jsonNews = $jsonNews.','.json_encode($formatedNews);
            else
                $jsonNews = $jsonNews.json_encode($formatedNews);
        }
    }
     
    // end file
    $endFile="]}}";
    $jsonNews = $jsonNews.$endFile;

    $handleFile = fopen($fileName, 'w'); 
    fwrite ($handleFile, $jsonNews); 
    fclose ($handleFile); 
}

generateTimeLineFile("timeline.json",strtolower($_SESSION['friendlist']->name));



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
                    <form method='post' action='news.php' name='langForm'>
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

echo "          <div class='Contenu_Texte'>"
                    .$lang['news-title']
                    ."<div id='timeline'></div>";

?>
