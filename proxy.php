<?PHP
// Disable sending error messages to the browser
ini_set('display_errors', '0');

// These are the URL parameters used by the form to initiate the download
// Set these to match what you use in the form and on the ScreenConnect server
// get("<parameter name>", "<default>");
$Name = get("name", "");
$Custom1 = get("client", "WebProxy");
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

// == No need to edit below this line ==

// Limit the possible values for Extension
if (!in_array($Extension, array("exe", "msi", "pkg", "deb"))) {
    exit("Error: Invalid input");
}

// Remove all the 'c' parameters in URL
$AccessURL = preg_replace('/&c=[^&]*/', '', $AccessURL);

// Replace .exe? in $AccessURL with the extension from $Extension
$AccessURL = preg_replace('/\.exe\?/', ".$Extension?", $AccessURL);

// Add parameters to URL
$AccessURL .= "&t=$Name&c=$Custom1&c=$Custom2&c=$Custom3&c=$Custom4&c=$Custom5&c=$Custom6&c=$Custom7&c=$Custom8";

// Download the file from the ScreenConnect server
ini_set('default_socket_timeout', 6);
$File = file_get_contents($AccessURL);

// If the file could not be downloaded then return a 500 error
if ($File === false) {
    http_response_code(500);
    exit("Error: Could not download file");
}

// If headers have already been sent then an error occurred before this point
If (headers_sent()) { exit("Error: Headers already sent"); }

// Set headers so the browser will treat this connection as a file download
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=$FileName");
header("Content-Length: " . strlen($File));

// Send the file to the browser
echo $File;

// Check GET variable and return default if not set
function get($var, $def)
{
    if (empty($_GET[$var])) {
        return $def;
    } else {
        $string = $_GET[$var];

        // Do some things to sanitize the input
        $string = substr($string, 0, 30);
        $string = urlencode($string);

        return $string;
    }
}
