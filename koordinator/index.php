<?php

require_once '../functions.php';
require_once 'auth.php';

$title = "Szakmai gyakorlat";
$table = all_read();

if( $table === false ) {
    $message = "Az adatok lekérdezése sikertelen!";
    require '../uzenet.php';
}
else {
    $fluid = true;
    require '../header.php';
    ?>
<!--suppress JSUnusedLocalSymbols -->
<script>
    'use strict';

    function rowStyle(row) {
        if (row[ 1] && row[ 2] && row[ 3] &&
            row[ 4] && row[ 6] && row[ 7] &&
            row[ 8] && row[ 9] && row[10] &&
            row[11] && row[12] && row[15] &&
            row[16] && row[19] && row[20] &&
            row[23] && row[26] && row[27] &&
            row[28])
        {
            return { classes: 'success' };
        }
        return {};
    }

    function formatter(email, person, name, index) {
        if (email) {
            return [
                '<div class="item">',
                    '<input type="checkbox" id="' + name + '_' + index + '" name="' +name + '[]" value="' + person + ' &lt;' + email + '&gt;" checked>',
                    '<label for="' + name + '_' + index + '"><a href="mailto:' + email + '">' + email + '</a></label>',
                '</div>'].join('');
        }
        return '';
    }

    function emailFormatter(value, row, index) {
        return formatter(value, row[1], 'email', index);
    }

    function konz_emailFormatter(value, row, index) {
        return formatter(value, row[12], 'int_konz_email', index);
    }

    function ig_emailFormatter(value, row, index) {
        return formatter(value, row[16], 'int_ig_email', index);
    }

    function tan_konz_emailFormatter(value, row, index) {
        return formatter(value, row[20], 'tan_konz_email', index);
    }
</script>
<header>
    <h1><?= GYAKORLAT_EV ?> szakmai gyakorlat</h1>
</header>
<form method="post" action="email.php" target="_blank">
    <table data-toggle="table" data-locale="hu-HU" data-classes="table table-striped"
           data-search="true"
           data-toolbar="#toolbar" data-row-style="rowStyle"
           data-pagination="true" data-smart-display="false" data-page-list="10, 25, 100" data-page-size="100" data-striped="false">
        <thead>
            <tr>
                <th data-align="center">Műveletek</th>
                <th data-sortable="true">Hallgató</th>
                <th data-sortable="true">Neptun-kód</th>
                <th data-sortable="true">Oktatási azonosító</th>
                <th>Állandó lakcím</th>
                <th>Értesítési cím</th>
                <th>Mobiltelefon</th>
                <th data-sortable="true" data-halign="center" data-formatter="emailFormatter"><input name="h_email" type="submit" value="&nbsp;&nbsp;&raquo;&nbsp;&nbsp;" class="btn btn-default btn-xs"><br>E-mail cím</th>
                <th data-sortable="true">Intézmény neve</th>
                <th>Intézmény címe</th>
                <th data-sortable="true">Feladat címe</th>
                <th>Feladat részletezése</th>
                <th>Külső konzulens</th>
                <th>Külső konzulens beosztása</th>
                <th>Külső konzulens telefonszáma</th>
                <th data-halign="center" data-formatter="konz_emailFormatter"><input name="kk_email" type="submit" value="&nbsp;&nbsp;&raquo;&nbsp;&nbsp;" class="btn btn-default btn-xs"><br>Külső konzulens e-mail címe</th>
                <th>Igazoló</th>
                <th>Igazoló beosztása</th>
                <th>Igazoló telefonszáma</th>
                <th data-halign="center" data-formatter="ig_emailFormatter"><input name="ig_email" type="submit" value="&nbsp;&nbsp;&raquo;&nbsp;&nbsp;" class="btn btn-default btn-xs"><br>Igazoló e-mail címe</th>
                <th data-sortable="true">Tanszéki konzulens</th>
                <th>Tanszéki konzulens beosztása</th>
                <th>Tanszéki konzulens telefonszáma</th>
                <th data-halign="center" data-formatter="tan_konz_emailFormatter"><input name="tk_email" type="submit" value="&nbsp;&nbsp;&raquo;&nbsp;&nbsp;" class="btn btn-default btn-xs"><br>Tanszéki konzulens e-mail címe</th>
                <th data-sortable="true">Kollégiumi igény</th>
                <th data-sortable="true">Képzés típusa</th>
                <th data-sortable="true">Gyakorlat kezdete</th>
                <th data-sortable="true">Gyakorlat vége</th>
                <th data-sortable="true">Igazolás ideje</th>
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
                <td><?= $row['email'] ?></td>
                <td><?= $row['int_nev'] ?></td>
                <td><?= $row['int_cim'] ?></td>
                <td class="break"><?= strlen( $row['cim'] ) > 50 ? substr( $row['cim'], 0, 47 ) . '...' : $row['cim'] ?></td>
                <td class="break"><?= strlen( $row['feladat'] ) > 50 ? substr( $row['feladat'], 0, 47 ) . '...' : $row['feladat'] ?></td>
                <td><?= $row['int_konz_nev'] ?></td>
                <td><?= $row['int_konz_beoszt'] ?></td>
                <td><?= $row['int_konz_tel'] ?></td>
                <td><?= $row['int_konz_email'] ?></td>
                <td><?= $row['int_ig_nev'] ?></td>
                <td><?= $row['int_ig_beoszt'] ?></td>
                <td><?= $row['int_ig_tel'] ?></td>
                <td><?= $row['int_ig_email'] ?></td>
                <td><?= $row['tan_konz_nev'] ?></td>
                <td><?= $row['tan_konz_beoszt'] ?></td>
                <td><?= $row['tan_konz_tel'] ?></td>
                <td><?= $row['tan_konz_email'] ?></td>
                <td><?= $row['kollegium'] ? 'Van' : 'Nincs' ?></td>
                <td><?= $row['bsc'] ? 'BSc' : 'MSc' ?></td>
                <td><?= empty( $row['eleje'] ) ? '' : date( 'Y.m.d.', $row['eleje'] ) ?></td>
                <td><?= empty( $row['vege'] ) ? '' : date( 'Y.m.d.', $row['vege'] ) ?></td>
		<td><?= empty( $row['igazolas'] ) ? '' : date( 'Y.m.d.', $row['igazolas'] ) ?></td>
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
    <a href="excel.php" target="_blank" role="button" class="btn btn-default">Excel</a>
    <a href="excel.php?type=neptun" target="_blank" role="button" class="btn btn-default">Neptun</a>
    <a href="excel.php?type=9050" target="_blank" role="button" class="btn btn-default">FRKP-9050</a>
    <a href="setup.php" target="_blank" role="button" class="btn btn-default">Beállítások</a>
</div>
	<?php
    require '../footer.php';
}
