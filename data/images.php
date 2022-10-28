<?php
$array = [];
$handle = opendir('../slides/');
while ($file = readdir($handle)) {
    if ($file !== '.' && $file !== '..') {
        array_push($array, '/slides/' . $file);
    }
}

echo json_encode($array);
