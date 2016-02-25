<?

require_once 'config.php';

$prefix = isset($_SERVER['REQUEST_URI']) && ( strpos($_SERVER['REQUEST_URI'], '/koordinator/') != false )
    ? '..'
    : '.';

?>
<!DOCTYPE html>
<html lang="hu">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width,initial-scale=1" />
		<meta name="robots" content="noarchive,nofollow,noimageindex,noindex,noodp,nosnippet" />
		<meta name="author" content="Groma István Ph.D." />
		<title><?=GYAKORLAT_EV?>. évi szakmai gyakorlat</title>
		<link rel="stylesheet" href="<?=$prefix?>/css/bootstrap.min.css" />
		<link rel="stylesheet" href="<?=$prefix?>/css/bootstrap-theme.min.css" />
		<link rel="stylesheet" href="<?=$prefix?>/css/bootstrap-table.min.css" />
		<link rel="stylesheet" href="<?=$prefix?>/css/szakmaigyak.css" />
	</head>
	<body class="container<?= isset($fluid) ? '-fluid' : '' ?>">
