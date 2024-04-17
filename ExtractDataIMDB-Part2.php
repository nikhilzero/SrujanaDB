<?php
// Name: Srujana
// Date: 04/17/2024
include('simple_html_dom.php');
include('db_config.php'); // Include your database configuration file

// Function to extract data from IMDb
function extractDataFromIMDb($url) {
    $html = file_get_html($url);

    if (!$html) {
        return false;
    }

    // Extracting the title and description
    $title = $html->find('title', 0)->plaintext;
    $description = $html->find("meta[name=description]", 0)->content;

    $html->clear();
    unset($html);

    return [
        'title' => $title,
        'description' => $description
    ];
}

// Check if the search query parameter exists
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $url = 'https://www.imdb.com/title/' . urlencode($searchTerm);

    // Extract data from IMDb
    $data = extractDataFromIMDb($url);

    if ($data) {
        // Database connection
        $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO ImdbDataExtract (title, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $data['title'], $data['description']);

        // Execute and close
        if ($stmt->execute()) {
            echo "New record created successfully for " . htmlspecialchars($data['title']);
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Error: Unable to access IMDb page or data not found for the search term.";
    }
} else {
    echo "No search query provided";
}
?>