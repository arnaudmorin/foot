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

The User class represents a User in the betting system

*/

// Include Emerginov Class
//require_once("loader.php");

class User{
    /* *************************************************************************
     * Variables
     * ************************************************************************/
    public $login;
    public $number;
    public $password;
    public $gift;
    public $isAdmin = false;
    public $friendlists = Array();
    public $timeOffsetSec = 0;
    public $language;
    public $ivr_voice;
    public $notification_join_friendlist = true;
    public $notification_resume_day = true;
    public $notification_no_bet = true;
    public $notification_new_score = true;

    /* *************************************************************************
     * Constructor
     * ************************************************************************/
    function __construct($login, $autobuild=true) {
        // Get back globals
        global $sql;
        
        // Set values
        $this->login = $login;
        
        // Set automatically all variables if autobuild requested
        if ($autobuild){
            // Get most of info
            $req = "SELECT * FROM members where login = '".mysql_real_escape_string($login)."'";
            $res = $sql->doQuery($req, true);
            
            if ($res){
                $this->number = $res['number'];
                $this->password = $res['password'];
                $this->gift = $res['gift'];
                $this->isAdmin = ($res['administrator'] == '1' ) ? true : false;
                $this->notification_join_friendlist = ($res['notification_join_friendlist'] == '1' ) ? true : false;
                $this->notification_resume_day = ($res['notification_resume_day'] == '1' ) ? true : false;
                $this->notification_no_bet = ($res['notification_no_bet'] == '1' ) ? true : false;
                $this->notification_new_score = ($res['notification_new_score'] == '1' ) ? true : false;
                
                // Get friendlists
                $req = "SELECT friendlist_name FROM friendlists_membership where member_login = '".mysql_real_escape_string($this->login)."'";
                $res = $sql->fetchAssoc($sql->doQuery($req));
                
                if ($res){
                    foreach ($res as $friendlistArray){
                        $this->friendlists[] = $friendlistArray['friendlist_name'];
                    }
                }
                else{
                    debug("Error occured while trying to build user object ({$this->login}) friendlist membership.");
                    $this->friendlists = array();
                }
                
                // Get lang things
                $this->language=selectLanguage($this->number);
                $this->ivr_voice=selectVoice($this->number);
            }
            else{
                debug("Error occured while trying to build user object {$this->login}.");
                $this->login = null;
            }
        }
    }
        
    
    /* *************************************************************************
     * Public methods
     * ************************************************************************/

    /*
     * Add the user in a friendlist
     * 
     * @param $friendlist
     * @param $password
     * @return true on success, false on error
     */
    function addInFriendlist($friendlist, $password){
        global $lang, $sql;
        
        // Check if user is not already in the friendlist
        if (in_array($friendlist, $this->friendlists)){
            debug("User {$this->login} already in friendlist {$friendlist}. Nothing to do.");
            return true;
        }
        
        // Check if this friendlist exist
        $friendlistObject = new Friendlist($friendlist);
        if (!$friendlistObject->name){
            debug("Error when trying to add {$this->login} into friendlist {$friendlist}. Maybe friendlist does not exist.");
            return false;
        }
        
        // Check if the password is the good one
        if ($password != $friendlistObject->password){
            debug("Error when trying to add {$this->login} into friendlist {$friendlist}. Bad password provided.");
            return false;
        }
        
        // Doing SQL request
        $req = "INSERT INTO friendlists_membership VALUES ('".mysql_real_escape_string($friendlist)."','".mysql_real_escape_string($this->login)."')";
        $res = $sql->doQuery($req);
        
        if (!$res){
            debug("SQL error when trying to add {$this->login} into friendlist {$friendlist}.");
        }
        
        // Being here suppose that the user is now in this friendlist
        // Update the user info
        $this->friendlists[] = $friendlist;
        $friendlistObject->members[] = $this->login;
                
        // Create friendlist with first friendlist this user is in if he does not
        // already have a friendlist in SESSION
        if (!isset($_SESSION['friendlist'])){
            $_SESSION['friendlist'] = $friendlistObject;
        }
        
        // Send notification to his friends
        $res = $friendlistObject->sendNotificationToFriendlist(
            $lang['sms-notification-user-join-friendlist-1']
            .$this->login
            .$lang['sms-notification-user-join-friendlist-2']
            .$friendlistObject->name
            .$lang['sms-notification-user-join-friendlist-3']
            .$this->gift,
            array($this->number)
        );
        return true;
    }
    
