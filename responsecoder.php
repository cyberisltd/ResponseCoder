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

//PHP doesn't offer an implementation of the old compress algorthim, so unfortunatly we must call the shell. Set the path below. 'ncompress' should give you the functionality you need.
$COMPRESS = '/usr/bin/compress';

//Replace the following to files of your choosing.
$EXEFILETODOWNLOAD = './helloworld.exe';
$TXTFILETODOWNLOAD = './helloworld.txt';

//Default options
$responsecode = "200";
$status = "OK";
$contenttype = "application/octet-stream";
$contentencodingheading1 = "";
$contentencodingheading2 = "";
$contentencoding = "";
$contentlength = "";
$filename = "helloworld.exe";
$type = "exe";
$chunked = "";
$file = $EXEFILETODOWNLOAD;

//Input validation - put some constraints on what manipulation can be carried out - relax if you can think of some test cases not possible within these constraints 
if (isset($_REQUEST['responsecode'])) { 
	$responsecode = filter_var($_REQUEST['responsecode'], FILTER_VALIDATE_INT);
}
if (isset($_REQUEST['status'])) {
	$status = filter_var($_REQUEST['status'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp' => '/^[A-Z \-\']+$/i')));
}
if (isset($_REQUEST['contenttype'])) {
	$contenttype = filter_var($_REQUEST['contenttype'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp' => '/^[A-Z\-\/]+$/i')));
}
if (isset($_REQUEST['contentencodingheading1'])) {
	$contentencodingheading1 = filter_var($_REQUEST['contentencodingheading1'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp' => '/^[0-9A-Z\- ,]+$/i')));
}
if (isset($_REQUEST['contentencodingheading2'])) {
	$contentencodingheading2 = filter_var($_REQUEST['contentencodingheading2'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp' => '/^[0-9A-Z\- ,]+$/i')));
}
if (isset($_REQUEST['contentencoding'])) {
	$contentencoding = filter_var($_REQUEST['contentencoding'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp' => '/^[0-9A-Z\- \+]+$/i')));
}
if (isset($_REQUEST['contentlength'])) {
	$contentlength = filter_var($_REQUEST['contentlength'], FILTER_VALIDATE_INT);
}
if (isset($_REQUEST['filename'])) {
	$filename = filter_var($_REQUEST['filename'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp' => '/^[A-Z0-9\.]+$/i')));
}
if (isset($_REQUEST['type'])) {
	$type = filter_var($_REQUEST['type'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp' => '/^(txt|exe)$/i')));
}
if (isset($_REQUEST['chunked'])) {
	$chunked = $_REQUEST['chunked'];
}

//Set the requested file type
if ($type === 'txt') {
	$file = $TXTFILETODOWNLOAD;
}

//Test selected file exists
if (!is_readable($file)){
	die("[ERROR] Cannot read selected file '$file'");
}

//Manipulate the headers NB: Apache doesn't allow us to play around with the protocol version, although browsers do accept alternatives (e.g. blah/1.2)! 
//Try httpversioncoder.pl if you want to manipulate the http version.
header($_SERVER["SERVER_PROTOCOL"] . " $responsecode $status");

//Set 'Content-Type' header if passed
if ($contenttype) {
	header("Content-Type: $contenttype");
}

//Set 'Content-Encoding' headers if passed
if ($contentencodingheading1) {
	header("Content-Encoding: $contentencodingheading1");
}
if ($contentencodingheading2) {
	//The 'false' parameter forces a second 'Content-Encoding' header
	header("Content-Encoding: $contentencodingheading2", false);
}

//Set 'Content-Disposition' header if passed
if ($filename) {
	header("Content-Disposition: attachment; filename=\"$filename\"");
}

//Encode response as directed.
switch ($contentencoding) {
	case "base64":
		$data = base64_encode(file_get_contents($file));
		break;	
	case "gzip":
		$data = gzencode(file_get_contents($file));
		break;
	case "2xgzip":
		$data = gzencode(gzencode(file_get_contents($file)));
		break;
	case "deflate1950":
		$data = gzcompress(file_get_contents($file));
		break;
	case "deflate1951":
		$data = gzdeflate(file_get_contents($file));
		break;
	case "gzip+deflate1950":
		$data = gzcompress(gzencode(file_get_contents($file)));
		break;
	case "gzip+deflate1951":
		$data = gzdeflate(gzencode(file_get_contents($file)));
		break;
	case "deflate1950+gzip":
		$data = gzencode(gzcompress(file_get_contents($file)));
		break;
	case "deflate1951+gzip":
		$data = gzencode(gzdeflate(file_get_contents($file)));
		break;
	case "compress":
		if (!is_file($COMPRESS)) { 
			//This is obviously a *nix tool that needs to be installed.
			die("Cannot find Unix compress tool. Select a different content-encoding");
		}
		$data = shell_exec("$COMPRESS -c $file");
		break;
	case "bzip":
		$data = bzcompress(file_get_contents($file));
		break;
	default:
		$data = file_get_contents($file);
}

//Set the Content-Length header (unless overridden)
if (!$contentlength && !$chunked) {
	header("Content-Length: " . strlen($data));
}
else if ($contentlength) {
	header("Content-Length: " . $contentlength);
}

//Return the actual data
echo $data;
?>
