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
Vocal portal

Main menu
Due to DTMF interaction, the actions are limited
- get information
- bet (for all groups)
- create a user

TODO ? shall we allow betting per group 
=> submenu group => bet for a group, rank of the group...

1) detects if the user is known or not
if user known skip to submenu 1.1
if not go to submenu 1.2

1.1: menu user
Welcome on the CAN kiosk
You are participating to N groups
what do you want to do
type
1 to bet for all groups => 1.1.1
2 to get your ranking in the different groups => 1.1.2
3 to get statistics => 1.1.3

1.2: menu register (create a user)
*/

require_once('passwords.php');
require_once('config.php');
require_once('lang/lang.php');
require_once('includes/miscFunctions.php');
require_once('Emerginov.php');
require_once('includes/User.class.php');
require_once('includes/Friendlist.class.php');
require_once('includes/SqlConnector.class.php');


// Load language
$lang = loadLanguage();

// Create an new SQL connector object
$sql = new SqlConnector();

// Set timezone
date_default_timezone_set(_TIMEZONE);

$lang = loadLanguage("lang/"._LANG_DEFAULT);
$voice = _LANG_DEFAULT_IVR;

// ****************************************************************************************************
// ****************************************************************************************************
// ****************************************************************************************************
//
//
// 	Start of the vocal menu
//
//
// ****************************************************************************************************
// ****************************************************************************************************
// ****************************************************************************************************

// Answer the call
$call->Answer();
 
//$fp = fopen("toto.log", "w");
//fwrite($fp, "Start of the Audio script");
             
// Get user info
$number = $call->getCallerNumber();
$number = filter_var($number, FILTER_VALIDATE_REGEXP, array( 'options' => array('regexp' => '/^(0|\+)[0-9]+$/')));
// The number is set, try to internationnlize it
// TODO
// for the moment only in French
// just replace 0XXXXXXX with +33XXXXXXX
// depends on the dialplan
$pattern[] = "/^0/";
//$pattern[] = "/^\+/";
$replace[] = "+33";
//$replace[] = "";
$number = preg_replace($pattern, $replace, $number);

echo("Call from $number\n");
    
// Try to get user from this number
$req = "SELECT login, number FROM members where number = '".mysqli_real_escape_string($sql->dbhandle, $number)."'";
$usersArray = $sql->fetchAssoc($sql->doQuery($req));

// If we have more than one user or no user at all, exit
if (count($usersArray) < 1){
    $call->Say($lang['ivr_error_unknown_number'],$api_login,$api_password,$voice);
    //$call->Say($lang['ivr_bye'],$api_login,$api_password,$voice);
    $call->Hangup();
    exit(0);
}
else if (count($usersArray) > 1){
    $call->Say($lang['ivr_error_to_many_accounts'],$api_login,$api_password,$voice);
    $call->Say($lang['ivr_bye'],$api_login,$api_password,$voice);
    $call->Hangup();
    exit(0);
}

// Being here suppose that we found an unique phone number for a user
$user = new User($usersArray[0]['login']);

echo("This is {$user->login}\n");

// Get language and voice
if (isset($user->language)){
    $lang=loadLanguage("lang/{$user->language}");
    $voice=$user->ivr_voice;
}

// Bonjour machin et bienvenue
$call->Say($lang['ivr_hello']." {$user->login}",$api_login,$api_password,$voice);
$call->Say($lang['ivr_welcome'],$api_login,$api_password,$voice);

// If no friendlists
if (count($user->friendlists)<1){
    $call->Say($lang['ivr_user_no_group'],$api_login,$api_password,$voice);
    $call->Say($lang['ivr_bye'],$api_login,$api_password,$voice);
    $call->Hangup();
    exit(0);
}