    /*
     * Do a bet for a specific match & friendlist. If friendlists is empty, the same
     * bet will be applied for all friendlists
     *
     * @param $matchID is the match ID to bet for
     * @param $team1Bet is the bet for the first team
     * @param $team2Bet is the bet for the second team
     * @param $friendlist is the friendlist name to bet for, if empty, the bet will be applied to all friendlists (exept those that already have a bet for this match
     * @param $joker to set if this match is selected as a joker or not
     * @return true if everything went well, false either
     */
    function doBet($matchID, $team1Bet, $team2Bet, $friendlists = array(), $joker=false){
        global $sql;
        
        // The friendlists parameter must be an array
        if (!is_array($friendlists)){
            $friendlists = array($friendlists);
        }
        
        // Check if friendlists is empty, use all of them
        if (count($friendlists) == 0){
            $friendlists = $this->friendlists;
        }
        
        // Check if the matchID can still be bet
        // Get match date
        $t = $sql->doQuery("SELECT 
                                date 
                            FROM 
                                matches
                            WHERE
                                id_match = '".mysql_real_escape_string($matchID)."'
                                ", true);
        
        if (!checkIfBetStillPossible($t['date'])) return false;
        
        // For each friendlist
        foreach ($friendlists as $friendlist){
            // Check if a bet already exists for this match, this player and this friendlist
            $alreadyBet = $sql->doQuery("   SELECT * 
                                            FROM pronostics 
                                            WHERE 
                                                members_login = '".mysql_real_escape_string($this->login)."' AND
                                                matches_id_match = '".mysql_real_escape_string($matchID)."' AND
                                                friendlist_name = '".mysql_real_escape_string($friendlist)."'",
                                            true);
            
            // If yes update
            if ($alreadyBet){
                $sql->doQuery(" UPDATE pronostics 
                                SET
                                    team1 = '".mysql_real_escape_string($team1Bet)."',
                                    team2 = '".mysql_real_escape_string($team2Bet)."',
                                    joker = '".mysql_real_escape_string($joker)."'
                                WHERE 
                                    members_login = '".mysql_real_escape_string($this->login)."' AND
                                    matches_id_match = '".mysql_real_escape_string($matchID)."' AND
                                    friendlist_name = '".mysql_real_escape_string($friendlist)."'"
                                );
            }
            // If no, new bet
            else if (!$alreadyBet){
                // No, do an SQL INSERT request
                $sql->doQuery(" INSERT INTO pronostics (
                                    members_login, 
                                    matches_id_match,
                                    friendlist_name,
                                    team1,
                                    team2,
                                    joker)
                                VALUES (
                                    '".mysql_real_escape_string($this->login)."',
                                    '".mysql_real_escape_string($matchID)."',
                                    '".mysql_real_escape_string($friendlist)."',
                                    '".mysql_real_escape_string($team1Bet)."',
                                    '".mysql_real_escape_string($team2Bet)."',
                                    '".mysql_real_escape_string($joker)."')"
                                );
            }
        }
    }
    
    /*
     * Return for a friendlist, all matches to bet
     *
     * @param $friendlist is one of the friendlist the user is in
     * @return an array with all match info from database
     */
    function getAllMatchesToBet($friendlist){
        // Get sql connexion
        global $sql;
        // Get all not played matches
        $req = "SELECT * FROM matches
                WHERE
                    status = '"._PLANNED."'
                ORDER BY date
                ";
        $res = $sql->doQuery($req);
        $matches = $sql->fetchAssoc($res);
        
        // Now for each match, check if the user has already done a bet
        $return = array();
        foreach($matches as $match){
            $req = "SELECT * FROM pronostics
                    WHERE
                        members_login = '".mysql_real_escape_string($this->login)."' AND
                        matches_id_match = '".mysql_real_escape_string($match['id_match'])."' AND
                        friendlist_name = '".mysql_real_escape_string($friendlist)."'
                    ";
            $bet = $sql->doQuery($req, true);
            // If no bet is found for this match, return it
            if (!$bet){
                $return[] = $match;
            }
        }
        // Return the matches
        return $return;
    }

