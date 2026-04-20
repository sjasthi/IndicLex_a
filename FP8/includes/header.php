<?php
$theme = $_COOKIE['pref_theme'] ?? 'light';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?php echo $theme; ?>">

<head>
    <meta charset="UTF-8">
    <title>IndicLex</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script>
    (function () {
        const value = "; " + document.cookie;
        const parts = value.split("; pref_theme=");
        let theme = "light";

        if (parts.length === 2) {
            theme = parts.pop().split(";").shift();
        }

        document.documentElement.setAttribute('data-bs-theme', theme);
    })();
    </script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.css">

    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body></body>