<?php

$_SERVER['REQUEST_URI'] = '/';

require_once 'config.php';
require_once 'functions.php';
require 'header.php';

?>
		<header>
			<h1><?=GYAKORLAT_EV?>. évi szakmai gyakorlat</h1>
		</header>
		<div class="content">
			<h2>Jelentkezés szakmai gyakorlatra</h2>
<?php
if( strtotime( JEL_HATARIDO ) > strtotime( 'now' ) ) {
?>
			<p>A szakmai gyakorlatra való jelentkezés módja a regisztrációs űrlap kitöltése.</p>
            <a href="jelentkezes.php" class="btn btn-default">Regsiztráció</a>
<?php
} else {
?>
			<div class="form">
				<p>A szakmai gyakorlatra való jelentkezés lezárult!</p>
			</div>
<?php
}
?>
			<h2>Jelentkezés módosítása</h2>
			<form action="jelentkezes.php" method="post" class="form">
				<p>
                    Amennyiben változás történt vagy új információval rendelkezik a szakmai gyakorlattal kapcsolatban, az e-mailben kapott jelszó segítségével változtathat az adatain.
                </p>
				<table>
					<tbody>
						<tr>
							<td>
                                <label class="control-label" for="neptunkod">Neptun-kód:</label>
                            </td>
							<td>
                                <input type="text" id="neptunkod" name="neptunkod" size="10" maxlength="6" class="form-control" />
                            </td>
						</tr>
						<tr>
							<td>
                                <label class="control-label" for="jelszo">Jelszó:</label>
                            </td>
							<td>
                                <input type="password" id="jelszo" name="jelszo" size="10" maxlength="8" class="form-control" />
                            </td>
						</tr>
						<tr>
							<td colspan="2">
                                <input type="submit" name="szerkesztes" value="Szerkesztés" class="btn btn-default" />
                            </td>
						</tr>
					<tbody>
				</table>
			</form>
		</div>
<?php

require 'footer.php';

?>