    /*
     * Return for a friendlist, all matches with bets
     *
     * @param $friendlist is one of the friendlist the user is in
     * @return an array with all match info from database and all bet if done for matches
     */
    function getAllMatchesWithBets($friendlist){
        // Get sql connexion
        global $sql;
        
        $return = array();
        
        // Get all not played matches
        $req = "SELECT * FROM matches
                WHERE
                    status = '"._PLANNED."'
                ORDER BY date
                ";
        $matches = $sql->fetchAssoc($sql->doQuery($req));
        
        // Now for each match, check if the user has already done a bet
        foreach($matches as $match){
            $req = "SELECT * FROM pronostics
                    WHERE
                        members_login = '".mysql_real_escape_string($this->login)."' AND
                        matches_id_match = '".mysql_real_escape_string($match['id_match'])."' AND
                        friendlist_name = '".mysql_real_escape_string($friendlist)."'
                    ";
            $bet = $sql->doQuery($req, true);
            // If no bet is found for this match, return it
            $return[] = array(  'match' => $match, 
                                'bet' => $bet,
                            );
        }
        // Return the matches
        return $return;
    }

    /*
     * Return a bet for a specific match and friendlist
     * 
     * @param $matchID
     * @param $friendlist
     * @return an array with all bet information from DB false if error or no bet found
     */
    function getBet($matchID, $friendlist){
        global $sql;
        
        return $sql->doQuery(
                        "SELECT *
                         FROM
                            pronostics
                         WHERE
                            members_login = '".mysql_real_escape_string($this->login)."' AND
                            matches_id_match = '".mysql_real_escape_string($matchID)."' AND
                            friendlist_name = '".mysql_real_escape_string($friendlist)."'
                        ",
                        true
                    );
    }

	/*
	* getGift of a given user
	*
	* @return the gift or an empty string if no gift set
	*/

	function getGift(){
        return $this->gift;
	}

    /*
     * getNextMatch
     * 
     * @return an array: the next match of the User 
     * (the oldes non-bet match if several friendlists) 
     * return null if no match to bet
     * 
     */
    function getNextMatchForAllFriendlists() {
            
        if (count($this->friendlists) > 0  ){
            // at least one friendlist
            $date_next_match="2011-01-01 0:00:00";
            $the_next_match = null;

            foreach ($this->friendlists as $friendlist) {
                
                $next_match = $this->getNextMatchToBet($friendlist);

                // if the user participates to several friendlists
                // we will return the oldest match found with no bet on a friendlist
                // even if some bets are already done for some friendlists on this match
                if ($next_match && (strcmp($date_next_match, $next_match['date']) < 0 ) && checkIfBetStillPossible($next_match['date'])){
                    $the_next_match = $next_match;
                }
            }
            return $the_next_match;
        }
        else{
            return null;
        }
    }

    /*
     * Return for a friendlist, the next match to bet
     *
     * @param $friendlist is one of the friendlist the user is in
     * @return an array with all match info from database
     */
    function getNextMatchToBet($friendlist){
        // Get sql connexion
        global $sql;
        // Get all not played matches
        $req = "SELECT * FROM matches
                WHERE
                    status = '"._PLANNED."'
                ORDER BY date
                ";
        $res = $sql->doQuery($req);
        $matches = $sql->fetchAssoc($res);
        
        // Now for each match, check if the user has already done a bet
        foreach($matches as $match){
            $req = "SELECT * FROM pronostics
                    WHERE
                        members_login = '".mysql_real_escape_string($this->login)."' AND
                        matches_id_match = '".mysql_real_escape_string($match['id_match'])."' AND
                        friendlist_name = '".mysql_real_escape_string($friendlist)."'
                    ";
            $bet = $sql->doQuery($req, true);
            // If no bet is found for this match, return it
            if (!$bet){
                return $match;
            }
        }
        // No matches found
        return null;
    }

