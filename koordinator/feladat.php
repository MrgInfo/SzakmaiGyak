<?php

require_once '../config.php';
require_once '../functions.php';

$title = "Feladatkiírás, módosítás";
$readonly = true;
$feladatkiiras = true;
$szerkesztes = true;

load_post();
$id =  empty($_POST['id']) ? filter_input(INPUT_GET, "id") : $_POST['id'];

if (! empty($_POST['szerkesztes'])) {
    if (check_required()) {
        $nev =  posted( 'nev' );
        $email = posted( 'email' );
        if (! jelentkezesi_edit()) {
            $message = "A módosítások nem hajthatók végre!";
        }
        elseif (! check_mandantory()) {
            $message = "A módosítások érvénybe léptek, a hallgatót a hiányzó adatok miatt még nem értesítettük.";
        }
        elseif (! jovahagyas_mail($nev, $email)) {
            $message = "A módosítások érvénybe léptek, viszont az e-mail küldés sikertelen volt a <a href=\"mailto:$email\">$email</a> címre.";
        }
        else {
            $message = "A módosítások érvénybe léptek, a hallgatót értesítettük a <a href=\"mailto:$email\">$email</a> címen.";
        }
        $done = true;
        require '../uzenet.php';
    }
    else {
        $missing = true;
        require '../urlap.php';
    }
}
elseif (jelentkezesi_read($id, null, null)) {
    require '../urlap.php';
}
else {
    $message = "A hallgató nincs regisztrálva!";
    require '../uzenet.php';
}
