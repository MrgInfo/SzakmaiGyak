<?php

$host = 'nikipi.gotdns.org';

require_once '../config.php';
require_once '../functions.php';

load_post();

if (! empty($_POST['save'])) {
	$fh = fopen( "../config.php", 'wb' );
	$data = <<<END
<?php

define( 'EXEC_DIR',    '/usr/bin' );
define( 'DB_HOST',     'localhost' );
define( 'DB_USER',     'szakmaigyak' );
define( 'DB_PASSWORD', '/tomi' );
define( 'DB_NAME',     '$_POST[DB_NAME]' );
define( 'EMAIL_FROM',  '$_POST[EMAIL_FROM]' );

define( 'GYAKORLAT_EV',        '$_POST[GYAKORLAT_EV]' );
define( 'GYAKORLAT_KEPZESKOD', '$_POST[GYAKORLAT_KEPZESKOD]' );
define( 'GYAKORLAT_FELEV',     '$_POST[GYAKORLAT_FELEV]' );
define( 'GYAKORLAT_KEZDETE',   '$_POST[GYAKORLAT_KEZDETE]' );
define( 'GYAKORLAT_VEGE',      '$_POST[GYAKORLAT_VEGE]' );
define( 'BEADASI_HATARIDO',    '$_POST[BEADASI_HATARIDO]' );
define( 'JEL_HATARIDO',        '$_POST[JEL_HATARIDO]' );
define( 'KONZULENS',           '$_POST[KONZULENS]' );
define( 'ALAIRO_TANSZEK',      '$_POST[ALAIRO_TANSZEK]' );
define( 'ALAIRO_KAR',          '$_POST[ALAIRO_KAR]' );

END;
	fputs( $fh, "\xEF\xBB\xBF".$data );
	fclose( $fh );
}

require "../header.php";

?>
<header>
    <h1>Beállítások</h1>
</header>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="form">
    <table>
        <tbody>
            <tr>
                <td>
                    <label for="DB_NAME" class="control-label">Adatbázis neve:</label>
                </td>
                <td>
                    <input type="text" id="DB_NAME" name="DB_NAME" value="<?= posted( 'DB_NAME', DB_NAME ) ?>" size="50" class="form-control">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="GYAKORLAT_EV" class="control-label">Szakmai gyakorlat éve:</label></td>
                <td>
                    <input type="number" id="GYAKORLAT_EV" name="GYAKORLAT_EV" value="<?= posted( 'GYAKORLAT_EV', GYAKORLAT_EV ) ?>" size="50" class="form-control">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="GYAKORLAT_KEPZESKOD" class="control-label">Képzés kódja:</label></td>
                <td>
                    <input type="text" id="GYAKORLAT_KEPZESKOD" name="GYAKORLAT_KEPZESKOD" value="<?= posted( 'GYAKORLAT_KEPZESKOD', GYAKORLAT_KEPZESKOD ) ?>" size="50" class="form-control">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="GYAKORLAT_FELEV" class="control-label">Aktuális félév:</label></td>
                <td>
                    <input type="text" id="GYAKORLAT_FELEV" name="GYAKORLAT_FELEV" value="<?= posted( 'GYAKORLAT_FELEV', GYAKORLAT_FELEV ) ?>" size="50" class="form-control">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="GYAKORLAT_KEZDETE" class="control-label">Szakmai gyakorlat kezdete:</label>
                </td>
                <td>
                    <input type="text" id="GYAKORLAT_KEZDETE" name="GYAKORLAT_KEZDETE" value="<?= posted( 'GYAKORLAT_KEZDETE', GYAKORLAT_KEZDETE ) ?>" size="50" class="form-control">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="GYAKORLAT_VEGE" class="control-label">Szakmai gyakorlat vége:</label>
                </td>
                <td>
                    <input type="text" id="GYAKORLAT_VEGE" name="GYAKORLAT_VEGE" value="<?= posted( 'GYAKORLAT_VEGE', GYAKORLAT_VEGE ) ?>" size="50" class="form-control">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="BEADASI_HATARIDO" class="control-label">Beadási határidő:</label>
                </td>
                <td>
                    <input type="text" id="BEADASI_HATARIDO" name="BEADASI_HATARIDO" value="<?= posted( 'BEADASI_HATARIDO', BEADASI_HATARIDO ) ?>" size="50" class="form-control">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="JEL_HATARIDO" class="control-label">Jelentkezési határidő:</label>
                </td>
                <td>
                    <input type="date" id="JEL_HATARIDO" name="JEL_HATARIDO" value="<?= posted( 'JEL_HATARIDO', JEL_HATARIDO ) ?>" size="50" class="form-control">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="ALAIRO_TANSZEK" class="control-label">Tanszéki aláíró:</label>
                </td>
                <td>
                    <input type="text" id="ALAIRO_TANSZEK" name="ALAIRO_TANSZEK" value="<?= posted( 'ALAIRO_TANSZEK', ALAIRO_TANSZEK ) ?>" size="50" class="form-control">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="ALAIRO_KAR" class="control-label">Kari aláíró:</label>
                </td>
                <td>
                    <input type="text" id="ALAIRO_KAR" name="ALAIRO_KAR" value="<?= posted( 'ALAIRO_KAR', ALAIRO_KAR ) ?>" size="50" class="form-control">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="EMAIL_FROM" class="control-label">Rendszer e-mail:</label>
                </td>
                <td>
                    <input type="email" id="EMAIL_FROM" name="EMAIL_FROM" value="<?= posted( 'EMAIL_FROM', EMAIL_FROM ) ?>" size="50" class="form-control">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="KONZULENS" class="control-label">Alapértelmezett konzulens:</label>
                </td>
                <td>
                    <input type="text" id="KONZULENS" name="KONZULENS" value="<?= posted( 'KONZULENS', KONZULENS ) ?>" size="50" class="form-control">
                </td>
            </tr>
        </tbody>
    </table>
    <div class="btn-group" role="group">
        <input type="submit" name="save" value="Mentés" class="btn btn-primary">
        <input type="button" onclick="close_page();"value="Mégsem" class="btn btn-default">
    </div>
</form>
<?php

require "../footer.php";
