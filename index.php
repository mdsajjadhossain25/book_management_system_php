<?php
// Define a function to load books from a JSON file.
function loadBooks() {
    $books = [];
    $booksJson = file_get_contents("books.json");
    if (!empty($booksJson)) {
        $books = json_decode($booksJson, true);
    }
    return $books;
}

// Handle the search request.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    searchBook();
}

// Define a function to search for books in the array.
function searchBook() {
    $searchTerm = strtolower($_POST["search"]);
    $books = loadBooks();
    $searchResults = [];

    if (!empty($searchTerm)) {
        foreach ($books as $book) {
            if (stripos($book["name"], $searchTerm) !== false) {
                $searchResults[] = $book;
            }
        }
    }

    // Return the search results as JSON
    header('Content-Type: application/json');
    echo json_encode($searchResults);
    exit; // Terminate the script after sending the response.
}

// Handle the book form submission.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["book-name"])) {
    $bookName = $_POST["book-name"];
    $bookDetails = $_POST["book-details"];
    $bookWriter = $_POST["book-writer"];

    $newBook = [
        "name" => $bookName,
        "details" => $bookDetails,
        "writer" => $bookWriter
    ];

    $books = loadBooks();
    $books[] = $newBook;
    file_put_contents("books.json", json_encode($books));
}

// Load books for display.
$books = loadBooks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            margin-bottom: 20px;
        }

        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        .container {
            margin-top: 20px;
        }

        .card-header {
            background-color: #183758;
            color: #fff;
            font-size: 1rem;
        }

        .book-link {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card" id="search-container">
        <div class="card-header">
            <h5 class="text-center" >Book Management System</h5>
        </div>
    </div>

    <!-- Search Section -->
    <div class="card" id="search-container">
        <div class="card-header">
            <h5>Search Books</h5>
        </div>
        <div class="card-body">
            <form id="search-form" method="post">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" id="search" placeholder="Search by Book Name">
                </div>
                <br>
                <div class="input-group">
                    <div class="input-group-append">
                        <button class="btn btn-primary" name="search-button" type="submit">Search</button>
                    </div>
                </div>
            </form>
            <br>
        </div>
    </div>

    <div class="card" id="search-results" style="display: none;">
        <div class="card-header">
            <h5>Search Results</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Book Name</th>
                    <th scope="col">Book Author</th>
                </tr>
                </thead>
                <tbody id="search-results-table">
                    <!-- Search results will be displayed here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Book Form Section -->
    <div class="card">
        <div class="card-header">
            <h5>Add a New Book</h5>
        </div>
        <div class="card-body">
            <form id="book-form" method="post">
                <div class="form-group">
                    <label for="book-name">Book Name:</label>
                    <input type="text" class="form-control" name="book-name" id="book-name" required>
                </div>
                <div class="form-group">
                    <label for="book-details">Book Details:</label>
                    <textarea class="form-control" name="book-details" id="book-details" required></textarea>
                </div>
                <div class="form-group">
                    <label for="book-writer">Book Author:</label>
                    <input type="text" class="form-control" name="book-writer" id="book-writer" required>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Add Book</button>
            </form>
        </div>
    </div>

    <!-- All Books Table Section -->
    <div class="card">
        <div class="card-header">
            <h5>All Books</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Book Name</th>
                    <th scope="col">Book Author</th>
                </tr>
                </thead>
                <tbody id="book-list">
                    <!-- Display books from the PHP array -->
                    <?php foreach ($books as $book) { ?>
                        <tr>
                            <td class="book-link" data-book='<?php echo json_encode($book); ?>'><?php echo $book["name"]; ?></td>
                            <td><?php echo $book["writer"]; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // JavaScript code for handling form submission and searching
    // ...

    // Define an event listener for the search form
    document.getElementById("search-form").addEventListener("submit", function (e) {
        e.preventDefault();
        searchBook();
    });

    // Define an event listener for book links
    document.querySelectorAll('.book-link').forEach(function (element) {
        element.addEventListener('click', function () {
            var bookData = JSON.parse(this.getAttribute('data-book'));
            showBookDetails(bookData);
        });
    });

    function searchBook() {
        var searchTerm = document.getElementById("search").value;
        var searchResults = document.getElementById("search-results");
        var searchResultsTable = document.getElementById("search-results-table");

        if (searchTerm) {
            // Make an AJAX request to the PHP script
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var results = JSON.parse(xhr.responseText);
                    searchResults.style.display = "block";
                    searchResultsTable.innerHTML = "";

                    // Update the table with search results
                    results.forEach(function (book) {
                        var row = searchResultsTable.insertRow();
                        var nameCell = row.insertCell(0);
                        var writerCell = row.insertCell(1);
                        nameCell.innerText = book.name;
                        writerCell.innerText = book.writer;
                    });
                }
            };

            // Send a POST request to the PHP script
            xhr.open("POST", window.location.href, true); // Use the current page URL
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("search=" + searchTerm);
        } else {
            searchResults.style.display = "none";
        }
    }

    function showBookDetails(bookData) {
        // Create a new URL with book details
        var url = 'book-details.php?name=' + encodeURIComponent(bookData.name) +
            '&details=' + encodeURIComponent(bookData.details) +
            '&author=' + encodeURIComponent(bookData.writer);
        // Open the URL in a new window or tab
        window.open(url, '_blank');
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
