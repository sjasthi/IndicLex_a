<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<?php

require_once __DIR__ . '/includes/db_mysqli.php';
	
if (isset($_POST['search_query']) && !empty(trim($_POST['search_query']))) {
    // Get the search query from the form input
    $search_query = "%" . trim($_POST['search_query']) . "%";
    //echo $search_query;

	// SQL query to run
	$sql = "Select dict_id, lang_1, lang_2, lang_3 FROM dictionary_entries WHERE lang_1 LIKE '$search_query' OR lang_2 LIKE '$search_query' OR lang_3 LIKE '$search_query'";
    $result = $conn->query($sql);
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
            <div class="row g-3 align-items-center">

                <div class="col-md-9">
                    <form action="search.php" method="POST" action="">
                        <input type="text" name="search_query"
                            class="form-control form-control-lg"
                            placeholder="Type dictionary name...">
                        <input type="submit" name="Search" value="Search">
                    </form>
                </div>

                <div class="col-md-3">
                    <button type="button"
                            class="btn btn-primary btn-lg w-100">
                        Search
                    </button>
                </div>

            </div>
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
            
            <?php
            if (isset($_POST['search_query']) && !empty(trim($_POST['search_query']))) {
                // If at least 1 result comes back, echo each result
                if (mysqli_num_rows($result) > 0) {
                    echo "Search Results" . "<br>";
                    // Output data of each row
                    while($row = mysqli_fetch_assoc($result)) {
                        // Results get displayed
                        echo "<h3>id: ".$row["dict_id"]." Language One: ".$row["lang_1"]." Language 2: ".$row["lang_2"]." Language 3: ".$row["lang_3"]."</h3><br>";
                    }
                } else {
                    echo "0 results found for your search";
                }
            } else {
                echo "<h5>Results will appear here</h5>";
            }

            mysqli_close($conn); // Close the database connection
            ?>
        </div>

    </div>
</main>


<?php include 'includes/footer.php'; ?>


