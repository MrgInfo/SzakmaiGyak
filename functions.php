<?php

require_once "config.php";

date_default_timezone_set( 'Europe/Budapest' );

function load_post() {
	if( empty( $_POST ) ) {
		$_POST = array();
		parse_str( urldecode( file_get_contents( "php://input" ) ), $_POST );
	}
}

function load_get() {
    if( empty( $_GET ) ) {
        $_GET = array();
        $_GET = filter_input(INPUT_GET, "id");
    }
}

function posted( $name, $default = '' ) {
    return isset( $_POST[$name] )
        ? $_POST[$name]
        : $default;
}

function check_required() {
    return
        ! empty($_POST['nev'])
        &&
        ! empty($_POST['neptunkod'])
        &&
        strlen($_POST['neptunkod']) == 6
        &&
        ! empty($_POST['omazonosito'])
        &&
        strlen($_POST['omazonosito']) == 11
        &&
        ! empty($_POST['allando_cim'])
        &&
        ! empty($_POST['mobil'])
        &&
        ! empty($_POST['email'])
        &&
        ! empty($_POST['kepzes'])
        &&
        ! empty($_POST['kollegium']);
}

function check_mandantory() {
    return
        ! check_required()
        &&
        ! empty($_POST['int_nev'])
        &&
        ! empty($_POST['int_cim'])
        &&
        ! empty($_POST['int_konz_nev'])
        &&
        ! empty($_POST['int_konz_emial'])
        &&
        ! empty($_POST['int_ig_nev'])
        &&
        ! empty($_POST['int_ig_emial'])
        &&
        ! empty($_POST['tan_konz'])
        &&
        ! empty($_POST['eleje'])
        &&
        ! empty($_POST['vege'])
        &&
        ! empty($_POST['feladat']);
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
    $return = mail( $to, $subject, $body, $headers, "-f$from" );
	return $return;
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
	if( ! empty( $def ) ) {
        return $def;
    }
	if( strlen($pre) == 2 && strlen($post) == 7 ) {
        return "+36$pre$post";
    }
	return null;
}

function trimPhone( $phone ) {
	return str_replace( " ", "", $phone );
}

function concatAddress( $def, $isz, $var, $kt, $hsz ) {
	if (! empty($def)) {
        return $def;
    }
	if (! empty($isz)
        &&
        ! empty($var)
        &&
        ! empty($kt)
        &&
        ! empty($hsz)) {
        return trim("$isz $var, $kt $hsz.", '.');
    }
	return null;
}

function _read( $query ) {
    $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
    if( ! $conn ) {
        echo $conn->error;
        return false;
    }
    $conn->set_charset( 'utf8' );
    $result = $conn->query( $query );
    if( ! $result ) {
        echo $conn->error;
        @$conn->close();
        return false;
    }
    $records = array();
    while( $row = $result->fetch_assoc() ) {
        $record =  array();
        foreach( $row as $key => $value ) {
            $record[$key] = $value;
        }
        $records[] = $record;
    }
    @$result->free();
    @$conn->close();
    return $records;
}

function all_read() {
    $select = <<<QUERY
SELECT id,
       nev,
       neptunkod,
       omazonosito,
       allando_cim,
       allando_tel,
       ideiglenes_cim,
       ideiglenes_tel,
       mobil,
       email,
       kollegium,
       bsc,
       int_nev,
       int_cim,
       int_konz_nev,
       int_konz_beoszt,
       int_konz_tel,
       int_konz_email,
       tan_konz,
       tan_konz_nev,
       tan_konz_beoszt,
       tan_konz_tel,
       tan_konz_email,
       int_ig_nev,
       int_ig_beoszt,
       int_ig_tel,
       int_ig_email,
       cim,
       feladat,
       megjegyzes,
       UNIX_TIMESTAMP(eleje) eleje,
       UNIX_TIMESTAMP(vege) vege,
       UNIX_TIMESTAMP(jelentkezes) jelentkezes,
       UNIX_TIMESTAMP(modositas) modositas
  FROM jelentkezesi_lap
QUERY;
    return _read( $select );
}

