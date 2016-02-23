<?php

$_SERVER['REQUEST_URI'] = '/koordinator';

require_once '../functions.php';
require '../header.php';

$table = all_read();
var_dump($table);

?>
<?php 
if( $table === false ) { 
    ?>
        <div class="jumbotron">
            <h2>Szakmai gyakorlat</h2>
            <p>Az adatok lekérdezése sikertelen!<p>
            <div class="btn-group" role="group">
                <a href="index.php" class="btn btn-default" role="button">Újra</a>
                <a href="setup.php" class="btn btn-default" role="button">Beállítások</a>
            </div>
        </div>
    <?php
} else { 
    ?>
		<header>
			<h1><?=GYAKORLAT_EV?>. évi szakmai gyakorlat</h1>
		</header>
		<div class="content">
			<form method="post" action="email.php" target="_blank" style="height: 80%">
				<table data-toggle="table" data-show-columns="true" data-search="true" data-toolbar="#toolbar" data-smart-display="false"
					   data-page-size="5">
					<thead>
						<tr>
							<th data-align="center">Műveletek</th>
							<th data-sortable="true">Hallgató</th>
							<th data-sortable="true">Neptun-kód</th>
                            <th data-sortable="true">FIR azonosító</th>
							<th>Állandó lakcím</th>
							<th>Értesítési cím</th>
							<th>Mobiltelefon</th>
							<th data-sortable="true">Hallgató e-mail címe <input name="h_email" type="submit" value="&raquo;" class="btn btn-default btn-xs"></th>
							<th data-sortable="true">Intézmény neve</th>
							<th>Intézmény címe</th>
							<th data-sortable="true">Feladat címe</th>
							<th>Feladat részletezése</th>
							<th>Külső konzulens</th>
							<th>Külső konzulens beosztása</th>
							<th>Külső konzulens telefonszáma</th>
							<th>Külső konzulens e-mail címe <input name="kk_email" type="submit" value="&raquo;" class="btn btn-default btn-xs"></th>
                            <th>Igazoló</th>
                            <th>Igazoló beosztása</th>
                            <th>Igazoló telefonszáma</th>
                            <th>Igazoló e-mail címe <input name="ig_email" type="submit" value="&raquo;" class="btn btn-default btn-xs"></th>
                            <th data-sortable="true">Tanszéki konzulens</th>
							<th>Tanszéki konzulens beosztása</th>
							<th>Tanszéki konzulens telefonszáma</th>
							<th>Tanszéki konzulens e-mail címe <input name="tk_email" type="submit" value="&raquo;" class="btn btn-default btn-xs"></th>
							<th data-sortable="true">Kollégiumi igény</th>
                            <th data-sortable="true">Képzés típusa</th>
							<th>Megjegyzés</th>
                            <th data-sortable="true">Gyakorlat kezdete</th>
                            <th data-sortable="true">Gyakorlat vége</th>
                            <th data-sortable="true">Jelentkezés ideje</th>
							<th data-sortable="true">Jelentkezés ideje</th>
							<th data-sortable="true">Utolsó módosítás</th>
						</tr>
					</thead>
					<tbody>
<?      foreach( $table as $row ) { ?>
						<tr>
							<td>
								<a href="torol.php?id=<?=$row['id']?>" target="_blank"><i class="glyphicon glyphicon-trash" title="Töröl"></i></a>
								<a href="feladat.php?id=<?=$row['id']?>" target="_blank"><i class="glyphicon glyphicon-edit" title="Szerkeszt"></i></a>
								<a href="jelszo.php?id=<?=$row['id']?>" target="_blank"><i class="glyphicon glyphicon-asterisk" title="Jelszó"></i></a>
							</td>
							<td><?=$row['nev']?></td>
							<td><?=$row['neptunkod']?></td>
							<td><?=$row['allando_cim']?></td>
							<td><?=$row['ideiglenes_cim']?></td>
							<td><?=$row['mobil']?></td>
							<td>
<?          if( ! empty( $row[email] ) ) { ?>
								<input type="checkbox" name="email[]" value="<?=$row['nev']?> &lt;<?=$row['email']?>&gt;" checked>
								<a href="mailto:<?=$row['email']?>"> <?=$row['email']?></a>

<?          } ?>
							</td>
							<td><?=$row['int_nev']?></td>
							<td><?=$row['int_cim']?></td>
							<td><?=$row['cim']?></td>
							<td class="break"><?=$row['feladat']?></td>
							<td><?=$row['int_konz_nev']?></td>
							<td><?=$row['int_konz_beoszt']?></td>
							<td><?=$row['int_konz_tel']?></td>
							<td>
<?          if( ! empty( $row['int_konz_emial'] ) ) { ?>
								<input type="checkbox" name="int_konz_email[]" value="<?=$row['int_konz_nev']?> &lt;<?=$row['int_konz_emial']?>&gt;" checked>
								<a href="mailto:<?=$row['int_konz_emial']?>"><?=$row['int_konz_emial']?></a>
<?          } ?>
							</td>
			 				<td><?=$row['k_nev']?></td>
							<td><?=$row['k_beoszt']?></td>
							<td><?=$row['k_tel']?></td>
							<td>
<?          if( ! empty(  $row[k_email] ) ) { ?>
								<input type="checkbox" name="k_email[]" value="<?= $row[k_nev]; ?> &lt;<?= $row[k_email]; ?>&gt;" checked>
								<a href="mailto:<?= $row[k_email]; ?>"><?= $row[k_email]; ?></a>
<?          } ?>
							</td>
							<td><?= $row[kollegium] ? 'Van' : 'Nincs'; ?></td>
							<td class="break"><?= $row[megjegyzes]; ?></td>
							<td><?= date( 'Y.m.d. H:m', $row[jelentkezes] ); ?></td>
							<td><?= $row[kiiras] ? date( 'Y.m.d. H:m', $row[kiiras] ) : ''; ?></td>
						</tr>
<?      } ?>
					</tbody>
				</table>
			</form>
			<div id="toolbar">
				<a href="../jelentkezes.php" class="btn btn-default" role="button">Új jelentkezés</a>
				<a href="excel.php" class="btn btn-default" role="button">Excel</a>
                <a href="excel.php" class="btn btn-default" role="button">Excel</a>
				<a href="setup.php" class="btn btn-default" role="button">Beállítások</a>
			</div>
		</div>
<? } ?>
<?

require '../footer.php';

?>
