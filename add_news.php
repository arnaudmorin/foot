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

$elements=array('date','headline','text','media','credit','tag','author');

// save data
if (    isset($_POST['date']) &&
        isset($_POST['headline']) &&
        isset($_POST['text']) &&
        isset($_POST['media']) &&
        isset($_POST['credit']) &&
        isset($_POST['tag']) &&
        isset($_POST['author'])
    ){
     
    $req= "INSERT INTO `news` (
        `id`,`date`, `headline`,`text` ,`media` ,`credit` ,`tag` ,`author`)
        VALUES (
        NULL , ".$_POST['date'].", ".$_POST['headline'].", ".$_POST['text'].", ".$_POST['media'].", ".$_POST['credit'].", ".$_POST['tag'].", ".$_POST['author']."
        );";
    
    echo $req;
    
    }


if (isset($_SESSION['user'])){
    echo "<h3>".$lang['addnews-title']."$</h3>"
        ."<form method='POST' action='add_news.php'>";
    foreach ($elements as $element){ 
        $elementMenu="addnews-".$element;
        echo 
        "<div class='register-line'>"
            ."<div class='generic-small-label'>"
                .$lang[$elementMenu]
            ."</div>"
            ."<span><input type='text' name='".$element."' size='45' maxlength='50'></span>"
        ."</div>";
    }

    echo"<div class='register-line'>"
            ."<input type='submit' value='".$lang['register-go']."'>"
        ."</div>"
     ."</form>";
}

require_once("footer.php");

?>