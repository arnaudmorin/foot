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


if ($detect->isMobile() && !isset($_SESSION['user'])) {
    // Mobile, not connected
    header('Location: identification.php');
}
if ($detect->isMobile() && isset($_SESSION['user'])) {
    // Mobile, already connected
    header('Location: mobile.php');
}

// If user is connected - show news
//if (isset($_SESSION['user'])){
//    // Modif to include JS code for graph
//    require_once("headerNews.php");
//}
//else{
    require_once("header.php");
//}

if (detect_ie()){
    warning($lang['ie-detected']);
    }

// Debug
debug($_SESSION);

//warning("Warning: Sending/Receiving SMS are not working well today, please be patient. <br/><br/><span style='margin-left:30px;'> “Adopt the pace of nature. Her secret is patience.” - Ralph Waldo Emerson</span>");

echo $lang['index-welcome'];

require_once("footer.php");
?>
