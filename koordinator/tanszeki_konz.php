<?php

require_once '../config.php';
require_once '../functions.php';
require_once 'auth.php';

$title = "Tanszéki konzulens";

load_post();
$nev = posted('nev');
$beoszt = posted('beoszt');
$tel = posted('tel');
$email = posted('email');
$felvesz = posted('felvesz');

if ($felvesz) {
    if (empty($nev)
        ||
        empty($beoszt)
        ||
	empty($tel)
        ||
	empty($email)) {
	$errormsg = 'Nem minden kötelező mező van kitöltve!';
    }
    elseif (! konzulens_uj()) {
        $errormsg = 'A konzulens felvétele sikertelen!';
    }
    else {
        $message = "$nev tanszéki konzules rögzítése sikeresen megtörtént.";
        $modal = true;
        require '../uzenet.php';
        exit;
    }
}

require '../header.php';

?>
<header>
    <h1><?= GYAKORLAT_EV ?> szakmai gyakorlat</h1>
</header>
<div class="content">
    <h2><?= $title ?></h2>
<?php

if (! empty($errormsg)) {
    ?>
    <div class="alert alert-danger" role="alert">
        <p><?= $errormsg ?></p>
    </div>
    <?php
}
?>
    <form action="tanszeki_konz.php" method="post">
        <table class="form border">
            <tbody>
                <tr>
                    <td class="sep" colspan="2"><label>Személyes adatok</label></td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-req" for="nev">Neve&deg;:</label>
                    </td>
                    <td>
                        <input type="text" id="nev" name="nev" value="<?= $nev ?>" size="40" maxlength="35" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-req" for="beoszt">Beosztása&deg;:</label>
                    </td>
                    <td>
                        <input type="text" id="beoszt" name="beoszt" value="<?= $beoszt ?>" size="40" maxlength="20" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-req" for="tel">Telefonszáma&deg;:</label>
                    </td>
                    <td>
                        <input type="text" id="tel" name="tel" value="<?= $tel ?>" size="15" maxlength="20" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-req" for="email">E-mail címe&deg;:</label>
                    </td>
                    <td>
                        <input type="email" id="email" name="email" value="<?= $email ?>" size="40" maxlength="30" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="btn-group" role="group">
                            <input type="submit" name="felvesz" value="Mentés" class="btn btn-primary">
                            <input type="button" onclick="close_page();" value="Mégsem" class="btn btn-default">
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
<div>
<footer class="fn">
    <p class="label-req">&deg; Kötelezően töltendő!</p>
</footer>
<?php

require '../footer.php';
