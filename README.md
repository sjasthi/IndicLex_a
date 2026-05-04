# IndicLex
### Management of Dictionaries
Project Overview

IndicLex helps users manage dictionaries, upload dictionary data, search entries, and browse dictionary information in a simple web interface.

### Core Features
- Dictionary catalog and entry management
- File upload for dictionary imports
- Search interface with match modes
- REST API search endpoint
- Autocomplete search on the public search page
- User login and admin dashboard
- Export support for dictionary data
- Theme and preference support


- `GET /api/search`
- Support for:
  - `q` — search term
  - `dict` — dictionary ID filter
  - `mode` — `exact`, `prefix`, `suffix`, `substring`
- Proper JSON responses
- HTTP status codes:
  - `200` — success
  - `400` — bad request
  - `404` — no results
  - `500` — server error
- Public search page autocomplete using jQuery AJAX
- Word Length Matching link to an external puzzle resource
- Clean API routing with `.htaccess`




## Installation

1. Clone the repository.
2. Import the SQL schema from the `sql/` folder into your database.
3. Update database credentials in the project DB connection file.
4. Place the project in your web server root.
5. Make sure Apache rewrite support is enabled.
6. Open the project in your browser.

---

## Project Structure

### indiclex_a
This main directory is for the public-facing webpages on the website, such as index.php, search.php, etc.

### /api/
The API directory holds files used for API endpoints.

### /includes/
This directory holds helper php pages, like the header, navbar, footer, and database connection.

### /uploads/
This directory should not contain any files, and is used for uploading .xlsx files into the database. This folder should be created automatically if it is not present upon uploading. Files in this folder are deleted when they are successfully added to the database.

### /vendor/
This directory contains code used by the PHPSpreadsheet library, and is necessary to upload dictionaries using the upload.php page.

## Uploading Files
Files can be uploaded via the upload.php page, visible in the navbar. It accepts .xlsx files, in the following format:
|Lang 1|Lang 2|Lang 3(optional)|

## Database

The project uses a MySQL database with tables such as:

- `dictionaries`
- `dictionary_entries`
- `users`
- `preferences`

The exact schema is stored in the `sql/` folder.

---

## API Documentation

### Endpoint
`GET /api/search`

### Query Parameters
- `q` — required search term
- `dict` — optional dictionary ID filter
- `mode` — optional search mode
- `limit` — optional max results, default `20`, max `50`

### Supported Modes
- `exact`
- `prefix`
- `suffix`
- `substring`

### Example Requests
```text
/api/search?q=test
/api/search?q=ab&mode=prefix
/api/search?q=word&dict=1&mode=substring
/api/search?q=abc&dict=2&mode=exact&limit=10
```

### Example Success Response
```json
{
  "status": "success",
  "query": "test",
  "dict": 0,
  "mode": "exact",
  "count": 2,
  "results": [
    {
      "entry_id": 1,
      "dict_id": 3,
      "dict_name": "English-Hausa Dictionary",
      "dict_identifier": "eng-hau",
      "lang_1": "test",
      "lang_2": "aji",
      "lang_3": null
    }
  ]
}
```

### Example Error Responses

#### Missing `q`
```json
{
  "status": "error",
  "message": "Query parameter 'q' is required."
}
```

#### No Results
```json
{
  "status": "error",
  "message": "No results found for 'test' in 'exact' mode."
}
```

---

## Search Page Features

The public search page supports:
- Search input
- Dictionary filter
- Search modes
- Live autocomplete suggestions
- Result table rendering from AJAX calls

---

## Word Length Matching

The search page includes a button that links to the external Telugupuzzles word-length matching tool.

---

## Clean Routing

The `.htaccess` file maps clean requests like:

```text
/api/search?q=test
```

to the PHP API handler.

---

## Troubleshooting

- If the API returns `500`, check database credentials and PHP errors.
- If clean URLs do not work, confirm `mod_rewrite` is enabled.
- If autocomplete does not show, check browser console and network requests.
- If results are missing, verify that the requested dictionary ID exists.

---
