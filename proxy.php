<?PHP
// Disable sending error messages to the browser
ini_set('display_errors', '0');

// Get the config from config.php
require_once 'config.php';

// Limit the possible values for Extension
if (!in_array($Extension, array("exe", "msi", "pkg", "deb"))) {
    http_response_code(500);
    exit("Error: Invalid extension");
}

// Replace .exe? in $AccessURL with the extension from $Extension
$AccessURL = preg_replace('/\.exe\?/', ".$Extension?", $AccessURL);

// Remove all the 'c' parameters in URL
$AccessURL = preg_replace('/&c=[^&]*/', '', $AccessURL);

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
header("Content-Transfer-Encoding: binary");

// Send the file to the browser
echo $File;

// Check GET variable and return default if not set
function get($value, $default)
{
    if (empty($_GET[$value])) {
        return $default;
    } else {
        $string = $_GET[$value];

        // Limit the length of the string
        $string = substr($string, 0, 30);
        // Remove backticks, quotes, semi-colons, brackets, and braces
        $string = preg_replace('/[`"\'\(\)\[\]\{\};]/', '', $string);
        // Remove leading and trailing whitespace
        $string = trim($string);
        // Encode the string for use in a URL
        $string = urlencode($string);

        return $string;
    }
}
