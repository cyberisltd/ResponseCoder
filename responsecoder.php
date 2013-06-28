<?php

/*
    Copyright (C) 2013  Cyberis Ltd. Author geoff.jones@cyberis.co.uk

    This file is part of ResponseCoder, a server-side script is designed to 
    allow you to easily manipulate HTTP response headers, specifically to 
    identify weaknesses in perimeter filtering appliances such as web proxies and 
    next generation firewalls.

    ResponseCoder is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    ResponseCoder is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


/*  Ideas for future enchancements:
	* Allow duplicate headers?
	* Chuncked encoding?
	* Ideas from http://greenbytes.de/tech/tc2231/
*/

//PHP doesn't offer an implementation of the old compress algorthim, so unfortunatly we must call the shell. Set the path below. 'ncompress' should give you the functionality you need.
$COMPRESS = '/usr/bin/compress';

//Replace the following to files of your choosing.
$EXEFILETODOWNLOAD = './helloworld.exe';
$TXTFILETODOWNLOAD = './helloworld.txt';

//Terminate with an appropriate error message if no parameters have been passed to this script.
if (count($_REQUEST) == 0) {
	die("[ERROR] No parameters passed");
}

//Input validation - put some constraints on what manipulation can be carried out - relax if you can think of some test cases not possible within these constraints 
$responsecode = filter_var($_REQUEST['responsecode'], FILTER_VALIDATE_INT, array('options'=>array('default' => '200')));
$status = filter_var($_REQUEST['status'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp' => '/^[A-Z \-\']+$/i')));
$contenttype = filter_var($_REQUEST['contenttype'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp' => '/^[A-Z\-\/]+$/i')));
$contentencodingheading = filter_var($_REQUEST['contentencodingheading'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp' => '/^[0-9A-Z\- ]+$/i')));
$contentencoding = filter_var($_REQUEST['contentencoding'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp' => '/^[0-9A-Z\- ]+$/i')));
$contentlength = filter_var($_REQUEST['contentlength'], FILTER_VALIDATE_INT);
$filename = filter_var($_REQUEST['filename'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp' => '/^[A-Z0-9\.]+$/i')));
$type = filter_var($_REQUEST['type'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp' => '/^(txt|exe)$/i')));
$file = $EXEFILETODOWNLOAD; //Download an exe as standard

if ($type === 'txt') {
	$file = $TXTFILETODOWNLOAD;
}

//Test selected file exists
if (!is_readable($file)){
	die("[ERROR] Cannot read selected file '$file'");
}

//Manipulate the headers NB: Apache doesn't allow us to play around with the protocol version, although browsers do accept alternatives (e.g. blah/1.2)!
header($_SERVER["SERVER_PROTOCOL"] . " $responsecode $status");

//Set 'Content-Type' header if passed
if ($contenttype) {
	header("Content-Type: $contenttype");
}

//Set 'Content-Encoding' header if passed
if ($contentencodingheading) {
	header("Content-Encoding: $contentencodingheading");
}

//Set 'Content-Disposition' header if passed
if ($filename) {
	header("Content-Disposition: attachment; filename=\"$filename\"");
}

//Set the user's supplied 'Content-Length' if passed
if ($contentlength) header("Content-Length: $contentlength");

//Encode response as directed, setting the correct length, unless overridden.
switch ($contentencoding) {
	case "base64":
		$data =  base64_encode(file_get_contents($file));
		if (!$contentlength) header("Content-Length: " . strlen($data));
		echo $data;
		break;	
	case "gzip":
		$data = gzencode(file_get_contents($file));
		if (!$contentlength) header("Content-Length: " . strlen($data));
		echo $data;
		break;
	case "deflate1950":
		$data = gzcompress(file_get_contents($file));
		if (!$contentlength) header("Content-Length: " . strlen($data));
		echo $data;
		break;
	case "deflate1951":
		$data = gzdeflate(file_get_contents($file));
		if (!$contentlength) header("Content-Length: " . strlen($data));
		echo $data;
		break;
	case "compress":
		if (!is_file($COMPRESS)) { 
			//This is obviously a *nix tool that needs to be installed.
			die("Cannot find Unix compress tool. Select a different content-encoding");
		}
		$data = shell_exec("$COMPRESS -c $file");
		if (!$contentlength) header("Content-Length: " . strlen($data));
		echo $data;
		break;
	case "bzip":
		$data = bzcompress(file_get_contents($file));
		if (!$contentlength) header("Content-Length: " . strlen($data));
		echo $data;
		break;
	default:
		if (!$contentlength) header("Content-Length: " . filesize($file));
		readfile($file);
}

exit();        

?>
