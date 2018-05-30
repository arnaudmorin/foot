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
//
//
// Callback for SMS receiveing
// All the SMS prefixed by CAN will be routed to this file
// 
//

// CAN => renvoit toutes les actions possibles
//  - help (pour avoir de l'aide)
//  - info (pour avoir la liste des paris à faire)
//  - pari (pour parier)
//  - clas (pour avoir le classement)
//  - class (pour avoir le classement)
//  - stat (pour avoir des stats)
//  - stats (pour avoir des stats)
//  - join (pour rejoindre un groupe)
//  - quit (pour quitter un groupe)
//  - invite (pour générer des invitations SMS pour rejoindre un groupe)  

// Include passwords.php to get back Nursery passwords
set_include_path(get_include_path() . PATH_SEPARATOR . "../");
require_once("loader.php");

// Get SMS information
$sender = $_REQUEST["SOA"];
$content = $_REQUEST["Content"];

$sender=$_REQUEST['SOA'];
$content=preg_split('/ /', $_REQUEST['Content'], 2);

$text = $content[1];
$content2=preg_split('/ /', $text, 2);

// Format
// FOOT <action> <parameters>
//
// remove FOOT
$order=$content2[0];
$text=$content2[1];

$Emerginov = new Emerginov($api_login, $api_password);

// Open a log file
//$fh = fopen("log","a");

// Write SMS content to this log file
//fwrite($fh, "---- On ".date("r").", I received a SMS from: $sender. Content is: $content\n");

// ----------------------------------------------------------------------------------------------------------------
// 
//
// retrieve User from mobile number and build User object
// 
//
// ----------------------------------------------------------------------------------------------------------------
$login = getLoginFromNumber($sender);

// if login OK =======> proceed operation
// if not send a SMS telling to create an account
if (empty($login) ) {
	 $text_sms =$lang['user_not_registered'];

    // send SMS
    //echo($text_sms);
    $Emerginov->sendSMS($sender,$text_sms); 
    exit();
} 

$user = new User($login[0]['login']);

//print_r($user);

// Select Language    
$lang;
if ($user->language === _LANG_FRENCH){
    $lang=loadLanguage('lang/french.php');
}else {
    $lang=loadLanguage('lang/english.php');
}

$list_of_friendlists = $user->friendlists;

