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

This file contains miscelanous functions that can be usefull for something

*/

/*
 * Will return true or false if number given is international or not
 */
function validateInternationalPhoneNumber($number){
    if( preg_match("/^\+[0-9]+$/i", $number) ) {
        return true;
    }
    else return false;
}

/*
 * Will return true or false if string given is a valid mail address
 */
function validateMailAddress($string){
    if( preg_match("/^.+@.+\..+$/i", $string) ) {
        return true;
    }
    else return false;
}

/*
 * Will print the text as an error
 */
function error($text){
    echo "<p class='error'>$text</p>";
}

function info($text){
    echo "<p class='info'>$text</p>";
}

function warning($text){
    echo "<p class='warning'>$text</p>";
}

function debug($text){
    if (_DEBUG){
        if (_DEBUG_LOGIN == 'all' || (isset($_SESSION['user']) && _DEBUG_LOGIN == $_SESSION['user']->login)){
            echo "<pre><p class='debug'>";
            print_r($text);
            echo "</p></pre>";
        }
    }
}

/**
 * StartsWith
 * Tests if a text starts with an given string.
 *
 * @param     string
 * @param     string
 * @return    bool
 */
function StartsWith($Haystack, $Needle){
    // Recommended version, using strpos
    return strpos($Haystack, $Needle) === 0;
}


/*
 * Function to detect Internet Explorer
 * return true if ie, false elsewhere
 * 
 */
