<?

require '../header.php';
require_once '../functions.php';

$table = all_read();

?>
		<header>
			<h1><?=GYAKORLAT_EV; ?>. évi szakmai gyakorlat</h1>
		</header>
		<div class="content">
			<form method="post" action="email.php" target="_blank" style="height: 80%">
				<table data-toggle="table" data-show-columns="true" data-search="true" data-toolbar="#toolbar" data-smart-display="false"
					   data-page-size="5">
					<thead>
						<tr>
							<th data-align="center">Műveletek</th>
							<th data-sortable="true">Név</th>
							<th data-sortable="true">Neptun-kód</th>
							<th>Állandó lakcím</th>
							<th>Értesítési cím</th>
							<th>Mobiltelefon</th>
							<th data-sortable="true">
								E-mail <input name="h_email" type="submit" value="&raquo;" class="btn btn-default btn-xs">
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							</th>
							<th data-sortable="true">Intézmény neve</th>
							<th>Intézmény címe</th>
							<th data-sortable="true">Feladat címe</th>
							<th>Feladat részletezése</th>
							<th>Külső konzulens</th>
							<th>Konzulens beosztása</th>
							<th>Konzulens telefonszáma</th>
							<th>
								Konzulens e-mail címe <input name="kk_email" type="submit" value="&raquo;" class="btn btn-default btn-xs">
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							</th>
							<th data-sortable="true">Tanszéki konzulens</th>
							<th>Konzulens beosztása</th>
							<th>Konzulens telefonszáma</th>
							<th>
								Konzulens e-mail címe <input name="tk_email" type="submit" value="&raquo;" class="btn btn-default btn-xs">
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							</th>
							<th data-sortable="true">Kollégiumi igény</th>
							<th>Megjegyzés</th>
							<th data-sortable="true">Jelentkezés ideje</th>
							<th data-sortable="true">Utolsó módosítás</th>
							<th data-align="center">Letöltés&nbsp;&nbsp;&nbsp;</th>
						</tr>
					</thead>
					<tbody>
<?
foreach( $table as $row ) {
?>
						<tr>
							<td>
								<a href="torol.php?id=<?= $row[id]; ?>"><i class="glyphicon glyphicon-trash" title="Töröl"></i></a>
								<a href="feladat.php?id=<?= $row[id]; ?>"><i class="glyphicon glyphicon-edit" title="Szerkeszt"></i></a>
								<a href="jelszo.php?id=<?= $row[id]; ?>"><i class="glyphicon glyphicon-asterisk" title="Jelszó"></i></a>
							</td>
							<td><?= $row[nev]; ?></td>
							<td><?= $row[neptunkod]; ?></td>
							<td><?= $row[allando_cim]; ?></td>
							<td><?= $row[ideiglenes_cim]; ?></td>
							<td><?= $row[mobil]; ?></td>
							<td>
<? if( $row[email] ) { ?>
								<input type="checkbox" name="email[]" value="<?= $row[nev] ?> &lt;<?= $row[email]; ?>&gt;" checked>
								<a href="mailto:<?= $row[email]; ?>"> <?= $row[email]; ?></a>

<? } ?>
							</td>
							<td><?= $row[int_nev]; ?></td>
							<td><?= $row[int_cim]; ?></td>
							<td><?= $row[cim]; ?></td>
							<td class="break"><?= $row[feladat]; ?></td>
							<td><?= $row[int_konz_nev]; ?></td>
							<td><?= $row[int_konz_beoszt]; ?></td>
							<td><?= $row[int_konz_tel]; ?></td>
							<td>
<? if( $row[int_konz_emial] ) { ?>
								<input type="checkbox" name="int_konz_email[]" value="<?= $row[int_konz_nev]; ?> &lt;<?= $row[int_konz_emial]; ?>&gt;" checked>
								<a href="mailto:<?= $row[int_konz_emial]; ?>"><?= $row[int_konz_emial]; ?></a>
<? } ?>
							</td>
							<td><?= $row[k_nev]; ?></td>
							<td><?= $row[k_beoszt]; ?></td>
							<td><?= $row[k_tel]; ?></td>
							<td>
<? if( $row[k_email] ) { ?>
								<input type="checkbox" name="k_email[]" value="<?= $row[k_nev]; ?> &lt;<?= $row[k_email]; ?>&gt;" checked>
								<a href="mailto:<?= $row[k_email]; ?>"><?= $row[k_email]; ?></a>
<? } ?>
							</td>
							<td><?= $row[kollegium] ? 'Van' : 'Nincs'; ?></td>
							<td class="break"><?= $row[megjegyzes]; ?></td>
							<td><?= date( 'Y.m.d. H:m', $row[jelentkezes] ); ?></td>
							<td><?= $row[kiiras] ? date( 'Y.m.d. H:m', $row[kiiras] ) : ''; ?></td>
							<td>
								<a href="nyomtatvany.php?id=<?= $row[id]; ?>&tipus=fel"><i class="glyphicon glyphicon-home" title="Feladatlap"></i></a>
								<a href="nyomtatvany.php?id=<?= $row[id]; ?>&tipus=em"><i class="glyphicon glyphicon-briefcase" title="Megállapodás"></i></a>
								<a href="nyomtatvany.php?id=<?= $row[id]; ?>&tipus=ert"><i class="glyphicon glyphicon-comment" title="Értékelés"></i></a>
							</td>
						</tr>
<? }
   $done = true;
?>
					</tbody>
				</table>
			</form>
			<div id="toolbar">
				<a href="../jelentkezes.php" class="btn btn-default" role="button">Új jelentkezés</a>
				<a href="excel.php" class="btn btn-default" role="button">Excel</a>
				<a href="setup.php" class="btn btn-default" role="button">Beállítások</a>
			</div>
		</div>
<?
if( !$done ) {
?>
		<div class="jumbotron">
			<h2>Szakmai gyakorlat</h2>
			<p>Az adatok lekérdezése sikertelen!<p>
			<div class="btn-group" role="group">
				<a href="index.php" class="btn btn-default" role="button">Újra</a>
				<a href="setup.php" class="btn btn-default" role="button">Beállítások</a>
			</div>
		</div>
<?
}

require '../footer.php';

?>
