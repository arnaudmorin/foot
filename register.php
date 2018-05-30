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

// If registration requested
if (    isset($_REQUEST['number']) &&
        isset($_REQUEST['login']) &&
        isset($_REQUEST['password']) &&
        isset($_REQUEST['gift']) &&
        isset($_REQUEST['friendlist']) &&
        isset($_REQUEST['friendlist_password'])
    ){
    // Check if each field is well provided
    if ($_REQUEST['number'] == "" || !validateInternationalPhoneNumber($_REQUEST['number'])){
        error($lang['register-please-fill-number']);
    }
    else if ($_REQUEST['login'] == ""){
        error($lang['register-please-fill-name']);
    }
    else if ($_REQUEST['password'] == ""){
        error($lang['register-please-fill-password']);
    }
    else if ($_REQUEST['gift'] == ""){
        error($lang['register-please-fill-gift']);
    }
    else{
        // Ok, every required fields are good
        
        // Check if user does not already exists
        $userTmp = new User($_REQUEST['login']);
        if ($userTmp->login){
            // Yes, show error
            error($lang['register-already-in']);
            unset($userTmp);
        }
        else{
        
            // Create the user in DB
            $result = createUser($_REQUEST['login'], $_REQUEST['gift'], $_REQUEST['password'], $_REQUEST['number']);
            
            // if result status false, an error occured
            if (!$result['status']){
                error($lang['register-an-error-occured']);
            }
            // Everything went fine during registration mecanism
            else{
                // Get new user object
                $user = new User($_REQUEST['login']);
                // Update the lang array with the language of the user
                $lang=loadLanguage('lang/'.$user->language);
                
                // Now add the user in friendlist if requested
                if (    $_REQUEST['friendlist'] != "" &&
                        $_REQUEST['friendlist_password'] != ""
                    ){
                    if (!$user->addInFriendlist($_REQUEST['friendlist'], $_REQUEST['friendlist_password'])){
                        warning($lang['register-add-in-friendlist-failed'].$_REQUEST['friendlist']);
                    }
                    else{
                        info($lang['register-add-in-friendlist-success'].$_REQUEST['friendlist']);
                    }
                }
                
                // Also add user in global friendlist
                if (_ADD_IN_DEFAULT_FRIENDLIST){
                    if (!$user->addInFriendlist(_DEFAULT_FRIENDLIST, _DEFAULT_FRIENDLIST_PASSWORD)){
                        warning($lang['register-add-in-friendlist-failed']._DEFAULT_FRIENDLIST);
                    }
                    else{
                        info($lang['register-add-in-friendlist-success']._DEFAULT_FRIENDLIST);
                    }
                }
                
                info($lang['register-you-are-now-registered']);
            }
        }
    }
}

// Preconfigured elements
$numberInputValue = "";
$passwordInputValue = "";
$loginInputValue = "";
$giftInputValue = "";
$friendlistInputValue = "";
$friendlistPasswordInputValue = "";
if ( isset($_REQUEST['number']) ){
    $numberInputValue = "value='".$_REQUEST['number']."' ";
}
if ( isset($_REQUEST['password']) ){
    $passwordInputValue = "value='".$_REQUEST['password']."' ";
}
if ( isset($_REQUEST['login']) ){
    $loginInputValue = "value='".$_REQUEST['login']."' ";
}
if ( isset($_REQUEST['gift']) ){
    $giftInputValue = "value='".$_REQUEST['gift']."' ";
}
if ( isset($_REQUEST['friendlist']) ){
    $friendlistInputValue = "value='".$_REQUEST['friendlist']."' ";
}
if ( isset($_REQUEST['friendlist_password']) ){
    $friendlistPasswordInputValue = "value='".$_REQUEST['friendlist_password']."' ";
}

$friendlistTitle = "";
if (isset($_REQUEST['friendlist']) && $_REQUEST['friendlist'] != "") $friendlistTitle = " - " . $_REQUEST['friendlist'];
echo "<h3>".$lang['register-title']."$friendlistTitle</h3>"
     ."<form method='POST' action='register.php'>"
        ."<div class='register-line'>"
            ."<div class='generic-small-label'>"
                .$lang['register-all-required']
            ."</div>"
        ."</div>"
        ."<div class='register-line'>"
            ."<div class='register-labels'>"
                .$lang['register-name']."<b> *</b>"
            ."</div>"
            ."<span>"
                ."<input type='text' name='login' size='45' maxlength='50' $loginInputValue>"
            ."</span>"
        ."</div>"
        ."<div class='register-line'>"
            ."<div class='register-labels'>"
                .$lang['register-phone-number']."<b> *</b>"
            ."</div>"
            ."<span>"
                ."<input type='text' name='number' size='15' maxlength='20' $numberInputValue>"
            ."</span>"
            ."<span>"
                ."<div class='generic-small-label'>"
                    .$lang['register-international-phone-only']
                ."</div>"
            ."</span>"
        ."</div>"
        ."<div class='register-line'>"
            ."<div class='register-labels'>"
                .$lang['register-password']."<b> *</b>"
            ."</div>"
            ."<span>"
                ."<input type='password' name='password' size='45' maxlength='50' $passwordInputValue>"
            ."</span>"
        ."</div>"
        ."<div class='register-line'>"
            ."<div class='register-labels'>"
                .$lang['register-gift']."<b> *</b>"
            ."</div>"
            ."<span>"
                ."<input type='text' name='gift' size='45' maxlength='50' $giftInputValue>"
            ."</span>"
        ."</div>"
        ."<div class='register-line invisible'>"
            ."<div class='register-labels'>"
                .$lang['register-friendlist']
            ."</div>"
            ."<span>"
                ."<input type='text' name='friendlist' size='45' maxlength='50' $friendlistInputValue>"
            ."</span>"
        ."</div>"
        ."<div class='register-line invisible'>"
            ."<div class='register-labels'>"
                .$lang['register-friendlist-password']
            ."</div>"
            ."<span>"
                ."<input type='password' name='friendlist_password' size='45' maxlength='50' $friendlistPasswordInputValue>"
            ."</span>"
        ."</div>"
        ."<div class='register-line'>"
            ."<input type='submit' value='".$lang['register-go']."'>"
        ."</div>"
     ."</form>";



require_once("footer.php");
?>
