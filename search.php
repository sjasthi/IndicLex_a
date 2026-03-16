<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<?php
    // Variables used to connect to the server
    $servername = "localhost";
    $username = "icsbinco_indiclex_a_db_user";
    $password = "ICS_anna";
    $dbname = "icsbinco_indiclex_a_db";

    // Establish connection to the server
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Throw error if connection fails
    if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
    }
	
if (isset($_POST['search_query']) && !empty(trim($_POST['search_query']))) {
    // Get the search query from the form input
    $search_query = "%" . trim($_POST['search_query']) . "%";
    
	// SQL query to run
	$sql = "Select dict_id, lang_1, lang_2 FROM dictionary_entries";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // Bind the parameters (s = string, bind the search query to the three placeholders)
    $stmt->bind_param("sss", $search_query, $search_query, $search_query);
    
    // Execute the query
    $stmt->execute();
    
    // Get the result set
    $result = $stmt->get_result();

    // If at least 1 result comes back, echo each result
	if ($result -> num_rows > 0) {
            echo "<h3>Search Results" . htmlspecialchars($search) . "</h3>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Telugu</th><th>English</th></tr>";
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["id"] . "</td><td>" . $row["Tegulu"] . "</td><td>" . $row["English"] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "0 results found for your search";
        }
        mysqli_close($conn); // Close the database connection
}
?>


<main>
    <div class="container mt-5">

        <div class="text-center mb-5">
            <h2 class="fw-bold">Search Dictionaries</h2>
            <p class="text-muted">Find dictionaries by name, language, or category.</p>
        </div>

        <!-- Search Card -->
        <div class="card shadow-sm p-4">
            <form>
                <div class="row g-3 align-items-center">

                    <div class="col-md-9">
                        <input type="text"
                               class="form-control form-control-lg"
                               placeholder="Type dictionary name...">
                    </div>

                    <div class="col-md-3">
                        <button type="button"
                                class="btn btn-primary btn-lg w-100">
                            Search
                        </button>
                    </div>

                </div>
            </form>
        </div>

        <label>Mode:</label>
        <select name="mode">
        <option value="exact">Exact</option>
        <option value="prefix">Prefix</option>
        <option value="suffix">Suffix</option>
        <option value="substring">Substring</option>
            </select>
            <br><br> 
    
        <!-- Placeholder Results Section -->
        <div class="mt-5 text-center text-muted">
            <h5>Results will appear here</h5>
            <p class="small">Search functionality will be connected to the database later.</p>
        </div>

    </div>
</main>


<?php include 'includes/footer.php'; ?>


