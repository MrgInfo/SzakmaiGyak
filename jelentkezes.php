<?php

require_once 'config.php';
require_once 'functions.php';

$title = "Jelentkezés";
$done = false;
load_post();
$_POST['allando_cim'] = concatAddress(
    posted( 'allando_cim' ),
    posted( 'allando_cim_isz' ),
    posted( 'allando_cim_var' ),
    posted( 'allando_cim_kt' ),
    posted( 'allando_cim_hsz' ) );
$_POST['ideiglenes_cim'] = concatAddress(
    posted( 'ideiglenes_cim' ),
    posted( 'ideiglenes_cim_isz' ),
    posted( 'ideiglenes_cim_var' ),
    posted( 'ideiglenes_cim_kt' ),
    posted( 'ideiglenes_cim_hsz') );
$_POST['int_cim'] = concatAddress(
    posted( 'int_cim' ),
    posted( 'int_cim_isz' ),
    posted( 'int_cim_var' ),
    posted( 'int_cim_kt' ),
    posted( 'int_cim_hsz' ) );
$_POST['mobil'] = concatPhone(
    posted( 'mobil' ),
    posted( 'mobil_pre' ),
    posted( 'mobil_post' ) );
$_POST['int_konz_tel'] = trimPhone( posted( 'int_konz_tel' ) );
$_POST['int_ig_tel'] = trimPhone( posted( 'int_konz_tel' ) );

$_POST['jelszo'] = generatePassword();
var_dump( $_POST );

$jelentkezes = isset( $_POST['jelentkezes'] );
$szerkesztes = isset( $_POST['szerkesztes'] );
$feladatkiiras =
    isset( $_SERVER['REQUEST_URI'] )
    &&
    substr( $_SERVER['REQUEST_URI'], -strlen( '/feladat.php' ) ) == '/feladat.php';

$missing = false;
if( $jelentkezes || $szerkesztes ) {
    if(
        posted( 'nev' ) == '' ||
        strlen( posted( 'neptunkod' ) ) != 6 ||
        strlen( posted( 'fir' ) ) != 11 ||
        posted( 'allando_cim' ) == '' ||
        posted( 'mobil' ) == '' ||
        posted( 'email'  ) == '' ||
        posted( 'kollegium' ) == '' ||
        posted( 'kepzes' ) == ''
    ) {
        $missing = true;
    }
}

if( ! $missing && $szerkesztes ) {
	if( empty( $_POST['id'] ) ) {
        if( ! jelentkezesi_read() ) {
            $meaasge = empty( $_POST['id'] )
                ? "A hallgató nincs regisztrálva a rendszerben!"
                : "Hibás Neptun-kód vagy jelszó!";
            require 'uzenet.php';
            $done = true;
        }
	}
    else {
        if( jelentkezesi_edit() ) {
            $meaasge = "A változtatásokat mentettük!";
            require 'uzenet.php';
        } else {
            $meaasge = "A módosítások nem hajthatók végre!";
            require 'uzenet.php';
        }
        $done = true;
	}
}
else if( ! $missing && $jelentkezes ) {
    if( jelentkezesi_edit()
        &&
        hallgato_mail()
    ) {
        $email = posted( 'email' );
        $meaasge = "A szakmai gyakorlatra való jelentkezés sikeresen megtörtént, erről e-mail értesítést is küldtünk a <a href=\"mailto:$email\">$email</a> címre.";
        require 'uzenet.php';
    }
    else {
        jelentkezesi_delete();
        $meaasge = "A jelentkezés sikertelen, próbálja meg később!";
        require 'uzenet.php';
    }
    $done = true;
}
if( ! $done )
{
    $readonly = isset( $_POST['szerkesztes'] )
        ? 'readonly'
        : '';
    require 'urlap.php';
}
