<?php

require_once '../config.php';
require_once '../functions.php';
require_once 'auth.php';

$title = "Jelszómódosítás";
$button = 'uj_jelszo';
$modal = true;

load_post();
$id =  posted('id') ?: filter_input(INPUT_GET, 'id');
$uj_jelszo = posted($button);

if (! jelentkezesi_read($id, null, null)) {
    $message = "A jelszóváltoztatás nem hajtható végre!";
    require '../uzenet.php';
}
elseif ($uj_jelszo) {
    $nev = posted('nev');
    $email = posted('email');
    $jelszo = generatePassword();
    if (jelszo_mail($nev, $email, $jelszo)
        &&
        jelentkezesi_password($id, $jelszo)
    ) {
        $message = "A hallgató jelszava megváltozott, erről értesítést kapott a <a href=\"mailto:$email\">$email</a> címre.";
    }
    else {
        $message = "A jelszóváltoztatás nem hajtható végre!";
    }
    require '../uzenet.php';
}
else {
    $nev = posted('nev');
    $neptunkod = posted('neptunkod');
    $message = "Akarja, hogy a rendszer új jelszót generáljon $nev ($neptunkod) nevű hallgatónak?";
    require '../kerdes.php';
}
