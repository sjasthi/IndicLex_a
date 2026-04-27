<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<body class="<?php echo $bodyClass ?>">
<main class="container py-4">
    <section class="hero-section text-center d-flex align-items-center">
        <div class="col-lg-6 justify-content-center container">
            <h1 class="display-4 fw-bold">Upload Dictionary</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <label class="form-control" for="upload-file">Dictionary File (.xlsx)</label>
                <input class="form-control" id="upload-file" type="file" name="fileToUpload" accept=".xlsx" required>
                <br>
                <label class="form-control" for="upload-name">Dictionary Name</label>
                <input class="form-control" id="upload-name" type="text" value="" name="dictName" placeholder="Enter dictionary name" required>
                <br>
                <label class="form-control" for="upload-desc">Dictionary Description</label>
                <input class="form-control" id="upload-desc" type="text" value="" name="dictDesc" placeholder="Enter dictionary description">
                <br>
                <label class="form-control" for="upload-lang1">Language 1</label>
                <input class="form-control" id="upload-lang1" type="text" value="" name="langOne" placeholder="Enter first language" required>
                <label class="form-control" for="upload-lang2">Language 2</label>
                <input class="form-control" id="upload-lang2" type="text" value="" name="langTwo" placeholder="Enter second language" required>
                <label class="form-control" for="upload-lang3">Language 3</label>
                <input class="form-control" id="upload-lang3" type="text" value="" name="langThree" placeholder="Enter third language">
                <br>
                <input class="btn btn-primary btn-lg" type="submit" value="Upload" name="submit">
            </form>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/theme.js"></script>

</body>
</html>

<?php
if(isset($_POST["submit"])) {
    // PhpSpreadsheet Library
    require 'vendor/autoload.php';

    // Variables to store file
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // create exports folder if it doesn't exist
    if (is_dir($target_dir)) {
        echo "uploads directory already exists.<br>";
    } else {
        mkdir($target_dir);
    }

    // Check if file already exists
    if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 10_000_000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
    } 

    // Allow certain file formats
    if($imageFileType != "xlsx") {
    echo "Sorry, only .xlsx files are allowed.";
    $uploadOk = 0;
    } 

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
        // If file uploaded, process the file for SQL upload
        processFile($target_file);
        // delete file after processing
        unlink($target_file);
        // Go to index
        //header("Location: index.php");
        echo "<script type='text/javascript'>location.href = 'index.php';</script>";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    }
}

function processFile($target_file) {

    echo "Processing file.<br>";

    // Get spreadsheet object
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($target_file);

    echo "Created Spreadsheet Object.<br>";

    // Get active worksheet
    $worksheet = $spreadsheet->getActiveSheet();

    echo "Got active worksheet.<br>";

    // Dictionary variables
    $dictName = $_POST["dictName"];
    $langOne = $_POST["langOne"];
    $langTwo = $_POST["langTwo"];
    $langThree = $_POST["langThree"];
    $dictDesc = $_POST["dictDesc"];
    $dictIdentifier = getDictIdentifier($langOne, $langTwo, $langThree);
    $dictType = getDictType($langThree);

    echo "Got POST Varibles.<br>";

    require_once __DIR__ . '/includes/db_mysqli.php';
    
    // Check if dictionary is already in database (by dict_identifier)
    $sql = "SELECT dict_id FROM dictionaries WHERE dict_identifier='$dictIdentifier'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $dictID = $result->fetch_assoc()['dict_id'];
        echo "Dicitonary already in database: ".$dictID.".";
        $dictIdentifier = $dictIdentifier.$result->num_rows;
        echo "New Dict ID: ".$dictIdentifier;
    }  
    
    // Add dictionary to database
    if ($langThree == null) { // if bilingual
        $sql = "INSERT INTO dictionaries (dict_identifier, name, type, source_lang_1, source_lang_2, description) 
            VALUES ('$dictIdentifier', '$dictName', '$dictType', '$langOne', '$langTwo', '$dictDesc')";
    } else { // if trilingual
        $sql = "INSERT INTO dictionaries (dict_identifier, name, type, source_lang_1, source_lang_2, source_lang_3, description) 
            VALUES ('$dictIdentifier', '$dictName', '$dictType', '$langOne', '$langTwo', '$langThree', '$dictDesc')";
    }
    if ($conn->query($sql) === TRUE) {
        echo "New dictionary created successfully";
        // Get id of new dictionary
        $sql = "SELECT dict_id FROM dictionaries WHERE dict_identifier='$dictIdentifier'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $dictID = $result->fetch_assoc()['dict_id'];
            echo "Created dictionary with ID: ".$dictID.".";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    
    
    // get each row of the spreadsheet
    foreach ($worksheet->getRowIterator() as $row) {
        echo '<p>';
        $entryData = array();
        // get each cell of the current row 
        foreach ($row->getCellIterator() as $cell) {
            $entryData[] = $cell->getValue();
        }
        $entryLangOne = $entryData[0];
        $entryLangTwo = $entryData[1];
        
        // check if entry is already in database
        $sql = "SELECT lang_1 FROM dictionary_entries WHERE dict_id='$dictID' AND lang_1='$entryLangOne'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "Entry \"".$entryLangOne."\" already in dictionary: ".$dictID.".";
        } else {
            // add entry
            if ($langThree == null) { // if bilingual
                $sql = "INSERT INTO dictionary_entries (dict_id, lang_1, lang_2)
                        VALUES ('$dictID', '$entryLangOne', '$entryLangTwo')";
            } else { // if trilingual
                $entryLangThree = $entryData[2];
                $sql = "INSERT INTO dictionary_entries (dict_id, lang_1, lang_2, lang_3)
                        VALUES ('$dictID', '$entryLangOne', '$entryLangTwo', '$entryLangThree')"; 
            }
            if ($conn->query($sql) === TRUE) {
                echo "New entry created successfully: '$dictID', '$entryLangOne'.";
            }
        }
        echo "</p>";
    }
}

function getDictIdentifier($langOne, $langTwo, $langThree) {
    // returns the correct format for the dict_identifier column in SQL database
    $output = strtolower($langOne)."-".strtolower($langTwo);
    if ($langThree != null) { $output = $output."-".strtolower($langThree); }
    return $output;
}

function getDictType($langThree) {
    return ($langThree == null) ? 'bilingual' : 'trilingual';
}
?>