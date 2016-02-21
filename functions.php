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

function all_read() {
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
    $stmt = $conn->prepare( $select );
    //$stmt->bind_param( 's', posted( 'jelszo', null ) );
    if( ! $stmt->execute() ) {
        $stmt->close();
        $conn->close();
        return false;
    }
    $result = $stmt->get_result();
    $records = array();
    while ($row = $result->fetch_assoc()) {
        $record =  array();
        foreach ($row as $key => $value) {
            $record[$key] = $value;
        }
        $records[] = $record;
    }
    $result->free();
    $stmt->close();
    $conn->close();
    return $records;
}