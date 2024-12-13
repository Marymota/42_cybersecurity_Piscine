<?php

function settings($argc, $argv) {
    // Default settings
    $sets = [ 'recursive' => false, 'level' => 5, 'path' => './data', 'url' => null ];

    for ($i = 1; $i < $argc; $i++  ) {
        switch ($argv[$i]) {
            case '-r':
                $sets['recursive'] = true;
                break;
            case '-l':
                $sets['level'] = isset($argv[$i + 1]) ? intval($argv[$i + 1]) : 5;
                $i++;
                break;
            case '-p':
                $sets['path'] = isset($argv[$i + 1]) ? $argv[$i + 1] : './data';
                $i++;
                break;
            default:
                $sets['url'] = $argv[$i];
        }
    }
    if ($sets['level'] > 5) { exit("Level should be between 1 and 5\n"); }
    return $sets;
}

?>