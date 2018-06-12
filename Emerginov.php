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

class Emerginov{
    private $api_login;
    private $api_password;

    /* *************************************************************************
     * Constructor
     * ************************************************************************/
    function __construct($api_login, $api_password) {
        $this->api_login = $api_login;
        $this->api_password = $api_password;
    }

    function SendSMS($number, $message) {
        $res = $this->http_post('http://maison.arnaudmorin.fr:5000/send', array(
            'message' => $message,
            'to'      => str_replace('+', '', $number),
            'token'   => $this->api_password,
        ));

        if (strpos($res, "OK") === 0) {
            return new EmerginovResult(True, $res);
        }
        else {
            return new EmerginovResult(False, $res);
        }
    }

    function http_post ($url, $data) {
        $data_url = http_build_query ($data);
        $data_len = strlen ($data_url);
    
        return file_get_contents(
            $url,
            false,
            stream_context_create(array(
                'http' => array(
                    'method'  => 'POST',
                    'header'  => "Connection: close\r\nContent-Length: $data_len\r\nContent-type: application/x-www-form-urlencoded\r\n",
                    'content' => $data_url
                 )
            ))
        );
    }
}

class EmerginovResult{
    public $Success;
    public $Result;

    /* *************************************************************************
     * Constructor
     * ************************************************************************/
    function __construct($Success, $Result) {
        $this->Success = $Success;
        $this->Result = $Result;
    }
}
