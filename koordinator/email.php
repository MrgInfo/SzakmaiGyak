<?

load_post();

$list = array();
if( $_POST['h_email'] ) $list = $_POST['email'];
elseif( $_POST['v_email'] ) $list = $_POST['int_vez_email'];
elseif( $_POST['kk_email'] ) $list = $_POST['int_konz_email'];
elseif( $_POST['tk_email'] ) $list = $_POST['k_email'];

$email_list = '';
foreach( array_unique( $list ) as $email ) {
    if( $email_list ) {
        $email_list .= ', ';
    }
    $email_list .= trim( str_replace( array( "<", ">" ), array( "&lt;", "&gt;" ), $email ) );
}

echo($email_list);
