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
 * This file is executed every minutes on Emerginov platform
 */
 
// Prepare the include path
set_include_path(get_include_path() . PATH_SEPARATOR . "..");
require_once('loader.php');

/***********************************************************************
 * Check if a match will be started in the _NOTIFICATION_NOT_BET 
 * (see config.php, usually, it's 2 HOUR (SQL code))
 **********************************************************************/
echo "\n\n ******** NOTIFICATION NO BET ******** \n";
$request = "SELECT * 
            FROM
                matches
            WHERE
                status = '"._PLANNED."' AND
                date > NOW() AND
                date < (NOW() + INTERVAL "._NOTIFICATION_NOT_BET.")
            ";
$matches = $sql->fetchAssoc($sql->doQuery($request));

// If no match in next _NOTIFICATION_NOT_BET hours, say it (for debug purpose)
if (!$matches) echo date('r')." -- No match in next "._NOTIFICATION_NOT_BET."...\n";

// For each match found
foreach ($matches as $match){
    echo date('r')." -- Match #".$match['id_match']." ( ".$match['team1_name']." - ".$match['team2_name']." ) will be played in less than "._NOTIFICATION_NOT_BET."...\n";
    
    // Check if notification is not already done
    if ($match['notification_done']){
        echo date('r')." -- --> Notification already done for this match...\n";
        continue;
    }
    
    // Update match to set notification has been done
    $request = "UPDATE 
                    matches
                SET
                    notification_done = '1'
                WHERE
                    id_match = ".$match['id_match'];
    $sql->doQuery($request);
    
    // Now check that each player has done a bet for this match
    $request = "SELECT login, number, notification_no_bet
                FROM
                    members
                ";
    $members = $sql->fetchAssoc($sql->doQuery($request));
    foreach ($members as $member){
        // For debug, only my number
        if ($member['number'] != "+33618671034") continue;
        
        // Get pronostics
        $request = "SELECT *
                    FROM
                        pronostics
                    WHERE
                        members_login = '".$member['login']."' AND
                        matches_id_match = '".$match['id_match']."'
                    ";
        
        $res = $sql->doQuery($request,true);
        
        // If pronostic not found
        if (!$res){
            echo date('r')." -- The member ".$member['login']." ( ".$member['number']." ) has not bet on this match! Does he want notification?\n";
            // Get language for this user
            $lang=loadLanguage('lang/'.selectLanguage($member['number']));
            
            // Check if this guy has disabled notification
            if (!$member['notification_no_bet']){
                echo date('r')." -- --> NO, continue to next user\n";
                continue;
            }
            
            echo date('r')." -- --> YES,  Sending SMS...\n";
            // This number does not have any bet for this match, send a notification
            $Emerginov = new Emerginov($api_login, $api_password);
            
            $res = $Emerginov->SendSMS($member['number'],   $lang['sms-notification-do-bet-1']
                                                            .$lang['teams-'.$match['team1_name']]
                                                            .$lang['sms-notification-do-bet-2']
                                                            .$lang['teams-'.$match['team2_name']]
                                                            .$lang['sms-notification-do-bet-3']
                                                            .$lang['sms-notification-do-bet-4']
                                                            );
            if (!$res->Success){
                echo date('r')." -- ERROR while sending SMS to ".$member['number']."...\n";
            }
            else{
                echo date('r')." -- --> Ok\n";
            }
        }
    }
}

/***********************************************************************
 * Check if all matches for the previous day are played
 * and send resume SMS to guys with rank and results
 **********************************************************************/
echo "\n\n ******** NOTIFICATION RESUME DAY ******** \n";
// How many matches for yesterday?
$request = "SELECT count(*) 
            FROM 
                matches 
            WHERE 
                DATE(date) = DATE_SUB( CURDATE() , INTERVAL "._NOTIFICATION_RESUME_INTERVAL." ) 
                ";
$totalMatches = $sql->doQuery($request, true);

// How many matches did we play yesterday?
$request = "SELECT count(*) 
            FROM 
                matches 
            WHERE 
                DATE(date) = DATE_SUB( CURDATE() , INTERVAL "._NOTIFICATION_RESUME_INTERVAL." )  AND
                status = '"._PLAYED."'
                ";
