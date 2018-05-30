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

// Check that the user is identified
require_once("checkAuth.php");

// If update info requested
if (    isset($_REQUEST['action']) &&
        $_REQUEST['action'] == "updateinfo"
    ){
    // Check what info need to be update
    if ($_REQUEST['number'] != $_SESSION['user']->number){
        // We want to change the number
        if ($_SESSION['user']->updateNumber($_REQUEST['number'])){
            info($lang['account-update-number-success']);
        }
        else{
            error($lang['account-update-number-error']);
        }
    }
    if ($_REQUEST['gift'] != $_SESSION['user']->gift){
        // We want to change the gift
        if ($_SESSION['user']->updateGift($_REQUEST['gift'])){
            info($lang['account-update-gift-success']);
        }
        else{
            error($lang['account-update-gift-error']);
        }
    }
}
// If add in friendlist requested
if (    isset($_REQUEST['action']) &&
        isset($_REQUEST['friendlist']) &&
        isset($_REQUEST['friendlist_password']) &&
        $_REQUEST['action'] == "addinfriendlist" &&
        $_REQUEST['friendlist'] != "" &&
        $_REQUEST['friendlist_password'] != ""
    ){
    if ($_SESSION['user']->addInFriendlist($_REQUEST['friendlist'], $_REQUEST['friendlist_password'])){
        info($lang['account-update-friendlist-success']);
    }
    else{
        error($lang['account-update-friendlist-error']);
    }
}

// If remove from friendlist requested
if (    isset($_REQUEST['action']) &&
        isset($_REQUEST['friendlist']) &&
        $_REQUEST['action'] == "removefromfriendlist" &&
        $_REQUEST['friendlist'] != ""
    ){
    if ($_SESSION['user']->removeFromFriendlist($_REQUEST['friendlist'], $_REQUEST['friendlist_password'])){
        info($lang['account-update-friendlist-success']);
    }
    else{
        error($lang['account-update-friendlist-error']);
    }
}


// If create a new friendlist requested
if (    isset($_REQUEST['action']) &&
        isset($_REQUEST['friendlist']) &&
        isset($_REQUEST['friendlist_password']) &&
        $_REQUEST['action'] == "createfriendlist" &&
        $_REQUEST['friendlist'] != "" &&
        $_REQUEST['friendlist_password'] != ""
    ){
    // Check if friendlist does not already exists
    $friendlistObject = new Friendlist($_REQUEST['friendlist']);
    
    // If the request is a success, then the group exists
    if ($friendlistObject->name){
        error($lang['account-friendlist-already-exists-error']);
    }
    else{
        // Prepare the friendlist creation
        if (createFriendlist($_REQUEST['friendlist'], $_REQUEST['friendlist_password'])){
            // Everything went well
            info($lang['account-friendlist-creation-ok']);
            // The friendlist has been created, add the user in the friendlist
            // Being here suppose that the user is now in this friendlist
            // Update the user info
            $_SESSION['user']->addInFriendlist($_REQUEST['friendlist'], $_REQUEST['friendlist_password']);
        }
        else{
            // An error occured
            error($lang['account-cant-create-friendlist']);
        }
    }
}

// If notification changes requested
if (    isset($_REQUEST['action']) &&
        $_REQUEST['action'] == "updatenotification" 
    ){
    if (isset($_REQUEST['notification_join_friendlist']) && $_REQUEST['notification_join_friendlist']){
        $_SESSION['user']->updateNotification("notification_join_friendlist", true);
    }
    else{
        $_SESSION['user']->updateNotification("notification_join_friendlist", false);
    }
    
    
    if (isset($_REQUEST['notification_resume_day']) && $_REQUEST['notification_resume_day']){
        $_SESSION['user']->updateNotification("notification_resume_day", true);
    }
    else{
        $_SESSION['user']->updateNotification("notification_resume_day", false);
    }
    
    
    if (isset($_REQUEST['notification_no_bet']) && $_REQUEST['notification_no_bet']){
        $_SESSION['user']->updateNotification("notification_no_bet", true);
    }
    else{
        $_SESSION['user']->updateNotification("notification_no_bet", false);
    }
    
    if (isset($_REQUEST['notification_new_score']) && $_REQUEST['notification_new_score']){
        $_SESSION['user']->updateNotification("notification_new_score", true);
    }
    else{
        $_SESSION['user']->updateNotification("notification_new_score", false);
    }
}

