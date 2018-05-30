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
    require_once("headerNews.php");
}

// Check that the user is identified
require_once("checkAuth.php");

// Check that the user is in a friendlist
require_once("checkFriendlist.php");


require_once("footer.php");
?>
