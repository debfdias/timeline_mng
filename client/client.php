<?php
// This client for local_timelinemanager is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//

/// MOODLE ADMINISTRATION SETUP STEPS
// 1- Install the plugin
// 2- Enable web service advance feature (Admin > Advanced features)
// 3- Enable XMLRPC protocol (Admin > Plugins > Web services > Manage protocols)
// 4- Create a token for a specific user and for the service 'Timeline Manager' (Admin > Plugins > Web services > Manage tokens)
// 5- Run this script directly from your browser: you should see the JSONs correspondent to the mdl_timeline_manager table records

/// SETUP - NEED TO BE CHANGED
$token = ''; // Put the token generated to the plugin in /admin/settings.php?section=webservicetokens
$domainname = 'http://localhost/moodle'; //Change to the actual website root folder 

/// FUNCTION NAME
$functionname = 'local_timelinemanager_get_timeline';

///// XML-RPC CALL
header('Content-Type: text/plain');
$serverurl = $domainname . '/webservice/xmlrpc/server.php'. '?wstoken=' . $token;
require_once('./curl.php');
$curl = new curl; 
$post = xmlrpc_encode_request($functionname, array());
$resp = xmlrpc_decode($curl->post($serverurl, $post));
print_r($resp);