// If update password requested
if (    isset($_REQUEST['action']) &&
        $_REQUEST['action'] == "updatepassword" 
    ){
    // Check values
    if ( !isset($_REQUEST['password_old']) )
        error($lang['account-update-please-provide-password']);
    else if ( !isset($_REQUEST['password_new']) || !isset($_REQUEST['password_new_confirmation']) || $_REQUEST['password_new'] == "" )
        error($lang['account-update-please-provide-new-password']);
    else if ( $_REQUEST['password_new'] != $_REQUEST['password_new_confirmation'])
        error($lang['account-update-password-confirmation-not-same']);
    else if (md5($_REQUEST['password_old']) != $_SESSION['user']->password)
        error($lang['account-update-password-old-password-invalid']);
    else{
        // Being here suppose that the fields are correct
        if ($_SESSION['user']->updatePassword($_REQUEST['password_new'], $_REQUEST['password_old'])){
            info($lang['account-update-password-success']);
        }
        else{
            error($lang['account-update-password-error']);
        }
    }
}
?>
<script>
	function removeUserFromFriendlist(friendlist){
        // Set the friendlist to leave
        $('#friendlist').val(friendlist);
        
        // Set the action
        $('#friendlist_action').val("removefromfriendlist");
        
        // Submit
        updatefriendlistsForm.submit();
    }
    
    function createFriendlist(){
        // Just change the default action and submit the form
        // Set the action
        $('#friendlist_action').val("createfriendlist");
        
        // Submit
        updatefriendlistsForm.submit();
    }
    
    function hover(entity){
        $("#action-" + entity).fadeIn();
    }

    function out(entity){
        $("#action-" + entity).fadeOut();
    }
    
</script>
<?php

// General Information
echo "<h3>".$lang['account-general-info']."</h3>"
     ."<form method='POST' action='account.php'>"
        ."<input type='hidden' name='action' value='updateinfo'/>"
        ."<div class='account-line'>"
            ."<div class='account-labels'>"
                .$lang['account-phone-number']
            ."</div>"
            ."<span>"
                ."<input type='text' name='number' size='15' maxlength='20' value='".escapeSingleQuote($_SESSION['user']->number)."'>"
            ."</span>"
            ."<span>"
                ."<div class='generic-small-label'>"
                    .$lang['account-international-phone-only']
                ."</div>"
            ."</span>"
        ."</div>"
        ."<div class='account-line'>"
            ."<div class='account-labels'>"
                .$lang['account-gift']
            ."</div>"
            ."<span>"
                ."<input type='text' name='gift' size='45' maxlength='50' value='".escapeSingleQuote($_SESSION['user']->gift)."'>"
            ."</span>"
        ."</div>"
        ."<div class='account-line'>"
            ."<input type='submit' value='".$lang['account-go']."'>"
        ."</div>"
     ."</form>";


// Friendlists membership
echo "<h3>".$lang['account-friendlists-membership']."</h3>"
     ."<form method='POST' action='account.php' name='updatefriendlistsForm'>"
        ."<input type='hidden' name='action' id='friendlist_action' value='addinfriendlist'/>"
        ."<input type='hidden' name='user_password' id='user_password'/>"
        ."<div class='account-line'>"
            ."<span>";

echo isset($_SESSION['friendlist']->name) ? $lang['account-you-are-involved-in'] : $lang['account-you-do-not-have-any-friendlist'];

echo         "</span>"
        ."</div>";

if (isset($_SESSION['friendlist']->name)){
    foreach ($_SESSION['user']->friendlists as $friendlistName){
        echo "<div class='account-line'>"
                ."<div class='account-friendlist-name'>"
                    .$friendlistName
                    ."<div class='account-friendlist-action'>"
                        ."<a href='javascript:removeUserFromFriendlist(\"".escapeSingleQuote($friendlistName)."\")' onmouseover='javascript:hover(\"remove-".escapeSingleQuote($friendlistName)."\")' onmouseout='javascript:out(\"remove-".escapeSingleQuote($friendlistName)."\")'>"
                            ."<img src='CSS/remove.png' />"
                        ."</a>"
                        ."<a href='invite.php?friendlist=".escapeSingleQuote($friendlistName)."' onmouseover='javascript:hover(\"invite-".escapeSingleQuote($friendlistName)."\")' onmouseout='javascript:out(\"invite-".escapeSingleQuote($friendlistName)."\")'>"
                            ."<img src='CSS/add.png'  width='20px'/>"
                        ."</a>"
                        ."<div class='account-hover' id='action-remove-".escapeSingleQuote($friendlistName)."'>".$lang['account-friendlist-action-remove']."</div>"
                        ."<div class='account-hover' id='action-invite-".escapeSingleQuote($friendlistName)."'>".$lang['account-friendlist-action-invite']."</div>"
                    ."</div>"
                ."</div>"
            ."</div>";
    }
}

