<?php

require_once '../functions.php';

load_post();

$list = array();
if (! empty($_POST['h_email'])) {
    $list = $_POST['email'];
}
elseif (! empty($_POST['kk_email'])) {
    $list = $_POST['int_konz_email'];
}
elseif (! empty($_POST['ig_email'])) {
    $list = $_POST['int_ig_email'];
}
elseif (! empty($_POST['tk_email'])) {
    $list = $_POST['tan_konz_email'];
}

$email_list = '';
foreach( array_unique( $list ) as $email ) {
    if( $email_list ) {
        $email_list .= ', ';
    }
    $email_list .= trim( str_replace( array( "<", ">" ), array( "&lt;", "&gt;" ), $email ) );
}

echo($email_list);
