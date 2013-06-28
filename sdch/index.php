<?php

$path = dirname($_SERVER['SCRIPT_NAME']);
$dict = $path . "/dict.php";

//Set the header to make the UA request the dictionary
header('Get-Dictionary: ' . $dict);
?>

<html>
<title>SDCH Encoded Exe to Bypass Filtering Devices</title>
<body>
<p>Your browser has been asked whether it wants to download a dictionary to support SDCH compression. If you are running  tcpdump/wireshark in the background, you hopefully observed a request to <i><b><?php echo $_SERVER['SERVER_NAME'] . $dict; ?></b></i>.</p>
<p>Once your browser has the compression dictionary, request <a href="./sdch_exe.php">this executable</a>. You should see a request with an 'Avail-Dictionary' heading, and of course, an SDCH encoded response back.</p>
<p>Rather than implementing full VCDIFF in PHP, the exe is manually encoded for this example. Recreate your dictionary (dict.raw) and encoded exe if you want to deliver something other than the example <i>helloworld.exe</i></p>

</body>
</html>
