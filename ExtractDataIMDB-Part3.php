<?php 
// Name: Srujana
// Date: 04/17/2024
require_once 'simple_html_dom.php';
require_once 'db_config.php';
function getIMDbData($url)
{
    $dom = file_get_html($url);
    if (!$dom) {
        return false;
    }
    $data = [];
    $data['title'] = $dom->find('title', 0)->plaintext;
    $data['description'] = $dom->find("meta[name=description]", 0)->content;
    $data['links'] = [];
    foreach ($dom->find('a') as $link) {
        if (!empty($link->href)) {
            $data['links'][] = $link->href;
        }
    }
    $dom->clear();
    unset($dom);
    return $data;
}
function saveLinksToDatabase($conn, $links)
{
    $stmt = $conn->prepare("INSERT INTO ImdbLinks (url) VALUES (?)");
    foreach ($links as $link) {
        $stmt->bind_param("s", $link);
        $stmt->execute();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $url = $_POST['search'];
    $imdbData = getIMDbData($url);
    if ($imdbData) {
        saveLinksToDatabase($conn, $imdbData['links']);
        echo "<h1>Title: {$imdbData['title']}</h1>";
        echo "<p>Description: {$imdbData['description']}</p>";
        echo "<h2>All Links:</h2>";
        foreach ($imdbData['links'] as $link) {
            echo "<p><a href='$link'>$link</a></p>";
        }
    } else {
        echo "Error: Unable to retrieve IMDb data or invalid URL.";
    }
} ?>
<!DOCTYPE html>
<html>

<head>
    <title>IMDb Data Extractor</title>
</head>

<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> <label for="search">IMDb
            URL:</label> <input type="text" id="search" name="search"> <input type="submit" value="Extract Data">
    </form>
</body>

</html>