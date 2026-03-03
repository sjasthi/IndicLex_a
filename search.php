<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

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

        <!-- Placeholder Results Section -->
        <div class="mt-5 text-center text-muted">
            <h5>Results will appear here</h5>
            <p class="small">Search functionality will be connected to the database later.</p>
        </div>

    </div>
</main>

<?php include 'includes/footer.php'; ?>