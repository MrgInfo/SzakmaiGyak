<?php

require_once '../functions.php';
require_once 'auth.php';

load_post();
if (! empty($_POST['h_email'])) {
    $name = 'email';
}
elseif (! empty($_POST['kk_email'])) {
    $name = 'int_konz_email';
}
elseif (! empty($_POST['ig_email'])) {
    $name = 'int_ig_email';
}
elseif (! empty($_POST['tk_email'])) {
    $name = 'tan_konz_email';
}
else {
    $name = '';
}

$list = isset($_POST[$name]) ? $_POST[$name] : array();
$email_list = '';
foreach( array_unique( $list ) as $email ) {
    if( $email_list ) {
        $email_list .= ', ';
    }
    $email_list .= trim( str_replace( array( "<", ">" ), array( "&lt;", "&gt;" ), $email ) );
}

echo($email_list);
