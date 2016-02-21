<?
$nyomtatvany_path = dirname( dirname( $_SERVER[SCRIPT_FILENAME] ) );
require_once "$nyomtatvany_path/config.php";
require_once "$nyomtatvany_path/functions.php";
$types = array(
	'fel' => 'feladatlap',
	'em'  => 'megallapodas',
	'ert' => 'ertekeles'
);
if( !$types[$_REQUEST[tipus]] ) {
	$errormsg = 'Ismeretlen nyomtatvány!';
} else {
	if( $conn = connect() ) {
		if( $_REQUEST[letoltes] ) {
			$nk = escape( $_REQUEST[neptunkod], $conn );
			$jelszo = escape( $_REQUEST[jelszo], $conn );
			$select = <<<END
   SELECT j.nev,
          j.neptunkod,
          j.allando_cim,
          j.allando_tel,
          j.ideiglenes_cim,
          j.ideiglenes_tel,
          j.mobil,
          j.email,
          CASE j.kollegium WHEN 0 THEN 'Nem' ELSE 'Igen' END kollegium,
          j.int_nev,
          j.int_cim,
          j.int_vez_nev vez_nev,
          j.int_vez_beoszt vez_beoszt,
          j.int_vez_tel vez_tel,
          j.int_vez_email vez_email,
          j.int_konz_nev ukonz_nev,
          j.int_konz_beoszt ukonz_beoszt,
          j.int_konz_tel ukonz_tel,
          j.int_konz_emial ukonz_email,
          j.cim,
          j.feladat,
          j.jelentkezes,
          k.nev tkonz_nev,
          k.beoszt tkonz_beoszt,
          k.tel tkonz_tel,
          k.email tkonz_email,
          f.leiras,
          f.kovetelmenyek
     FROM jelentkezesi_lap j 
LEFT JOIN feladatlap f 
       ON j.id = f.id
LEFT JOIN konzulensek k
       ON f.konzulens = k.id
    WHERE j.neptunkod = UPPER($nk)
      AND j.jelszo = PASSWORD($jelszo)
END;
		} else {
			$id = escape( $_REQUEST[id], $conn );
			$select = <<<END
   SELECT j.nev,
          j.neptunkod,
          j.allando_cim,
          j.allando_tel,
          j.ideiglenes_cim,
          j.ideiglenes_tel,
          j.mobil,
          j.email,
          CASE j.kollegium WHEN 0 THEN 'Nem' ELSE 'Igen' END kollegium,
          j.int_nev,
          j.int_cim,
          j.int_vez_nev vez_nev,
          j.int_vez_beoszt vez_beoszt,
          j.int_vez_tel vez_tel,
          j.int_vez_email vez_email,
          j.int_konz_nev ukonz_nev,
          j.int_konz_beoszt ukonz_beoszt,
          j.int_konz_tel ukonz_tel,
          j.int_konz_emial ukonz_email,
          j.cim,
          j.feladat,
          j.jelentkezes,
          k.nev tkonz_nev,
          k.beoszt tkonz_beoszt,
          k.tel tkonz_tel,
          k.email tkonz_email,
          f.leiras,
          f.kovetelmenyek
     FROM jelentkezesi_lap j
LEFT JOIN feladatlap f
       ON j.id = f.id
LEFT JOIN konzulensek k
       ON f.konzulens = k.id
    WHERE j.id = $id
END;
		}
		if( $result = @mysql_query( $select, $conn ) ) {
			if( @mysql_num_rows( $result ) == 1 ) {
				$data = @mysql_fetch_array( $result );
			}
			@mysql_free_result( $result );
		}
		disconnect( $conn );
	}
	if( !$data ) {
		$errormsg = $_REQUEST[letoltes]
			? 'Hibás Neptun-kód vagy jelszó, a hallgató nincs regisztrálva a rendszerben!'
			: 'A hallgató nincs regisztrálva a rendszerben!';
	} elseif( $data[nev] == '' ||
			  $data[neptunkod] == '' ||
			  $data[allando_cim] == '' ||
			  $data[mobil] == '' ||
			  $data[email] == '' ||
			  $data[kollegium] == '' ||
			  $data[int_nev] == '' ||
			  $data[int_cim] == '' ||
			  $data[ukonz_nev] == '' ||
			  $data[ukonz_email] == '' ||
			  $data[jelentkezes] == '' ||
			  $data[tkonz_nev] == '' ||
			  $data[tkonz_email] == '' ) {
		$errormsg = 'A koordinátor még nem írta ki a feladatot, ezért ez a nyomtatvány nem tölthető le. Próbálja meg később!';
	} else {
		$tfn = '../'.$types[$_REQUEST[tipus]].'.tex';
		$in = @fopen( $tfn, 'r' );
		$template = @fread( $in, filesize( $tfn ) );
		@fclose( $in );
		$template = preg_replace( '/\[gyak_ev\]/',    format( GYAKORLAT_EV ),      $template );
		$template = preg_replace( '/\[gyak_kezd\]/',  format( GYAKORLAT_KEZDETE ), $template );
		$template = preg_replace( '/\[gyak_veg\]/',   format( GYAKORLAT_VEGE ),    $template );
		$template = preg_replace( '/\[hatarido\]/',   format( BEADASI_HATARIDO ),  $template );
		$template = preg_replace( '/\[alairo_tsz\]/', format( ALAIRO_TANSZEK ),    $template );
		$template = preg_replace( '/\[alairo_kar\]/', format( ALAIRO_KAR ),        $template );
		foreach( $data as $key => $value ) {
			$template = preg_replace( "/\[$key\]/", format( $value ), $template );
		}
		$tmpfname = tempnam( '/tmp', 'nyomtatvany' );
		$out = @fopen( "$tmpfname.tex", 'w' );
		@fwrite( $out, $template );
		@fclose( $out );
		chdir( '/tmp' );
		exec( EXEC_DIR.'/pdflatex '.basename( $tmpfname ).'.tex' );
		header( 'Content-Description: File Transfer' );
		header( 'Content-Type: application/pdf' );
		header( 'Content-Length: '.filesize( "$tmpfname.pdf" ) );
		header( 'Content-Disposition: attachment; filename='.$types[$_REQUEST[tipus]].'.pdf' );
		readfile( "$tmpfname.pdf" );
		foreach( glob( "$tmpfname.*" ) as $fn ) {
			@unlink( $fn );
		}
		@unlink( $tmpfname );
	}
}
if( $errormsg ) {
	require '../header.php';
?>
		<div class="jumbotron">
			<h2>Nyomtatvány</h2>
			<p><?= $errormsg; ?><p>
			<p><a href="index.php" class="btn btn-default" role="button">Vissza</a></p>
		</div>
<?
	require '../footer.php';
}
?>
