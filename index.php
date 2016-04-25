<?php

require_once 'config.php';
require_once 'functions.php';
require 'header.php';

?>
<header>
    <h1><?= GYAKORLAT_EV ?> szakmai gyakorlat</h1>
</header>
<div class="content">
    <h2>Jelentkezés szakmai gyakorlatra</h2>
<?php
if (strtotime(JEL_HATARIDO) > strtotime('now')) {
	?>
    <form action="jelentkezes.php" method="post" class="form-inline" role="form">
        <p>A szakmai gyakorlatra való jelentkezés módja a regisztrációs űrlap kitöltése.</p>
        <input type="submit" value="Regisztráció" class="btn btn-default" />
    </form>
	<?php
}
else {
	?>
    <p>A szakmai gyakorlatra való jelentkezés lezárult!</p>
<?php
}
?>
    <h2>Jelentkezés módosítása</h2>
<?php
if (strtotime(BEADASI_HATARIDO) > strtotime('now')) {
    ?>
    <form action="jelentkezes.php" method="post" class="form-inline" role="form">
        <p>
            Amennyiben változás történt vagy új információval rendelkezik a szakmai gyakorlattal kapcsolatban, az
            e-mailben kapott jelszó segítségével módosíthat az adatain.
        </p>
        <div class="form-group">
            <label class="control-label" for="neptunkod">Neptun-kód:</label>
            <input type="text" id="neptunkod" name="neptunkod" size="10" maxlength="6" class="form-control">
        </div>
        <div class="form-group">
            <label class="control-label" for="jelszo">Jelszó:</label>
            <input type="password" id="jelszo" name="jelszo" size="10" maxlength="8" class="form-control">
        </div>
        <input type="submit" name="szerkesztes" value="Szerkesztés" class="btn btn-default">
    </form>
    <?php
}
else {
    ?>
    <p>A szakmai gyakorlatra lezárult!</p>
    <?php
}
?>
</div>
<?php

require 'footer.php';
