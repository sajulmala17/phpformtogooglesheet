<?php
// Load Google API client library
require_once __DIR__ . "/vendor/autoload.php";

// Define access credentials
$client = new Google_Client();
$client->setApplicationName("MyApplicationName");
$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
$client->setAuthConfig(__DIR__ . "/credentials.json");
$client->setAccessType("offline");
$client->setPrompt("select_account consent");

// Connect to Google Sheets API
$service = new Google_Service_Sheets($client);

// Define spreadsheet ID and sheet name
$spreadsheetId = "MySheetID";
$sheetName = "Sheet1";

// Get submitted data
$id = $_POST["id"];
$name = $_POST["name"];
$class = $_POST["class"];
$attandancetype = $_POST["att"];

// Get current date
$timestamp = time();
$currenttimestamp = date("Y-m-d H:i:s", $timestamp);
$date = date("d/m/Y");

// Get subject from hidden input
$createdby = $_POST["createdby"];
$subject= $_POST["subject"];

// Build data array for Google Sheets API
$data = [];
foreach ($nis as $key => $value) {
    $row = [
        $currenttimestamp,
        $value,
        $name[$key],
        $class,
        $attandancetype[$value],
        $date,
        $subject,
        $createdby
    ];
    $data[] = $row;
}

// Define range to write data to
$range = $sheetName . "!A2:H2" . (count($data) + 1);

// Build value range object for Google Sheets API
$valueRange = new Google_Service_Sheets_ValueRange();
$valueRange->setValues(["values" => $data]);

// Write data to Google Sheets
$result = $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, ["valueInputOption" => "USER_ENTERED"]);

// Check for errors
if ($result->getUpdates()->getUpdatedCells() > 0) {
    echo "Record has been sent to Google Sheets.";
} else {
    echo "Have an error when send data to Google Sheets.";
}
?>
