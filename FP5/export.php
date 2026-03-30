<?php
require_once __DIR__ . '/includes/db_mysqli.php';

// PhpSpreadsheet Library
require 'vendor/autoload.php';

// get id from url
$dictID = $_GET['dict'];
$dict_name = $_GET['name'];

// get each entry in the given dictionary
$sql = "SELECT lang_1, lang_2, lang_3 FROM dictionary_entries WHERE dict_id = '$dictID'";
$result = $conn->query($sql);

// create spreadsheet
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
// if dictionary has any entries
if ($result->num_rows > 0) {
    $i = 1;
    while ($row = $result->fetch_assoc()) {
        //echo "<p>".$row['lang_1']."\t".$row['lang_2']."\t".$row['lang_3']."</p>";
        // write each language to the row
        $spreadsheet->getActiveSheet()->setCellValue([1, $i], $row['lang_1']);
        $spreadsheet->getActiveSheet()->setCellValue([2, $i], $row['lang_2']);
        $spreadsheet->getActiveSheet()->setCellValue([3, $i], $row['lang_3']);
        $i++;
    }
}

// create exports folder if it doesn't exist
if (is_dir("exports/")) {
    //echo "exports directory already exists.";
} else {
    mkdir("exports/");
}
// create xlsx file and save to directory
$filePath = "exports/".$dict_name.".xlsx";
$writer->save("$filePath");


// DOWNLOAD FILE TO CLIENT
// Check if the file exists on the server.
if (file_exists($filePath)) {
    // Get the file's basename (e.g., 'myfile.txt')
    $fileName = basename($filePath);

    header("Content-type: " . mime_content_type($filePath));
    header("Content-disposition: attachment; filename=" . $fileName); // Force download with specified filename
    readfile($filePath);

    exit; // Stops further execution after downloading file.
} else {
// Handle the case where the file does not exist.
   echo "Error: File not found.";
}

// redirect to home page
header("Location: index.php");
?>