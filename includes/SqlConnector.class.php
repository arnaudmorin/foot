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

SQL connection

*/

class SqlConnector{
    /* *************************************************************************
     * Variables
     * ************************************************************************/
    public $db = null;
    public $dbhandle = null;
    
    /* *************************************************************************
     * Constructor
     * ************************************************************************/
    function __construct($dbhost = _DB_HOST, $dbname = _DB_NAME, $dbusername = _DB_USERNAME, $dbpwd = _DB_PWD) {
        $this->dbhandle = mysqli_connect($dbhost, $dbusername, $dbpwd);
        if (!$this->dbhandle) {
            // TODO handle errors
        }

	    $this->db = mysqli_select_db($this->dbhandle, $dbname);
        if (!$this->db) {
            // TODO 
        }
        
        // Configure the DB connection to UTF8
	    mysqli_query($this->dbhandle, "SET NAMES 'utf8'");
 	    mysqli_query($this->dbhandle, "SET CHARACTER SET 'utf8'");
        
        // Configure the DB to use timezone specified in config file
        $timezone = _TIMEZONE;
        $time = new \DateTime('now', new DateTimeZone($timezone));
        $timezoneOffset = $time->format('P');
        mysqli_query($this->dbhandle, "SET time_zone = '".$timezoneOffset."'");
    }
    
    public function __destruct() {
        if ($this->dbhandle) {
            mysqli_close($this->dbhandle);
        }
    }
    
    /* *************************************************************************
     * Public methods
     * ************************************************************************/

    /*
     * Do a query, eventually fetch the resource also
     *
     * inputs:
     *      $query
     *      $fetch false if not provided
     * outputs:
     *      Return the mysqli resource on success. False on error.
     *      It can also return the associative array containing the results
     */
    public function doQuery($query, $fetch=false) {
        $result = mysqli_query($query, $this->dbhandle);
        if (!$result) {
            // On error return false
            debug(mysqli_error());
			return false;
        }
        if ($fetch) return mysqli_fetch_assoc($result);
        else return $result;
    }
    
    /*
     * Fetch the resource as an array
     *
     * inputs:
     *      $resource is the sql resource to fetch
     * outputs:
     *      Return an array
     */
    public function fetchAssoc($resource) {
        $return = array();
        while ($t = mysqli_fetch_assoc($resource)){
            $return[] = $t;
        }
        return $return;
    }
}
?>
