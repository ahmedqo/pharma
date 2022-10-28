<?php
$array = [];
$handle = opendir('../assets/slide/');
while ($file = readdir($handle)) {
    if ($file !== '.' && $file !== '..') {
        array_push($array, '/assets/slide/' . $file);
    }
}

echo json_encode($array);