// ----------------------------------------------------------------------------------------------------------------
// 
//
// Operations
// 
//
// ----------------------------------------------------------------------------------------------------------------
/*
Change the function with the first keyword
*/
switch (strtolower($order)){
        
/*
Translate and List refer to Translateuage class linked to Google Translate API
*/
        case $lang['info']: 
        // Get info of the user: 
        // groups he belongs to
        // THE next match to bet, ...   
        // we just consider CAN info
        // TODO do we consider CAN info mygroup => idem rank?
        //
        // SMS example 
        //
        // =>
        // CAN info
        // <= 
        // Your group:007(3/15),Los_Amigos(5/13)
        // next match to bet: Senegal-Gabon
        // send the score by SMS 
        // CAN bet 1-3
        // this will bet 1 for Senegal and 3 for Gabon
        //
        
        $text_sms ="";

        // get info on nxt match to be
        $next_match = array();
            
        if (sizeof($list_of_friendlists) > 0  ){
            $text_sms .=$lang['your_friendlist'];

            $date_next_match = "2012-01-01 0:00:00";
            $the_next_match = $list_of_friendlists[0];
    
            foreach ($list_of_friendlists as $i) {
                $text_sms .= " - ".$i.",\n";
            }
            $text_sms = rtrim($text_sms, ",\n");
            $text_sms.=".\n";

            $the_next_match = $user->getNextMatchForAllFriendlists();
            if (!$the_next_match || $the_next_match['id_match']  === "0") {
                // No next match to bet, don't say anything
                $text_sms .= $lang['ivr_no_match_to_bet']; 
            } else {    
                $text_sms .= $lang['next_bet'].$lang['teams-'.$the_next_match['team1_name']]." vs ".$lang['teams-'.$the_next_match['team2_name']].".\n\n".$lang['bet_instruction'];
            }       
        } else {
            $text_sms .=$lang['no_friendlist_selected'];         
        }
        
        //echo "\n SEND SMS :".$text_sms.".\n";         
        // send SMS
        $Emerginov->sendSMS($sender,$text_sms);         

     break;
        
        case $lang['bet']:
        // Bet on 1 match
        //
        // SMS example
        //
        // => 
        // CAN bet 1-3
        // <=
        // Bet registered
        // next bet: Ivory Coast-Lybia
        //
            
        // Parse bets
        // in first step, we assume that the score will be given this way
        // 1-2 CAN bet 1-2
        // 1:2 CAN bet 1:2
        // 1 2 CAN bet 1 2
        // TODO for the moment we do not consider country name
        //$regex = "#('/ /'|'/-/'|'/,/'|'/:/')#";
        $regex = "/[:,-\s]/";
        $score=preg_split($regex, $text, 2);
        // 3-2
        // => score[0]=3
        // => score[1]=2
        $match = $user->getNextMatchForAllFriendlists();

        if (!$match || $match['id_match']  === "0") {
            // No more bet to do
            $text_sms =$lang['all_bet_done'];           
        } else {
            // A bet is available
            // Check if score provided is valid
            if (is_numeric($score[0]) && is_numeric($score[1])){
                $bet= $user->doBet($match['id_match'],$score[0],$score[1]);
                //$text_sms =$lang['bet']." ".$lang['teams-'.$match['team1_name']].":".$score[0]."-".$lang['teams-'.$match['team2_name']].":".$score[1].$lang['recorded'];
                $text_sms = $lang['bet']." ".$lang['teams-'.$match['team1_name']]." vs ".$lang['teams-'.$match['team2_name']].": ".$score[0]."-".$score[1]." ".$lang['recorded'];
                
                // in ack send next match
                $the_next_match = $user->getNextMatchForAllFriendlists();
                if ($the_next_match && $the_next_match['id_match']  !== "0") {
                    $text_sms .= "\n".$lang['next_bet']." ".$lang['teams-'.$the_next_match['team1_name']]." vs ".$lang['teams-'.$the_next_match['team2_name']];
                }
            }
            else{
                // Error in score provided
                $text_sms = $lang['sms_bet_error'];
            }
        }
        
        //echo "\n SEND SMS :".$text_sms.".\n";         
        // send SMS
        $Emerginov->sendSMS($sender,$text_sms);         
        // Feed the Database
    break;

        case $lang['stat']:
        case 'stats':
        // get stats
        // nb of players
        // nb of groups
        // bet stats
        //
        // =>
        // CAN stat
        // <=
        // CAN betting: 
        // nb of players: 35
        // nb of groups
        // rating for next Match Senegal(59%)-Gabon(21%)
        //      
        // Get Next match

        $match = $user->getNextMatchForAllFriendlists();

        $stats = getPronosticsMatch($match['id_match']);
        //$text_sms = $lang['prono']." ".$lang['teams-'.$match['team1_name']]."(".$stats['team1']."%),".$lang['teams-'.$match['team2_name']]."(".$stats['team2']."%),".$lang['draw']."(".$stats['draw']."%) (".$lang['nb_players'].$stats['nb'].")";
        $text_sms = $lang['prono']." ".$lang['teams-'.$match['team1_name']]." vs ".$lang['teams-'.$match['team2_name']]."\n";
        $text_sms.= " - ".$stats['team1']."% ".$lang['for']." ".$lang['teams-'.$match['team1_name']]."\n";
        $text_sms.= " - ".$stats['team2']."% ".$lang['for']." ".$lang['teams-'.$match['team2_name']]."\n";
        $text_sms.= " - ".$stats['draw']."% ".$lang['for']." ".$lang['draw']."\n";
        $text_sms.= "(".$lang['nb_players'].$stats['nb'].")";
        
        //echo "\n SEND SMS :".$text_sms.".\n";         
        // send SMS
        $Emerginov->sendSMS($sender,$text_sms);         
        break;

        case $lang['rank']:
        case 'class':
        //
        // get ranking
        //
        // => 
        // FOOT rank my_friendlist
        // <= 
        // my_friendlist: 1st:toto(15 pts),5èmeYOU(10):8th titi(5)
        //
        // =>
        // Foot rank
        // =>
        // my_friendlist_1: 1:toto(15 pts),5:YOU(10),8:titi(5)
        // my_friendlist_2: 1:YOU(10 pts),2:titi(8),15:toto(0)
        //
        // the group name is optional, if no name, 
        // we will send back the ranking for all the groups
        // if a name is specified, retrieve the rank for the given group
        //

        // case Foot rank (no group mentioned)
        if (!isset($text) || $text == "") {
            // check the user rank for all the group            
            $text_sms = "";                
                $text_sms .= $lang['sms-notification-rank-title'];
            
                foreach ($list_of_friendlists as $i) {
                    $rank = $user->getRank($i);
                    //
                    // rank = complete rank for a given group
                    //
                    // check the number to detecte the rank of the user
                    //
                    //  
                    $rank_nb=1;
                    $text_sms.= $lang['sms-notification-rank-friendlist'].$i;

                    foreach ($rank as $player => $points) {

                        // By SMS we provide the ranking as follow
                        // 1st: sdfdf with X points
                        // Nth: you with Y poitns
                        // last: sdfkjfskf with Z points
                        
                        // You, first and last guy
                        if ($rank_nb == 1 || $rank_nb == sizeof($rank) || $player == $user->login) {
                            // It's you!
                            if ($player == $user->login) {
                                $text_sms .= $lang['sms-notification-rank-n-begin'].$rank_nb.$lang['sms-notification-rank-n-separator'].$lang['sms-notification-rank-you'].$lang['sms-notification-rank-points-begin'].$points.$lang['sms-notification-rank-points-end'];
                            } 
                            // It's someone else
                            else{
                                $other_user = new User ($player);
                                $name =$other_user->login;
                                $text_sms .= $lang['sms-notification-rank-n-begin'].$rank_nb.$lang['sms-notification-rank-n-separator'].$name.$lang['sms-notification-rank-points-begin'].$points.$lang['sms-notification-rank-points-end'];
                            }
                        }
                        $rank_nb++;
                    }
                }           
        // case CAN rank group
        } else {
                // check that the group exists
                $parameters =   array('group'=> $text);
                $res = $Emerginov->ManageGroups("getGroupInfo", $parameters);
                // the group exists             
                if ($res->Success) {
                    $rank = $user->getRank($text);
                    $rank_nb=1;
                    
                    foreach ($rank as $player => $points) {
                        $user = new User ($player,"","","",false,0,"","",true);
                        $text_sms .= $rank_nb.":".$user->name."(".$points.$lang['sms_pts'];
                        $rank_nb++; 
                    }
                    $text_sms=rtrim($text_sms, ',');
                }
                else {
                    $text_sms =$lang['group_notfound'];                 
                }       
        }
        
        //echo $text_sms;
        $Emerginov->sendSMS($sender,$text_sms);
        break;

        case $lang['join']:
        // join a betting group
        //
        // =>
        // CAN join <group> <groupe pwd>
        // <=
        // OK you have been added successfuly to the group <groupe>
        // next bet: Senegal-Gabon
        //
        // if group name with a space character 
        // we assumed that there is no space in group password
        //
        // if it is the first group, the first bet will be sent
        // if it is new group and the user has already bet
        // we will use the same bet for the former matchs
        //
        // Morevoer by SMS, we assume that it is the same bet for all the groups
        //
        // When joining, we first check if the user exists or not
        // if not we create the user and answer the user password
        //
        // =>
        // CAN join <group> <groupe pwd>
        //
        // <= 
        // Welcome on the CAN betting system
        // your pwd is <user pwd>
        // you may bet by SMS (send CAN to xxxxxxxx)
        // by vocal portal xxxxxxxx
        // by web www.xxxx.org
        //
        //
        // =>
        // CAN join
        // <=
        // If you want to join a group
        // send CAN join <group> <group password> to XXXXXX
        $content_join=preg_split('/ /', strrev($text), 2);
        $gpwd=strrev($content_join[0]);
        $group=strrev($content_join[1]);
        
        $text_sms="";
        

// if login OK =======> proceed operation
// if not send a SMS telling to create an account
	if (empty($login) ) {
		// create user in the group DB and in the applicative DB
		$create_user = createUser($user->login,$user->gift);

		// create user OK
		// try to join the mentioned group
		if ($create_user['status']) {
			// either the user already exists or we create it
			// a SMS shall be sent to provide the user password (to be used on web portal)
			$text_sms=$lang['user_creation_ok'].$lang['your_password'].$create_user['pwd'].".";

		// creation user failed         
		} else {
			$text_sms=$lang['user_creation_ko'];        
		}

	} 

	// then we add the user in the specified (if any) group
	if (!isset($group) || $group == "" || !isset($gpwd) || $gpwd == "") {
		// TODO change message
		$text_sms = $lang['group_required'].$lang['group_password_required'];
	} else{

			$res = $user->addInFriendlist($group,$gpwd);
            if ($res) {
                $text_sms .= $lang['join_ok'];
            } else {
                $text_sms .= $lang['join_ko'];
            }
        }               
    
        // Generate SMS to say that the user joined the group
        //echo "\nSEND SMS:".$text_sms;
        $Emerginov->sendSMS($sender,$text_sms); 
             
        break;

        case $lang['leave']:
        // leave a betting group
        //
        // =>
        // CAN leave <groupe> <groupe pwd>
        //
        
        break;

        case $lang['invite']:
        // invite friends into a group
        //
        // CAN invite <groupe> <pwd> <list of numbers>
        //
        // =>
        // CAN invite +33123456789,+33234567890,+33345678901 the_friendlist the_friendlist_pwd
        //
        // CAN invite +33123456789,+33234567890,+33345678901 los amigos 1234
        //
        //parse the group name
        $content_invite=preg_split('/ /', strrev($text), 2);
        $gpwd=strrev($content_invite[0]);
        $group_numbers=strrev($content_invite[1]);

        $invite=preg_split('/ /', $group_numbers, 2);
        $numbers=$invite[0];
        $group=$invite[1];
        
        $array_numbers=preg_split('/,/', $numbers);     
        
        $text_sms = $user->inviteSmsUsers($array_numbers,$group,$gpwd);
        break;
     
    default:
       // fputs($log, date("Y/m/d-H:i:s")." ".$lang['sms_action_unknown'].$order);
        $text_sms = $lang['sms_authorized_actions'];
        $text_sms.= " - ".$lang["info"].",\n";
        $text_sms.= " - ".$lang["bet"]." 1-2,\n";
        $text_sms.= " - ".$lang["rank"].",\n";
        $text_sms.= " - ".$lang["stat"].",\n";
        $text_sms.= " - ".$lang["join"].",\n";
        $text_sms.= " - ".$lang["invite"].".\n";
        //echo $text_sms; 
        $Emerginov->sendSMS($sender,$text_sms);
    break;
}



// Close the log file
//fclose($fh);
?>
