<?php

require_once "config.php";

define('DB_HOST',      'localhost');
define('DB_USER',      'szakmaigyak');
define('DB_PASSWORD',  '/tomi');

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
        isset($_POST['bsc'])
        &&
        isset($_POST['kollegium']);
}

function check_mandantory() {
    return
        check_required()
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
        isset($_POST['tan_konz'])
        &&
        ! empty($_POST['igazolas'])
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
    $body = <<<BODY
$body

---
Kérjük, erre az e-mailre ne válaszoljon!
BODY;
    $return = mail( $to, $subject, $body, $headers, "-f$from" );
    if(! $return) {
	print_r(error_get_last());
    }
    return $return;
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
    if( substr( $phone, 3, 2 ) == $prefix ) {
	return 'selected=selected';
    }
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
    if (! empty($isz) && ! empty($var) && ! empty($kt) && ! empty($hsz)) {
        $hsz = trim($hsz, '.');
        return "$isz $var, $kt $hsz.";
    }
    return null;
}

function _read( $query ) {
    $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
    if( ! $conn ) {
        return false;
    }
    $conn->set_charset( 'utf8' );
    $result = $conn->query( $query );
    if( ! $result ) {
        @$conn->close();
        return false;
    }
    $records = array();
    while( $row = $result->fetch_assoc() ) {
        $record = array();
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
       UNIX_TIMESTAMP(igazolas) igazolas,
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
        $table = _read($select);
        return $table;
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
        $table = _read($select);
        return $table[0];
    }
}

function jelentkezesi_read($id, $neptunkod, $jelszo) {
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

function jelentkezesi_read_beautiful() {
    $select = <<<QUERY
   SELECT nev "Hallgató",
          neptunkod "Neptun-kód",
          omazonosito "Oktatási azonosító",
          allando_cim "Állandó lakcím",
          ideiglenes_cim "Értesítési cím",
          mobil "Mobiltelefon",
          email "E-mail",
          int_nev "Intézmény neve",
          int_cim "Intézmény címe",
          cim "Feladat címe",
          feladat "Feladat részletezése",
          eleje "Gyakorlat kezdete",
          vege "Gyakorlat vége",
          int_konz_nev "Külső konzulens",
          int_konz_beoszt "K.k. beosztása",
          int_konz_tel "K.k. telefonszáma",
          int_konz_email "K.k. e-mail címe",
          int_ig_nev "Igazoló",
          int_ig_beoszt "I. beosztása",
          int_ig_tel "I. telefonszáma",
          int_ig_email = "I. e-mail címe",
          tan_konz_nev "Tanszéki konzulens",
          tan_konz_beoszt "T.k. beosztása",
          tan_konz_tel "T.k. telefonszáma",
          tan_konz_email "T.k. e-mail címe",
          CASE kollegium WHEN 1 THEN 'Van' ELSE 'Nincs' END "Kollégiumi igény",
          CASE bsc WHEN 1 THEN 'BSc' ELSE 'MSc' END "Képzés",
          megjegyzes "Megjegyzés",
          jelentkezes "Jelentkezés ideje",
          modositas "Utolsó módosítás"
     FROM jelentkezesi_lap
 ORDER BY nev
QUERY;
    return _read( $select );
}

function jelentkezesi_delete($id) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (! $conn) {
        return false;
    }
    $conn->set_charset('utf8');
    if (empty($id)) {
        $delete = <<<COMMNAD
DELETE FROM jelentkezesi_lap
      WHERE id = LAST_INSERT_ID()
COMMNAD;
        $stmt = $conn->prepare($delete);
    }
    else {
        $delete = <<<COMMNAD
DELETE FROM jelentkezesi_lap
      WHERE id = ?
COMMNAD;
        $stmt = $conn->prepare($delete);
        $stmt->bind_param('i', $id);
    }
    $result = $stmt->execute();
    @$stmt->close();
    @$conn->close();
    return $result;
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
    $bsc = posted( 'bsc', 0 );
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
    $igazolas = posted( 'igazolas', null );
    $eleje = posted( 'eleje', null );
    $vege = posted( 'vege', null );
    if ($tan_konz) {
        $db = konzulens_read($tan_konz);
        $tan_konz_nev = $db['tan_konz_nev'];
        $tan_konz_beoszt =  $db['tan_konz_beoszt'];
        $tan_konz_tel = $db['tan_konz_tel'];
        $tan_konz_email = $db['tan_konz_email'];
    }
    else {
        $tan_konz_nev = null;
        $tan_konz_beoszt =  null;
        $tan_konz_tel = null;
        $tan_konz_email = null;
    }
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
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
       igazolas = ?,
       bsc = ?,
       modositas = now()
 WHERE id = ?
QUERY;
        $stmt = $conn->prepare( $update );
        $stmt->bind_param( 'sssissssssissssssssssssssii',
            $allando_cim, $ideiglenes_cim, $mobil, $kollegium, $int_nev, $int_cim,
            $int_konz_nev, $int_konz_beoszt, $int_konz_tel, $int_konz_email,
            $tan_konz, $tan_konz_nev, $tan_konz_beoszt, $tan_konz_tel, $tan_konz_email,
            $cim, $feladat, $megjegyzes, $int_ig_nev, $int_ig_beoszt, $int_ig_tel,
            $int_ig_email, $eleje, $vege, $igazolas, $bsc, $id );
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
	    igazolas,
            eleje,
            vege,
            bsc)
     VALUES (
        ?, UPPER(?), ?, PASSWORD(?), ?,
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
QUERY;
        $stmt = $conn->prepare( $insert );
        $stmt->bind_param( 'ssssssssissssssssssssssssi',
            $nev, $neptunkod, $omazonosito, $jelszo, $email,
            $allando_cim, $ideiglenes_cim, $mobil, $kollegium, $int_nev,
	    $int_cim, $int_konz_nev, $int_konz_beoszt, $int_konz_tel, $int_konz_email,
            $cim, $feladat, $megjegyzes, $int_ig_nev, $int_ig_beoszt,
	    $int_ig_tel, $int_ig_email, $igazolas, $eleje, $vege,
	    $bsc );
    }
    $result = $stmt->execute();
    @$stmt->close();
    @$conn->close();
    return $result;
}

