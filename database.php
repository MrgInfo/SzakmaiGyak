<?  require_once 'config.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?=DB_USER.':'.DB_PASSWORD.'@'.DB_HOST.'/'.DB_NAME?></title>
    </head>
    <body>
<?  $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
    $conn->set_charset( 'utf8' );
    if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT nev, email FROM konzulensek";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) { ?>
        <ul>
<?      while($row = $result->fetch_assoc()) { ?>
            <li><?=$row['nev']?> (<?=$row['email']?>)</li>
<?      } ?>
        </ul>
<?  } else { ?>
        <p>Semmi!</p>
<?  }
    $conn->close(); ?>
    </body>
</html>
