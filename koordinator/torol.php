<?php

require_once '../config.php';
require_once '../functions.php';

$title = "Hallgató törlése";

load_post();
$id =  empty($_POST['id']) ? filter_input(INPUT_GET, "id") : $_POST['id'];

if (! empty($_POST['torol'])) {
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
    require '../header.php';
    ?>
    <div class="jumbotron">
        <h2>Hallgató törlése</h2>
        <p>Biztos törölni akarja a/az <?= posted( 'nev' ) ?> (<?= posted( 'neptunkod' ) ?>) nevű hallgatót?</p>
        <form action="torol.php" method="post">
            <input type="hidden" name="id" value="<?= $id ?>">
            <div class="btn-group" role="group">
                <input type="submit" name="torol" class="btn btn-primary" value="Töröl" />
                <button onclick="close_page()" class="btn btn-default">Mégsem</button>
            </div>
        </form>
    </div>
    <?php
    require '../footer.php';
}

