<?php
// alligator/includes/db_mysqli.php

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "indiclex_a_db"; // make sure this matches phpMyAdmin

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("DB connection failed: " . $conn->connect_error);
}

// Ensure proper character support for multilingual text
$conn->set_charset("utf8mb4");