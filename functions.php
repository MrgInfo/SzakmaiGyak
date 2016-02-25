<?

require_once "config.php";

date_default_timezone_set( 'Europe/Budapest' );

function load_post() {
	if( empty( $_POST ) ) {
		$_POST = array();
		parse_str( urldecode( file_get_contents( "php://input" ) ), $_POST );
	}
}

function posted( $name, $default = '' ) {
    return isset( $_POST[$name] )
        ? $_POST[$name]
        : $default;
}

function smartmail( $name, $email, $subject, $body ) {
	$from = EMAIL_FROM;
	$headers = <<<END
MIME-Version: 1.0
Content-type: text/plain; charset=utf-8
From: Szakmai gyakorlat <$from>
Reply-To: Szakmai gyarkorlat <$from>
END;
	mb_internal_encoding( "utf-8" );
	$subject = mb_encode_mimeheader( $subject, "utf-8", "B" );
	$to = mb_encode_mimeheader( $name, "utf-8", "B" ) . " <$email>";
	return @mail( $to, $subject, $body, $headers, "-f$from" );
}

function format( $str ) {
	return str_replace(
		array( "\\", "%", "_", "$", "{", "}", "#", "<", ">", "&" ),
		array( "$\setminus$", "\\%", "\\_", "\\$", "\\{", "\\}", "\\#", "$<$", "$>$", "\\&" ),
		$str );
}

function generatePassword( $length = 8 ) {
	$password = "";
	$possible = "0123456789bcdfghjkmnpqrstvwxyz";
	$i = 0;
	while( $i < $length )
	{
		$char = substr( $possible, mt_rand( 0, strlen( $possible ) - 1 ), 1 );
		if( !strstr( $password, $char ) )
		{
			$password .= $char;
			$i++;
		}
	}
	return $password;
}

function removePrefix( $phone ) {
	return substr( $phone, 5 );
}

function isPrefix( $phone, $prefix ) {
	if( substr( $phone, 3, 2 ) == $prefix )
		return 'selected=selected';
	else
		return '';
}

function concatPhone( $def, $pre, $post ) {
	if( $def != '' )
		return $def;
	if( strlen($pre) == 2 && strlen($post) == 7 )
		return "+36$pre$post";
	return '';
}

function trimPhone( $phone ) {
	return str_replace( " ", "", $phone );
}

function concatAddress( $def, $isz, $var, $kt, $hsz ) {
	if( $def != '' )
		return $def;
	if( $isz != '' && $var != '' && $kt != '' && $hsz != '' )
		return trim("$isz $var, $kt $hsz.", '.');
	return '';
}

function _read( $query ) {
    $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
    if( ! $conn ) {
        return false;
    }
    $conn->set_charset( 'utf8' );
    $result = $conn->query( $query );
    $records = array();
    while( $row = $result->fetch_assoc() ) {
        $record =  array();
        foreach( $row as $key => $value ) {
            $record[$key] = $value;
        }
        $records[] = $record;
    }
    $result->free();
    $conn->close();
    return $records;
}

function all_read() {
    $select = <<<QUERY
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
          UNIX_TIMESTAMP(j.jelentkezes) jelentkezes,
          k.nev k_nev,
          k.beoszt k_beoszt,
          k.tel k_tel,
          k.email k_email,
          f.leiras,
          f.kovetelmenyek,
          UNIX_TIMESTAMP(f.kiiras) kiiras
     FROM jelentkezesi_lap j
LEFT JOIN feladatlap f
       ON j.id = f.id
LEFT JOIN konzulensek k
       ON f.konzulens = k.id
 ORDER BY j.nev
QUERY;
    return _read( $select );
}

function konzulens_read() {

}

