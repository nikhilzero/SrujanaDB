<?php
// Name: Srujana
// Date: 04/17/2024
require_once 'simple_html_dom.php';
require_once 'db_config.php';
function getIMDbData($imdb_url)
{
    $dom = file_get_html($imdb_url);
    if (!$dom) {
        return false;
    }
    $title = $dom->find('title', 0)->plaintext;
    $description = $dom->find("meta[name=description]", 0)->content;
    $dom->clear();
    unset($dom);
    return ['title' => $title, 'description' => $description];
}
if (isset($_GET['search'])) {
    $search_term = $_GET['search'];
    $imdb_url = 'https://www.imdb.com/title/' . urlencode($search_term);
    $imdb_data = getIMDbData($imdb_url);
    if ($imdb_data) {
        $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        $stmt = $db->prepare("INSERT INTO ImdbDataExtract (title, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $imdb_data['title'], $imdb_data['description']);
        if ($stmt->execute()) {
            echo "Data inserted successfully for " . htmlspecialchars($imdb_data['title']);
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
        $db->close();
    } else {
        echo "Error: Unable to fetch IMDb data for the given search term.";
    }
} else {
    echo "Search query not provided.";
} ?>