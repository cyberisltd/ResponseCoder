<?php

//A horribly inefficient way of calcuating the hash, but it allows easy porting of this PoC between servers
$hash = hash("sha256",file_get_contents("http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'])."/dict.php"),true);

//Check client request hash matches
$clienthash = strtr(base64_encode(substr($hash,0,6)), '+/=', '-_,');

if (!isset($_SERVER['HTTP_AVAIL_DICTIONARY'])) {
	die ("[ERROR] 'Avail-Dictionary' header not set. Perhaps your browser doesn't support SDCH, the dictionary hasn't yet been retrieved, or some other problem has occured. A common issue observed in testing is Chrome seems to want a FQDN (http://x.y.z) to work. If running locally, you may want to configure some /etc/host entries.\n");
}

if ($_SERVER['HTTP_AVAIL_DICTIONARY'] !== $clienthash) {
	die ("[ERROR] Client hash mismatch - " . htmlspecialchars($clienthash . " does not match " . $_SERVER['HTTP_AVAIL_DICTIONARY']."\n"));
}

$serverhash = strtr(base64_encode(substr($hash,6,6)), '+/=', '-_,');

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=helloworld.exe");
header("Content-Encoding: sdch");
$serverid = $serverhash . "\0";
$response =  $serverid . file_get_contents("./exe.encoded");
echo $response;
?>
