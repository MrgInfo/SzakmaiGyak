<?
$path = dirname( dirname( $_SERVER[SCRIPT_FILENAME] ) );
if( $_POST[save] ) {
	$fh = fopen( "$path/config.php", 'wb' );
	$data = <<<END
<?
define( EXEC_DIR,    '/usr/bin' );
define( DB_HOST,     'localhost' );
define( DB_USER,     'szakmaigyak' );
define( DB_PASSWORD, '/tomi' );
define( DB_NAME,     '$_POST[DB_NAME]' );
define( EMAIL_FROM,  '$_POST[EMAIL_FROM]' );

define( GYAKORLAT_EV,      '$_POST[GYAKORLAT_EV]' );
define( GYAKORLAT_KEZDETE, '$_POST[GYAKORLAT_KEZDETE]' );
define( GYAKORLAT_VEGE,    '$_POST[GYAKORLAT_VEGE]' );
define( BEADASI_HATARIDO,  '$_POST[BEADASI_HATARIDO]' );
define( JEL_HATARIDO,      '$_POST[JEL_HATARIDO]' );
define( KONZULENS,         '$_POST[KONZULENS]' );
define( ALAIRO_TANSZEK,    '$_POST[ALAIRO_TANSZEK]' );
define( ALAIRO_KAR,        '$_POST[ALAIRO_KAR]' );
?>
END;
	fputs( $fh, "\xEF\xBB\xBF".$data );
	fclose( $fh );

	define( DB_NAME,           $_POST[DB_NAME] );
	define( GYAKORLAT_EV,      $_POST[GYAKORLAT_EV] );
	define( GYAKORLAT_KEZDETE, $_POST[GYAKORLAT_KEZDETE] );
	define( GYAKORLAT_VEGE,    $_POST[GYAKORLAT_VEGE] );
	define( BEADASI_HATARIDO,  $_POST[BEADASI_HATARIDO] );
	define( JEL_HATARIDO,      $_POST[JEL_HATARIDO] );
	define( ALAIRO_TANSZEK,    $_POST[ALAIRO_TANSZEK] );
	define( ALAIRO_KAR,        $_POST[ALAIRO_KAR] );
	define( EMAIL_FROM,        $_POST[EMAIL_FROM] );
	define( KONZULENS,         $_POST[KONZULENS] );
}
include "$path/header.php";
?>
		<div class="content">
			<h2>Beállítások <?= $path ?></h2>
			<p class="err"><?= $errormsg; ?></p>
			<form action="setup.php" method="post" class="form">
				<table>
					<tbody>
						<tr>
							<td><label for="DB_NAME" class="control-label">Adatbázis neve:</label></td>
							<td><input type="text" id="DB_NAME" name="DB_NAME" value="<?= DB_NAME; ?>" size="50" class="form-control"></td>
						</tr>
						<tr>
							<td><label for="GYAKORLAT_EV" class="control-label">Szakmai gyakorlat éve:</label></td>
							<td><input type="number" id="GYAKORLAT_EV" name="GYAKORLAT_EV" value="<?= GYAKORLAT_EV; ?>" size="50" class="form-control"></td>
						</tr>
						<tr>
							<td><label for="GYAKORLAT_KEZDETE" class="control-label">Szakmai gyakorlat kezdete:</label></td>
							<td><input type="text" id="GYAKORLAT_KEZDETE" name="GYAKORLAT_KEZDETE" value="<?= GYAKORLAT_KEZDETE; ?>" size="50" class="form-control"></td>
						</tr>
						<tr>
							<td><label for="GYAKORLAT_VEGE" class="control-label">Szakmai gyakorlat vége:</label></td>
							<td><input type="text" id="GYAKORLAT_VEGE" name="GYAKORLAT_VEGE" value="<?= GYAKORLAT_VEGE; ?>" size="50" class="form-control"></td>
						</tr>
						<tr>
							<td><label for="BEADASI_HATARIDO" class="control-label">Beadási határidő:</label></td>
							<td><input type="text" id="BEADASI_HATARIDO" name="BEADASI_HATARIDO" value="<?= BEADASI_HATARIDO; ?>" size="50" class="form-control"></td>
						</tr>
						<tr>
							<td><label for="JEL_HATARIDO" class="control-label">Jelentkezési határidő:</label></td>
							<td><input type="date" id="JEL_HATARIDO" name="JEL_HATARIDO" value="<?= JEL_HATARIDO; ?>" size="50" class="form-control"></td>
						</tr>
						<tr>
							<td><label for="ALAIRO_TANSZEK" class="control-label">Tanszéki aláíró:</label></td>
							<td><input type="text" id="ALAIRO_TANSZEK" name="ALAIRO_TANSZEK" value="<?= ALAIRO_TANSZEK; ?>" size="50" class="form-control"></td>
						</tr>
						<tr>
							<td><label for="ALAIRO_KAR" class="control-label">Kari aláíró:</label></td>
							<td><input type="text" id="ALAIRO_KAR" name="ALAIRO_KAR" value="<?= ALAIRO_KAR; ?>" size="50" class="form-control"></td>
						</tr>
						<tr>
							<td><label for="EMAIL_FROM" class="control-label">Rendszer e-mail:</label></td>
							<td><input type="email " id="EMAIL_FROM" name="EMAIL_FROM" value="<?= EMAIL_FROM; ?>" size="50" class="form-control"></td>
						</tr>
						<tr>
							<td><label for="KONZULENS" class="control-label">Alapértelmezett konzulens:</label></td>
							<td><input type="text" id="KONZULENS" name="KONZULENS" value="<?= KONZULENS; ?>" size="50" class="form-control"></td>
						</tr>
					</tbody>
				</table>
				<div class="btn-group" role="group">
					<input type="submit" name="save" value="Mentés" class="btn btn-primary">
					<a href="index.php" class="btn btn-default" role="button">Vissza</a>
				</div>
			</form>
		</div>
<?
include "$path/footer.php";
?>