function konzulens_read( $id ) {
    if (empty($id)) {
        $select = <<<QUERY
  SELECT id,
         nev
    FROM konzulensek
ORDER BY nev
QUERY;
        return _read($select);
    }
    else {
        $select = <<<QUERY
  SELECT nev tan_konz_nev,
         beoszt tan_konz_beoszt,
         tel tan_konz_tel,
         email tan_konz_email
    FROM konzulensek
   WHERE id = $id
QUERY;
        return _read($select);
    }
}

function jelentkezesi_read( $id, $neptunkod, $jelszo ) {
    $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
    if( ! $conn ) {
        return false;
    }
    $conn->set_charset( 'utf8' );
    $select = <<<QUERY
SELECT id,
       nev,
       neptunkod,
       omazonosito,
       allando_cim,
       ideiglenes_cim,
       mobil,
       email,
       kollegium,
       bsc,
       int_nev,
       int_cim,
       int_konz_nev,
       int_konz_beoszt,
       int_konz_tel,
       int_konz_email,
       tan_konz,
       tan_konz_nev,
       tan_konz_beoszt,
       tan_konz_tel,
       tan_konz_email,
       int_ig_nev,
       int_ig_beoszt,
       int_ig_tel,
       int_ig_email,
       cim,
       feladat,
       megjegyzes,
       eleje,
       vege
  FROM jelentkezesi_lap
 WHERE (id = ?)
    OR (neptunkod = UPPER(?) AND jelszo = PASSWORD(?))
QUERY;
    $stmt = $conn->prepare( $select );
    $stmt->bind_param( 'iss', $id, $neptunkod, $jelszo );
    if( ! $stmt->execute() ) {
        @$stmt->close();
        @$conn->close();
        return false;
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if( ! $row ) {
        @$stmt->close();
        @$conn->close();
        return false;
    }
    foreach( $row as $key => $value ) {
        $_POST[$key] = $value;
    }
    @$stmt->close();
    @$conn->close();
    return true;
}

function jelentkezesi_delete() {
}

function jelentkezesi_edit() {
    $id = posted( 'id', null );
    $nev = posted( 'nev', null );
    $neptunkod = posted( 'neptunkod', null );
    $omazonosito = posted( 'omazonosito', null );
    $jelszo = posted( 'jelszo', null );
    $email = posted( 'email', null );
    $allando_cim = posted( 'allando_cim', null );
    $ideiglenes_cim = posted( 'ideiglenes_cim', null );
    $mobil = posted( 'mobil', null );
    $kollegium = posted( 'kollegium', 0 );
    $int_nev = posted( 'int_nev', null );
    $int_cim = posted( 'int_cim', null );
    $int_konz_nev = posted( 'int_konz_nev', null );
    $int_konz_beoszt = posted( 'int_konz_beoszt', null );
    $int_konz_tel = posted( 'int_konz_tel', null );
    $int_konz_email = posted( 'int_konz_email', null );
    $tan_konz = posted( 'tan_konz', null );
    $cim = posted( 'cim', null );
    $feladat = posted( 'feladat', null );
    $megjegyzes = posted( 'megjegyzes', null );
    $int_ig_nev = posted( 'int_ig_nev', null );
    $int_ig_beoszt = posted( 'int_ig_beoszt', null );
    $int_ig_tel = posted( 'int_ig_tel', null );
    $int_ig_email = posted( 'int_ig_email', null );
    $eleje = posted( 'eleje', null );
    $vege = posted( 'vege', null );
    $bsc = posted( 'bsc', 0 );

    if ($tan_konz) {
        $db = konzulens_read($tan_konz);
        $tan_konz_nev = $db[0]['tan_konz_nev'];
        $tan_konz_beoszt =  $db[0]['tan_konz_beoszt'];
        $tan_konz_tel = $db[0]['tan_konz_tel'];
        $tan_konz_email = $db[0]['tan_konz_email'];
    }
    else {
        $tan_konz_nev = null;
        $tan_konz_beoszt =  null;
        $tan_konz_tel = null;
        $tan_konz_email = null;
    }
    $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
    if( ! $conn ) {
        return false;
    }
    $conn->set_charset( 'utf8' );
    if( $id ) {
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
       int_konz_email = ?,
       tan_konz = ?,
       tan_konz_nev = ?,
       tan_konz_beoszt = ?,
       tan_konz_tel = ?,
       tan_konz_email = ?,
       cim = ?,
       feladat = ?,
       megjegyzes = ?,
       int_ig_nev = ?,
       int_ig_beoszt = ?,
       int_ig_tel = ?,
       int_ig_email = ?,
       eleje = ?,
       vege = ?,
       bsc = ?,
       modositas = now()
 WHERE id = ?
QUERY;
        $stmt = $conn->prepare( $update );
        $stmt->bind_param( 'sssissssssisssssssssssssii',
            $allando_cim, $ideiglenes_cim, $mobil, $kollegium, $int_nev, $int_cim,
            $int_konz_nev, $int_konz_beoszt, $int_konz_tel, $int_konz_email,
            $tan_konz, $tan_konz_nev, $tan_konz_beoszt, $tan_konz_tel, $tan_konz_email,
            $cim, $feladat, $megjegyzes, $int_ig_nev, $int_ig_beoszt, $int_ig_tel,
            $int_ig_email, $eleje, $vege, $bsc, $id );
    }
    else {
        $insert = <<<QUERY
INSERT INTO jelentkezesi_lap (
            nev,
            neptunkod,
            omazonosito,
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
            int_konz_email,
            cim,
            feladat,
            megjegyzes,
            int_ig_nev,
            int_ig_beoszt,
            int_ig_tel,
            int_ig_email,
            eleje,
            vege,
            bsc)
     VALUES (
        ?, UPPER(?), ?, PASSWORD(?), ?,
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
QUERY;
        $stmt = $conn->prepare( $insert );
        $stmt->bind_param( 'ssssssssisssssssssssssssi',
            $nev, $neptunkod, $omazonosito, $jelszo, $email,
            $allando_cim, $ideiglenes_cim, $mobil, $kollegium, $int_nev, $int_cim,
            $int_konz_nev, $int_konz_beoszt, $int_konz_tel, $int_konz_email,
            $cim, $feladat, $megjegyzes, $int_ig_nev, $int_ig_beoszt, $int_ig_tel,
            $int_ig_email, $eleje, $vege, $bsc );
    }
    $result = $stmt->execute();
    if( ! $result ) {
        echo $stmt->error;
    }
    @$stmt->close();
    @$conn->close();
    return $result;
}

function hallgato_mail( $nev, $email, $neptunkod, $jelszo ) {
    $subject = 'Jelentkezés szakmai gyakorlatra';
    $body  = <<<MAIL
Kedves $nev!

Ön sikeresen jelentkezett szakmai gyakorlatra. A megadott adatokat, a tájékoztatóban megadott határidőig,
a megadott Neptun-kóddal ($neptunkod) és a következő jelszó segítségével tudja módosítani: $jelszo.

---
Erre az e-mailre ne válaszoljon!
MAIL;
    return smartmail( $nev, $email, $subject, $body );
}

function jovahagyas_mail( $nev, $email ) {
    $subject = 'Szakmai gyakorlat feladat jóváhagyás';
    $body  = <<<MAIL
Kedves $nev!

A szakmai gyakorlat tanszéki felelőse jóváhagyta a szakmai gyakorlata során elvégzendő feladatát.
A további teendőkről a "Tájékoztató a szakmai gyakorlatról (BSc képzés)" hirdetményben tud tájékozódni.

---
Erre az e-mailre ne válaszoljon!
MAIL;
    return smartmail( $nev, $email, $subject, $body );
}
