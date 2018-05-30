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
<?php

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

                <div class='background_top'>";
if ($detect->isMobile() && $pageName != "mobile.php"){
    // Show a link to menu
    echo "<a href='mobile.php'><span class='menu-top'>".$lang['menu-mobile-go']."</span></a>";
}
echo "
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
else if ($pageName != "mobile.php"){
    
}

echo "          <div class='Contenu_Texte'>";

?>
