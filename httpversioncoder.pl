#!/usr/bin/perl

# Basic web server responding with an executable file and logging the request to STDOUT
# Returns an incorrect HTTP version number to see how intermediary devices handle the request

# Copyright(C) 2013 Cyberis Limited
# Author: geoff.jones@cyberis.co.uk

# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.

# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.

# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

use strict;
use warnings;
use Term::ANSIColor;
use IO::Socket;


# Create socket (needs root of course)
my $sock = new IO::Socket::INET (
	LocalPort => '81',
	Proto => 'tcp',
	Listen => 10,
	ReuseAddr => 1,
) || die "Could not create socket: $!\n";

# Prepate the response
my @httpresponse = <DATA>;
my $file = "./helloworld.exe";
open (FILE, $file) or die "[ERROR] Cannot open file $file";
push (@httpresponse, <FILE>);

# Start listening
print &httpLabel . "Listening...\n\n";

my $client;
while ($client = $sock->accept()) {

	next if my $pid = fork;
	die "Error forking child process - $!\n" unless defined $pid;

	my @httprequest;

	my $line = <$client>;

	while (defined ($line) && $line ne "\r\n") {
           	push (@httprequest,$line);
		$line = <$client>;
	}
	
	# Print the request
	print &httpLabel;
	foreach (@httprequest) {
		print $_;
	}
	
	# Print the response
	foreach (@httpresponse) {
		print $client $_;
	}
	print "\n";

	# Clost the client connection
	$client->close;
	exit( fork );
}
continue
{
	close($client);
	kill CHLD => -$$;
}

sub httpLabel {
	print color 'green';
	print "[HTTP]\n";
	print color 'reset';
	return "";
}

__DATA__
HTTP/3.2 200 OK
Server: Fake-dns-web
Content-Type: appliation/octet-stream
Content-Disposition: attachment; filename="helloworld.exe";