echo    "<div class='account-line'>"
            ."<span>"
                .$lang['account-join-friendlist']
            ."</span>"
        ."</div>"
        ."<div class='account-line'>"
            ."<div class='account-labels'>"
                .$lang['account-friendlist']
            ."</div>"
            ."<span>"
                ."<input type='text' name='friendlist' id='friendlist' size='45' maxlength='50'>"
            ."</span>"
        ."</div>"
        ."<div class='account-line'>"
            ."<div class='account-labels'>"
                .$lang['account-friendlist-password']
            ."</div>"
            ."<span>"
                ."<input type='password' name='friendlist_password' id='friendlist_password' size='45' maxlength='50'>"
            ."</span>"
        ."</div>"
        ."<div class='account-line'>"
            ."<input type='submit' value='".$lang['account-join-friendlist-button']."'>"
            ."<input type='button' value='".$lang['account-create-friendlist-button']."' onclick='createFriendlist()'>"
        ."</div>"
     ."</form>";

// Notifications
$notification_join_friendlistC = false;
$notification_resume_dayC = false;
$notification_no_betC = false;
$notification_new_scoreC = false;
if ($_SESSION['user']->notification_join_friendlist) $notification_join_friendlistC = "checked='checked'";
if ($_SESSION['user']->notification_resume_day) $notification_resume_dayC = "checked='checked'";
if ($_SESSION['user']->notification_no_bet) $notification_no_betC = "checked='checked'";
if ($_SESSION['user']->notification_new_score) $notification_new_scoreC = "checked='checked'";
echo "<h3>".$lang['account-notification']."</h3>"
     ."<form method='POST' action='account.php'>"
        ."<input type='hidden' name='action' value='updatenotification'/>"
        ."<div class='account-line'>"
            ."<input type='checkbox' name='notification_join_friendlist' id='notification_join_friendlist' $notification_join_friendlistC value='1' />"
            ."<label for='notification_join_friendlist'>"
                .$lang['account-notification-join-friendlist']
            ."</label>"
        ."</div>"
        ."<div class='account-line'>"
            ."<input type='checkbox' name='notification_resume_day' id='notification_resume_day' $notification_resume_dayC value='1'/>"
            ."<label for='notification_resume_day'>"
                .$lang['account-notification-resume-day']
            ."</label>"
        ."</div>"
        ."<div class='account-line'>"
            ."<input type='checkbox' name='notification_no_bet' id='notification_no_bet' $notification_no_betC value='1'/>"
            ."<label for='notification_no_bet'>"
                .$lang['account-notification-no-bet']
            ."</label>"
        ."</div>"
        ."<div class='account-line'>"
            ."<input type='checkbox' name='notification_new_score' id='notification_new_score' $notification_new_scoreC value='1'/>"
            ."<label for='notification_new_score'>"
                .$lang['account-notification-new-score']
            ."</label>"
        ."</div>"
        ."<div class='account-line'>"
            ."<input type='submit' value='".$lang['account-go']."'>"
        ."</div>"
     ."</form>";

// Update password
echo "<h3>".$lang['account-update-password']."</h3>"
     ."<form method='POST' action='account.php'>"
        ."<input type='hidden' name='action' value='updatepassword'/>"
        ."<div class='account-line'>"
            ."<div class='account-labels'>"
                .$lang['account-old-password']
            ."</div>"
            ."<span>"
                ."<input type='password' name='password_old' size='45' maxlength='50'>"
            ."</span>"
        ."</div>"
        ."<div class='account-line'>"
            ."<div class='account-labels'>"
                .$lang['account-new-password']
            ."</div>"
            ."<span>"
                ."<input type='password' name='password_new' size='45' maxlength='50'>"
            ."</span>"
        ."</div>"
        ."<div class='account-line'>"
            ."<div class='account-labels'>"
                .$lang['account-new-password-confirmation']
            ."</div>"
            ."<span>"
                ."<input type='password' name='password_new_confirmation' size='45' maxlength='50'>"
            ."</span>"
        ."</div>"
        ."<div class='account-line'>"
            ."<input type='submit' value='".$lang['account-go']."'>"
        ."</div>"
     ."</form>";

require_once("footer.php");
?>
