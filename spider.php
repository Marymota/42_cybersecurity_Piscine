<?php

require __DIR__ . '/utils/settings.php';
require __DIR__ . '/utils/request.php';
require __DIR__ . '/utils/printASCII.php';

// Call function
spider($argc, $argv);

function spider($argc, $argv) {

    // Check there is enough arguments
    if ($argc < 2) {
        echo "Not enough arguments\n";
        exit;
    }
    
    // define settings (flags [-rlp: recursive, level, path, url)
    $sets = settings($argc, $argv);
    $url = isset($sets['url']) ? $sets['url'] : exit ("No URL defined\n");

    $response = curl_request($url);

    //  Parse HTML 
    $document = new DOMDocument();          // install php-xml
    if($response) {
        libxml_use_internal_errors(true);   //  Disable libxml errors and allow user to fetch error information as needed 
        $document->loadHTML($response);     //  Load HTML from string
        libxml_clear_errors();              //  Clear libxml error buffer
    }

    crawl($url, $document, $sets, $sets['level']);

}

function crawl ($url, $document, $sets, $level) {
    // $url = "https://scrapingcourse.com/ecommerce/";
    $index = 0;

    echo "Scrapping page: $url\n";

    // Retrieve the HTML from page
    /* (cURL) libcurl - Allows connection and communication with many different types of servers and protocols.
        -> https:www/php.net/manual/en/intro.curl.php */

    // Get image Links
    $images = tagsFinder($document, "img");
    $images = array_values($images);

    for ($i = 0; $i < sizeof($images); $i++) {
        // If the URL is relative, make it absolute
        if (!filter_var($images[$i], FILTER_VALIDATE_URL)) {
            $images[$i] = rtrim($url, '/') . '/' . ltrim($images[$i], '/');
        }

        // Get images data
        $data = curl_request($images[$i]);

        // Create directory for downloads
        $path = $sets['path'];
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        file_put_contents("$path/image_$level" . "_$i", $data);
        echo "$i crawl level $level...\n";
    }

    // Recursively crawl links
    if ($sets['recursive'] && $level > 0) {
        $links = tagsFinder($document, 'a');
        $links = array_values($links);

        foreach ($links as $link) {
            if (filter_var($link, FILTER_VALIDATE_URL)) {
                $response = curl_request($link);
                $document = new DOMDocument();
                libxml_use_internal_errors(true);
                $document->loadHTML($response);
                libxml_clear_errors();
            
                // Recursively crawl next level
                if ($level - 1 > 0)
                    crawl($link, $document, $sets, $level - 1);
                else
                    break;
            }
        }
    }   
}
 




// Resources:
// https://www.zenrows.com/blog/web-scraping-php
// simple_html_dom.php (HTML parsing)
// https://scrapingant.com/blog/download-image-php
// https://www.geekality.net/blog/php-how-to-get-all-images-from-an-html-page


?>