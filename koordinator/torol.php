<?php

require_once '../config.php';
require_once '../functions.php';

$title = "Hallgató törlése";
$button = 'torol';
$modal = true;

load_post();
$id =  posted('id') ?: filter_input(INPUT_GET, 'id');
$torol = posted($button);

if ($torol) {
    if (jelentkezesi_delete($id)) {
        $message = "A hallgató törlése megtörtént.";
    }
    else {
        $message = "A hallgató törlése sikertelen!";
    }
    require '../uzenet.php';
}
elseif (! jelentkezesi_read($id, null, null))
{
    $message = "A hallgató nem található a rendszerben!";
    require '../uzenet.php';
}
else {
    $nev = posted('nev');
    $neptunkod = posted('neptunkod');
    $message = "Biztos törölni akarja $nev ($neptunkod) nevű hallgatót?";
    require '../kerdes.php';
}