$totalPlayedMatches = $sql->doQuery($request, true);

echo date('r')." -- Played matches yesterday: ".$totalPlayedMatches['count(*)']."/".$totalMatches['count(*)']."\n";

// If all matches are played
if (($totalPlayedMatches['count(*)'] == $totalMatches['count(*)']) && ($totalPlayedMatches['count(*)'] != 0)){
    // We can notify everybody about the results
    // Is the notification resume already done for today?
    // Is it the time to do such notification?
    if (!is_file("resumeNotifDone") && intval(date("G")) >= _NOTIFICATION_RESUME_TIME){
        // Notification not yet done
        // It's time
        // Send notif to all guys
        
        // Get back matches results
        // Get matches we played yesterday
        $request = "SELECT * 
                    FROM 
                        matches 
                    WHERE 
                        DATE(date) = DATE_SUB( CURDATE() , INTERVAL "._NOTIFICATION_RESUME_INTERVAL." )  AND
                        status = '"._PLAYED."'
                        ";
        $matches = $sql->fetchAssoc($sql->doQuery($request));
        
        echo date('r')." -- Sending notifications...\n";
        
        $request = "SELECT login, number, notification_resume_day
                    FROM
                        members
                    ";
        $members = $sql->fetchAssoc($sql->doQuery($request));
        foreach ($members as $member){
            // Get language for this user
            $lang=loadLanguage('lang/'.selectLanguage($member['number']));
            
            // Check if this guy has disabled notification
            if (!$member['notification_resume_day']) continue;
            
            // Emerginov object
            $Emerginov = new Emerginov($api_login, $api_password);
            
            // Build user object
            $User = new User($member['login']);
            
            // Build message
            $message = $lang['sms-notification-resume-day-1'];
            foreach ($matches as $match){
                $message .= $lang['teams-'.$match['team1_name']];
                $message .= $lang['sms-notification-resume-day-2'];
                $message .= $lang['teams-'.$match['team2_name']];
                $message .= $lang['sms-notification-resume-day-3'];
                $message .= $match['team1_result']."-".$match['team2_result'].". \n";
            }
            
            $message .= $lang['sms-notification-rank-title'];
            
            foreach ($User->friendlists as $i) {
                $rank = $User->getRank($i);
                //
                // rank = complete rank for a given group
                //
                // check the number to detecte the rank of the user
                //
                //     
                $rank_nb=1;
                $message.=$lang['sms-notification-rank-friendlist'].$i;

                foreach ($rank as $player => $points) {

                    // By SMS we provide the ranking as follow
                    // 1st: sdfdf with X points
                    // Nth: you with Y poitns
                    // last: sdfkjfskf with Z points
                    
                    // You, first and last guy
                    if ($rank_nb == 1 || $rank_nb == sizeof($rank) || $player == $User->login) {
                        // It's you!
                        if ($player == $User->login) {
                            $message .= $lang['sms-notification-rank-n-begin'].$rank_nb.$lang['sms-notification-rank-n-separator'].$lang['sms-notification-rank-you'].$lang['sms-notification-rank-points-begin'].$points.$lang['sms-notification-rank-points-end'];
                        } 
                        // It's someone else
                        else{
                            $otherUser = new User ($player);
                            $name =$otherUser->login;
                            $message .= $lang['sms-notification-rank-n-begin'].$rank_nb.$lang['sms-notification-rank-n-separator'].$name.$lang['sms-notification-rank-points-begin'].$points.$lang['sms-notification-rank-points-end'];
                        }
                    }
                    $rank_nb++;
                }
            }
            
            // For debug, only my number
            if ($User->number != "+33618671034") continue;
            
            // Send SMS
            $Emerginov = new Emerginov($api_login, $api_password);
            $res = $Emerginov->SendSMS($User->number, $message);
            if (!$res->Success){
                echo date('r')." -- ERROR while sending SMS to ".$User->number."...\n";
            }
            else{
                echo date('r')." -- SUCCESS while sending SMS to ".$User->number."...\n";
            }
        }
        
        // Create the file
        $fp = fopen("resumeNotifDone", 'w');
        fwrite($fp, '1');
        fclose($fp);
    }
    else{
        echo date('r')." -- Resume notification already sent or it's not yet time to send resume\n";
        // Remove the alreadydone notfication file
        if (is_file("resumeNotifDone") && intval(date("G")) < _NOTIFICATION_RESUME_TIME) unlink("resumeNotifDone");
    }
}
else{
    // Remove the alreadydone notfication file (it is a new day)
    if (is_file("resumeNotifDone")) unlink("resumeNotifDone");
    echo date('r')." -- All matches not yet played, nothing to do...\n";
}



