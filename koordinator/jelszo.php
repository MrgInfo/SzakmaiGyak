<?php

require_once '../config.php';
require_once '../functions.php';

$title = "Jelszómódosítás";
$modal = true;

load_post();
$id =  posted('id') ?: filter_input(INPUT_GET, 'id');
$uj_jelszo = posted('uj_jelszo');

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
        jelentkezesi_password($id, $jelszo)) {
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
    require '../header.php';
    ?>
<div class="jumbotron">
    <h2>
        <?= $title ?>
    </h2>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="form-inline" role="form">
        <input type="hidden" name="id" value="<?= $id ?>">
        <p>Akarja, hogy a rendszer új jelszót generáljon a <?= $nev ?> (<?= $neptunkod ?>) nevű hallgatónak?</p>
        <div class="btn-group" role="group">
            <button type="submit" name="uj_jelszo" value="1" class="btn btn-primary">Igen</button>
            <button onclick="close_page();" class="btn btn-default">Nem</Button>
        </div>
    </form>
</div>
    <?php
    require '../footer.php';
}
