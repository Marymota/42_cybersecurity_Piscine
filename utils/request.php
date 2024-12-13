<?php

function  curl_request($url) {
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

    // close cURL session
    curl_close($request);
    return $response;
}

function tagsFinder ($document, $tag) {

    $attribute = null;
    if ($tag == 'img') { $attribute = 'src'; }
    elseif ($tag == 'a') { $attribute = 'href'; }
    else ( exit("Not a valid tag") );

    $elements = array();
    foreach($document->getElementsByTagName($tag) as $tags) {
        $src = $tags->getAttribute($attribute);
        
        // Skip images without src
        if (!$src) {
            continue;
        }
    
        //  Add to collection to avoid repetition
        $elements[$src] = $src;
    }
    return $elements;
}

?>