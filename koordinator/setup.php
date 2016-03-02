<?php

require_once '../config.php';
require_once '../functions.php';

$title = "Beállítások";
$modal = true;

load_post();
$save = posted('save');

if ($save) {
	$fh = fopen('../config.php', 'wb');
	$data = <<<END
<?php

define('DB_HOST',     'localhost');
define('DB_USER',     'szakmaigyak');
define('DB_PASSWORD', '/tomi');
define('DB_NAME',     '$_POST[DB_NAME]');
define('EMAIL_FROM',  '$_POST[EMAIL_FROM]');

define('GYAKORLAT_KEPZESKOD', '$_POST[GYAKORLAT_KEPZESKOD]');
define('GYAKORLAT_EV',        '$_POST[GYAKORLAT_EV]');
define('ELFOGADO',            '$_POST[ELFOGADO]');
define('ELFOGADO_BEOSZTASA',  '$_POST[ELFOGADO_BEOSZTASA]');
define('JEL_HATARIDO',        '$_POST[JEL_HATARIDO]');
define('BEADASI_HATARIDO',    '$_POST[BEADASI_HATARIDO]');
define('ELFOGADAS',           '$_POST[ELFOGADAS]');

END;
	fputs($fh, "\xEF\xBB\xBF".$data);
	fclose($fh);
    $message = "Az új beállításokat mentettük.";
    require '../uzenet.php';
}
else {
    require "../header.php";
    ?>
<header>
    <h1><?= $title ?></h1>
</header>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="form" role="form">
    <div class="form-group">
        <label for="DB_NAME" class="control-label">Adatbázis neve:</label>
        <input type="text" id="DB_NAME" name="DB_NAME" value="<?= posted('DB_NAME', DB_NAME) ?>" size="50" class="form-control">
    </div>
    <div class="form-group">
        <label for="GYAKORLAT_KEPZESKOD" class="control-label">Képzés kódja:</label>
        <input type="text" id="GYAKORLAT_KEPZESKOD" name="GYAKORLAT_KEPZESKOD" value="<?= posted('GYAKORLAT_KEPZESKOD', GYAKORLAT_KEPZESKOD) ?>" size="50" class="form-control">
    </div>
    <div class="form-group">
        <label for="GYAKORLAT_EV" class="control-label">Aktuális félév:</label>
        <input type="text" id="GYAKORLAT_EV" name="GYAKORLAT_EV" value="<?= posted('GYAKORLAT_EV', GYAKORLAT_EV) ?>" size="50" class="form-control">
    </div>
    <div class="form-group">
        <label for="ELFOGADO" class="control-label">Elfogadó neve:</label>
        <input type="text" id="ELFOGADO" name="ELFOGADO" value="<?= posted('ELFOGADO', ELFOGADO) ?>" size="50" class="form-control">
    </div>
    <div class="form-group">
        <label for="ELFOGADO_BEOSZTASA" class="control-label">Elfogadó beosztása:</label>
        <input type="text" id="ELFOGADO_BEOSZTASA" name="ELFOGADO_BEOSZTASA" value="<?= posted('ELFOGADO_BEOSZTASA', ELFOGADO_BEOSZTASA) ?>" size="50" class="form-control">
    </div>
    <div class="form-group">
        <label for="EMAIL_FROM" class="control-label">Elfogadó e-mail címe:</label>
        <input type="email" id="EMAIL_FROM" name="EMAIL_FROM" value="<?= posted('EMAIL_FROM', EMAIL_FROM) ?>" size="50" class="form-control">
    </div>
    <div class="form-group">
        <label for="JEL_HATARIDO" class="control-label">Jelentkezési határidő:</label>
        <input type="date" id="JEL_HATARIDO" name="JEL_HATARIDO" value="<?= posted('JEL_HATARIDO', JEL_HATARIDO) ?>" size="50" class="form-control">
    </div>
    <div class="form-group">
        <label for="BEADASI_HATARIDO" class="control-label">Beadási határidő:</label>
        <input type="date" id="BEADASI_HATARIDO" name="BEADASI_HATARIDO" value="<?= posted('BEADASI_HATARIDO', BEADASI_HATARIDO) ?>" size="50" class="form-control">
    </div>
    <div class="form-group">
        <label for="ELFOGADAS" class="control-label">Elfogadás ideje:</label>
        <input type="date" id="ELFOGADAS" name="ELFOGADAS" value="<?= posted('ELFOGADAS', ELFOGADAS) ?>" size="50" class="form-control">
    </div>
    <div class="btn-group" role="group">
        <input type="submit" name="save" value="Mentés" class="btn btn-primary">
        <input type="button" onclick="close_page();" value="Mégsem" class="btn btn-default">
    </div>
</form>
    <?php
    require "../footer.php";
}
