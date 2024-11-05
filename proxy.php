<?PHP
// Disable sending error messages to the browser
ini_set('display_errors', '0');

// Get the config from config.php
require_once 'config.php';

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
