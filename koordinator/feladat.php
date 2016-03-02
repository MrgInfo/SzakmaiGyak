<?php

require_once '../config.php';
require_once '../functions.php';

$title = "Feladatkiírás, módosítás";
$modal = true;
$readonly = true;
$feladatkiiras = true;
$szerkesztes = true;

load_post();
$id =  posted('id') ?: filter_input(INPUT_GET, 'id');
$szerkesztes = posted('szerkesztes');

if ($szerkesztes) {
    if (! check_required()) {
        $missing = true;
        require '../urlap.php';
    }
    else {
        $nev =  posted('nev');
        $email = posted('email');
        $tan_konz = posted('tan_konz');
        $konzulens = '';
        if (! empty($tan_konz)) {
            $db = konzulens_read($tan_konz);
            $konzulens = $db['nev'];
        }
        if (! jelentkezesi_edit()) {
            $message = "A módosítások nem hajthatók végre!";
        }
        elseif (! check_mandantory()) {
            $message = "A módosítások érvénybe léptek, a hallgatót a hiányzó adatok miatt még nem értesítettük.";
        }
        elseif (! jovahagyas_mail($nev, $email, $konzulens)) {
            $message = "A módosítások érvénybe léptek, viszont az e-mail küldés sikertelen volt a <a href=\"mailto:$email\">$email</a> címre.";
        }
        else {
            $message = "A módosítások érvénybe léptek, a hallgatót értesítettük a <a href=\"mailto:$email\">$email</a> címen.";
        }
        $modal = true;
        require '../uzenet.php';
    }
}
elseif (jelentkezesi_read($id, null, null)) {
    $szerkesztes = true;
    require '../urlap.php';
}
else {
    $message = "A hallgató nincs regisztrálva!";
    require '../uzenet.php';
}
