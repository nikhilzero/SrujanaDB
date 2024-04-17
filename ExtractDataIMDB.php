<?php
// Student Name: Srujana
// Date: 04/17/2024
require_once 'simple_html_dom.php';
$imdb_url = 'https://www.imdb.com/title/tt0898266/';
$dom = file_get_html($imdb_url);
if (!$dom) {
    die("Error: Unable to retrieve IMDB page.");
}
$page_title = $dom->find('title', 0)->plaintext;
echo "<h1>Title: $page_title</h1>";
$meta_description = $dom->find("meta[name=description]", 0)->content;
echo "<p>Description: $meta_description</p>";
$meta_keywords = $dom->find("meta[name=keywords]", 0)->content;
echo "<p>Keywords: $meta_keywords</p>";
echo "<h2>Links:</h2>";
foreach ($dom->find('a') as $link) {
    echo "<p><a href='" . $link->href . "'>" . $link->plaintext . "</a></p>";
}
echo "<h2>Images:</h2>";
foreach ($dom->find('img') as $image) {
    echo "<img src='" . $image->src . "' alt='" . $image->alt . "'><br>";
}
$rating_elements = $dom->find('div[data-testid="hero-rating-bar__aggregate-rating"]');
if ($rating_elements) {
    foreach ($rating_elements as $element) {
        if (strpos($element->innertext, 'IMDb RATING') !== false) {
            $rating_span = $element->find('span.sc-bde20123-1', 0);
            if ($rating_span) {
                $imdb_rating = trim($rating_span->plaintext);
                echo "<p>IMDb Rating: $imdb_rating</p>";
                break;
            }
        }
    }
} else {
    echo "<p>IMDb Rating not found</p>";
}
$dom->clear();
unset($dom);