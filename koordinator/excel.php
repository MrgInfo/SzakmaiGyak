<?php

require_once '../config.php';
require_once '../functions.php';

$type = filter_input(INPUT_GET, 'type');
if ($type == 'neptun') {
    $table = neptun();
    $name = $type;
}
elseif ($type == '9050') {
    $table = null;
    $name = $type;
}
else {
    $table = jelentkezesi_read_beautiful();
    $name = DB_NAME;
}

// disable caching
$now = gmdate("D, d M Y H:i:s");
header("Expires: $now GMT");
header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
header("Last-Modified: $now GMT");
// force download
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
// disposition / encoding on response body
header("Content-Disposition: attachment;filename=$name.csv");
header("Content-Transfer-Encoding: binary");

$first = true;
$df = fopen('php://output', 'w');
foreach ($table as $row) {
    if ($first) {
        fputcsv($df, array_keys( $row ), ';', '"');
        $first = false;
    }
    fputcsv($df, $row, ';', '"');
}
fclose($df);
