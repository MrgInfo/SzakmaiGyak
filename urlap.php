<?php

require_once 'config.php';
require_once 'functions.php';
require 'header.php';

?>
<header>
    <h1>
        <?= GYAKORLAT_EV ?> szakmai gyakorlat
    </h1>
</header>
<div class="content">
    <h2>
        <?= $title ?>
    </h2>
<?php
if (! empty($missing)) {
    ?>
    <div class="alert alert-danger" role="alert">
        <p>Nem minden kötelező mező van kitöltve!</p>
    </div>
    <?php
}
?>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" role="form">
        <input type="hidden" name="id" value="<?= posted( 'id' ) ?>">
        <table class="form border">
            <tbody>
                <tr>
                    <td class="sep" colspan="2">
                        <label>Személyes adatok</label>
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-req" for="nev">Név&deg;:</label>
                    </td>
                    <td>
                        <input type="text" id="nev" name="nev" value="<?= posted( 'nev' ) ?>" size="60" maxlength="40" <?= ! empty($readonly) ? 'readonly' : '' ?> class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-req" for="neptunkod">Neptun-kód&deg;:</label>
                    </td>
                    <td>
                        <input type="text" id="neptunkod" name="neptunkod" value="<?= posted( 'neptunkod' ) ?>" size="6" maxlength="6" <?= ! empty($readonly) ? 'readonly' : '' ?> class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-req" for="omazonosito">Oktatási azonosító&deg;:</label>
                    </td>
                    <td>
                        <input type="text" id="omazonosito" name="omazonosito" value="<?= posted( 'omazonosito' ) ?>" size="11" maxlength="11" <?= ! empty($readonly) ? 'readonly' : '' ?> class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-req" for="allando_cim">Állandó lakóhely címe&deg;:</label>
                    </td>
                    <td>
<?php
if (posted('allando_cim')) {
    ?>
                        <input type="text" id="allando_cim" name="allando_cim" value="<?= posted( 'allando_cim' ) ?>" size="60" maxlength="50" class="form-control">
    <?php
}
else {
    ?>
                        <div class="controls form-inline">
                            <input type="text" id="allando_cim" name="allando_cim_isz" size="4" maxlength="4" placeholder="ir.sz." class="form-control">
                            <input type="text" name="allando_cim_var" size="20" maxlength="200" placeholder="város" class="form-control">,
                            <input type="text" name="allando_cim_kt" size="12" maxlength="30" placeholder="közterület neve" class="form-control">
                            <input type="text" name="allando_cim_hsz" size="5" maxlength="20" placeholder="házszám" class="form-control">
                        </div>
    <?php
}
?>
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label" for="ideiglenes_cim">Lakása címe oktatási időszakban:</label>
                    </td>
                    <td>
<?php
if (posted('ideiglenes_cim')) {
    ?>
                        <input type="text" id="ideiglenes_cim" name="ideiglenes_cim" value="<?= posted( 'ideiglenes_cim' ) ?>" size="60" maxlength="50">
    <?php
}
else {
    ?>
                        <div class="controls form-inline">
                            <input type="text" id="ideiglenes_cim" name="ideiglenes_cim_isz" size="4" maxlength="4" placeholder="ir.sz." class="form-control">
                            <input type="text" name="ideiglenes_cim_var" size="20" maxlength="200" placeholder="város" class="form-control">,
                            <input type="text" name="ideiglenes_cim_kt" size="12" maxlength="30" placeholder="közterület neve" class="form-control">
                            <input type="text" name="ideiglenes_cim_hsz" size="5" maxlength="20" placeholder="házszám" class="form-control">
                        </div>
    <?php
}
?>
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-req" for="mobil">Mobiltelefonszám&deg;:</label>
                    </td>
                    <td>
                        <div class="controls form-inline">
