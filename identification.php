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
// Load PHP require mecanism
require_once("loader.php");

// Check if identification is requested
$error = "";
$info = "";
if (isset($_REQUEST['login']) and isset($_REQUEST['password'])){
    // Create user object and store it in session
    $user = new User($_REQUEST['login']);
    
    // Check that this user exists and password is valid
    if (!$user->login || (md5($_REQUEST['password']) != $user->password)){
        $error .= $lang['ident-error-occured-checking'];
    }
    else{
        
        // Add this user to the session
        $_SESSION['user'] = $user;
        
        // Set timeOffsetSec for this user (offset is between UTC)
        $_SESSION['user']->timeOffsetSec = $_REQUEST['time'];
        
        // Create friendlist with first friendlist this user is in if he has a friendlist
        if (isset($user->friendlists[0])){
            $_SESSION['friendlist'] = new Friendlist($user->friendlists[0]);
        }
        
        // Load language again - this is identification specific
        // as we create here the $user object after loading language...
        $lang = loadLanguage();
        
        //$info .= $lang['ident-ok-1'] . "<b>" . $user->login . "</b>" . $lang['ident-ok-2'];
        // Redirect to index
        header('Location: index.php');
        
        // If here & mobile, well connected so redirect to mobile page (menu)
        if ($detect->isMobile()) {
            // Mobile connected
            header('Location: mobile.php');
        }
    }
}

// Check if identification is needed
//if (isset($_SESSION['user']) && !isset($_REQUEST['number'])){
//    // If here & mobile, well connected so redirect to mobile page (menu)
//    if ($detect->isMobile()) {
//        // Mobile connected
//        header('Location: mobile.php');
//    }
//    info($lang['ident-already-done-1'] . $_SESSION['user']->name . $lang['ident-already-done-2']);
//}

// Load HTML headers
require_once("header.php");

debug(mktime(date("H"),date("i"),date("s"),date("n"),date("j"),date("Y")));
debug($_REQUEST);
debug($_SESSION);

// Print any error or info that may have orccured
if ($error != "") error($error);
if ($info != "") info($info);


if (!isset($_SESSION['user']) && !isset($_REQUEST['login'])){
    // FORM
    echo "<form class='ident-form' method='POST' action='identification.php' onsubmit='var d = new Date();this.time.value=-d.getTimezoneOffset() * 60;'>
        <input type='hidden' name='time'/>
        <p><span class='ident-labels'>".$lang['ident-login']." </span><input type='text' name='login' autocomplete='on' size='15' maxlength='50'></p>
        <p><span class='ident-labels'>".$lang['ident-password']." </span><input type='password' name='password' size='15' maxlength='50'></p>
        <p><input type='submit' value='".$lang['ident-go']."'></p>
    </form>";
    echo "
    <div class='box_content_center' >
					<a href='./forgotten_code.php' style='color:#222222; font-size:80%'>".$lang['forgot_password']."</a>
				        &nbsp;&nbsp;&nbsp;	
				</div>";
}

require_once("footer.php");
?>
