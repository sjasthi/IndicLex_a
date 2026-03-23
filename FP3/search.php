<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<?php
require_once __DIR__ . '/includes/db_mysqli.php';
	
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
	if (mysqli_num_rows($result) > 0) {
            echo "Search Results" . "<br>";
            // Output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                echo "id: " . $row["dict_id"] . " Tegulu: " . $row["lang_1"] . " English: ". $row["lang_2"] . "<br>";
            }
        } else {
            echo "0 results found for your search";
        }
        mysqli_close($conn); // Close the database connection
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
                        <form method="POST" action="">
							<input type="text"
                               class="form-control form-control-lg"
                               placeholder="Type dictionary name...">
							<input type="submit" name="Search" value="Search"
						</form>
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


