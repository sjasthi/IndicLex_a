<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<?php

require_once __DIR__ . '/includes/db_mysqli.php';

if(isset($_COOKIE['pref_results'])) {
	$rows = $_COOKIE['pref_results'];
}
	
if (isset($_POST['search_query']) && !empty(trim($_POST['search_query']))) {
    // Get the search query from the form input
    $search_query = "%" . trim($_POST['search_query']) . "%";
    //echo $search_query;

	// SQL query to run
	$sql = "Select dict_id, lang_1, lang_2, lang_3 FROM dictionary_entries WHERE lang_1 LIKE '$search_query' OR lang_2 LIKE '$search_query' OR lang_3 LIKE '$search_query'";
    $result = $conn->query($sql);
}
?>

<body class="<?php echo $bodyClass ?>">
<main>
    <div class="container mt-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Search Dictionaries</h2>
            <p class="text-muted">Find dictionaries by name, language, or category.</p>
        </div>
		
        <!-- Search Card -->
        <div class="card shadow-sm p-4">
            <div class="row g-3 align-items-center">

                <div class="row">
                    <form action="search.php" method="POST" action="">
                        <input type="text" name="search_query" size = "50"
                            class="form-control form-control-lg"
                            placeholder="Type Search Term...">
                        <input type="submit" name="Search" value="Search" class="btn btn-primary btn-lg">
                    </form>
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
            <table id='myTable' border='1'>
			<thead>
				<tr>
					<th>ID</th>
					<th>Language One</th>
					<th>Language Two</th>
					<th>Language Three</th>
				</tr>
			</thead>
			<tbody>
			<?php
            if (isset($_POST['search_query']) && !empty(trim($_POST['search_query']))) {
                // If at least 1 result comes back, echo each result
                if (mysqli_num_rows($result) > 0) {
                    //echo "Search Results" . "<br>";
                    // Output data of each row
					 while($row = mysqli_fetch_assoc($result)) {
                        // Results get displayed
                        echo "<tr>";
                            echo "<td>" . $row["dict_id"] . "</td>";
                            echo "<td>" . $row["lang_1"] . "</td>";
                            echo "<td>" . $row["lang_2"] . "</td>";
                            echo "<td>" . $row["lang_3"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "0 results found for your search";
                }
            } else {
                echo "<h5>Results will appear here</h5>";
            }

            ?>
			</tbody>
			</table>
        </div>
	</div>
    </div>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/2.3.7/js/dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('#myTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
    });
});
</script>
<?php include 'includes/footer.php'; ?>
<?php mysqli_close($conn); // Close the database connection ?>
</body>
</html>


<?php include 'includes/footer.php'; ?>