function jelentkezesi_read() {
    $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
    if( ! $conn ) {
        return false;
    }
    $conn->set_charset( 'utf8' );
    $select = <<<QUERY
SELECT j.id,
       j.nev,
       j.neptunkod,
       j.allando_cim,
       j.ideiglenes_cim,
       j.mobil,
       j.email,
       j.kollegium,
       j.int_nev,
       j.int_cim,
       j.int_konz_nev,
       j.int_konz_beoszt,
       j.int_konz_tel,
       j.int_konz_emial,
       j.cim,
       j.feladat,
       j.megjegyzes,
       CASE j.jelszo WHEN PASSWORD(?) THEN 1 ELSE 0 END password
  FROM jelentkezesi_lap j
 WHERE j.neptunkod = UPPER(?)
QUERY;
    $stmt = $conn->prepare( $select );
    $stmt->bind_param( 's', posted( 'jelszo', null ) );
    $stmt->bind_param( 's', posted( 'neptunkod', null ) );
    if( ! $stmt->execute() ) {
        $stmt->close();
        $conn->close();
        return false;
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if( ! $row ) {
        $stmt->close();
        $conn->close();
        return false;
    }
    foreach( $row as $key => $value ) {
        $_POST[$key] = $value;
    }
    $stmt->close();
    $conn->close();
    return true;
}

function jelentkezesi_edit() {
    $id = posted( 'id' );
    $nev = posted( 'nev' );
    $neptunkod = posted( 'neptunkod' );
    $jelszo = posted( 'jelszo' );
    $email = posted( 'email' );
    $allando_cim = posted( 'allando_cim', null );
    $ideiglenes_cim = posted( 'ideiglenes_cim', null );
    $mobil = posted( 'mobil', null );
    $kollegium = posted( 'kollegium', null );
    $int_nev = posted( 'int_nev', null );
    $int_cim = posted( 'int_cim', null );
    $int_konz_nev = posted( 'int_konz_nev', null );
    $int_konz_beoszt = posted( 'int_konz_beoszt', null );
    $int_konz_tel = posted( 'int_konz_tel', null );
    $int_konz_emial = posted( 'int_konz_emial', null );
    $cim = posted( 'cim', null );
    $feladat = posted( 'feladat', null );
    $megjegyzes = posted( 'megjegyzes', null );
    $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
    if( ! $conn ) {
        return false;
    }
    $conn->set_charset( 'utf8' );
    $insert = <<<QUERY
INSERT INTO jelentkezesi_lap (
            nev,
            neptunkod,
            jelszo,
            email,
            allando_cim,
            ideiglenes_cim,
            mobil,
            kollegium,
            int_nev,
            int_cim,
            int_konz_nev,
            int_konz_beoszt,
            int_konz_tel,
            int_konz_emial,
            cim,
            feladat,
            megjegyzes)
     VALUES (?, UPPER(?), PASSWORD(?), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
QUERY;
    $update = <<<QUERY
UPDATE jelentkezesi_lap
   SET allando_cim = ?,
       ideiglenes_cim = ?,
       mobil = ?,
       kollegium = ?,
       int_nev = ?,
       int_cim = ?,
       int_konz_nev = ?,
       int_konz_beoszt = ?,
       int_konz_tel = ?,
       int_konz_emial = ?,
       cim = ?,
       feladat = ?,
       megjegyzes = ?
 WHERE id = ?
QUERY;
    if( $id ) {
        $stmt = $conn->prepare( $update );
    }
    else {
        $stmt = $conn->prepare( $insert );
        $stmt->bind_param( 's', $nev );
        $stmt->bind_param( 's', $neptunkod );
        $stmt->bind_param( 's', $jelszo );
        $stmt->bind_param( 's', $email );
    }
    $stmt->bind_param( 's', $allando_cim );
    $stmt->bind_param( 's', $ideiglenes_cim );
    $stmt->bind_param( 's', $mobil );
    $stmt->bind_param( 'i', $kollegium );
    $stmt->bind_param( 's', $int_nev );
    $stmt->bind_param( 's', $int_cim );
    $stmt->bind_param( 's', $int_konz_nev );
    $stmt->bind_param( 's', $int_konz_beoszt );
    $stmt->bind_param( 's', $int_konz_tel );
    $stmt->bind_param( 's', $int_konz_emial );
    $stmt->bind_param( 's', $cim );
    $stmt->bind_param( 's', $feladat );
    $stmt->bind_param( 's', $megjegyzes );
    if( $id ) {
        $stmt->bind_param( 'i', $id );
    }
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

function hallgato_mail() {
    $subject = 'Jelentkezés szakmai gyakorlatra';
    $body  = <<<MAIL
Kedves $_POST[nev]!

Ön sikeresen jelentkezett szakmai gyakorlatra. A megadott adatokat, a tájékoztatóban megadott határidőig,
a megadott Neptun-kóddal ($_POST[neptunkod]) és a következő jelszó segítségével tudja módosítani: $_POST[jelszo].

---
Erre az e-mailre ne válaszoljon!
MAIL;
    return smartmail( $_POST['nev'], $_POST['email'], $subject, $body );
}