$dtmf = "-1";
$nextMatch = null;
// Ok, the user has at least 1 friendlist, go for voice state machine
while ($dtmf !== "9" ){
    // Ask and do things
    switch ($dtmf) {
        case "-1":
            // ---------------------------------------------------------
            // Main menu
            // ---------------------------------------------------------
            $menu = array(
                "1" => $lang['ivr_menu_1'],
                "2" => $lang['ivr_menu_2'],
                "3" => $lang['ivr_menu_3'],
                "9" => $lang['ivr_menu_9'],
            );
            // get info on next match to bet for
            $nextMatch = $user->getNextMatchForAllFriendlists();
            
            // if nextMatch is null, no match to bet
            if (!$nextMatch){
                echo("No next match\n");
                $call->Say($lang['ivr_no_match_to_bet'],$api_login,$api_password,$voice);
                unset($menu["1"]);
                unset($menu["2"]);
            }
            else{
                // Next match
                echo("Next match is: ".$nextMatch['team1_name']." vs. ".$nextMatch['team2_name']." on ".$nextMatch['date']."\n");
                $call->Say($lang['ivr_next_match'],$api_login,$api_password,$voice);
                $call->Say($lang['teams-'.$nextMatch['team1_name']].$lang['ivr_versus'].$lang['teams-'.$nextMatch['team2_name']],$api_login,$api_password,$voice);
            }
            
            // Menu
            $prompt = "";
            foreach ($menu as $item){
                $prompt .= $item;
            }
            $ret = $call->Ask($prompt,$api_login,$api_password,$voice);
            $dtmf = isset($ret['result']) ? $ret['result'] : "-1";
        break;
        case "1":
            // ---------------------------------------------------------
            // Do bet
            // ---------------------------------------------------------
            // if nextMatch is null, no match to bet - user got it wrong
            if (!$nextMatch){
                //$call->Say($lang['ivr_no_match_to_bet'],$api_login,$api_password,$voice);
                $dtmf = "-1";
                break;
            }
            $call->Say($lang['teams-'.$nextMatch['team1_name']].$lang['ivr_versus'].$lang['teams-'.$nextMatch['team2_name']],$api_login,$api_password,$voice);
            $score = array();
            $score[0] = $call->Ask($lang['ivr_enter_nb_goal'].$lang['teams-'.$nextMatch['team1_name']],$api_login,$api_password,$voice);
            $score[1] = $call->Ask($lang['ivr_enter_nb_goal'].$lang['teams-'.$nextMatch['team2_name']],$api_login,$api_password,$voice);
            if (!is_numeric($score[0]['result']) || !is_numeric($score[1]['result'])){
                $call->Say($lang['ivr_bet_invalid'],$api_login,$api_password,$voice);
                break;
            }
            $score[0]['result'] = $score[0]['result'] + 0; // Convert to real numbers
            $score[1]['result'] = $score[1]['result'] + 0; // Convert to real numbers
            $prompt = $lang['ivr_you_bet'].$lang['teams-'.$nextMatch['team1_name']].$lang['ivr_versus'].$lang['teams-'.$nextMatch['team2_name']]." : ".$score[0]['result']." ".$score[1]['result'].". ".$lang['ivr_confirm_bet'];
            $confirm = $call->Ask($prompt,$api_login,$api_password,$voice);
            
            if ($confirm['result'] == "1") {
                $bet= $user->doBet($nextMatch['id_match'],$score[0]['result'],$score[1]['result']);
                $call->Say($lang['ivr_bet_registered'],$api_login,$api_password,$voice);
                $dtmf = "-1";
            }
            else if ($confirm['result'] != "0") {
                $dtmf = "-1";
            }
        break;
        case "2":
            // ---------------------------------------------------------
            // Get stats
            // ---------------------------------------------------------
            // if nextMatch is null, no match to bet - user got it wrong
            if (!$nextMatch){
                //$call->Say($lang['ivr_no_match_to_bet'],$api_login,$api_password,$voice);
                $dtmf = "-1";
                break;
            }
            $stats = getPronosticsMatch($nextMatch['id_match']);
            if ($stats['nb'] > 0)
                $prompt = $stats['nb'].$lang['ivr_stats'].$lang['teams-'.$nextMatch['team1_name']]." ".$stats['team1']."%, ".$lang['teams-'.$nextMatch['team2_name']]." ".$stats['team2']."%, ".$lang['draw']." ".$stats['draw']."%";
            else
                $prompt = $lang['ivr_not_enough_bet'];
            $call->Say($prompt,$api_login,$api_password,$voice);
            $dtmf = "-1";
        break;
        case "3":
            // ---------------------------------------------------------
            // Get rank
            // ---------------------------------------------------------
            $prompt = "";
            foreach ($user->friendlists as $friendlist) {
                $rank = $user->getRank($friendlist);
                //
                // rank = complete rank for a given group
                //
                // check the number to detect the rank of the user
                //
                // 	
                $rank_nb=1;
                print_r($rank);
                foreach ($rank as $player => $points) {
                    $exten = "";
                    if ($rank_nb == 1) {
                        $exten = $lang['first'];
                    } else if($rank_nb == 2) {
                        $exten = $lang['second'];
                    } else if($rank_nb == 3) {
                        $exten = $lang['third'];
                    } else {
                        $exten = $lang['xth'];
                    }
                    if ($player == $user->login) {
                        $prompt .= $lang['ivr_you_are'].$rank_nb.$exten.$lang['ivr_of_friendlist'].$friendlist.", ";
                        break;
                    }
                    $rank_nb++;
                }
            }
            $call->Say($prompt,$api_login,$api_password,$voice);
            $dtmf = "-1";
        break;
        default:
            // ---------------------------------------------------------
            // Wrong choice
            // ---------------------------------------------------------
            
            $dtmf = "-1";
        break;
    }
}

$call->Say($lang['ivr_bye'],$api_login,$api_password,$voice);
$call->Hangup();
?>
