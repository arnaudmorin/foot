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
if (!isset($_REQUEST['friendlist'])){
    error($lang['invite-error-no-friendlist']);
}
else if (isset($_REQUEST['contact-1']) && isset($_REQUEST['friendlist_password'])){
    // Being here suppose that almost one contact is filled
    $i = 1;
    while (isset($_REQUEST['contact-'.$i])){
        $contact = $_REQUEST['contact-'.$i];
        
        if (validateInternationalPhoneNumber($contact)){
            // This is a international phone number
            // Send SMS to this guy
            if ($_SESSION['user']->inviteSmsUsers(array($contact),$_REQUEST['friendlist'],$_REQUEST['friendlist_password'])){
                info($lang['invite-sms-sent'].$contact);
            }
        }
        else if (validateMailAddress($contact)){
            // This is a mail address
            if ($_SESSION['user']->inviteMailUsers(array($contact),$_REQUEST['friendlist'],$_REQUEST['friendlist_password'])){
                info($lang['invite-mail-sent'].$contact);
            }
        }
        else{
            // Invalid contact given
            error($lang['invite-error-invalid-contact-1'].$contact.$lang['invite-error-invalid-contact-2']);
        }
        // Move to next contact
        $i++;
    }
}
else{
    // Show form


?>
<script>
	function addLine(){
        // Get the counter
        counter = parseInt($("#counter").html()) + 1;
        
        // Update counter in html div
        $("#counter").html(counter);
        
        // Update line container to add a line
        $("#line-container").append('<div class="invite-line"><input type="text" name="contact-' + counter + '" size="45"><div class="invite-friendlist-action"><a href="javascript:addLine()"><img src="CSS/add.png" width="20px"></a></div>');
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
echo "<h3>".$lang['invite-title']."</h3>"
     ."<form method='POST' action='invite.php'>"
        ."<input type='hidden' name='friendlist' value='".escapeSingleQuote($_REQUEST['friendlist'])."'/>"
        ."<div class='invisible' id='counter'>1</div>"
        ."<div class='generic-line-headers'>"
            ."<div>"
                .$lang['invite-friendlist-password']."<b>".$_REQUEST['friendlist']."</b>"
            ."</div>"
        ."</div>"
        ."<div class='invite-line'>"
            ."<input type='password' name='friendlist_password' size='45' >"
        ."</div>"
        ."<div class='generic-line-headers'>"
            ."<div>"
                .$lang['invite-friend-mail-or-number']
            ."</div>"
        ."</div>"
        ."<div id='line-container'>"
            ."<div class='invite-line'>"
                ."<input type='text' name='contact-1' size='45' >"
                ."<div class='invite-friendlist-action'>"
                    ."<a href='javascript:addLine()' onmouseover='javascript:hover(\"add\")' onmouseout='javascript:out(\"add\")'>"
                        ."<img src='CSS/add.png'  width='20px'/>"
                    ."</a>"
                    ."<div class='invite-hover' id='action-add'>".$lang['invite-action-add']."</div>"
                ."</div>"
            ."</div>"
        ."</div>"
        ."<div class='invite-line'>"
            ."<input type='submit' value='".$lang['invite-go']."'>"
        ."</div>"
     ."</form>";

}
require_once("footer.php");
?>
