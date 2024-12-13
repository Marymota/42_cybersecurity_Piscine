<?php

require __DIR__ . '/utils/settings.php';

$spider = [
 "        (                                                                                 ",
 "         )                                                                                ",                                                                     
 "        (                                                                                 ",
 "  /\\ .-\"\"\"-.  /\\                                                                     ",
 "  //\\/  ,,,  \//\\             __________________________________________________        ",
 "  |/\| ,;;;;;, |/\|         / SSSSS  / PPPPPP  / III  / DDDD   / EEEEE  / RRRRR\          ",
 "  //\\\;-\"\"\"-;///\\          | S    /  | P  / P   | I   | D_/ D  | E      | R  / R     ",
 " //  \/   .   \/  \\         \ SSSS   | PPPPPP   | I   | D | D  | EEEE   | RRRRR          ",
 "(| ,-_| \ | / |_-, |)       /   / S  ! P____/   ! I   ! D ! D  ! E      | R \ R           ",
 "  //`__\.-.-./__`\\         | SSSSS   | P       / III  | DDDD/  | EEEEE  | R  \ R         ",
 " //    /   .   \    \      /_____/   |_/       |___/  |____/   |_____/  |_/  |_/          ",
 "(|   /   /   \   \   |)    ------------------------------------------------------         ",
 "  \\  \  /   \  /  //         -----------------------------------------------------       ",
 "   \\  \'-     -\'/  //          ---------------------------------------------------      ",
 "    \\_/         \_//                -------------------------------------------------    ",
 "",
];

// Loop through the spider array and echo each line
foreach ($spider as $line) {
    echo $line . "\n";
}

function spider($argc, $argv) {

    // Check there is enough arguments
    if ($argc < 2) {
        echo "Not enough arguments\n";
        exit;
    }
    
    // define settings (flags [-rlp: recursive, level, path, url)
    $sets = settings($argc, $argv);
    $url = isset($sets['url']) ? $sets['url'] : exit ("No URL defined\n");

    // $url = "https://scrapingcourse.com/ecommerce/";
    $index = 0;

    echo "Scrapping page: $url\n";

    // Retrieve the HTML from page
    /* (cURL) libcurl - Allows connection and communication with many different types of servers and protocols.
        -> https:www/php.net/manual/en/intro.curl.php */
    
    $request = curl_init();                                // Initiate a cURL session (install php-curl)
    curl_setopt($request, CURLOPT_URL, $url);              // set the website URL
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);   // return the response as a string
    curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);   // follow redirects
    curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);  // ignore SSL verification
    $response = curl_exec($request);                       // execute the CURL session

    if ($response === false) {                             // check for errors
        $error = curl_error($request);
        echo "cURL error: " . $error . "\n";
        exit;
    }
    // print the HTML content
    //echo $response;
    // close cURL session
    curl_close($request);

    //  Parse HTML 
    $document = new DOMDocument();          // install php-xml
    if($response) {
        libxml_use_internal_errors(true);   //  Disable libxml errors and allow user to fetch error information as needed 
        $document->loadHTML($response);     //  Load HTML from string
        libxml_clear_errors();              //  Clear libxml error buffer
    }

    // Get image Links
    $images = array();

    foreach($document->getElementsByTagName('img') as $img) {
        $image = array (
            $src = $img->getAttribute('src')
        );
        
        // Skip images without src
        if (!$src) {
            continue;
        }

        //  Add to collection to avoid repetition
        $images[$src] = $src;
    }
    $images = array_values($images);

    for ($i = 0; $i < sizeof($images); $i++) {

        // Get images data
        $data = get_images($images[$i]);

        // Create directory for downloads
        $dir = "./data";
        if(!is_dir($dir)) mkdir($dir);
        file_put_contents("$dir/image_$i", $data);
        echo "$i spider... \n";
    }
    echo "\n";
}

// Call function
spider($argc, $argv);


function get_images($url) {
    $request = curl_init();

    curl_setopt($request, CURLOPT_URL, $url);              // set the website URL
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);   // return the response as a string
    curl_setopt($request, CURLOPT_HEADER, 0);               // follow redirects

    $data = curl_exec($request);
    curl_close($request);

    return $data;
}



//  Web crawling -> following more pages by links
//  check if there is a next page
//  $link =$html->find("a", 0);
//  if($link) {
//      $nextPageUrl = $link->href;
//  }
//  spider($nextPageUrl);

//  call the start function
//  spider($url):






// Resources:
// https://www.zenrows.com/blog/web-scraping-php
// simple_html_dom.php (HTML parsing)
// https://scrapingant.com/blog/download-image-php
// https://www.geekality.net/blog/php-how-to-get-all-images-from-an-html-page


?>