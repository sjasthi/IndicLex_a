<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IndicLex</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<main class="container py-4">
    <section class="hero-section text-center d-flex align-items-center">
        <div class="col-lg-6 justify-content-center container">
            <h1 class="display-4 fw-bold">Upload File</h1>
            <form action="process_upload.php" method="post" enctype="multipart/form-data">
                <label class="form-control" for="upload-file">Dictionary File</label>
                <input class="form-control" id="upload-file" type="file" name="fileToUpload" required>
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