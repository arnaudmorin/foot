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

The Friendlist class represents a Friendlist in the betting system

*/

class Friendlist{
    /* *************************************************************************
     * Variables
     * ************************************************************************/
    public $name;
    public $password;
    public $description;
    public $members = Array();
    
    /* *************************************************************************
     * Constructor
     * ************************************************************************/
    function __construct($name, $autobuild = true) {
        global $sql;
        
        $this->name = $name;
        
        // Set automatically other variables if requested
        if ($autobuild){
            // Get most of info
            $req = "SELECT * FROM friendlists WHERE name = '".mysqli_real_escape_string($name)."'";
            $res = $sql->doQuery($req, true);
            
            if ($res){
                $this->password = $res['password'];
                $this->description = $res['description'];
            }
            else{
                debug("Error occured while trying to build friendlist object {$name}.");
                $this->name = null;
            }
            
            // Get members
            $req = "SELECT * FROM friendlists_membership WHERE friendlist_name = '".mysqli_real_escape_string($name)."'";
            $res = $sql->fetchAssoc($sql->doQuery($req));
            
            if ($res){
                foreach ($res as $membersArray){
                    $this->members[] = $membersArray['member_login'];
                }
            }
            else{
                debug("Error occured while trying to build friendlist object ({$name}) members array.");
                $this->members = null;
            }
        }
    }
    
    
    
    /* *************************************************************************
     * Public methods
     * ************************************************************************/

    /*
     * Return all bets done for a match in this friendship
     *
     * @param $matchID
     * @return an array with all bets
     */
    function getBets($matchID){
        // TODO SQL request
    }

    /*
     * Send SMS to all friendlist members to tell them a message
     *
     * @param $message
     * @return always true but may not work (see debug info)
     */
    function sendNotificationToFriendlist($message, $exclude){
        global $api_login, $api_password, $sql;
        $Emerginov = new Emerginov($api_login, $api_password);
        
        // For all members of this friendlist
        foreach ($this->members as $login){
            // Build user object
            $userObject = new User($login);
            
            // If we do not have a valid phone number
            if (!validateInternationalPhoneNumber($userObject->number)) continue;
            
            // If we do not want to send notif to this guy
            if (in_array($userObject->number, $exclude)) continue;
            
            // Check if this guy has disabled notification_join_group
            if (!$userObject->notification_join_friendlist) continue;
            
            debug("Trying to send SMS to {$login}/{$userObject->number} (friendlist {$this->name}): {$message}");
            $res = $Emerginov->SendSMS($userObject->number, $message);
            if (!$res->Success) debug("Error occured: {$res->Result}");
        }
        return true;
    }

}
?>
