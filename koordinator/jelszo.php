<?
include '../header.php';
?>
		<div class="jumbotron">
			<h2>Jelszómódosítás</h2>
<?
$done = false;
if( $_GET[id] ) {
	if( $conn = connect() ) {
		$id = escape( $_GET[id], $conn );
		$select = <<<END
SELECT j.nev,
       j.email
  FROM jelentkezesi_lap j
 WHERE j.id = $id
END;
		if( $result = @mysql_query( $select, $conn ) ) {
			if( @mysql_num_rows( $result ) == 1 ) {
				$_SQL = @mysql_fetch_array( $result );
				$jelszo = generatePassword();
				$jelszo_sql = escape( $jelszo, $conn );
				$update = <<<END
UPDATE jelentkezesi_lap
   SET jelszo = PASSWORD($jelszo_sql)
 WHERE id = $id
END;
				if( @mysql_query( $update, $conn ) ) {
					$subject = 'Jelszó változás';
					$body  = <<<END
Kedves $_SQL[nev]!

A szakmai gyakorlat nyomtatványait ezentúl a követketző jelszó segítségével tudja letölteni: $jelszo.

---
Erre az e-mailre ne válaszoljon!
END;
					if( smartmail( $_SQL[nev], $_SQL[email], $subject, $body ) ) {
?>
			<p>A hallgató jelszava megváltozott, erről értesítést kapott a <a href="mailto:<?= $_SQL[email]; ?>"><?= $_SQL[email]; ?></a> címre.</p>
<?
						$done = true;
					}
				}
			}
			@mysql_free_result( $result );
		}
		disconnect( $conn );
	}
}
if( !$done ) { 
?>
			<p>A jelszóváltoztatás nem hajtható végre!</p>
<? 
}
?>
			<p><a href="index.php" class="btn btn-default" role="button">Vissza</a></p>
		</div>
<?
include '../footer.php';
?>
