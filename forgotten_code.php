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
if (isset($_REQUEST['login']) ){
    // Create user object and store it in session
    $user = new User($_REQUEST['login']);
    
    // Check that this user exists
    if ($user->login){
        if ($user->number){
            $e = new Emerginov($api_login, $api_password);
            $newPassword = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz',8)),0,8);
            $ret = $e->SendSMS($user->number,$lang['lost-password-sms-message'].$newPassword);
            if (!$ret->Success){
                // Send mail to arnaud
                mail("arnaud.morin@gmail.com", "Oubli mot de passe", "Salut toi,\n\n{$user->login} a oublié son mot de passe et l'envoi de SMS n'a pas marché !\n\n".print_r($ret,true)."\n\nBisous.");
                $info.=$lang['lost-password-admin-will-care'];
            }
            else{
                // Update user password
                $user->updatePassword($newPassword);
                // Success sending SMS
                $info.=$lang['lost-password-sms-sent'];
            }
        }
        else{
            // Send mail to arnaud
            mail("arnaud.morin@gmail.com", "Oubli mot de passe", "Salut toi,\n\n{$user->login} a oublié son mot de passe et il n'a pas de mobile !\n\nBisous.");
            $info.=$lang['lost-password-admin-will-care'];
        }
    }
}


// Load HTML headers
require_once("header.php");

//debug(mktime(date("H"),date("i"),date("s"),date("n"),date("j"),date("Y")));
// Print any error or info that may have orccured
if ($error != "") error($error);
if ($info != "") info($info);


if (!isset($_SESSION['user']) && !isset($_REQUEST['number'])){
    // FORM
    echo "<form class='ident-form' method='POST' action='forgotten_code.php' onsubmit='var d = new Date();this.time.value=d.getTime() / 1000;'>
        <input type='hidden' name='time'/>
        <p><span class='ident-labels'>".$lang['ident-login']." </span><input type='text' name='login' autocomplete='on' size='15' maxlength='20'></p>
        <p><input type='submit' value='".$lang['ident-go']."'></p>
    </form>";
}

require_once("footer.php");
?>
