<?php
$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "indiclex_a_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_query = $_POST['search_query'];
    // Sanitize the input to prevent SQL injection
    $search_query = mysqli_real_escape_string($conn, $search_query);

  
    $sql = "SELECT * FROM 'dictionary'";
    $result = $conn->query($sql);
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
<title>Search Page</title>
</head>
<body>

<form action="search.php" method="post">
    <input type="text" name="search_query" placeholder="Enter search term">
    <button type="submit">Search</button>
</form>


</body>
</html>