function jelentkezesi_password($id, $jelszo) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (! $conn) {
        return false;
    }
    $conn->set_charset('utf8');
    $update = <<<DML
UPDATE jelentkezesi_lap
   SET jelszo = PASSWORD(?)
 WHERE id = ?
DML;
    $stmt = $conn->prepare($update);
    $stmt->bind_param('si', $jelszo, $id);
    $result = $stmt->execute();
    @$stmt->close();
    @$conn->close();
    return $result;
}

function konzulens_uj() {
    $nev = posted('nev', null);
    $beoszt = posted('beoszt', null);
    $tel = posted('tel', null);
    $email = posted('email', null);
    $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
    if( ! $conn ) {
        return false;
    }
    $conn->set_charset( 'utf8' );
    $insert = <<<DML
INSERT INTO konzulensek (
            nev,
            beoszt,
            tel,
            email)
     VALUES (?, ?, ?, ?)
DML;
    $stmt = $conn->prepare( $insert );
    $stmt->bind_param( 'ssss', $nev, $beoszt, $tel, $email );
    $result = $stmt->execute();
    @$stmt->close();
    @$conn->close();
    return $result;
}

function neptun() {
    $kepzes = GYAKORLAT_KEPZESKOD;
    $felev = GYAKORLAT_EV;
    $elfogado = ELFOGADO;
    $elfogado_beoszt = ELFOGADO_BEOSZTASA;
    $elfogadas = date('Y.m.d. 00:00:00', strtotime(ELFOGADAS));
    $select = <<<QUERY
SELECT neptunkod as "Hallgató Neptun kódja",
       '$kepzes' as "Képzéskód",
       '$felev' as "Felvétel féléve",
       CONCAT(omazonosito, '-$felev-$kepzes') as "Azonosító",
       'GEGI' as "Szervezeti egység kódja",
       IF(eleje, DATE_FORMAT(eleje, '%Y.%m.%d. 00:00:00'), '') as "Kezdődátum",
       IF(vege, DATE_FORMAT(vege, '%Y.%m.%d. 00:00:00'), '') as "Végdátum",
       CEIL(IFNULL(DATEDIFF(vege, eleje), 0) / 7) as "Időtartam egység száma",
       'Hét' as "Időtartam egysége",
       IFNULL(int_ig_nev, '') as "Igazoló neve",
       IF(igazolas, DATE_FORMAT(igazolas, '%Y.%m.%d. 00:00:00'), '') as "Igazolás dátuma",
       IFNULL(cim, '') as "Leírás",
       '' as "Leírás_1",
       '' as "Leírás_2",
       '' as "Leírás_3",
       '' as "Leírás_4",
       '' as "Külső szervezet név",
       '' as "Szerződés kezdete",
       '' as "Szerződés vége",
       '' as "Szerződés száma",
       '' as "Szerződés megszűnésének indoka",
       '$elfogadas' as "Teljesítés elfogadásának időpontja",
       '$elfogado' as "Elfogadó neve",
       '$elfogado_beoszt' as "Elfogadó beosztása",
       'Kötelezően előírt szakmai gyakorlat' as "Megnevezés",
       IFNULL(int_nev, '') as "Szakmai gyakorlóhely",
       IFNULL(int_konz_nev, '') as "Gyakorlatvezető neve"
  FROM jelentkezesi_lap
 ORDER BY nev
QUERY;
    return _read($select);
}

function hallgato_mail($nev, $email, $neptunkod, $jelszo) {
    $subject = 'Jelentkezés szakmai gyakorlatra';
    $body  = <<<MAIL
Kedves $nev!

Ön sikeresen jelentkezett szakmai gyakorlatra. A megadott adatokat, a tájékoztatóban előírt határidőig,
a megadott Neptun-kóddal ($neptunkod) és a következő jelszó segítségével tudja módosítani: $jelszo.
MAIL;
    return smartmail($nev, $email, $subject, $body);
}

function jovahagyas_mail($nev, $email, $konzulens) {
    $subject = 'Szakmai gyakorlat feladat jóváhagyás';
    $body  = <<<MAIL
Kedves $nev!

A szakmai gyakorlat tanszéki felelőse jóváhagyta a szakmai gyakorlata során elvégzendő feladatát és
$konzulens-t jelölte ki tanszéki konzulensnek.
MAIL;
    return smartmail($nev, $email, $subject, $body);
}

function jelszo_mail($nev, $email, $jelszo) {
    $subject = 'Jelszó változás';
    $body  = <<<MAIL
Kedves $nev!

A szakmai gyakorlat nyomtatványait ezentúl a követketző jelszó segítségével tudja letölteni: $jelszo.
MAIL;
    return smartmail($nev, $email, $subject, $body);
}

function admin_mail($nev, $neptunkod) {
    $admin = ELFOGADO;
    $subject = '[szakmai gyakorlat] Jelentkezés szakmai gyakorlatra';
    $body  = <<<MAIL
Kedves $admin!

$nev ($neptunkod) nevű hallgató szakmai gyakorlatra jelentkezett vagy módosította a jelentkezési adatait.
MAIL;
    return smartmail(ELFOGADO, EMAIL_FROM, $subject, $body);
}
