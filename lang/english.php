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
 * This file is a language file
 * Every variable defined in this file must overwrite those in default language file
 * 
 * R.1: Initial Release
 * 
 */
 
/***********************************************************************
 * Language
 **********************************************************************/

$langArray = Array(
// HTML
// headers
"html-description" => "Pronostics for Euro 2016",
"html-currentlanguage" => "en",
"html-keywords" => "Euro 2016, coupe du monde,  pronostics, pronostiques, bet, pari, sms, vocal, kiosk, emerginov, friends, amis, groupes, groups",
"html-title" => "Bets",
"ie-detected" => "You are currently using <a href='http://www.saveie6.com/'>Internet Explorer</a> to browse this website. This is not recommended, please visit this page and choose an other web browser: <a href='http://browsehappy.com/'>http://browsehappy.com/</a>",

// Teams
"teams-algeria" => "Algeria",
"teams-argentina" => "Argentina",
"teams-australia" => "Australia",
"teams-belgium" => "Belgium",
"teams-bosnia" => "Bosnia",
"teams-bosnia-herzegovina" => "Bosnia",
"teams-brazil" => "Brazil",
"teams-cameroon" => "Cameroon",
"teams-chili" => "Chili",
"teams-chile" => "Chili",
"teams-columbia" => "Columbia",
"teams-colombia" => "Columbia",
"teams-costarica" => "Costa rica",
"teams-croatia" => "Croatia",
"teams-denmark" => "Denmark",
"teams-ecuador" => "Ecuador",
"teams-egypt" => "Egypt",
"teams-england" => "England",
"teams-france" => "France",
"teams-germany" => "Germany",
"teams-ghana" => "Ghana",
"teams-greece" => "Greece",
"teams-honduras" => "Honduras",
"teams-iceland" => "Iceland",
"teams-iran" => "Iran",
"teams-italy" => "Italy",
"teams-ivorycoast" => "Ivory Coast",
"teams-japan" => "Japan",
"teams-marocco" => "Marocco",
"teams-mexico" => "Mexico",
"teams-netherlands" => "The Netherlands",
"teams-nigeria" => "Nigeria",
"teams-portugal" => "Portugal",
"teams-russia" => "Russia",
"teams-southkorea" => "South korea",
"teams-spain" => "Spain",
"teams-switzerland" => "Switzerland",
"teams-uruguay" => "Uruguay",
"teams-usa" => "USA",
"teams-albania" => "Albania",
"teams-austria" => "Austria",
"teams-czechrepublic" => "Czech Republic", 
"teams-hungary" => "Hungary",
"teams-iceland" => "Iceland",
"teams-northernireland" => "Northern Ireland",
"teams-poland" => "Poland",
"teams-peru" => "Peru",
"teams-republicofireland" => "Rep of Ireland",
"teams-romania" => "Romania",
"teams-saudiarabia" => "Saudi Arabia",
"teams-slovakia" => "Slovakia",
"teams-sweden" => "Sweden",
"teams-turkey" => "Turkey",
"teams-ukraine" => "Ukraine",
"teams-wales" => "Wales",

// Rounds
"rounds-groups-1" => "Groups phase - match 1",
"rounds-groups-2" => "Groups phase - match 2",
"rounds-groups-3" => "Groups phase - match 3",
"rounds-eights" => "16th Round",
"rounds-quarter" => "Quarter Finals",
"rounds-semi" => "Semi Finals",
"rounds-final" => "Final",

// Groups
"groups-1" => "Group A",
"groups-2" => "Group B",
"groups-3" => "Group C",
"groups-4" => "Group D",
"groups-5" => "Group E",
"groups-6" => "Group F",
"groups-7" => "Group G",
"groups-8" => "Group H",

// PAGES
// General & Misc
"Welcome" => "Welcome on Euro 2016 friendly bet website!",
"french" => "french",
"english" => "english",
"date-format-date" => "F, j",
"date-format-time" => "g.i a",
"date-format-full" => "F, j - g.i a",
"date-switch-local-time" => "Match times are set to '" . _TIMEZONE . "' time, click here to switch to your local time.",
"date-switch-timezone-time" => "Match times are set to your local time, click here to switch to '" . _TIMEZONE . "' time.",
"number-not-international" => "Your phone number is not in international form!",
"error-occured" => "An error occured, sorry for that...",
"you-are-not-authorized-identify" => "This page is authorized only to identified users, please login and try again...",
"you-are-not-authorized-admin" => "This page is authorized only to administrators...",
"you-are-not-in-any-friendlist" => "You are not involved in any group yet! You can get get involved in a group with the Account menu!",

// Account
"account-general-info" => "General information about you",
"account-update-password" => "Update your password", 
"account-friendlists-membership" => "Groups membership",
"account-phone-number" => "Phone number",
"account-password" => "Password",
"account-gift" => "Gift to winner",
"account-name" => "Pseudo",
"account-friendlist" => "Group name",
"account-friendlist-password" => "Group password",
"account-international-phone-only" => "(international form without space: e.g. +33612345678 or +221771234567)",
"account-you-are-involved-in" => "You are currently involved in the followings groups:",
"account-you-do-not-have-any-friendlist" => "You are not involved in any group. Please fill the following fields to join a group:",
"account-join-friendlist" => "Join or create a group:",
"account-friendlist-action-remove" => "Click to leave this group",
"account-friendlist-action-invite" => "Click to invite friends to this group",
"account-confirm-remove-from-friendlist-1" => "Are you sure to want to be deleted from ",
"account-confirm-remove-from-friendlist-2" => "? All your pronostics might be lost!",
"account-old-password" => "Old password", 
"account-new-password" => "New password",
"account-new-password-confirmation" => "New password again",
"account-your-password-to-create-friendlist" => "Your password is needed to create a group, please provide it now:",
"account-friendlist-creation-asked" => "Your group creation is saved. It will created as soon as possible by an administrator (within 24 hours).",
"account-friendlist-creation-ok" => "Your group has been created successfully!",
"account-friendlist-already-exists-error" => "The group already exists! Please join instead of create.",
"account-cant-create-friendlist" => "An error occured while trying to create your group, did you make a mistake in your password?",
"account-update-number-success" => "Number updated successfully!",
"account-update-number-error" => "An error occured while updating your account, please check your password!",
"account-update-name-success" => "Name updated successfully!",
"account-update-name-error" => "An error occured while updating your account, please check your password!",
"account-update-gift-success" => "Gift updated successfully!",
"account-update-gift-error" => "An error occured while updating your account, please check your password!",
"account-update-friendlist-success" => "Groups info successfully updated!",
"account-update-friendlist-error" => "An error occured while updating your groups info, please check the group name and password!",
"account-update-password-success" => "Your password has been updated successfully!",
"account-update-password-error" => "An error occured while updating your account, please check your password!",
"account-update-please-provide-password" => "Please provide your password to update this info!",
"account-update-please-provide-new-password" => "Please provide the new password to set!",
"account-update-password-confirmation-not-same" => "New password and confirmation are not identical!",
"account-update-password-old-password-invalid" => "Old password provided not valid!",
"account-update-please-provide-friendlist-password" => "Please provide the group password: ",
"account-notification" => "Manage your notifications",
"account-notification-join-friendlist" => "Check to be notified by SMS when a friend join a group you are in.",
"account-notification-resume-day" => "Check to be notified by SMS of results of the previous days.",
"account-notification-no-bet" => "Check to be notified by SMS when you forgot doing a bet.",
"account-notification-new-score" => "Check to be notified in live by SMS when there is a new score (experimental).",
"account-go" => "Go!",
"account-join-friendlist-button" => "Join!",
"account-create-friendlist-button" => "Create!",
"forgot_password" => "forgot your password?",

// Add match
"addmatch-title" => "Add a match",
"addmatch-team1" => "Team 1",
"addmatch-team2" => "Team 2",
"addmatch-round" => "Round",
"addmatch-group" => "Group",
"addmatch-date" => "Date",
"addmatch-time" => "Time",
"addmatch-timezone-warning" => "Please remember that the time and date set here must follow the timezone where are played the matches",
"addmatch-go" => "Add!",
"addmatch-cant-add" => "An error occured while adding the match, please try again.",
"addmatch-add-success" => "The following match has been added: ",

// Do bet
"dobet-title" => "Make your bet for planned matches",
"dobet-date-1" => "On ",
"dobet-date-2" => " at ",
"dobet-versus-1" => ", the team ",
"dobet-versus-2" => " will play against ",
"dobet-what-is-your-bet" => "You do not have any bet on this match yet. Do a bet: ",
"dobet-your-bet-is" => "Your current bet is: ",
"dobet-default-for-all-friendlists" => "Make this bet the default bet for all same matches on other groups",
"dobet-go" => "Ok!",
"dobet-show-friends-bet" => "[Click here to show your friends bets]",

// Identification
"ident-number" => "Phone number:",
"ident-login" => "Login:",
"ident-password" => "Password:", 
"ident-go" => "Go!",
"ident-ok-1" => "You are now identified as ",
"ident-ok-2" => "! Welcome!",
"ident-already-done-1" => "You are already identified as ",
"ident-already-done-2" => "! To logout, please click on Logout button on left menu.",
"ident-wrong-password" => "Your password is invalid",
"ident-error-occured-checking" => "An error occured while checking your identity. Are you registered? If yes, please try again later...",

// Invite
"invite-title" => "Invite friends",
"invite-friendlist-password" => "The Group password for ",
"invite-friend-mail-or-number" => "Your friend mail address or phone number (one per line)",
"invite-action-add" => "Click to add a new line",
"invite-mail-sent" => "A mail invitation has been sent to: ",
"invite-sms-sent" => "A sms invitation has been sent to: ",
"invite-mail-subject" => "Pronotics invitation",
"invite-mail-message-1" => "Hello,\n\nYou've been invited to join the group ",
"invite-mail-message-2" => "!\n\nTo join this group, please click on the following link:\n http://"._WEBSITE_BASEURL._WEBSITE_BASEFOLDER."/",
"invite-mail-message-3" => "\n\nIf you are not yet registered on Foot, please use the following link: \n http://"._WEBSITE_BASEURL._WEBSITE_BASEFOLDER."/",
"invite-mail-message-4" => "\n\nSee you soon !\n",
"invite-error-no-friendlist" => "No group selected!",
"invite-error-invalid-contact-1" => "The contact ",
"invite-error-invalid-contact-2" => " is invalid!",
"invite-go" => "Invite!",

// Logout
"logout-ok" => "You are now disconnected...", 

// Matches Results
"matches-results-line-match" => "Match",
"matches-results-line-date" => "Date",
"matches-results-line-score" => "Result",
"matches-results-line-your-bet" => "Your bet",
"matches-results-line-your-points" => "Pts",
"matches-results-line-stats" => "Stats",
"matches-results-versus" => " vs. ", 

// Menus
"menu-title" => "Euro - Menu",
"menu-index" => "Home",
"menu-account" => "My Account",
"menu-matches-results" => "Matches results",
"menu-ranking" => "Ranking",
"menu-dobet" => "Do a bet",
"menu-inbox" => "Inbox",
"menu-add-match" => "Add matches",
"menu-add-result" => "Enter matches results",
"menu-disconnect" => "Logout", 
"menu-connect" => "Login", 
"menu-register" => "Register", 
"menu-add-team" => "Add teams",
"menu-help" => "Help",
"menu-friendlist-selector" => "Group:",
"menu-news" => "News", 
"menu-mobile-go" => "menu",
"menu-title-mobile" => "Euro 2016",

// Ranking
"rank-line-name" => "Name",
"rank-line-points" => "Points", 
"rank-line-gift" => "Gift to winner",

// Register
"register-title" => "Register on website",
"register-phone-number" => "Phone number",
"register-password" => "Password",
"register-gift" => "Gift to winner",
"register-name" => "Login",
"register-friendlist" => "Group name",
"register-friendlist-password" => "Group password",
"register-all-required" => "All fields with * are required",
"register-international-phone-only" => "(international form without space: e.g. +33612345678 or +221771234567)",
"register-please-fill-number" => "Please fill your phone number correctly!",
"register-please-fill-name" => "Please fill your pseudo correctly!",
"register-please-fill-password" => "Please fill your password correctly!",
"register-please-fill-gift" => "Please fill your gift correctly!",
"register-an-error-occured" => "An error occured while trying to register you, please try again...",
"register-already-in" => "This login is already used!",
"register-you-are-now-registered" => "Good! You are now registered on our website! Please login to continue!",
"register-add-in-friendlist-failed" => "We could not add you in the group: ", 
"register-add-in-friendlist-success" => "You were also added in the group: ",
"register-go" => "Register me!",

// Lost password
"lost-password-admin-will-care" => "A mail has been sent to administrators, they will reset your password. Cheers.",
"lost-password-sms-sent" => "A SMS has been sent to your phone number, please check the new password!",
"lost-password-sms-message" => "Your new password is: ",

// Set result
"setresult-title" => "Set results for played matches",
"setresult-go" => "Go!",
"setresult-cant-set" => "An error occured while setting the match result, please try again.",
"setresult-set-success" => "The following result has been set: ",

// SMS
"info" => "info",
"bet" => "bet",
"stat" => "stat",
"rank" => "rank",
"join" => "join",
"leave" => "leave",
"invite" => "invite",
"sms_action_unknown" => "Oops, I don't understand!",
"sms_authorized_actions" => "Authorized actions are: \n",
"sms_invite_1" => "invites you to the Foot application. Send Foot join",
"no_friendlist_selected" => "No group selected yet, join or create groups (web)",
"user_not_registered" =>"You are probably not register to the Foot betting system, send CAN join.",
"friendlist_required" => "Group name and password are mandatory.",
"friendlist_notfound" => "Group name and password are mandatory.",
"friendlist_bad_password" => "group bad password.",
"friendlist_password_required" => "Group password mandatory. send Foot join group group_password.",
"user_creation_ok" => "User successfully created.",
"user_creation_ko" => "User creation failed.",
"join_ok" => "You have been successfully added",
"join_ko" => "Impossible to join the group",
"leave_ok" => "You have been successfully removed from the group",
"leave_ko" => "Impossible to leave the group",
"info_instruction" => "Send Foot info to get the next match to bet.",
"bet_instruction" => "Send Foot bet 1-2 if you want to bet this score.",
"your_friendlist" => "Your group(s): \n",
"your_password" => "Your password is ",
"next_bet" => "\nNext bet: ",
"sms_bet_error" => "Oops, I can't understand the score you provided, please check the SMS content!",
"for" => "for",
"to" => " to ",
"to_sms_number" => "",
"sms_invite" =>  " invite you to Foot betting. Send Foot join ",
"sms_send" => "send SMS ",
"sms_invite_ko" => "impossible to invite friends by SMS. Check the SMS format (Foot invite +3312334,+23455,+34456 group group_pwd)",
"first" => "st",
"second" => "nd",
"third" => "rd",
"xth" => "th",
"sms-notification-do-bet-1" => "Warning, the match : \n",
"sms-notification-do-bet-2" => " - ",
"sms-notification-do-bet-3" => " \nwill start soon but you did not bet ye!",
"sms-notification-do-bet-4" => "\nAnswer this message with 'Foot info'to handle your bets.",
"sms-notification-resume-day-1" => "This is the end of the day for the Euro. Today results are: \n",
"sms-notification-resume-day-2" => " vs ",
"sms-notification-resume-day-3" => ": ",
"sms-notification-rank-title" => "\nYour rank:",
"sms-notification-rank-friendlist" => "\nGroup: ",
"sms-notification-rank-n-begin" => "\n  ",
"sms-notification-rank-n-separator" => " - ",
"sms-notification-rank-you" => "You!",
"sms-notification-rank-points-begin" => " [",
"sms-notification-rank-points-end" => " pts]",
"sms-notification-user-join-friendlist-1" => "Your friend ",
"sms-notification-user-join-friendlist-2" => " has join your group '",
"sms-notification-user-join-friendlist-3" => "' ! His gift is: ",
"sms-notification-new-match" => "A new match is starting!\n",
"sms-notification-new-score" => "GOOOOAL!\n",
"code_sms_sent" => "code sent by SMS",
"code_sms_unknown_user" => "unknown phone number, please Register before Login.",
"prono" => "Pronostics:", 
"draw" => "draw",
"nb_players" => "Nb players:",
"all_bet_done" => "All the bets have been done.",
"recorded" => " recorded.",
"sms_first_you" => " 1st: you(",
"sms_pts" => " pts),",
"sms_last_you" => " (and last one): you(",
"sms_last" => " (and last one):",
"sms_you" => ": you (",
"sms_friendlist" => "|Group: ",

// IVR
"ivr_hello" => "Hello ",
"ivr_welcome" => "Welcome on the betting kiosk of the Euro 2016!",
"ivr_error_unknown_number" => "Sorry, we don't know your phone number! Please, connect on the web site to add your phone number in your account.",
"ivr_error_to_many_accounts" => "Your phone number is used on many accounts, there is no way to bet from this vocal kiosk!",	
"ivr_bye" => "Bye",
"ivr_user_no_group" => "You do not belong to any group. Please join a group on web site to be allowed to bet from this vocal kiosk.",
"ivr_no_match_to_bet" => "There is no match to be for.",
"ivr_next_match" => "The next match to bet for is : ",
"ivr_versus" => " againts ",
"ivr_menu_1" => "Press 1 to place a bet on this match. ",
"ivr_menu_2" => "Press 2 to get pronostics trends on this match. ",
"ivr_menu_3" => "Press 3 to get your rank. ",
"ivr_menu_9" => "Press 9 to exit. ",
"ivr_enter_nb_goal" => "Enter the number of goals for ",
"ivr_you_bet" => "You bet ",
"ivr_confirm_bet" => " Press 1 to confirm, 0 to cancel and bet again.",
"ivr_bet_invalid" => "Sorry, your bet is invalid!",
"ivr_bet_registered" => "Bet saved!",
"ivr_you_are" => "You are ",
"ivr_of_friendlist" => " of the group ",
"ivr_stats" => " bets already done by other players. The last trend is: ",
"ivr_not_enough_bet" => "Sorry, we don't have enough bet this far to give you a valid trend.",

// Add news
/*
"addnews-date"=> "Date",
"addnews-headline"=> "Titre",
"addnews-text"=> "Text",
"addnews-media"=> "Media",
"addnews-credit"=> "Credit",
"addnews-tag"=> "Tag",
"addnews-author" => "Auteur",
*/

// News
"news-title" => "<b>Last news : </b>",

// Index
"index-welcome" => "
<h3>Welcome!</h3>
<p>The next Euro of football will take place in June 2016. Let's organize friendly betting competition to share some fun during the competition.</p>
<p>You can already register freely (it is only friendly betting, no money to win here) on this web site!</p>

<h3>You, Your friends, gifts</h3>
<p>This web site deals with friendly betting within a closed group that you can create or join. it is very simple, when you register on the site, you are automatically added to a default group called Africafriends.
This group is public and no gift is needed except the consideration and admiration of all the other players. The default group allows player who do not have friends to feel having some anyway. 
If you are invited in a private group or if you create your own private group, you shall accept to give a cheap gift to the winner of the group. 
You chose the gift (it may be warmful congratulations, which has no price...), be creative, be generous, be you !</p>

<h3>Web, SMS, Calls</h3>
<p>On the web site, you can bet and manage your groups of friends.</p>
<p>But the story does not end here. You can bet without internet access. In fact you may join groups, bet, get the ranking or statistics through <b>SMS!</b></p>
<p>Even better, you can use a vocal kiosk to perform all these actions !</b></p>
<p>For more details, please click on the help menu.</p>
",

// Help page
"help-title" => "Learn how to use this website",
"help-content-1" => "
<p><b>What can I do on this site ?</b></p>
<p>--> You can bet with friends on the matchs of the next football Euro planned in June in Brazil. Each bet grants you points. You are ranked per group. You can thus compare your betting skills with your friends. It is possible to be directly on the web site, but also by SMS or voal kiosk.</p>

<p><b>How can I register?</b></p>
<p>--> Take it easy ! You just have to click on the menu register on the main page of the web site, then follow the instructions !
If you have been invited by a friend by SMS or by mail, you will be redirected to the registration page.
Your identifier is your mobile number ! Do not forget to precise the present for the winner of the group !</p>

<p><b>Once registered, what can I do ?</b></p>
<p>--> During registration (and if you have been invited by  a friend), you will be automatically added in a group (the group of your friends :p). You may thus bet directly.</p>
<p> If you do not belong to any group, do not panic! You can join groups or even cretae yours. To join a group, you will need the group name and its password. Use the My Account menu of the web site.</p>

<p><b>Why a bloody system of groups ?</b></p>
<p>--> With the system of group, you can create as many groups as you want (google would have said circles) and launch as many competitions as you want...if your cousin does not speak with the best friend fo your sister, you may compete in 2 groups !
Everything is in the My Account menu !</p>

<p><b>How to bet by SMS ?</b></p>
<p>--> What a good question! Once you have registered (see above) send Foot info on the right number (see below), you shall get the next match to bet in the SMS answer (standard SMS).
</p>
<p>You can get statistics on the next match by sending, Foot stat.</p>
<p>get your ranking, sending CAN rank.</p>
<p>Join a group (here the group \"My friends\" with password equals to 1234), Foot join My friends 1234.</p>
<p>To invite friends to join your group , Foot invite +22512345678,+3323456789,+24034567890 My friends 1234.</p>

<p><b>Which SMS number ?</b></p>
<p>--> In France, SMS shall be sent to +33 7 87 90 39 52</p>
<p>--> In Mali,  SMS shall be sent to (not yet available)</p>

<p><b>Betting through vocal kiosk, myth or reality?</b></p>
<p>--> Yes, you can, call the following number. According tou your mobile phone the vocal kiosk will be in French or in English (standard local rate):</p>
<p>In France : not yet available</p>
<p>In Mali : not yet available </p>

<p><b>How do we calculate the points ?</b></p>
    <p>
    for non draw results :<br>
    for exact bet: the maximum of points<br>
    for a good bet with the right goal difference : the minimum of points + bonus<br>
    for a good bet (you found the winning team but the score is totally different) : the minimum of points<br>
    <br>
    in case of draw :<br>
    for exact bet: the maximum of points<br>    
    for a good result without the right score (1 goal gap max) : the minimum of points + bonus<br>
    for a good result with a difference greater than 1 goal: the minimum de points<br>
    <br>
    <br>
    Hereafter the table of points according to the game phase  :
    <table>
        <tr>
            <td>Phase</td>
            <td>Maximum</td>
            <td>Minimum + Bonus</td>
            <td>Minimum</td>
        </tr>
        <tr>
            <td>Round-robin</td>
            <td>3</td>
            <td>2</td>
            <td>1</td>
        </tr>
        <tr>
            <td>Quarter</td>
            <td>5</td>
            <td>4</td>
            <td>2</td>
        </tr>
        <tr>
            <td>Semi</td>
            <td>7</td>
            <td>5</td>
            <td>3</td>
        </tr>
        <tr>
            <td>Final</td>
            <td>10</td>
            <td>8</td>
            <td>5</td>
        </tr>
    </table>
    <br>
    <br>
    Example 1 : I bet 3-2 for France versus Sweden in 1st round phase. <br>
    The result is 3-2 : I win 3 points<br>
    The result is 1-0 : I win 2 points<br>
    The result is 3-1 : I win 1 points<br>
    The result is 1-1 : no point<br>
    <br>
    <br>
    Example 2 : I bet 1-1 for france versus Sweden in 1st round phase.  <br>
    The result is 1-1 : I win 3 points<br>
    The result is 2-2 : I win 2 points<br>
    The result is 3-3 : I win 1 points<br>
    The result is 1-0 : no point<br>
    </p>
    </p>
",
);

?>