<?php
if (posted('mobil')) {
    ?>
                            <input type="text" id="mobil" name="mobil" value="<?= posted( 'mobil' ) ?>" size="15" maxlength="20" class="form-control">
    <?php
}
else {
    ?>
                            +36
                            <select name="mobil_pre" class="form-control" title="Előhívó">
                                <option selected>20</option>
                                <option>30</option>
                                <option>70</option>
                            </select>
                            <input type="text" id="mobil" name="mobil_post" size="20" maxlength="7" placeholder="számok elválasztás nélkül" class="form-control">
    <?php
}
?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-req" for="email">E-mail cím&deg;:</label>
                    </td>
                    <td>
                        <input type="email" id="email" name="email" value="<?= posted( 'email' ) ?>" size="60" maxlength="30" <?=  ! empty($readonly) ? 'readonly' : '' ?> class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label" for="kepzes">Képzés típusa:</label>
                    </td>
                    <td>
                        <div class="controls form-inline">
                            <div class="radio">
                                <input type="radio" id="bsc" name="bsc" value="1" <?= posted( 'bsc', 1 ) == 1 ? 'checked' : '' ?>>
                                <label for="bsc">BSc</label>
                            </div>
                            <div class="radio">
                                <input type="radio" id="msc" name="bsc" value="0" <?= posted( 'bsc', 1 ) == 0 ? 'checked' : ''  ?>>
                                <label for="msc">MSc</label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label" for="kollegium">Gyakorlata alatt igényel-e<br>kollégiumi elhelyezést:</label>
                    </td>
                    <td>
                        <div class="controls form-inline">
                            <div class="radio">
                                <input type="radio" id="kollegium_igen" name="kollegium" value="1" <?= posted( 'kollegium', 0 ) == 1 ? 'checked' : '' ?>>
                                <label for="kollegium_igen">Igen</label>
                            </div>
                            <div class="radio">
                                <input type="radio" id="kollegium_nem" name="kollegium" value="0" <?= posted( 'kollegium', 0 ) == 0 ? 'checked' : '' ?>>
                                <label for="kollegium_nem">Nem</label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="sep" colspan="2">
                        <label>A fogadó intézmény adatai</label>
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-mand" for="int_nev">Teljes neve*:</label>
                    </td>
                    <td>
                        <input type="text" id="int_nev" name="int_nev" value="<?= posted( 'int_nev' ) ?>" size="60" maxlength="35" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label label-mand" for="int_cim">Címe*:</label></td>
                    <td>
<?php
if (posted('int_cim')) {
    ?>
                        <input type="text" id="int_cim" name="int_cim" value="<?= posted( 'int_cim' ) ?>" size="60" maxlength="50" class="form-control">
    <?php
}
else {
    ?>
                        <div class="controls form-inline">
                            <input type="text" id="int_cim" name="int_cim_isz" size="4" maxlength="4" placeholder="ir.sz." class="form-control">
                            <input type="text" name="int_cim_var" size="20" maxlength="200" placeholder="város" class="form-control">,
                            <input type="text" name="int_cim_kt" size="12" maxlength="30" placeholder="közterület neve" class="form-control">
                            <input type="text" name="int_cim_hsz" size="5" maxlength="20" placeholder="házszám" class="form-control">
                        </div>
    <?php
}
?>
                    </td>
                </tr>
                <tr>
                    <td class="sep" colspan="2"><label>Intézményi konzulens</label></td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-mand" for="int_konz_nev">Neve*:</label>
                    </td>
                    <td>
                        <input type="text" id="int_konz_nev" name="int_konz_nev" value="<?= posted( 'int_konz_nev' ) ?>" size="60" maxlength="35" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label" for="int_konz_beoszt">Beosztása:</label>
                    </td>
                    <td>
                        <input type="text" id="int_konz_beoszt" name="int_konz_beoszt" value="<?= posted( 'int_konz_beoszt' ) ?>" size="60" maxlength="20" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label" for="int_konz_tel">Telefonszáma:</label>
                    </td>
                    <td>
                        <input type="text" id="int_konz_tel" name="int_konz_tel" value="<?= posted( 'int_konz_tel' ) ?>" size="20" maxlength="20" placeholder="számok elválasztás nélkül" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-mand" for="int_konz_email">E-mail címe*:</label>
                    </td>
                    <td>
                        <input type="email" id="int_konz_email" name="int_konz_email" value="<?= posted( 'int_konz_email' ) ?>" size="60" maxlength="30" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="sep" colspan="2">
                        <label>Igazoló</label>
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-mand" for="int_ig_nev">Neve*:</label>
                    </td>
                    <td>
                        <input type="text" id="int_ig_nev" name="int_ig_nev" value="<?= posted( 'int_ig_nev' ) ?>" size="60" maxlength="35" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label" for="int_ig_beoszt">Beosztása:</label>
                    </td>
                    <td>
                        <input type="text" id="int_ig_beoszt" name="int_ig_beoszt" value="<?= posted( 'int_ig_beoszt' ) ?>" size="60" maxlength="20" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label" for="int_ig_tel">Telefonszáma:</label>
                    </td>
                    <td>
                        <input type="text" id="int_ig_tel" name="int_ig_tel" value="<?= posted( 'int_ig_tel' ) ?>" size="20" maxlength="20" placeholder="számok elválasztás nélkül" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-mand" for="int_ig_email">E-mail címe*:</label>
                    </td>
                    <td>
                        <input type="email" id="int_ig_email" name="int_ig_email" value="<?= posted( 'int_ig_email' ) ?>" size="60" maxlength="30" class="form-control">
                    </td>
                </tr>
