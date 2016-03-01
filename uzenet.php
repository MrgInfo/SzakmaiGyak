<?php

require 'header.php';

?>
<div class="jumbotron">
    <h2>
        <?= $title ?>
    </h2>
    <p>
        <?= $message ?>
    </p>
    <p>
<?php
if (! empty($modal)) {
    ?>
        <button onclick="close_page();" class="btn btn-default">Vissza</Button>
    <?php
}
else {
    ?>
        <a href="index.php" role="button" class="btn btn-default">Vissza</a>
    <?php
}
?>
    </p>
</div>
<?php

require 'footer.php';
