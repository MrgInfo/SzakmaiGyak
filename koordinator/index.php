<?php

require_once '../functions.php';

$fluid = true;
require '../header.php';

$table = all_read();
var_dump($table);

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
}
else {
    ?>
		<header>
			<h1><?= GYAKORLAT_EV  ?>. évi szakmai gyakorlat</h1>
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
							<th data-sortable="true" data-width="400">E-mail cím <input name="h_email" type="submit" value="&raquo;" class="btn btn-default btn-xs"></th>
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
                            <th data-sortable="true">Gyakorlat kezdete</th>
                            <th data-sortable="true">Gyakorlat vége</th>
                            <th>Megjegyzés</th>
                            <th data-sortable="true">Jelentkezés ideje</th>
							<th data-sortable="true">Módosítás ideje</th>
						</tr>
					</thead>
					<tbody>
	<?php
    $idx = 0;
	foreach( $table as $row ) {
        $idx++;
		?>
						<tr>
							<td>
								<a href="torol.php?id=<?= $row['id'] ?>" target="_blank"><i class="glyphicon glyphicon-trash" title="Töröl"></i></a>
								<a href="feladat.php?id=<?= $row['id'] ?>" target="_blank"><i class="glyphicon glyphicon-edit" title="Szerkeszt"></i></a>
								<a href="jelszo.php?id=<?= $row['id'] ?>" target="_blank"><i class="glyphicon glyphicon-asterisk" title="Jelszó"></i></a>
							</td>
							<td><?= $row['nev'] ?></td>
							<td><?= $row['neptunkod'] ?></td>
                            <td><?= $row['omazonosito'] ?></td>
							<td><?= $row['allando_cim'] ?></td>
							<td><?= $row['ideiglenes_cim'] ?></td>
							<td><?= $row['mobil'] ?></td>
							<td>
		<?php
		if( ! empty( $row['email'] ) ) {
			?>
								<input type="checkbox" id="email_<?= $idx ?>" name="email[]" value="<?= $row['nev'] ?> &lt;<?= $row['email'] ?>&gt;" checked>
                                <label for="email_<?= $idx ?>">
                                    <a href="mailto:<?= $row['email'] ?>"><?= $row['email'] ?></a>
                                </label>
			<?php
		}
		?>
							</td>
							<td><?= $row['int_nev'] ?></td>
							<td><?= $row['int_cim'] ?></td>
                            <td class="break"><?= strlen( $row['cim'] ) > 50 ? substr( $row['cim'], 0, 47 ) . '...' : $row['cim'] ?></td>
                            <td class="break"><?= strlen( $row['feladat'] ) > 50 ? substr( $row['feladat'], 0, 47 ) . '...' : $row['feladat'] ?></td>
							<td><?= $row['int_konz_nev'] ?></td>
							<td><?= $row['int_konz_beoszt'] ?></td>
							<td><?= $row['int_konz_tel'] ?></td>
							<td>
		<?php
		if( ! empty( $row['int_konz_email'] ) ) {
			?>
								<input type="checkbox" id="int_konz_email_<?= $idx ?>" name="int_konz_email[]" value="<?= $row['int_konz_nev'] ?> &lt;<?= $row['int_konz_email'] ?>&gt;" checked>
                                <label for="int_konz_email_<?= $idx ?>">
                                    <a href="mailto:<?= $row['int_konz_email'] ?>"><?= $row['int_konz_email'] ?></a>
                                </label>
			<?php
		}
		?>
							</td>
                            <td><?= $row['int_ig_nev'] ?></td>
                            <td><?= $row['int_ig_beoszt'] ?></td>
                            <td><?= $row['int_ig_tel'] ?></td>
                            <td>
                                <?php
                                if( ! empty( $row['int_ig_email'] ) ) {
                                    ?>
                                    <input type="checkbox" id="int_ig_email_<?= $idx ?>" name="int_ig_email[]" value="<?= $row['int_ig_email'] ?> &lt;<?= $row['int_ig_email'] ?>&gt;" checked>
                                    <label for="int_ig_email_<?= $idx ?>">
                                        <a href="mailto:<?= $row['int_ig_email'] ?>"><?= $row['int_ig_email'] ?></a>
                                    </label>
                                    <?php
                                }
                                ?>
                            </td>
                            <td><?= $row['tan_konz_nev'] ?></td>
							<td><?= $row['tan_konz_beoszt'] ?></td>
							<td><?= $row['tan_konz_tel'] ?></td>
							<td>
		<?php
		if( ! empty( $row['tan_konz_email'] ) ) {
			?>
								<input type="checkbox" id="tan_konz_email_<?= $idx ?>" name="tan_konz_email[]" value="<?= $row['tan_konz_nev'] ?> &lt;<?= $row['tan_konz_email'] ?>&gt;" checked>
                                <label for="tan_konz_email_<?= $idx ?>">
                                    <a href="mailto:<?= $row['tan_konz_email'] ?>"><?= $row['tan_konz_email'] ?></a>
                                </label>
			<?php
		}
		?>
							</td>
							<td><?= $row['kollegium'] ? 'Van' : 'Nincs' ?></td>
                            <td><?= $row['bsc'] ? 'BSc' : 'MSc' ?></td>
                            <td><?= empty( $row['eleje'] ) ? '' : date( 'Y.m.d.', $row['eleje'] ) ?></td>
                            <td><?= empty( $row['vege'] ) ? '' : date( 'Y.m.d.', $row['vege'] ) ?></td>
                            <td class="break"><?= strlen( $row['megjegyzes'] ) > 50 ? substr( $row['megjegyzes'], 0, 47 ) . '...' : $row['megjegyzes'] ?></td>
                            <td><?= date( 'Y.m.d. H:m', $row['jelentkezes'] ) ?></td>
							<td><?= empty( $row['modositas'] ) ? '' : date( 'Y.m.d. H:m', $row['modositas'] ) ?></td>
						</tr>
		<?php
	}
	?>
					</tbody>
				</table>
			</form>
			<div id="toolbar">
				<a href="excel.php" class="btn btn-default" role="button">Excel</a>
                <a href="excel.php" class="btn btn-default" role="button">Neptun</a>
                <a href="excel.php" class="btn btn-default" role="button">FRKP-9050</a>
				<a href="setup.php" class="btn btn-default" role="button">Beállítások</a>
			</div>
		</div>
	<?php
}

require '../footer.php';
