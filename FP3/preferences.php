<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<title>Preferences Page</title>
</head>

<?php include 'includes/navbar.php'; ?>

<body class="bg-light">

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-body p-4">

            <h1 class="text-center mb-4">Preferences Page</h1>

            <form>

                <!-- Language & Region -->
                <h5 class="mb-3">Language & Region</h5>

                <div class="mb-3">
                    <label class="form-label">Interface Language</label>
                    <select class="form-select" name="interface_language">
                        <option value="" disabled selected>Select language</option>
                        <option value="en-US">English (US)</option>
                        <option value="hi">हिन्दी (Hindi)</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Dictionary Variant:</label>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="variant" value="american" id="american">
                        <label class="form-check-label" for="american">
                            American English
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="variant" value="hindi" id="hindi">
                        <label class="form-check-label" for="hindi">
                           हिन्(Hindi)
                        </label> 
                    </div>

                </div>

                <hr>

                <!-- Definitions -->
                <h5 class="mb-3">Definitions & Content</h5>

                <div class="mb-3">
                    <label class="form-label">Definition Style:</label>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="definition_style" value="simple" id="simple">
                        <label class="form-check-label" for="concise">Simple </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="definition_style" value="standard" id="standard">
                        <label class="form-check-label" for="standard">Standard</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="definition_style" value="detailed" id="detailed">
                        <label class="form-check-label" for="detailed">Full detailed</label>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="examples" id="examples">
                        <label class="form-check-label" for="examples">
                            Example Sentences
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="audio" id="audio">
                        <label class="form-check-label" for="audio">
                            Audio Pronunciation
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="etymology" id="etymology">
                        <label class="form-check-label" for="etymology">
                            Word Origin (Etymology)
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="synonyms" id="synonyms">
                        <label class="form-check-label" for="synonyms">
                            Synonyms & Antonyms
                        </label>
                    </div>
                </div>

                <hr>

                <!-- Accessibility -->
                <h5 class="mb-3">Accessibility</h5>

                <div class="mb-3">
                    <label class="form-label">Font Size</label>
                    <select class="form-select" name="font_size">
                        <option value="small">Small</option>
                        <option value="medium" selected>Medium</option>
                        <option value="large">Large</option>
                    </select>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="dark_mode" id="dark_mode">
                        <label class="form-check-label" for="dark_mode">
                            Dark Mode
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="light_mode" id="light_modet">
                        <label class="form-check-label" for="light_mode">
                            Light mode
                        </label>
                    </div>

                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary me-2">
                        Save Changes
                    </button>
                    <button type="button" class="btn btn-secondary">
                        Cancel
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Bootstrap JS (optional but recommended) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>