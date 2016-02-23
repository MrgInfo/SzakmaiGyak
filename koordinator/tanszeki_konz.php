<?
require '../header.php';
$done = false;
if( $_POST[felvesz] ) {
	if( $_POST[nev] == '' ||
		$_POST[beoszt] == '' ||
		$_POST[tel] == '' ||
		$_POST[email] == '' ) {
		$errormsg = 'Nem minden kötelező mező van kitöltve!';
	} else {
		if( $conn = connect() ) {
			$SQL = escape( $_POST, $conn );
			$insert = <<<END
INSERT INTO konzulensek
(
  nev,
  beoszt,
  tel,
  email
)
VALUES
(
  $SQL[nev],
  $SQL[beoszt],
  $SQL[tel],
  $SQL[email]
)
END;
			if( @mysql_query( $insert, $conn ) ) {
				$done = true;
			}
			disconnect( $conn );
		}
		if( !$done ) {
			$errormsg = 'A konzulens felvétele sikertelen!';
		}
	}
}
if( $done ) {
?>
		<div class="jumbotron">
			<h2>Tanszéki konzulens</h2>
			<p><?= $_POST[nev]; ?> tanszéki konzules rögzítése sikeresen megtörtént.<p>
			<p><a href="tanszeki_konz.php" class="btn btn-default" role="button">Vissza</a></p>
		</div>
<?
} else {
?>
		<header>
			<h1><?=GYAKORLAT_EV; ?>. évi szakmai gyakorlat</h1>
		</header>
		<div class="content">
			<h2>Tanszéki konzulens</h2>
<?	if( $errormsg ) { ?>
			<div class="alert alert-danger" role="alert">
				<p><?= $errormsg; ?></p>
			</div>
<? 	} ?>
			<form action="tanszeki_konz.php" method="post">
				<table class="form">
					<tbody>
						<tr>
							<td class="form-label"><label class="control-label label-req" for="nev">Neve:</label></td>
							<td><input type="text" id="nev" name="nev" value="<?= $_POST[nev]; ?>" size="40" maxlength="35" class="form-control"></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label label-req" for="beoszt">Beosztása:</label></td>
							<td><input type="text" id="beoszt" name="beoszt" value="<?= $_POST[beoszt]; ?>" size="40" maxlength="20" class="form-control"></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label label-req" for="tel">Telefonszáma:</label></td>
							<td><input type="text" id="tel" name="tel" value="<?= $_POST[tel]; ?>" size="15" maxlength="20" class="form-control"></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label label-req" for="email">E-mail címe:</label></td>
							<td><input type="email" id="email" name="email" value="<?= $_POST[email]; ?>" size="40" maxlength="30" class="form-control"></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" name="felvesz" value="Felvétel" class="btn btn-primary"></td>
						</tr>
					</tbody>
				</table>
			</form>
		<div>
		<footer class="fn">
			<p><span class="label-req">Kötelezően töltendő adatok.</span></p>
			<p><span class="label-mand">* A mező kitöltése jelentkezéshez nem, de a feladat jóváhagyásához kötelezően kitöltendő adat!</span></p>
		</footer>
<?
}

require '../footer.php';

?>
