<?php
// These are the URL parameters used by the form to initiate the download
// Set these to match what you use in the form and on the ScreenConnect server
// get("<parameter name>", "<default>");
$Name = get("name", "");
$Custom1 = get("client", "Web");
$Custom2 = get("type", "");
$Custom3 = get("location", "");
$Custom4 = get("users", "");
$Custom5 = get("notes", "");
$Custom6 = "";
$Custom7 = "";
$Custom8 = "";
$Extension = get("ext", "exe"); // The name can be changed to match the form but the extension must be one of the following: exe, msi, pkg, deb

// This is your URL normally used to download the file from the ScreenConnect server
// This URL needs to be accessible by the webserver running this script but not by the user trying to download the client
// This allows users to access the client download use this script while the ScreenConnect server is behind a firewall
$AccessURL = "http://sc-server/Bin/ScreenConnect.ClientSetup.exe?e=Access&y=Guest&c=&c=&c=&c=&c=&c=&c=&c=";

// Set a filename for the download
$FileName = "RemoteSupport.$Extension";