    /*
     * Return the rank array for this friendlist, sorted by points
     *
     * @param $friendlist is one of the friendlist name the user is in
     * @param $round to get rank for this round only
     * @return an array membersPoints(  [number] => 'points' )
     */
    function getRank($friendlist, $round=false){
        global $sql;
        
        // Member array with points
        $membersPoints = array();
        
        // Build the friendlist to get all players in this friendlist
        $friendlistObject = new Friendlist($friendlist);
        
        // Initialise to 0 for all members of this friendlist
        foreach ($friendlistObject->members as $member){
            $membersPoints[$member] = 0;
        }
        
        // Select all matchs for this round or all rounds
        $sqlRound = "";
        if ($round) $sqlRound = " AND round_name='".mysql_real_escape_string($round)."'";
        $matches = $sql->fetchAssoc(
                        $sql->doQuery(
                            "SELECT * 
                             FROM 
                                matches
                             WHERE
                                status = '"._PLAYED."'
                                $sqlRound
                                "
                        )
                    );
        
        // For each match
        foreach ($matches as $match){
            // Select all pronostics for this match, this friendlist
            $bets = $sql->fetchAssoc(
                        $sql->doQuery(
                            "SELECT * 
                             FROM 
                                pronostics
                             WHERE
                                matches_id_match = '".mysql_real_escape_string($match['id_match'])."' AND
                                friendlist_name = '".mysql_real_escape_string($friendlistObject->name)."'
                                "
                        )
                    );
            
            // For each bet
            foreach ($bets as $bet){
                // Get points for this bet
                $points = computePoints($match['team1_result'],
                                        $match['team2_result'],
                                        $bet['team1'],
                                        $bet['team2'],
                                        $bet['joker'],
                                        $match['round_name']
                                        );
                // Add points to members of this friendlist
                if (isset($membersPoints[$bet['members_login']])) $membersPoints[$bet['members_login']] += $points;
            }
        }
        arsort($membersPoints);
        return $membersPoints;
    }

	/*
	* function to send mail to users to invite them to join the friendlist
	* from the selected User
	* 
	* @param $friends array of users to be invited
	* @param $friendlist the friendlist you want to invite your friends to
	* @param $password, the friendlist password
	* @return true on success, false if any problem
	*/
	function inviteMailUsers($friends,$friendlist,$password) {
        global $lang;
        
		if ($friendlist != "" && $password != ""){
            // Check if friendlist exists
            $friendlistObject = new Friendlist($friendlist);
			if ($friendlistObject->name) {
				// send mail
				foreach ($friends as $i) {
					mb_internal_encoding("UTF-8");
                    $headers = "";
                    $headers .= "From: Bresil 2014 <noreply@emerginov.com>\n";
                    $headers .= "MIME-version: 1.0\n"; 
                    $headers .= "Content-Type: text/plain; charset=utf-8\n";         

                    $res = mail($i, 
                                $lang['invite-mail-subject'], 
                                $lang['invite-mail-message-1']
                                    .$friendlist
                                    .$lang['invite-mail-message-2']
                                        ."account.php?action=addinfriendlist&friendlist=".urlencode($friendlist)."&friendlist_password=".urlencode($password)
                                    .$lang['invite-mail-message-3']
                                        ."register.php?friendlist=".urlencode($friendlist)."&friendlist_password=".urlencode($password), 
                                $headers
                                );
				}
                return true;
			}
        }
        // Being here suppose that an error occured
        return false;
	}

	/*
	* function to send SMS to users to invite them to join the group
	* from the selected User
	* 
	* @param $friends of users to be invited
	* @param $friendlist the friendlist you want to invite your friends to
	* @param password the friendlist password
	*
	* output send as many SMS as necessary
	* return false if any problem
	*/
	function inviteSmsUsers($friends,$friendlist,$password) {
        global $api_login, $api_password;
        
		$Emerginov = new Emerginov($api_login, $api_password);
		$lang;
		// As we retrived SMS we need to load the lang variable
		if ($this->language == _LANG_FRENCH){
			$lang=loadLanguage('lang/french.php');
		} else {
			$lang=loadLanguage('lang/english.php');
		}
        
        if ($friendlist != "" && $password != ""){
            // Check if friendlist exists
            $friendlistObject = new Friendlist($friendlist);
			if ($friendlistObject->name && $friendlistObject->password == $password) {
				// send SMS
				foreach ($friends as $i){
					//$text_dest="==> send SMS to".$i.":";				
					//$text_sms .= $text_dest.$this->name." ".$lang['sms_invite'].$friendlist." ".$gpwd.$lang['to_sms_number'];
					$text_sms = $this->login." ".$lang['sms_invite'].$friendlist." ".$password.$lang['to_sms_number'];
					//echo $text_sms;
					$res = $Emerginov->sendSMS($i,$text_sms);
					if (!$res->Success){
						return false;			
					}
				}
			} else {
				if ( $friendlistObject->password != $password) {
					$text_sms = $lang['friendlist_bad_password'];
				} else {
					$text_sms =	$lang['sms_action_unknown'];			
				}
				//echo $text_sms;
				$res = $Emerginov->sendSMS($i,$text_sms);
				if (!$res->Success){
					return false;			
				}				
			}
		} 
        else {
			return false;	
		}
		// TODO first step return the string
		// next steps the status code of SMS sending
		return true;
	}