function detect_ie(){
    if (isset($_SERVER['HTTP_USER_AGENT']) && 
    (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        return true;
    else
        return false;
}

/*
 * Select Language
 * 
 * @param $sender
 * @return the $lang according to the phone number (country code)  
 *
 */    
function selectLanguage($sender){
    // TODO add english countries
    // If cookie is set
    if (isset($_REQUEST['CAN_betting_prefered_lang']) && constant("_LANG_".$_REQUEST['CAN_betting_prefered_lang'])  != "" ){
        return constant("_LANG_".$_REQUEST['CAN_betting_prefered_lang']);
    }
	if (	StartsWith($sender,"+33")	|| 	// France
            StartsWith($sender,"+212")	||	// Morocco
            StartsWith($sender,"+213")	||	// Algeria
            StartsWith($sender,"+216")	||	// Tunisia
            StartsWith($sender,"+221")	||	// Senegal 
            StartsWith($sender,"+223")	||	// Mali
            StartsWith($sender,"+224")	||	// Guinea
            StartsWith($sender,"+225")	||	// Ivory Coast
            StartsWith($sender,"+226")	||	// Burkina
            StartsWith($sender,"+227")	||	// Niger
            StartsWith($sender,"+229")	||	// Benin
            StartsWith($sender,"+236")	||	// Central African Republic
            StartsWith($sender,"+237")	||	// Cameroon
            StartsWith($sender,"+240")	||	// Equatorial Guinea
            StartsWith($sender,"+241")	||	// Gabon
            StartsWith($sender,"+242")	||	// Republic of the Congo
            StartsWith($sender,"+243")	||	// Democratic Republic of the Congo
            StartsWith($sender,"+261")	||	// Madagascar
            StartsWith($sender,"+262")	||	// Réunion
            StartsWith($sender,"+32")	) {	// Belgium
		return _LANG_FRENCH;
	} else {
		return _LANG_DEFAULT;
	}
}

/*
 * Select Voice
 * 
 * @param $sender
 * @return the $voice 
 *  Agnes for French
 *  Elizabeth for English
 */    
function selectVoice($sender){
    // TODO add english countries
    // TODO gerer avec les cookies
	if (    StartsWith($sender,"+33")	|| 	// France
            StartsWith($sender,"+212")	||	// Morocco
            StartsWith($sender,"+213")	||	// Algeria
            StartsWith($sender,"+216")	||	// Tunisia
            StartsWith($sender,"+221")	||	// Senegal 
            StartsWith($sender,"+223")	||	// Mali
            StartsWith($sender,"+224")	||	// Guinea
            StartsWith($sender,"+225")	||	// Ivory Coast
            StartsWith($sender,"+226")	||	// Burkina
            StartsWith($sender,"+227")	||	// Niger
            StartsWith($sender,"+229")	||	// Benin
            StartsWith($sender,"+236")	||	// Central African Republic
            StartsWith($sender,"+237")	||	// Cameroon
            StartsWith($sender,"+240")	||	// Equatorial Guinea
            StartsWith($sender,"+241")	||	// Gabon
            StartsWith($sender,"+242")	||	// Republic of the Congo
            StartsWith($sender,"+243")	||	// Democratic Republic of the Congo
            StartsWith($sender,"+261")	||	// Madagascar
            StartsWith($sender,"+262")	||	// Réunion
            StartsWith($sender,"+32")	) {	// Belgium
		return _LANG_FRENCH_IVR;
	} else {
		return _LANG_DEFAULT_IVR;
	}
}


/*
 * Compute points
 * input: 
 *      $team1 real score
 *      $team2 reald score
 *      $team1 bet
 *      $team2 bet  
 *      $joker for bonus
 *      $round is the round order
 * 
 * return the number of point with this bet
 *      
 */    
function computePoints ($team1, $team2, $team1Bet, $team2Bet ,$joker, $round){
    $points = 0;
    // If exact score
    if ($team1 == $team1Bet && $team2 == $team2Bet){
        $points = constant("_POINTS_ROUND_".$round."_MAX");
        //if ($joker) $points += constant("_POINTS_ROUND_".$round."_JOKER"); // Joker is not yet ready
    }
    
    // If winner found, but not exact score
    else if (
                (($team1 - $team2) > 0 && ($team1Bet - $team2Bet) > 0) ||    // Team1 is winner and bet is good
                (($team1 - $team2) < 0 && ($team1Bet - $team2Bet) < 0)       // Team2 is winner and bet is good
            ){
        $points = constant("_POINTS_ROUND_".$round."_MIN");
        // If score really close
        if (($team1 - $team2) == ($team1Bet - $team2Bet)){                   // Bon écart de but
            $points += constant("_POINTS_ROUND_".$round."_BONUS");
        }
        //if ($joker) $points += constant("_POINTS_ROUND_".$round."_JOKER"); // Joker is not yet ready
    }
    
    // If draw found, but not exact score
    else if ((($team1 - $team2) == 0) && (($team1Bet - $team2Bet) == 0)){     // Team1 and team2 are draw and bet also
        $points = constant("_POINTS_ROUND_".$round."_MIN");
        // If score really close
        if (abs($team1 - $team1Bet) < 2){                                     // Ecart inférieur à 2
            $points += constant("_POINTS_ROUND_".$round."_BONUS");
        }
        //if ($joker) $points += constant("_POINTS_ROUND_".$round."_JOKER"); // Joker is not yet ready
    }
    
    // Bad bet, bad luck, if joker, very bad trip!
    else{
        //if ($joker) $points -= constant("_POINTS_ROUND_".$round."_JOKER"); // Joker is not yet ready
    }
    
    return $points;
}

/*
 * Coeff points - not yet used
 * input: 
 *      $team1 real score
 *      $team2 reald score
 *      $team1 bet
 *      $team2 bet  
 *      $joker for bonus
 *      $round is the round order
 * 
 * return the number of point with this bet
 *      
 */   
function coeffPoints(){
    // TODO
    return false;
    
    /* OLD
    $requete_coeff="SELECT eq1, eq2 FROM `prono` WHERE id_match='$id_match'";
    $connexion=connexion();
    $res_coeff=execreq($requete_coeff,$connexion);
    $i=0.0;												//pour compter le nombre de prono au total
    $j=0.0;												//pour compter le nombre de prono en faveur de eq1
    $k=0.0;	
    $pourcentage=0.00;										//pour compter le nombre de prono nul
    while ($prono_coeff=mysql_fetch_array($res_coeff))
        {
        $i++;
        $diff=$prono_coeff['eq1']-$prono_coeff['eq2'];
        //echo "diff=".$diff;
        if ($diff>0)
            {
            $j++;
            }
        elseif ($diff==0)
            {
            $k++;
            }
        }

    if ($prono_victoire==1)										//si le joueur avait choisi eq1 gagnante
        {
        $pourcentage=$j/$i;									//pourcentage de eq1 gagnante
        }
    elseif ($prono_victoire==2)
        {
        $pourcentage=1-($j/$i)-($k/$i);								//pourcentage de eq2 gagnante
        }
    else
        {
        $pourcentage=$k/$i;									//pourcentage de nulle
        }
    //echo "i=".$i."  j=".$j."  k=".$k;
    //echo "  pourcentage=".$pourcentage;

    if ($pourcentage<0.15)
        {
        return ($points_ss_coeff*2);
        }
    elseif ($pourcentage<0.3)
        {
        return (round($points_ss_coeff*1.5));
        }
    else
        {
        return ($points_ss_coeff);
        }
    */
}

/*
 * This function returns all played matches
 * 
 * inputs:
 *      none
 * Outputs:
 *      an array with all matches ordered by date
 */
function getAllPlayedMatches(){
    global $sql;
    
    return $sql->fetchAssoc(
                $sql->doQuery(
                    "SELECT *
                     FROM 
                        matches
                     WHERE
                        status = '"._PLAYED."'
                     ORDER BY
                        date
                    "
                )
            );
}

/*
 * Create a friendlist
 * Before adding the line it will check that the friendlist does not already exists
 * 
 * @param $friendlist
 * @param $password
 * @param $description
 * 
 * @return Return true if ok, false on error
 */
function createFriendlist($friendlist, $password, $description=""){
    global $sql;
    
    $friendlistObject = new Friendlist($friendlist);
    
    // If the request is a success, then the friendlist exists
    if ($friendlistObject->name){
        return false;
    }
    else{
        // This friendlist does not exist, add it to the DB
        $res = $sql->doQuery(" INSERT INTO friendlists (
                            name,
                            password,
                            description )
                        VALUES (
                            '".mysqli_real_escape_string($sql->dbhandle, $friendlist)."',
                            '".mysqli_real_escape_string($sql->dbhandle, $password)."', 
                            '".mysqli_real_escape_string($sql->dbhandle, $description)."' )
                        ");
        
        if ($res)
            return true;
        else
            return false;
    }
}

/*
 * Create a new user in database
 * 
 * @param $login
 * @param $gift
 * @param $password, if empty, will be generated automatically from 4 digits
 * @return an array with:
 *      first item: status 
 *          true if user has been created or is already created
 *          false if an error occured while creating the user
 *      second item: pwd
 * 
 */
function createUser($login, $gift="", $password="", $number="") {
    // Get sql connexion
    global $sql;
    
    $answer = array();
    $answer['status'] = true;
    
    // Check if user is already created is DB
    $req = "SELECT * FROM members
            WHERE
                login = '".mysqli_real_escape_string($sql->dbhandle, $login)."'
            ";
    $res = $sql->doQuery($req, true);
    
    // random 4 digits password if needed
    if ($password == "") $password = substr(str_shuffle(str_repeat('0123456789',4)),0,4);
    $answer['pwd']=$password;
    
    // If user not already in DB
    if (!isset($res['login'])){
        // Create the user in DB
        $req="INSERT INTO members (login, number, gift, password) VALUES ('".mysqli_real_escape_string($sql->dbhandle, $login)."', '".mysqli_real_escape_string($sql->dbhandle, $number)."','".mysqli_real_escape_string($sql->dbhandle, $gift)."','".md5($password)."');";
        $res = $sql->doQuery($req);
        
        // Check result
        if (!$res){
            debug("Error occured while trying to create new user {$login}.");
            $answer['status']=false;
        }
    }
    
    // If here, all requested creations went well
    return $answer;
}

/*
 * Escape only single quote in order to be print in HTML code
 * 
 * @param $string is the string to input
 * @return the string escaped
 */
function escapeSingleQuote($string){
    return str_replace("'","&#39;",$string);
}

/*
 * function formatNumberForIvr
 *
 * @param $number a number as a string
 * @param $nb number of digit to group
 * @param $replace, true or false country code by local number
 * @return an ivr readable formatted string
 *
 */
function formatNumberForIvr($number, $nb = 2, $replace = false){
	// for the IVR you may have to pronounce
	// +33637753326
	// +221123456789
	//
	// the role of this function is to modify the number so it will be pronounced more naturally 
	// (no +33 millions....)
	// +33637753326 => 06 37 75 33 26 (French way to announce phone number)
	// +221123456789 => to be checked with students in Dakar

	// does not format if it does not start with a +

	// List all Code Countries
	$cc = array( "32", "33", "44", "221", "222", "223", "224", "225", "226", "227", "229", "212", "213", "216", "236", "237", "240", "241", "242", "243", "261", "262" );
	
	// We need to group from the end (to not have a single digit at the end)
	// so we loop until we have only group of $nb digits
	do {
		$number = preg_replace(
			"/^\+(".implode('|',$cc).") ?(\S*?)(\d{".$nb."})(( \d{".$nb."})*)$/",
			"+\\1 \\2 \\3\\4",
			$number, -1, $count);
	}while ($count);
	
	// Remove surnumeral space (can happen if previous (\S*?) is empty
	$number = preg_replace('/\s\s+/', ' ', $number );

	// If we need to remove the country code set $replace
	// Don't forget the preg format "/^\+cc /" to catch CC at the start of the line and remove the first space
	if ( $replace ){
		$cc_toreplace = array ( '/^\+33 /',	'/^\+222 /' );
		$cc_replaced =	array ( '0',		'9' );
		$number = preg_replace ( $cc_toreplace, $cc_replaced, $number );
	}
	
	return $number;
}

/*
 * This function will return a proper filename with a given string argument
 * 
 * @param filename
 * @return filename in a better shape
 */
function filename_safe($filename) {
	$temp = $filename;
	$maxlen = '200';
	//$ext = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,10);

	// Lower case
	$temp = strtolower($temp);

	// Replace spaces with a '_'
	$temp = str_replace(" ", "_", $temp);



	// Loop through string
	$result = '';
	for ($i=0; $i<strlen($temp); $i++) {
		if (preg_match('([0-9]|[a-z]|_)', $temp[$i])) {
			$result = $result . $temp[$i];
		}
	}

	// Return filename
	return substr($result,0,$maxlen);//."$ext";
}

/*
 * Parse the datetime string from DB to retrieve only 
 * inputs: 
 *      $date is the datetime to parse
 *      $what is the expecting value to extract
 * return
 *      the extracted value or false if $what not found
 */
function parseDatetime($date,$what){ 
    switch ($what)
    {
    case "year":
        return substr($date,0,4); // année 
        break;
    case "month":
        return substr($date,5,2);  // mois 
        break;
    case "day":
        return substr($date,8,2);        // jour 
        break;
    case "hour":
        return substr($date,11,2);     // heures 
        break;
    case "minute":
        return substr($date,14,2);     // minutes
        break;
    default:
        return false;
    }
}

/*
 * Compare the datetime from MySQL to current time
 * in order to know if it is still time to bet.
 * This function take the global constant _TIME_BEFORE_BET
 * into account.
 * inputs: 
 *      $date is the datetime to check with
 * return
 *      true or false
 */
function checkIfBetStillPossible($date){
    // Compute the last time for bets for this match
    $dateMatchForBet = strtotime($date) - _TIME_BEFORE_BET;
    
    // What time is it please?
    $now = time();
    
    // Is it still possible to do a bet?
    if ($now > $dateMatchForBet){
        return false;
    }
    else{
        return true;
    }
}

/*
 * Determine how many minutes are between server and client
 * Inputs:
 *      $clientTime and $serverTime are in seconds since Unix Epoch
 * Return:
 *      a number of secondes rounded to the 15 minutes because
 *      timezones in world are separate by 15 minutes minimum
 */
function getTimeDiffWithServer($clientTime, $serverTime = 0){
    if ($serverTime == 0) $serverTime = time();
    
    // Determine how many 900 sec (15 minutes) we can found in this diff
    return round(($clientTime - $serverTime) / 900) * 900;
}


/*
 * getPronosticsMatch
 * Returns an array with the percentage of pronostics for the teams
 * 
 * @param $matchID
 * @return an array such as:
 * [$team1]=PercentageTeam1 
 * [$team2]=PercentageTeam2
 * ['draw']=PercentageDraw
 * ['team1']=PercentageTeam1
 * ['team2']=PercentageTeam2
 * ['nb']=nbPronostics

 * Example : ['Lybie']=12 ['Senegal']=70 ['draw']=18 ['team1']=12 ['team2']=70 ['nbPronos']=15
 */
function getPronosticsMatch($matchID) {
    global $sql;
    
    $result = array();
    
    // Initialisation
    $team1Pronos=0;
    $team2Pronos=0;
    
    // SQL request to fetch the name of the teams
    $req = "SELECT * FROM matches WHERE id_match='".mysqli_real_escape_string($sql->dbhandle, $matchID)."'";
    
    $match = $sql->doQuery($req, true);
    
    $team1=$match['team1_name'];
    $team2=$match['team2_name'];
    
    // SQL request to fetch the pronostics associated with the match
    $req = "SELECT * FROM pronostics WHERE matches_id_match='".mysqli_real_escape_string($sql->dbhandle, $matchID)."'";
    
    $pronos = $sql->fetchAssoc($sql->doQuery($req));
    
    // Keep number of pronostics
    $result['nb']=count($pronos);
    
    foreach ($pronos as $prono){
        if ($prono['team1'] > $prono['team2'])
            $team1Pronos++;
        else if ($prono['team1'] < $prono['team2'])
            $team2Pronos++;
    }
    
    // Percentage computation only if more than 1 prono
    if ($result['nb'] > 0){
        $result[$match['team1_name']] = round ( 100 * $team1Pronos / $result['nb'] );
        $result[$match['team2_name']] = round ( 100 * $team2Pronos / $result['nb'] );
        $result['team1'] = $result[$match['team1_name']];
        $result['team2'] = $result[$match['team2_name']];
        $result['draw'] = 100 - $result[$match['team1_name']] - $result[$match['team2_name']];
    } else {
        $result[$match['team1_name']] = 0;
        $result[$match['team2_name']] = 0;
        $result['team1'] = $result[$match['team1_name']];
        $result['team2'] = $result[$match['team2_name']];
        $result['draw'] = 100 - $result[$match['team1_name']] - $result[$match['team2_name']];
    }
    
    return $result;
}

/*
 * getStatsBetPerMatch
 * Returns the percentage of good pronostics
 *
 *     
 * @param $matchId
 * @return percentage
 */
function getStatsBetPerMatch($matchID) {


   global $sql;

    // Initialisation
    $goodBets=0;
    
    // SQL request to fetch the result of the match
    $req= "SELECT * FROM `matches` WHERE `id_match`='".mysqli_real_escape_string($sql->dbhandle, $matchID)."'";
    $match = $sql->doQuery($req, true);
    
    
    // Select all pronostics for this match
    $bets = $sql->fetchAssoc(
                $sql->doQuery(
                    "SELECT * 
                     FROM 
                        pronostics
                     WHERE
                        matches_id_match = '".mysqli_real_escape_string($sql->dbhandle, $match['id_match'])."' 
                        "
                )
            );
    
    // For each bet
    foreach ($bets as $bet){
    
        if(($bet['team1'] == $match['team1_result']) && ($bet['team2'] == $match['team2_result'])) $goodBets+=1;

    }
    
    $nbBets=count($bets);
    
    // Percentage computation
    if ($nbBets>1){
        $percentageGoodBets = round ( 100 * $goodBets / $nbBets );
    } else {
        $percentageGoodBets = 0;
    }


    return $percentageGoodBets;    
}


/*
 * getStatsPerMatch
 * Returns an array with the pronos
 *
 *     
 * @param $matchId
 * @return pronos
 * ['0-0']='toto' 
 * ['1-0']='toto3' 

 */
function getStatsPerMatch($matchID,$friendlist_name) {

    global $sql;
   
    // Initialisation
    $result = array();
    
    // SQL request to fetch the result of the match
    $req= "SELECT * FROM `matches` WHERE `id_match`='".mysqli_real_escape_string($sql->dbhandle, $matchID)."'";
    $match = $sql->doQuery($req, true);

    // Select all pronostics for this match
    $bets = $sql->fetchAssoc(
                $sql->doQuery(
                    "SELECT * 
                     FROM 
                        pronostics
                     WHERE
                        matches_id_match = '".mysqli_real_escape_string($sql->dbhandle, $match['id_match'])."' 
                    AND    
                        friendlist_name = '".mysqli_real_escape_string($sql->dbhandle, $friendlist_name)."'   
                        "
                )
            );
    
    // For each bet
    $MAX_GOALS = 4;
    foreach ($bets as $bet){
        // limit the score to 4
        $team1 = intval($bet['team1']);
        $team2 = intval($bet['team2']);
        if ($team1 > $MAX_GOALS) $team1 = $MAX_GOALS;
        if ($team2 > $MAX_GOALS) $team2 = $MAX_GOALS;        
        
        $score=strval($team1).'-'.strval($team2);
        if (!isset($result[$score]))  $result[$score]=array(); 
        array_push($result[$score], $bet['members_login']);
        //if(($bet['team1'] == $match['team1_result']) && ($bet['team2'] == $match['team2_result'])) $goodBets+=1;
    }

    return $result;    
}



/*
 * getGoodBetPerMatch
 * Returns the list of members who did the good pronostic
 *
 *     
 * @param $matchId
  *@param $friendlist_name
 * @return result : array of members 
 */
function getGoodBetPerMatch($matchID, $friendlist_name) {

   global $sql;

    // Initialisation
    $result = array();
    
    // SQL request to fetch the result of the match
    $req= "SELECT * FROM `matches` WHERE `id_match`='".mysqli_real_escape_string($sql->dbhandle, $matchID)."'";
    $match = $sql->doQuery($req, true);
    
    
    // Select all pronostics for this match
    $bets = $sql->fetchAssoc(
                $sql->doQuery(
                    "SELECT * 
                     FROM 
                        pronostics
                     WHERE
                        matches_id_match = '".mysqli_real_escape_string($sql->dbhandle, $match['id_match'])."' 
                     AND    
                        friendlist_name = '".mysqli_real_escape_string($sql->dbhandle, $friendlist_name)."'   
                        "
                )
            );
    
    // For each bet
    foreach ($bets as $bet){
        if(($bet['team1'] == $match['team1_result']) && ($bet['team2'] == $match['team2_result'])) 
            array_push($result, $bet['members_login']);
    }
    
    return $result;    
}


/*
 * getMatchID
 * Returns the matchID
 *
 * Parameters
 * team1    name of the team1
 * team2    name of the team2
 * round    name of the round
 * Return   int : matchID in the SQL table 
*/
function getMatchID($team1, $team2, $round) {
    global $sql;
    // SQL request to fetch the match ID
    $req= "SELECT * FROM `matches` WHERE `team1_name`='".mysqli_real_escape_string($sql->dbhandle, $team1)."' AND `team2_name`='".mysqli_real_escape_string($sql->dbhandle, $team2)."' AND `round_name`='".mysqli_real_escape_string($sql->dbhandle, $round)."'";
    $resource = $sql->doQuery($req);
    $match = $sql->fetchAssoc($resource);
    $matchID= $match['id_match'];
    
    // get
    if ($matchID)
        return $matchID;    
    else
        return NULL;
}

/*
 * Return the rank array for this friendlist
 *
 * @param $friendlist is one of the friendlist name the user is in
 * @param $round
 * @return an array $rankPointsProgression     // $rankPointsProgression['member']['day']['points']
 *                                             // $rankPointsProgression['member']['day']['place']
 */
function getRankPointsProgression($friendlist, $round=false){
    global $sql;
    
    // Member array with points
    $rankPointsProgression = array();
     
    // Build the friendlist to get all players in this friendlist
    $friendlistObject = new Friendlist($friendlist);
    
    // Get the list of days and times off matches with bets

    $dayTimeMatches = $sql->fetchAssoc(
                        $sql->doQuery(
                            "SELECT date 
                             FROM 
                                matches
                             WHERE
                                status = 'PLAYED'
                                ORDER BY DATE ASC"   
                        )
                    );
   
    
    // Create the array of days 
    $days=array();
    foreach ($dayTimeMatches as $dayTimeMatch) {
        $fields=explode (" ", $dayTimeMatch['date']);
        $day=$fields[0];
        if (!isset($days[$day])) $days[$day]=$day;
    }
    
    // Initialise to 0 for all members of this friendlist
    foreach ($friendlistObject->members as $member){
        foreach ($days as $day) {
        $rankPointsProgression[$member][$day]['points'] = 0;
        $rankPointsProgression[$member][$day]['rank'] = 0;
        }
    }
    
    // Get the place and points per day
    foreach ($days as $day) {
        // Select all matches till this day
        $sqlRound = "";
        if ($round) $sqlRound = " AND round_name='".mysqli_real_escape_string($sql->dbhandle, $round)."'";
        $matches = $sql->fetchAssoc(
                        $sql->doQuery(
                            "SELECT * 
                             FROM 
                                matches
                             WHERE
                                status = 'PLAYED'
                             AND date <'".$day." 23:59:59'
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
                                matches_id_match = '".mysqli_real_escape_string($sql->dbhandle, $match['id_match'])."' AND
                                friendlist_name = '".mysqli_real_escape_string($sql->dbhandle, $friendlistObject->name)."'
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
                if (isset($rankPointsProgression[$bet['members_login']][$day]['points'])) $rankPointsProgression[$bet['members_login']][$day]['points'] += $points;
            }
        } // foreach match
    } // foreach day
    
    // compute the rank 
    foreach ($days as $day) {
        $memberPoints=array();
        foreach ($friendlistObject->members as $member){
            $memberPoints[$member]=$rankPointsProgression[$member][$day]['points'];
        }
        arsort($memberPoints);    
     
        $nbMembers=0;
        $rank=0;
        $lastPoint=100000;
        foreach ($memberPoints as $member => $points){
            $currentPoint=$points; 
            $nbMembers++;
            if ($currentPoint<$lastPoint) $rank=$nbMembers;
            $lastPoint=$currentPoint;
            $rankPointsProgression[$member][$day]['rank'] = $rank;
        }
    }
    return $rankPointsProgression;
}


function getLoginFromNumber($number) {
	global $sql;
	    
	$req = "SELECT login FROM `members` where number = '".mysqli_real_escape_string($sql->dbhandle, $number)."'";
	$login = $sql->fetchAssoc($sql->doQuery($req));

	return $login;
}

?>
