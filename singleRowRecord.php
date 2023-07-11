<?php
// Membutuhkan autoload.php dan credentials.json dari Google API Client untuk dapat digunakan
require_once 'gapi/vendor/autoload.php';

// Inisialisasi client Google Sheets API
$client = new Google_Client();
$client->setApplicationName('SheetPHP');
$client->setDeveloperKey("f561a5e633a447679094e3e23a7d7470c234a60e");
$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
$client->setAuthConfig('credentials.json');

// Membuat request API untuk mengirim data ke Google Sheet
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Mendapatkan data dari form
  $nm_mapel=$_POST['nm_mapel'];
  $nm_kelas=$_POST['nm_kelas'];
  $tanggal=$_POST['tanggal'];
  $oleh=$_POST['oleh'];
  $nm_siswa=$_POST['nm_siswa'];

  // Mengirim data ke Google Sheet
  $service = new Google_Service_Sheets($client);
  $spreadsheetId = "1v6LpXMY1o0CmuExmLGiMFn0qcIJ0nNvkk4f_qznbIdI";
  $range = "Sheet1!A2:D2";
  $values = [[$nama, $jenis_kelamin, $umur, $tanggal_lahir]];
  $body = new Google_Service_Sheets_ValueRange(['values' => $values]);
  $params = ['valueInputOption' => 'RAW'];
  $result = $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);

  // Mengirimkan feedback berhasil atau tidak
  if ($result->getUpdates()->getUpdatedRows() > 0) {
    echo "Data berhasil dikirim ke Google Sheet";
  } else {
    echo "Data gagal dikirim ke Google Sheet";
  }
}

?>
