<?php

function settings($argc, $argv) {
    // Default settings
    $sets = [ 'recursive' => false, 'level' => 5, 'output' => './data', 'url' => null ];
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
            case '-rl':
                    $sets['recursive'] = true;
                    $sets['level'] = isset($argv[$i + 1]) ? intval($argv[$i + 1]) : 5;
                    $i++;
                    break;
            case '-rp':
                $sets['recursive'] = true;
                $sets['path'] = isset($argv[$i + 1]) ? $argv[$i + 1] : './data';
                $i++;
                break;
            default:
                $sets['url'] = $argv[$i];
        }
    }
    return $sets;
}

?>