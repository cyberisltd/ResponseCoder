<?php
//Generate the dictionary on the fly, which in this case, is the first 128 bytes of helloworld.exe 

header("Content-type: application/x-sdch-dictionary");
header("Cache-Control: private, max-age=0");
header("Vary: Accept-Encoding");

$response = "Domain: {$_SERVER['HTTP_HOST']}\nPath: " . dirname($_SERVER['SCRIPT_NAME']) ."/\n\n";
$response = $response . file_get_contents("./dict.raw");

echo $response;
?>
