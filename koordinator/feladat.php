<?php

require_once 'config.php';
require_once 'functions.php';

$title = "Feladatkiírás";
$done = false;

if( !$_POST['modositas'] ) {
	$done = false;
	if( $conn = connect() ) {
		$id = escape( $_GET[id], $conn );
		$select = <<<END
    SELECT j.id,
           j.nev,
           j.neptunkod,
           j.allando_cim,
           j.allando_tel,
           j.ideiglenes_cim,
           j.ideiglenes_tel,
           j.mobil,
           j.email,
           j.kollegium,
           j.int_nev,
           j.int_cim,
           j.int_vez_nev,
           j.int_vez_beoszt,
           j.int_vez_tel,
           j.int_vez_email,
           j.int_konz_nev,
           j.int_konz_beoszt,
           j.int_konz_tel,
           j.int_konz_emial,
           j.cim,
           j.feladat,
           j.megjegyzes,
           j.jelentkezes,
           f.id fid,
           f.konzulens,
           f.leiras,
           f.kovetelmenyek,
           f.kiiras
     FROM jelentkezesi_lap j
LEFT JOIN feladatlap f
       ON j.id = f.id
    WHERE j.id = $id
END;
		if( $result = @mysql_query( $select, $conn ) ) {
			if( @mysql_num_rows( $result ) == 1 ) {
				$row = @mysql_fetch_array( $result );
				foreach( $row as $key => $value ) {
					$_POST[$key] = $value;
				}
				$done = true;
			}
			@mysql_free_result( $result );
		}
		disconnect( $conn );
	}
	if( !$done ) {
		$errormsg = 'A hallgató nincs regisztrálva!';
	}
} else {
	if( $_POST[nev] == '' ||
		strlen( $_POST[neptunkod] ) != 6 ||
		$_POST[allando_cim] == '' ||
		$_POST[mobil] == '' ||
		$_POST[email] == '' ||
		$_POST[kollegium] == '' ||
		$_POST[konzulens] == '' ) {
		$errormsg = 'Nem minden kötelező mező van kitöltve!';
	}
}
if( $_POST[modositas] && !$errormsg ) {
	$done = false;
	if( $conn = connect() ) {
		$SQL = escape( $_POST, $conn );		
		$update_jel = <<<END
UPDATE jelentkezesi_lap
   SET nev = $SQL[nev],
       neptunkod = UPPER($SQL[neptunkod]),
       allando_cim = $SQL[allando_cim],
       ideiglenes_cim = $SQL[ideiglenes_cim],
       mobil = $SQL[mobil],
       email = $SQL[email],
       kollegium = $SQL[kollegium],
       int_nev = $SQL[int_nev],
       int_cim = $SQL[int_cim],
       int_konz_nev = $SQL[int_konz_nev],
       int_konz_beoszt = $SQL[int_konz_beoszt],
       int_konz_tel = $SQL[int_konz_tel],
       int_konz_emial = $SQL[int_konz_emial],
       cim = $SQL[cim],
       feladat = $SQL[feladat],
       megjegyzes = $SQL[megjegyzes]
 WHERE id = $SQL[id]
END;
		if( $_POST[fid] ) {
			$update_feladatlap = <<<END
UPDATE feladatlap
   SET konzulens = $SQL[konzulens],
       leiras = $SQL[leiras]
 WHERE id = $SQL[fid] 
END;
		} else {
			$update_feladatlap = <<<END
INSERT INTO feladatlap
(
  id,
  konzulens,
  leiras
)
VALUES
(
  $SQL[id],
  $SQL[konzulens],
  $SQL[leiras]
)
END;
		}
		if( @mysql_query( $update_jel, $conn ) &&
		    @mysql_query( $update_feladatlap, $conn ) ) {
			if( $_POST[nev] == '' ||
				$_POST[neptunkod] == '' ||
				$_POST[allando_cim] == '' ||
				$_POST[mobil] == '' ||
				$_POST[email] == '' ||
				$_POST[kollegium] == '' ||
				$_POST[int_nev] == '' ||
				$_POST[int_cim] == '' ||
				$_POST[int_konz_nev] == '' ||
				$_POST[int_konz_emial] == '' ||
				$_POST[konzulens] == '' ||
				$_POST[feladat] == '' ) {
?>
		<div class="jumbotron">
			<h2>Feladatkiírás</h2>
			<p>A módosítások érvénybe léptek, a hallgatót a hiányzó adatok miatt még nem értesítettük.<p>
			<p><a href="index.php" class="btn btn-default" role="button">Vissza</a></p>
		</div>
<?
				$done = true;
			}
            elseif( jovahagyas_mail( $_POST['nev'], $_POST['email'], $subject, $body ) ) {
?>
		<div class="jumbotron">
			<h2>Feladatkiírás</h2>
			<p>A módosítások érvénybe léptek, a hallgatót értesítettük a <a href="mailto:<?= $_POST[email]; ?>"><?= $_POST[email]; ?></a> címen.<p>
			<p><a href="index.php" class="btn btn-default" role="button">Vissza</a></p>
		</div>
<?
				$done = true;
			}
		}
		disconnect( $conn );
	}
	if( !$done ) {
?>
		<div class="jumbotron">
			<h2>Feladatkiírás</h2>
			<p>A módosítások nem hajthatók végre!<p>
			<p><a href="index.php" class="btn btn-default" role="button">Vissza</a></p>
		</div>
<?
	}
} else {
?>
		<header>
			<h1><?=GYAKORLAT_EV?>. évi szakmai gyakorlat</h1>
		</header>
		<div class="content">
			<h2>Feladatkiírás, módosítás</h2>
<?	if( $errormsg ) { ?>
			<div class="alert alert-danger" role="alert">
				<p><?= $errormsg; ?></p>
			</div>
<? 	} ?>
			<form action="feladat.php" method="post">
				<input type="hidden" name="id" value="<?= $_POST[id]; ?>">
				<input type="hidden" name="fid" value="<?= $_POST[fid]; ?>">
				<input type="hidden" name="konzulens" value="<?= $_POST[konzulens]; ?>">
				<input type="hidden" name="leiras" value="<?= $_POST[leiras]; ?>">
				<table class="form">
					<tbody>
						<tr>
							<td class="sep" colspan="2"><label>1. Hallgatói adatok</label></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label label-req" for="nev">Név:</label></td>
							<td><input type="text" id="nev" name="nev" value="<?= $_POST[nev]; ?>" size="60" maxlength="40" <?= $_POST[szerkesztes] ? 'readonly' : ''; ?> class="form-control"></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label label-req" for="neptunkod">Neptun-kód:</label></td>
							<td><input type="text" id="neptunkod" name="neptunkod" value="<?= $_POST[neptunkod]; ?>" size="6" maxlength="6" <?= $_POST[szerkesztes] ? 'readonly' : ''; ?> class="form-control"></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label label-req" for="allando_cim">Állandó lakóhely címe:</label></td>
							<td><input type="text" id="allando_cim" name="allando_cim" value="<?= $_POST[allando_cim]; ?>" size="60" maxlength="50" class="form-control"></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label" for="ideiglenes_cim">Lakása címe oktatási időszakban:</label></td>
							<td><input type="text" id="ideiglenes_cim" name="ideiglenes_cim" value="<?= $_POST[ideiglenes_cim]; ?>" size="60" maxlength="50" class="form-control"></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label label-req" for="mobil">Mobil telefonjának száma:</label></td>
							<td><input type="text" id="mobil" name="mobil" value="<?= $_POST[mobil]; ?>" size="20" maxlength="20" class="form-control"></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label label-req" for="email">E-mail címe:</label></td>
							<td><input type="email" id="email" name="email" value="<?= $_POST[email]; ?>" size="60" maxlength="30" class="form-control"></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label label-req" for="kollegium">Gyakorlata alatt igényel-e kollégiumi elhelyezést:</label></td>
							<td>
								<div class="controls form-inline">
									<div class="radio">
										<label><input type="radio" id="kollegium" name="kollegium" value="1" <?= $_POST[kollegium] ? 'checked' : ''; ?>> Igen</label>
									</div>
									<div class="radio">
										<label><input type="radio" name="kollegium" value="0" <?= $_POST[kollegium] ? '' : 'checked'; ?>> Nem</label>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td class="sep" colspan="2"><label>2. A fogadó intézmény adatai</label></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label label-mand" for="int_nev">Teljes neve*:</label></td>
							<td><input type="text" id="int_nev" name="int_nev" value="<?= $_POST[int_nev]; ?>" size="60" maxlength="35" class="form-control"></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label label-mand" for="int_cim">Címe*:</label></td>
							<td><input type="text" id="int_cim" name="int_cim" value="<?= $_POST[int_cim]; ?>" size="60" maxlength="80" class="form-control"></td>
						</tr>
						<tr>
							<td class="sep" colspan="2"><label>3. Üzemi konzulens</label></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label label-mand" for="int_konz_nev">Neve*:</label></td>
							<td><input type="text" id="int_konz_nev" name="int_konz_nev" value="<?= $_POST[int_konz_nev]; ?>" size="60" maxlength="35" class="form-control"></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label" for="int_konz_beoszt">Beosztása:</label></td>
							<td><input type="text" id="int_konz_beoszt" name="int_konz_beoszt" value="<?= $_POST[int_konz_beoszt]; ?>" size="60" maxlength="20" class="form-control"></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label" for="int_konz_tel">Telefonszáma:</label></td>
							<td><input type="text" id="int_konz_tel" name="int_konz_tel" value="<?= $_POST[int_konz_tel]; ?>" size="20" maxlength="20" class="form-control"></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label label-mand" for="int_konz_emial">E-mail címe*:</label></td>
							<td><input type="email" id="int_konz_emial" name="int_konz_emial" value="<?= $_POST[int_konz_emial]; ?>" size="60" maxlength="30" class="form-control"></td>
						</tr>
						<tr>
							<td class="sep" colspan="2"><label>4. Tanszéki konzulens</label></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label label-req" for="konzulens">Neve:</label></td>
							<td>
								<div class="controls form-inline">
									<select id="konzulens" name="konzulens" class="form-control">
<?
	if( $conn = connect() ) {
		if( $result = @mysql_query( 'SELECT id, nev FROM konzulensek ORDER BY nev', $conn ) ) {
			$selection = false;
			while( $row = @mysql_fetch_array( $result ) ) {
				if( $_POST[konzulens] ? $row[id] == $_POST[konzulens] : $row[nev] == KONZULENS ) {
?>
										<option value="<?= $row[id]; ?>" selected><?= $row[nev]; ?></option>
<?
					$selection = true;
				} else {
?>
										<option value="<?= $row[id]; ?>"><?= $row[nev]; ?></option>
<?
				}
				$_POST[$key] = $value;
			}
?>
										<option value="" <?= $selection ? '' : 'selected'; ?>>&ndash; &deg; &ndash;</option>
<?
			@mysql_free_result( $result );
		}
		disconnect( $conn );
	}
?>
									</select>
									<a href="tanszeki_konz.php" target="_blank" role="button" class="btn btn-default"> + </a>
								</div>
							</td>
						</tr>
						<tr>
							<td class="sep" colspan="2"><label>5. A feladat</label></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label" for="cim">Címe:</label></td>
							<td><input type="text" id="cim" name="cim" value="<?= $_POST[cim]; ?>" size="60" maxlength="100" class="form-control"></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label label-mand" for="feladat">Részletezése*:</label></td>
							<td><textarea id="feladat" name="feladat" rows="8" cols="62" maxlength="1000" placeholder="Maximum 1000 karakter." class="form-control"><?= $_POST[feladat]; ?></textarea></td>
						</tr>
						<tr>
							<td class="form-label"><label class="control-label" for="megjegyzes">Megjegyzés:</label></td>
							<td><textarea id="megjegyzes" name="megjegyzes" rows="8" cols="62" maxlength="500" placeholder="pl. tanszéki konzulens" class="form-control"><?= $_POST[megjegyzes]; ?></textarea></td>
						</tr>
						<tr>
							<td colspan="2">
								<div class="btn-group" role="group">
									<input type="submit" name="modositas" value="Módosítás" class="btn btn-primary">
									<a href="index.php" role="button" class="btn btn-default">Mégsem</a>
								</div>
							</td>
						</tr>
					<tbody>
				</table>      
			</form>
		</div>
		<footer class="fn">
			<p><span class="label-req">Kötelezően töltendő adatok.</span></p>
			<p><span class="label-mand">* A mező kitöltése jelentkezéshez nem, de a feladat jóváhagyásához kötelezően kitöltendő adat!</span></p>
		</footer>
<?
}

include '../footer.php';

?>

