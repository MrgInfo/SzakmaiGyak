<?
require '../header.php';
$email_list = '';
if( $_POST[h_email] ) $list = $_POST[email];
elseif( $_POST[v_email] ) $list = $_POST[int_vez_email];
elseif( $_POST[kk_email] ) $list = $_POST[int_konz_email];
elseif( $_POST[tk_email] ) $list = $_POST[k_email];
$first = true;
if( is_array( $list ) ) {
	foreach( array_unique( $list ) as $email ) {
		if( $email_list ) {
			$email_list .= ', ';
		}
		$email_list .= trim( str_replace( array( "<", ">" ), array( "&lt;", "&gt;" ), $email ) );
	}
}
?>
		<textarea style="width: 100%; height: 90%"><?= $email_list; ?></textarea>
<?
require '../footer.php';
?>