    /*
     * Remove the user from a friendlist,
     * also remove the friendlist from the array in this object
     * also update the SESSION friendlist with an other friendlist found
     * in the user friendlists array. If no more friendlist found, remove the
     * SESSION friendlist
     * 
     * @param $friendlist is the friendlist to remove the user from
     * @return true if everything went fine false if an error occured
     */
    function removeFromFriendlist($friendlist){
        global $sql;
        
        // First check if user is in this friendlist
        if (!in_array($friendlist, $this->friendlists)){
            debug("User {$this->login} not in friendlist {$friendlist}. Nothing to do.");
            return false;
        }
        
        // Yes the user is in, remove it
        $res = $sql->doQuery(
                            "DELETE FROM 
                                friendlists_membership
                             WHERE
                                friendlist_name = '".mysql_real_escape_string($friendlist)."' AND
                                member_login = '".mysql_real_escape_string($this->login)."'
                                "
                            );
        if (!$res){
            debug("Error occured while trying to delete user {$this->login} from {$friendlist}");
            return false;
        }
        
        // Being here suppose that the user is not in this friendlist anymore
        // Update the user info
        $this->friendlists = $arr = array_merge(array_diff($this->friendlists, array($friendlist)));
        
        // Unset the SESSION friendlist
        unset($_SESSION['friendlist']);
        
        // Create friendlist with first friendlist this user is in if he has a friendlist
        if (isset($this->friendlists[0])){
            $_SESSION['friendlist'] = new Friendlist($this->friendlists[0]);
        }
        
        return true;
    }

    /*
     * Update the user gift in DB
     * also, the current user object is updated
     * 
     * @param $gift is the new gift to set for this user
     * @return true on success, false on error
     * 
     */
    function updateGift($gift){
        global $sql;
        // Update the DB
        $result = $sql->doQuery("   UPDATE members
                                    SET
                                        gift = '".mysql_real_escape_string($gift)."'
                                    WHERE
                                        login = '".mysql_real_escape_string($this->login)."'
                                    ");
        
        // Check if something went wrong
        if (!$result){
            debug("Error while updating SQL DB to change gift for user {$this->login}.");
            // Finally return false
            return false;
        }
        
        // Being here suppose that DB is updated
        // Now update the current user object
        $this->gift = $gift;
        return true;
    }
    
    /*
     * Update the user notification in DB
     * also, the current user object is updated
     * 
     * @param $type is the notification type
     * @param $value is the value to set for this notification (true or false)
     * @return true on success, false on error
     * 
     */
    function updateNotification($type, $value){
        global $sql;
        $valueString = "0";
        if ($value) $valueString = "1";
        // Update the DB
        $result = $sql->doQuery("   UPDATE members
                                    SET
                                        $type = '".$valueString."'
                                    WHERE
                                        login = '".mysql_real_escape_string($this->login)."'
                                    ");
        
        // Check if something went wrong
        if (!$result){
            debug("Error while updating SQL DB to change $type...");
            // Finally return false
            return false;
        }
        
        // Being here suppose that DB is updated
        // Now update the current user object
        $this->$type = $value;
        return true;
    }

    /*
     * Update the user number in DB
     * also, the current user object is updated
     * 
     * @param $number is the new number to set for this user
     * @return true on success, false on error
     * 
     */
    function updateNumber($number){
        global $sql;
        
        $result = $sql->doQuery("   UPDATE members
                                    SET
                                        number = '".mysql_real_escape_string($number)."'
                                    WHERE
                                        login = '".mysql_real_escape_string($this->login)."'
                                    ");
        
        // Check if something went wrong
        if (!$result){
            debug("Error while updating phone number of user {$this->login}");
            // Finally return false
            return false;
        }
        
        // Being here suppose that DB is updated
        // Now update the current user object
        $this->number = $number;
        return true;
    }

    /*
     * Update the user password in DB
     * 
     * @param $password is the new password
     * @return true on success, false on error
     * 
     */
    function updatePassword($password){
        global $sql;
        
        $result = $sql->doQuery("   UPDATE members
                                    SET
                                        password = '".md5($password)."'
                                    WHERE
                                        login = '".mysql_real_escape_string($this->login)."'
                                    ");
        
        // Check if something went wrong
        if (!$result){
            debug("Error while updating password of user {$this->login}");
            // Finally return false
            return false;
        }
        
        // Being here suppose that DB is updated
        // Now update the current user object
        $this->password = md5($password);
        return true;
    }
}
?>
