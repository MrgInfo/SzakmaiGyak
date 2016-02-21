<?
$excel_path = dirname( dirname( $_SERVER[SCRIPT_FILENAME] ) );
require_once "$excel_path/functions.php";

// disable caching
$now = gmdate( "D, d M Y H:i:s" );
header( "Expires: Tue, 03 Jul 2001 06:00:00 GMT" );
header( "Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate" );
header( "Last-Modified: {$now} GMT" );
// force download
header( "Content-Type: application/force-download" );
header( "Content-Type: application/octet-stream" );
header( "Content-Type: application/download" );
// disposition / encoding on response body
header( "Content-Disposition: attachment;filename=".DB_NAME.".csv" );
header( "Content-Transfer-Encoding: binary" );

$df = fopen( "php://output", 'w' );
if( $conn = connect() ) {
	$select = <<<END
   SELECT j.nev "Hallgató",
          j.neptunkod "Neptun-kód",
          j.allando_cim "Állandó lakcím",
          j.ideiglenes_cim "Értesítési cím",
          j.mobil "Mobiltelefon",
          j.email "E-mail",
          j.int_nev "Intézmény neve",
          j.int_cim "Intézmény címe",
          j.cim "Feladat címe",
          j.feladat "Feladat részletezése",
          j.int_konz_nev "Külső konzulens",
          j.int_konz_beoszt "Konzulens beosztása",
          j.int_konz_tel "Konzulens telefonszáma",
          j.int_konz_emial "Konzulens e-mail címe",
          k.nev "Tanszéki konzulens",
          k.beoszt "Konzulens beosztása",
          k.tel "Konzulens telefonszáma",
          k.email "Konzulens e-mail címe",
          CASE j.kollegium WHEN 1 THEN 'Van' ELSE 'Nincs' END "Kollégiumi igény",
          j.megjegyzes "Megjegyzés",
          j.jelentkezes "Jelentkezés ideje",
          f.kiiras "Utolsó módosítás"
     FROM jelentkezesi_lap j 
LEFT JOIN feladatlap f
       ON j.id = f.id
LEFT JOIN konzulensek k
       ON f.konzulens = k.id
 ORDER BY j.nev
END;
	if( $result = @mysql_query( $select, $conn ) ) {
		$first = true;
		while( $row = @mysql_fetch_assoc( $result ) ) {
			if( $first ) {
				@fputcsv( $df, array_keys( $row ), ';', '"' );
				$first = false;
			}
			@fputcsv( $df, $row, ';', '"' );
		}
	}
}
fclose( $df );
?>
