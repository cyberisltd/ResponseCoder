<!DOCTYPE html>

<!--
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
-->

<html>
<head>
<meta charset="utf-8" />
<title>ResponseCoder - HTTP Response Manipulator</title>
</head>
<body>
<h1>ResponseCoder</h1>
<p>ResponseCoder is a server-side script is designed to allow you to easily manipulate HTTP response headers, specifically to identify weaknesses in perimeter filtering appliances such as web proxies and next generation firewalls.</p>
<p>The test cases are centred around the download of a Win32 executable - a common file format that is often blocked at the perimeter to prevent unauthorised code and malware from entering the corporate environment. </p>
<p>Obviously manipulation of such HTTP response headers may lead to unexpected results in your browser - redirect codes, client errors and server error codes are typical examples that may (or should) cause a browser to ignore the body of a response. Try it for yourself - a 201 in Internet Explorer for example will cause it to ignore the specified filename in the 'Content-Disposition' header, whilst Chrome will accept that just fine.</p>
<p>There are numerous tests you can conduct with HTTP response headers (take a look over at <a href="http://greenbytes.de/tech/tc2231/">http://greenbytes.de/tech/tc2231/</a> for some ideas), and this script certainly doesn't expose all possible scenarios. However it does provide a quick testing framework for intermediary filtering devices.</p>
<p>This page exposes common options passed to the server-side script, though not all. Feel free to play around with GET parameters as necessary to accomplish your test cases (for example, 'responsecode' will take any integer value). NB: Fairly strict input validation is applied - if it prevents you running your specific test, grab the source and modify as necessary.</p>
<p>The correct MD5 checksums for the two test files are as follows:</p>
<ul>
<li>helloworld.txt - <?php echo md5_file("./helloworld.txt");?></li>
<li>helloworld.exe - <?php echo md5_file("./helloworld.exe");?></li>
</ul>
<form action="./responsecoder.php" method="get" enctype="application/x-www-form-urlencoded">
<table>
<tr>
        <td>Response Code: </td>
        <td>
                <select name="responsecode">
                        <option value="100">100</option>
                        <option value="101">101</option>
                        <option value="102">102</option>
                        <option selected value="200">200</option>
                        <option value="201">201</option>
                        <option value="202">202</option>
                        <option value="203">203</option>
                        <option value="204">204</option>
                        <option value="205">205</option>
                        <option value="206">206</option>
                        <option value="207">207</option>
                        <option value="208">208</option>
                        <option value="226">226</option>
                        <option value="300">300</option>
                        <option value="301">301</option>
                        <option value="302">302</option>
                        <option value="303">303</option>
                        <option value="304">304</option>
                        <option value="305">305</option>
                        <option value="306">306</option>
                        <option value="307">307</option>
                        <option value="308">308</option>
                        <option value="400">400</option>
                        <option value="401">401</option>
                        <option value="402">402</option>
                        <option value="403">403</option>
                        <option value="404">404</option>
                        <option value="405">405</option>
                        <option value="406">406</option>
                        <option value="407">407</option>
                        <option value="408">408</option>
                        <option value="409">409</option>
                        <option value="410">410</option>
                        <option value="418">418</option>
                        <option value="500">500</option>
                        <option value="501">501</option>
                        <option value="502">502</option>
                        <option value="503">503</option>
                        <option value="504">504</option>
                        <option value="505">505</option>
                        <option value="506">506</option>
                        <option value="507">507</option>
                        <option value="508">508</option>
                        <option value="509">509</option>
                        <option value="510">510</option>
                        <option value="666">666</option>
                </select>
        </td>
</tr>
<tr>
        <td>Status:
        </td>
        <td>
                <select name="status">
                        <optgroup label="1XX Informational">
                                <option value="Continue">Continue (100)</option>
                                <option value="Switching Protocols">Switching Protocols (101)</option>
                                <option value="Processing">Processing (102)</option>
                        </optgroup>
                        <optgroup label="2XX Sucess">
                                <option selected value="OK">OK (200)</option>
                                <option value="Created">Created (201)</option>
                                <option value="Accepted">Accepted (202)</option>
                                <option value="Non-Authoritative Information">Non-Authoritative Information (203)</option>
                                <option value="No Content">No Content (204)</option>
                                <option value="Reset Content">Reset Content (205)</option>
                                <option value="Partial Content">Partial Content (206)</option>
                                <option value="Multi-Status">Multi-Status (207)</option>
                                <option value="Already Reported">Already Reported (208)</option>
                                <option value="IM Used">IM Used (226)</option>
                        </optgroup>
                        <optgroup label="3XX Redirection">
                                <option value="Multiple Choices">Multiple Choices (300)</option>
                                <option value="Moved Permanently">Moved Permanently (301)</option>
                                <option value="Found">Found (302)</option>
                                <option value="See Other">See Other (303)</option>
                                <option value="Not Modified">Not Modified (304)</option>
                                <option value="Use Proxy">Use Proxy (305)</option>
                                <option value="Switch Proxy">Switch Proxy (306)</option>
                                <option value="Temporary Redirect">Temporary Redirect (307)</option>
                                <option value="Permanent Redirect">Permanent Redirect (308)</option>
                        </optgroup>
                        <optgroup label="4XX Client Error">
                                <option value="Bad Request">Bad Request (400)</option>
                                <option value="Unauthorized">Unauthorized (401)</option>
                                <option value="Payment Required">Payment Required (402)</option>
                                <option value="Forbidden">Forbidden (403)</option>
                                <option value="Not Found">Not Found (404)</option>
                                <option value="Method Not Allowed">Method Not Allowed (405)</option>
                                <option value="Not Acceptable">Not Acceptable (406)</option>
                                <option value="Proxy Authentication Required">Not Acceptable (407)</option>
                                <option value="Request Timeout">Request Timeout (408)</option>
                                <option value="Conflict">Conflict (409)</option>
                                <option value="Gone">Conflict (410)</option>
                                <option value="I'm a teapot">I'm a teapot (418)</option>
                        </optgroup>
                        <optgroup label="5XX Server Error">
                                <option value="Internal Server Error">Internal Server Error (500)</option>
                                <option value="Not Implemented">Not Implemented (501)</option>
                                <option value="Bad Gateway">Bad Gateway (502)</option>
                                <option value="Service Unavailable">Service Unavailable (503)</option>
                                <option value="Gateway Timeout">Gateway Timeout (504)</option>
                                <option value="HTTP Version Not Supported">HTTP Version Not Supported (505)</option>
                                <option value="Varient Also Negotiates">Varient Also Negotiates (506)</option>
                                <option value="Insufficient Storage">Insufficient Storage (507)</option>
                                <option value="Loop Detected">Loop Detected (508)</option>
                                <option value="Bandwidth Limit Exceeded">Bandwidth Limit Exceeded (509)</option>
                                <option value="Not Extended">Not Extended (510)</option>
                        </optgroup>
                </select>
        </td>
</tr>
<tr>
        <td>
                Content-Type:
        </td>
        <td>
                <select name="contenttype">
                        <option value="">EMPTY (Ensure default_mimetype='' in php.ini)</option>
                        <option selected value="application/octet-stream">application/octet-stream</option>
                        <option value="text/plain">text/plain</option>
                        <option value="text/html">text/html</option>
                        <option value="application/json">application/json</option>
                        <option value="application/zip">application/zip</option>
                        <option value="application/pdf">application/pdf</option>
                        <option value="text/javascript">text/javascript</option>
                        <option value="text/cmd">text/cmd</option>
                        <option value="image/gif">image/gif</option>
                        <option value="message/http">message/http</option>
                </select>
        </td>
</tr>
<tr>
        <td>Content-Transfer-Encoding (first heading):</td>
        <td>
                <select name="contentencodingheading1">
                        <option value="">NONE</option>
                        <option value="gzip">gzip (RFC1952)</option>
                        <option value="gzip, gzip">gzip, gzip - (2 x gzip encoding)</option>
			<option value="gzip, gzip, gzip, gzip, gzip, gzip, gzip, gzip, gzip, gzip">gzip, gzip,... - (10 x gzip encoding)</option>
                        <option value="gzip, deflate">gzip, deflate</option>
                        <option value="deflate, gzip">deflate, gzip</option>
                        <option value="deflate">deflate (RFC1950/1951)</option>
                        <option value="compress">compress (Unix Compress)</option>
                        <option value="bzip">bzip</option>
                        <option value="identity">identity (No encoding)</option>
                        <option value="base64">base64 (browsers are unlikely to decode this!)</option>
                </select>
        </td>
</tr>
<tr>
        <td>Content-Transfer-Encoding (superfluous second heading):</td>
        <td>
                <select name="contentencodingheading2">
                        <option value="">NONE</option>
                        <option value="gzip">gzip (RFC1952)</option>
                        <option value="gzip, gzip">gzip, gzip - (2 x gzip encoding)</option>
                        <option value="gzip, deflate">gzip, deflate</option>
                        <option value="deflate, gzip">deflate, gzip</option>
                        <option value="deflate">deflate (RFC1950/1951)</option>
                        <option value="compress">compress (Unix Compress)</option>
                        <option value="bzip">bzip</option>
                        <option value="identity">identity (No encoding)</option>
                        <option value="base64">base64 (browsers are unlikely to decode this!)</option>
                </select>
	</td>
</tr>
<tr>
        <td>Content-Transfer-Encoding (actual):</td>
        <td>
                <select name="contentencoding">
                        <option value="">NONE</option>
                        <option value="gzip">gzip (RFC1952)</option>
                        <option value="2xgzip">2 rounds of gzip encoding</option>
                        <option value="10xgzip">10 rounds of gzip encoding</option>
                        <option value="gzip+deflate1951">gzip + deflate1951</option>
                        <option value="gzip+deflate1950">gzip + deflate1950</option>
                        <option value="deflate1950+gzip">deflate1950+gzip</option>
                        <option value="deflate1951+gzip">deflate1951+gzip</option>
                        <option value="deflate1950">deflate (RFC1950)</option>
                        <option value="deflate1951">deflate (RFC1951)</option>
                        <option value="compress">compress (Unix compress)</option>
                        <option value="bzip">bzip</option>
                        <option value="base64">base64 (browsers are unlikely to decode this!)</option>
                </select>
        </td>
</tr>
<tr>
        <td>
                Content-Length:
        </td>
        <td>
                <input type="text" name="contentlength" />(Leave blank for auto)
        </td>
</tr>
<tr>
	<td>
		Chunked Encoding (will remove any set 'Content-Length' heading):
	</td>
	<td>
		<input type="checkbox" name="chunked" value="chunked"/>
	</td>
</tr>
<tr>
        <td>
                Filename:
        </td>
        <td>
                <input type="text" name="filename" value="helloworld.exe" />(Leave blank for no 'Content-Disposition' header)
        </td>
</tr>
<tr>
        <td>
                File type to download:
        </td>
        <td>
        	<input type="radio" name="type" checked value="exe">Win32 Exe</input>
        	<input type="radio" name="type" value="txt">Plain-text file</input>
	</td>
</tr>
<tr><td></td><td><input type="submit" name="Download" value="Download"/></td></tr>
</table>
</form>
<h2>Common Test Cases:</h2>
<p><i>NB: All of these test cases should pass on a recent version of Chrome.</i><p>
<ul>
	<li><a href='./responsecoder.php?responsecode=206&status=OK'>Executable download with a '206 OK' response code, all other headers present and correct</a></li>
	<li><a href='./responsecoder.php?contenttype=text%2Fhtml'>Executable download with a 'Content-Type' specified as 'text/html'</a></li>
	<li><a href='./responsecoder.php?contentencodingheading1=gzip&contentencoding=gzip'>Executable download with a 'Content-Encoding' of 'gzip'</a></li>
	<li><a href='./responsecoder.php?contentencodingheading1=deflate&contentencoding=deflate1951'>Executable download with a 'Content-Encoding of 'deflate' (RFC1951 - IE supported)</a></li>
	<li><a href='./responsecoder.php?contentlength=-1'>Executable download with a 'Content-Length' of '-1'</a></li>
	<li><a href='./responsecoder.php?contentencodingheading1=gzip&contentencodingheading2=gzip&contentencoding=2xgzip'>Executable download with two rounds of gzip encoding and two seperate gzip headers</a></li>
	<li><a href='./responsecoder.php?contentencodingheading1=gzip%2c+gzip&contentencoding=2xgzip'>Executable download with two rounds of gzip encoding and one 'gzip, gzip' header</a></li>
	<li><a href='./responsecoder.php?contentencodingheading1=gzip%2c+deflate&contentencoding=gzip%2bdeflate1951'>Executable download with gzip and deflate (RFC1951 encoding) compression and only one 'Content-Encoding' heading set with 'gzip, deflate'</a></li>
	<li><a href='./responsecoder.php?chunked=true'>Executable download with chunked encoding and no compression</a></li>
	<li><a href='./responsecoder.php?chunked=true&contentencodingheading1=gzip&contentencoding=gzip'>Executable download with chunked encoding and gzip compression</a></li>
	<li><a href='./sdch/'>Shared Dictionary Compression over HTTP (SDCH) Encoding (currently Chrome only)</a></li>
</ul>
</body>