/***********************************************************************
 * Check if new score
 * and send SMS to guys if needed
 **********************************************************************/
echo "\n\n ******** NOTIFICATION LIVE SCORE ******** \n";
// get DOM from URL or file with random 8 digits at the end of url request to bypass proxy cache...
$opts = array(
    'http' => array(
        'protocol_version'=>'1.1',
        'method'=>'GET',
        'header'=>array(
            'Connection: close'
        ),
        'user_agent'=>'Mozilla'
     )
); 
$context = stream_context_create($opts);
$html = file_get_html('http://www.livescores.com/worldcup/?'.substr(str_shuffle(str_repeat('0123456789',8)),0,8), false, $context);
//$string = $html;
//echo $string;

// foreach div class="row-light"
foreach ($html->find('div.row-light') as $tr){
    //print_r($tr->plaintext);
    
    // Parse HTML to get values
    $team1 = trim($tr->find('div.ply',0)->plaintext);
    $team2 = trim($tr->find('div.ply',1)->plaintext);
    $score = str_replace(array(" "),array(""),trim($tr->find('div.sco',0)->plaintext));
    
    // If score is ?-?, match not yet started, continue
    if ($score == "?-?") continue;
    
    // If good values
    if ($team1 != "" && $team2 != "" && $score != ""){
        // Filename will not be found if new score!
        $file = filename_safe("$team1 $score $team2");
        echo date('r')." -- Found a live score for match: $team1 $score $team2\n";
        
        if (is_file($file)){
			echo date('r')." -- Score already notified, skipping...\n";
		}
		else{
			echo date('r')." -- New score! $score\n";
            $request = "SELECT login, number, notification_new_score
                        FROM
                            members
                        ";
            $members = $sql->fetchAssoc($sql->doQuery($request));
            foreach ($members as $member){
                // For debug, only my number
                if ($member['number'] != "+33618671034") continue;
                
                // Get language for this user
                $lang=loadLanguage('lang/'.selectLanguage($member['number']));
                
                // Try to overwrite team name with lang
                $team1 = isset($lang['teams-'.strtolower(str_replace(array(" "),array(""),$team1))]) ? $lang['teams-'.strtolower(str_replace(array(" "),array(""),$team1))] : $team1;
                $team2 = isset($lang['teams-'.strtolower(str_replace(array(" "),array(""),$team2))]) ? $lang['teams-'.strtolower(str_replace(array(" "),array(""),$team2))] : $team2;
                
                // Check if this guy has disabled notification
                if (!$member['notification_new_score']) continue;
                
                // Send SMS
                $Emerginov = new Emerginov($api_login, $api_password);
                if ($score == "0-0")
                    $res = $Emerginov->SendSMS($member['number'], $lang["sms-notification-new-match"]."$team1 $score $team2");
                else
                    $res = $Emerginov->SendSMS($member['number'], $lang["sms-notification-new-score"]."$team1 $score $team2");
                if (!$res->Success){
                    echo date('r')." -- ERROR while sending SMS to ".$member['login']." / ".$member['number']."...\n";
                }
                else{
                    echo date('r')." -- SUCCESS while sending SMS to ".$member['login']." / ".$member['number']."...\n";
                }
            }
            
            // Create the file
			$fp = fopen($file, 'w');
			fwrite($fp, '1');
			fclose($fp);
		}
    }
}
?>