<?php
if ($feladatkiiras) {
    ?>
                <tr>
                    <td class="sep" colspan="2"><label>Tanszéki konzulens</label></td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-mand" for="tan_konz">Neve*:</label>
                    </td>
                    <td>
                        <div class="controls form-inline">
                            <select id="tan_konz" name="tan_konz" class="form-control">
    <?php
    $selection = false;
    $konzulens = posted('tan_konz', null);
    $table = konzulens_read(null);
    foreach ($table as $row) {
        if ($row['id'] == $konzulens || $row['nev'] == ELFOGADO) {
            ?>
                                <option value="<?= $row['id'] ?>" selected><?= $row['nev'] ?></option>
            <?php
            $selection = true;
        }
        else {
            ?>
                                <option value="<?= $row['id'] ?>"><?= $row['nev'] ?></option>
            <?php
        }
    }
    ?>
                                <option value="" <?= $selection ? '' : 'selected' ?>>&ndash; &deg; &ndash;</option>
                            </select>
                            <a href="tanszeki_konz.php" target="_blank" role="button" class="btn btn-default"> + </a>
                        </div>
                    </td>
                </tr>
    <?php
}
?>
                <tr>
                    <td class="sep" colspan="2"><label>A feladat</label></td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label" for="cim">Címe:</label>
                    </td>
                    <td>
                        <input type="text" id="cim" name="cim" value="<?= posted( 'cim' ) ?>" size="60" maxlength="100" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-mand" for="eleje">Kezdete*:</label>
                    </td>
                    <td>
                        <input type="date" id="eleje" name="eleje" value="<?= posted( 'eleje' ) ?>" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-mand" for="vege">Vége*:</label>
                    </td>
                    <td>
                        <input type="date" id="vege" name="vege" value="<?= posted( 'vege' ) ?>" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label label-mand" for="feladat">Részletezése*:</label>
                    </td>
                    <td>
                        <textarea id="feladat" name="feladat" rows="8" cols="62" maxlength="1000" placeholder="Maximum 1000 karakter." class="form-control"><?= posted( 'feladat' ) ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="form-label">
                        <label class="control-label" for="megjegyzes">Megjegyzés:</label>
                    </td>
                    <td>
                        <textarea id="megjegyzes" name="megjegyzes" rows="8" cols="62" maxlength="500" placeholder="pl. tanszéki konzulens" class="form-control"><?= posted( 'megjegyzes' ) ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="btn-group" role="group">
<?php
if ($szerkesztes) {
    ?>
                            <input type="submit" name="szerkesztes" value="Módosítás" class="btn btn-primary">
    <?php
}
else {
    ?>
                            <input type="submit" name="jelentkezes"  value="Jelentkezés" class="btn btn-primary">
    <?php
}
if (! empty($modal)) {
    ?>
                            <button onclick="close_page();" class="btn btn-default">Mégsem</Button>
    <?php
}
else {
    ?>
                            <a href="index.php" role="button" class="btn btn-default">Mégsem</a>
    <?php
}
?>
                        </div>
                    </td>
                </tr>
            <tbody>
        </table>
    </form>
</div>
<footer class="fn">
    <p class="label-req">&deg; Kötelezően töltendő!</p>
    <p class="label-mand">* A mező kitöltése jelentkezéshez nem, de a feladat jóváhagyásához kötelezően kitöltendő!</p>
</footer>
<?php

require 'footer.php';
