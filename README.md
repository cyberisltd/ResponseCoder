ResponseCoder
=============

* Author: geoff.jones@cyberis.co.uk
* Copyright: Cyberis Limited 2013
* License: GPLv3 (See LICENSE)

A server-side PHP script to manipulate HTTP Response Headers, designed to identify weaknesses in perimeter filtering devices (e.g. web proxies and next generation firewalls)

Description
-----------

ResponseCoder is designed to allow you to easily manipulate HTTP response headers, specifically to identify weaknesses in perimeter filtering appliances such as web proxies and next generation firewalls. It’s an opensource PHP script that formulates HTTP response headers on-the-fly, allowing the operator to form specific test cases as necessary.

The test cases are centred around the download of a Win32 executable - a common file format that is often blocked at the perimeter to prevent unauthorised code and malware from entering the corporate environment. To test the download of ‘permissible’ files, a text file can also be specified, allowing you to concentrate on discovering the oddities of any intermediary filtering devices.

Obviously manipulation of such HTTP response headers may lead to unexpected results in your browser - redirect codes, client errors and server error codes are typical examples that may (or should) cause a browser to ignore the body of a response. Try it for yourself - a 201 in Internet Explorer for example will cause it to ignore the specified filename in the ‘Content-Disposition’ header, whilst Chrome will accept that just fine.

There are numerous tests you can conduct with HTTP response headers (take a look over at <a href="http://greenbytes.de/tech/tc2231/">http://greenbytes.de/tech/tc2231/</a> for some ideas), and this script certainly doesn't expose all possible scenarios. However it does provide a quick testing framework which is easier to use and more intuitive than NetCat.

Dependencies
------------
Apache and PHP are required (other web servers that support PHP may work - but you'll need to check how the server handles tampering with response headers). It is recommended that you edit php.ini to remove any default 'Content-Type' header - set *default_mimetype* to null.

Issues
------
Kindly report all issues via https://github.com/cyberisltd/ResponseCoder/issues
