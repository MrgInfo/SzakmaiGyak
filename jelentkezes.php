<?php

require_once 'config.php';
require_once 'functions.php';

$title = "Jelentkezés";

load_post();
$_POST['allando_cim'] = concatAddress(posted('allando_cim'), posted('allando_cim_isz'), posted('allando_cim_var'), posted('allando_cim_kt'), posted('allando_cim_hsz'));
$_POST['ideiglenes_cim'] = concatAddress(posted('ideiglenes_cim'), posted('ideiglenes_cim_isz'), posted('ideiglenes_cim_var'), posted('ideiglenes_cim_kt'), posted('ideiglenes_cim_hsz'));
$_POST['int_cim'] = concatAddress(posted('int_cim'), posted('int_cim_isz'), posted('int_cim_var'), posted('int_cim_kt'), posted('int_cim_hsz'));
$_POST['mobil'] = concatPhone(posted('mobil'), posted('mobil_pre'), posted('mobil_post'));
$_POST['int_konz_tel'] = trimPhone(posted('int_konz_tel'));
$_POST['int_ig_tel'] = trimPhone(posted('int_konz_tel'));
$jelentkezes = isset($_POST['jelentkezes']);
$szerkesztes = isset($_POST['szerkesztes']);

if ($jelentkezes) {
    if (check_required()) {
        $nev = posted('nev', null);
        $email = posted('email', null);
        $neptunkod = posted('neptunkod', null);
        $_POST['jelszo'] = generatePassword();
        if (jelentkezesi_edit()
            &&
            hallgato_mail($nev, $email, $neptunkod, $_POST['jelszo'])
            &&
            admin_mail($nev, $neptunkod)
        ) {
            $message = "A szakmai gyakorlatra való jelentkezés sikeresen megtörtént, erről e-mail értesítést is küldtünk a <a href=\"mailto:$email\">$email</a> címre.";
        } else {
	    $message = "Ezzel a NEPTUN kóddal és/vagy Oktatási azonosítóval már regisztráltak!";
            jelentkezesi_delete(null);
	}
        require 'uzenet.php';
    }
    else {
        $missing = true;
        require 'urlap.php';
    }
}
elseif ($szerkesztes) {
    $readonly = true;
    $id = posted('id', null);
    $nev = posted('nev', null);
    $email = posted('email', null);
    $neptunkod = posted('neptunkod', null);
    $jelszo = posted('jelszo', null);
    if ($id) {
        if (check_required()) {
            if (jelentkezesi_edit()) {
                $message = "A változtatásokat mentettük!";
            } else {
                $message = "A módosítások nem hajthatók végre!";
            }
            require 'uzenet.php';
        }
        else {
            $missing = true;
            require 'urlap.php';
        }
    }
    else {
        if (jelentkezesi_read(null, $neptunkod, $jelszo)) {
            require 'urlap.php';
        }
        else {
            $message = "Hibás Neptun-kód vagy jelszó!";
            require 'uzenet.php';
        }
    }
}
else {
    require 'urlap.php';
}

