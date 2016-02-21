<?
include '../header.php';
?>
		<div class="jumbotron">
			<h2>Hallgató törlése</h2>
<?
if( !$_POST[torol] ) {
	$done = false;
	if( $conn = connect() ) {
		$id = escape( $_GET[id], $conn );
		$select = <<<END
SELECT j.id,
       j.nev,
       j.neptunkod
  FROM jelentkezesi_lap j
 WHERE j.id = $id
END;
		if( $result = @mysql_query( $select, $conn ) ) {
			if( @mysql_num_rows( $result ) == 1 ) {
				$row = @mysql_fetch_array( $result );
				foreach( $row as $key => $value ) {
					$SQL[$key] = $value;
				}
				$done = true;
			}
			@mysql_free_result( $result );
		}
		disconnect( $conn );
	}
	if( $done ) {
?>
			<p>Biztos törölni akarja a/az <?= $SQL[nev]; ?> (<?= $SQL[neptunkod]; ?>) nevű hallgatót?</p>
			<form action="torol.php" method="post">
				<input type="hidden" name="id" value="<?= $SQL[id]; ?>">
				<div class="btn-group" role="group">
					<input type="submit" name="torol" class="btn btn-primary" value="Töröl" />
					<a href="index.php" class="btn btn-default" role="button">Mégsem</a>
				</div>
			</form>
<?
	} else {
?>
			<p>A hallgató nem található a rendszerben!</p>
			<a href="index.php" class="btn btn-default" role="button">Vissza</a>
<?
	}
} else {
	$done = false;
	if( $conn = connect() ) {
		$id = escape( $_POST[id], $conn );
		$delete_fel = "DELETE FROM feladatlap WHERE id = $id";
		$delete_jel = "DELETE FROM jelentkezesi_lap WHERE id = $id";
		if( @mysql_query( $delete_fel, $conn ) && 
			@mysql_query( $delete_jel, $conn ) ) {
			$done = true;
		}
		disconnect( $conn );
	}
	if( $done ) {
?>
			<p>A hallgató törlése megtörtént.</p>
			<a href="index.php" class="btn btn-default" role="button">Vissza</a>
<?
	} else {
?>
			<p>A hallgató törlése sikertelen!</p>
			<a href="index.php" class="btn btn-default" role="button">Vissza</a>
<?
	}
}
?>
		</div>
<?
include '../footer.php';
?>
