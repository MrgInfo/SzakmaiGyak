<?php

require 'header.php';

?>
<div class="jumbotron">
    <h2>
        <?= $title ?>
    </h2>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="form-inline" role="form">
        <input type="hidden" name="id" value="<?= $id ?>">
        <p><?= $message ?></p>
        <div class="btn-group" role="group">
            <button type="submit" name="<?= $button ?>" value="1" class="btn btn-primary">Igen</button>
            <button onclick="close_page();" class="btn btn-default">Nem</Button>
        </div>
    </form>
</div>
<?php

require 'footer.php';
