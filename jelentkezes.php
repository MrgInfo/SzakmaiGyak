<?

require 'header.php';

function jelentkezesi_read() {
    $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
    if( ! $conn ) {
        return false;
    }
    $conn->set_charset( 'utf8' );
    $select = <<<QUERY
SELECT j.id,
       j.nev,
       j.neptunkod,
       j.allando_cim,
       j.ideiglenes_cim,
       j.mobil,
       j.email,
       j.kollegium,
       j.int_nev,
       j.int_cim,
       j.int_konz_nev,
       j.int_konz_beoszt,
       j.int_konz_tel,
       j.int_konz_emial,
       j.cim,
       j.feladat,
       j.megjegyzes,
       CASE j.jelszo WHEN PASSWORD(?) THEN 1 ELSE 0 END password
  FROM jelentkezesi_lap j
 WHERE j.neptunkod = UPPER(?)
QUERY;
    $stmt = $conn->prepare( $select );
    $stmt->bind_param( 's', posted( 'jelszo', null ) );
    $stmt->bind_param( 's', posted( 'neptunkod', null ) );
    if( ! $stmt->execute() ) {
        $stmt->close();
        $conn->close();
        return false;
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if( ! $row ) {
        $stmt->close();
        $conn->close();
        return false;
    }
    foreach( $row as $key => $value ) {
        $_POST[$key] = $value;
    }
    $stmt->close();
    $conn->close();
    return true;
}

function jelentkezesi_edit() {
    $id = posted( 'id' );
    $nev = posted( 'nev' );
    $neptunkod = posted( 'neptunkod' );
    $jelszo = posted( 'jelszo' );
    $email = posted( 'email' );
    $allando_cim = posted( 'allando_cim', null );
    $ideiglenes_cim = posted( 'ideiglenes_cim', null );
    $mobil = posted( 'mobil', null );
    $kollegium = posted( 'kollegium', null );
    $int_nev = posted( 'int_nev', null );
    $int_cim = posted( 'int_cim', null );
    $int_konz_nev = posted( 'int_konz_nev', null );
    $int_konz_beoszt = posted( 'int_konz_beoszt', null );
    $int_konz_tel = posted( 'int_konz_tel', null );
    $int_konz_emial = posted( 'int_konz_emial', null );
    $cim = posted( 'cim', null );
    $feladat = posted( 'feladat', null );
    $megjegyzes = posted( 'megjegyzes', null );
    $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
    if( ! $conn ) {
        return false;
    }
    $conn->set_charset( 'utf8' );
    $insert = <<<QUERY
INSERT INTO jelentkezesi_lap (
            nev,
            neptunkod,
            jelszo,
            email,
            allando_cim,
            ideiglenes_cim,
            mobil,
            kollegium,
            int_nev,
            int_cim,
            int_konz_nev,
            int_konz_beoszt,
            int_konz_tel,
            int_konz_emial,
            cim,
            feladat,
            megjegyzes)
     VALUES (?, UPPER(?), PASSWORD(?), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
QUERY;
    $update = <<<QUERY
UPDATE jelentkezesi_lap
   SET allando_cim = ?,
       ideiglenes_cim = ?,
       mobil = ?,
       kollegium = ?,
       int_nev = ?,
       int_cim = ?,
       int_konz_nev = ?,
       int_konz_beoszt = ?,
       int_konz_tel = ?,
       int_konz_emial = ?,
       cim = ?,
       feladat = ?,
       megjegyzes = ?
 WHERE id = ?
QUERY;
    if( $id ) {
        $stmt = $conn->prepare( $update );
    }
    else {
        $stmt = $conn->prepare( $insert );
        $stmt->bind_param( 's', $nev );
        $stmt->bind_param( 's', $neptunkod );
        $stmt->bind_param( 's', $jelszo );
        $stmt->bind_param( 's', $email );
    }
    $stmt->bind_param( 's', $allando_cim );
    $stmt->bind_param( 's', $ideiglenes_cim );
    $stmt->bind_param( 's', $mobil );
    $stmt->bind_param( 'i', $kollegium );
    $stmt->bind_param( 's', $int_nev );
    $stmt->bind_param( 's', $int_cim );
    $stmt->bind_param( 's', $int_konz_nev );
    $stmt->bind_param( 's', $int_konz_beoszt );
    $stmt->bind_param( 's', $int_konz_tel );
    $stmt->bind_param( 's', $int_konz_emial );
    $stmt->bind_param( 's', $cim );
    $stmt->bind_param( 's', $feladat );
    $stmt->bind_param( 's', $megjegyzes );
    if( $id ) {
        $stmt->bind_param( 'i', $id );
    }
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

function hallgato_mail() {
    $subject = 'Jelentkezés szakmai gyakorlatra';
    $body  = <<<MAIL
Kedves $_POST[nev]!

Ön sikeresen jelentkezett szakmai gyakorlatra. A megadott adatokat, a tájékoztatóban megadott határidőig,
a megadott Neptun-kóddal ($_POST[neptunkod]) és a következő jelszó segítségével tudja módosítani: $_POST[jelszo].

---
Erre az e-mailre ne válaszoljon!
MAIL;
    return smartmail( $_POST['nev'], $_POST['email'], $subject, $body );
}

$done = false;
load_post();
$_POST['allando_cim'] = concatAddress(
    posted( 'allando_cim' ),
    posted( 'allando_cim_isz' ),
    posted( 'allando_cim_var' ),
    posted( 'allando_cim_kt' ),
    posted( 'allando_cim_hsz' ) );
$_POST['ideiglenes_cim'] = concatAddress(
    posted( 'ideiglenes_cim' ),
    posted( 'ideiglenes_cim_isz' ),
    posted( 'ideiglenes_cim_var' ),
    posted( 'ideiglenes_cim_kt' ),
    posted( 'ideiglenes_cim_hsz') );
$_POST['int_cim'] = concatAddress(
    posted( 'int_cim' ),
    posted( 'int_cim_isz' ),
    posted( 'int_cim_var' ),
    posted( 'int_cim_kt' ),
    posted( 'int_cim_hsz' ) );
$_POST['mobil'] = concatPhone(
    posted( 'mobil' ),
    posted( 'mobil_pre' ),
    posted( 'mobil_post' ) );
$_POST['int_konz_tel'] = trimPhone(
    posted( 'int_konz_tel' ) );
$_POST['jelszo'] = generatePassword();
var_dump( $_POST );

$missing = false;
if( isset( $_POST['szerkesztes'] ) ||
    isset( $_POST['jelentkezes'] )
) {
    if(
        posted( 'nev' ) == '' ||
        strlen( posted( 'neptunkod' ) ) != 6 ||
        posted( 'allando_cim' ) == '' ||
        posted( 'mobil' ) == '' ||
        posted( 'email'  ) == '' ||
        posted( 'kollegium' ) == ''
    ) {
        $missing = true;
    }
}

if( ! $missing && isset( $_POST['szerkesztes'] ) ) {
	if( empty( $_POST['id'] ) ) {
        if( ! jelentkezesi_read() ) {
            ?>
<div class="jumbotron">
    <h2>Jelentkezés</h2>
    <p><?= empty( $_POST['id'] ) ? 'A hallgató nincs regisztrálva a rendszerben!' : 'Hibás Neptun-kód vagy jelszó!' ?></p>
    <p><a href="index.php" class="btn btn-default" role="button">Vissza</a></p>
</div>
            <?
            $done = true;
        }
	} else {
        if( jelentkezesi_edit() ) {
            ?>
<div class="jumbotron">
    <h2>Jelentkezés</h2>
    <p>A változtatásokat mentettük!<p>
    <p><a href="index.php" class="btn btn-default" role="button">Vissza</a></p>
</div>
            <?
        } else {
            ?>
<div class="jumbotron">
    <h2>Jelentkezés</h2>
    <p>A módosítások nem hajthatók végre!<p>
    <p><a href="index.php" class="btn btn-default" role="button">Vissza</a></p>
</div>
            <?
        }
        $done = true;
	}
} else if( ! $missing && isset( $_POST['jelentkezes'] ) ) {
    if(
        jelentkezesi_edit()
        &&
        hallgato_mail()
    ) {
        ?>
<div class="jumbotron">
    <h2>Jelentkezés</h2>
    <p>A szakmai gyakorlatra való jelentkezés siekresen megtörtént, erről e-mail értesítést is küldtünk a <a href="mailto:<?=$_POST['email']?>"><?=$_POST['email']?></a> címre.<p>
    <p><a href="index.php" class="btn btn-default" role="button">Vissza</a></p>
</div>
        <?
    } else {
        ?>
<div class="jumbotron">
    <h2>Jelentkezés</h2>
    <p>A jelentkezés sikertelen, próbálja meg később!<p>
    <p><a href="index.php" class="btn btn-default" role="button">Vissza</a></p>
</div>
        <?
    }
    $done = true;
}
if( ! $done )
{
    $readonly = isset( $_POST['szerkesztes'] )
        ? 'readonly'
        : '';
    ?>
<header>
    <h1><?=GYAKORLAT_EV?>. évi szakmai gyakorlat</h1>
</header>
<div class="content">
    <h2>Jelentkezési lap</h2>
    <?
    if( $missing ) {
        ?>
        <div class="alert alert-danger" role="alert">
            <p>Nem minden kötelező mező van kitöltve!</p>
        </div>
        <?
    }
    ?>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
        <input type="hidden" name="id" value="<?=posted( 'id' )?>">
        <table class="form">
            <tbody>
                <tr>
                    <td class="sep" colspan="2"><label>1. Személyes adatok</label></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label label-req" for="nev">Név:</label></td>
                    <td><input type="text" id="nev" name="nev" value="<?=posted( 'nev' )?>" size="60" maxlength="40" <?= $readonly ?> class="form-control"></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label label-req" for="neptunkod">Neptun-kód:</label></td>
                    <td><input type="text" id="neptunkod" name="neptunkod" value="<?=posted( 'neptunkod' )?>" size="6" maxlength="6" <?= $readonly ?> class="form-control"></td>
                </tr>

                <tr>
                    <td class="form-label"><label class="control-label label-req" for="fir">FIR azonosító:</label></td>
                    <td><input type="text" id="fir" name="neptunkod" value="<?=posted( 'fir' )?>" size="6" maxlength="6" <?= $readonly ?> class="form-control"></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label label-req" for="allando_cim">Állandó lakóhely címe:</label></td>
                    <td>
    <?
    if( ! empty( $_POST['allando_cim'] ) ) {
        ?>
		                <input type="text" id="allando_cim" name="allando_cim" value="<?=posted( 'allando_cim' )?>" size="60" maxlength="50" class="form-control">
        <?
    } else {
        ?>
                        <div class="controls form-inline">
                            <input type="text" id="allando_cim" name="allando_cim_isz" size="4" maxlength="4" placeholder="ir.sz." class="form-control">
                            <input type="text" name="allando_cim_var" size="20" maxlength="200" placeholder="város" class="form-control">,
                            <input type="text" name="allando_cim_kt" size="12" maxlength="30" placeholder="közterület neve" class="form-control">
                            <input type="text" name="allando_cim_hsz" size="5" maxlength="20" placeholder="házszám" class="form-control">
                        </div>
        <?
    }
    ?>
                    </td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label" for="ideiglenes_cim">Lakása címe oktatási időszakban:</label></td>
                    <td>
    <?
    if( ! empty( $_POST['ideiglenes_cim'] ) ) {
        ?>
                        <input type="text" id="ideiglenes_cim" name="ideiglenes_cim" value="<?=posted( 'ideiglenes_cim' )?>" size="60" maxlength="50">
        <?
    } else {
        ?>
                        <div class="controls form-inline">
                            <input type="text" id="ideiglenes_cim" name="ideiglenes_cim_isz" size="4" maxlength="4" placeholder="ir.sz." class="form-control">
                            <input type="text" name="ideiglenes_cim_var" size="20" maxlength="200" placeholder="város" class="form-control">,
                            <input type="text" name="ideiglenes_cim_kt" size="12" maxlength="30" placeholder="közterület neve" class="form-control">
                            <input type="text" name="ideiglenes_cim_hsz" size="5" maxlength="20" placeholder="házszám" class="form-control">
                        </div>
        <?
    }
    ?>
                    </td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label label-req" for="mobil">Mobiltelefonszám:</label></td>
                    <td>
                        <div class="controls form-inline">
    <?
    if( ! empty( $_POST['mobil'] ) ) {
        ?>
                            <input type="text" id="mobil" name="mobil" value="<?=posted( 'mobil' )?>" size="15" maxlength="20" class="form-control">
        <?
    } else {
        ?>
                            +36
                            <select name="mobil_pre" class="form-control">
                                <option selected>20</option>
                                <option>30</option>
                                <option>70</option>
                            </select>
                            <input type="text" id="mobil" name="mobil_post" size="20" maxlength="7" placeholder="számok elválasztás nélkül" class="form-control">
        <?
    }
    ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label label-req" for="email">E-mail cím:</label></td>
                    <td><input type="email" id="email" name="email" value="<?=posted( 'email' )?>" size="60" maxlength="30" <?= $readonly ?> class="form-control"></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label label-req" for="kepzes">Képzés típusa:</label></td>
                    <td>
                        <div class="controls form-inline">
                            <div class="radio">
                                <label><input type="radio" id="kepzes" name="kepzes" value="0" <?=empty($_POST['kepzes']) ?  '' : 'checked'?>> BsC</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="kepzes" value="1" <?=empty($_POST['kepzes']) ? '' : 'checked'?>> MsC</label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label label-req" for="kollegium">Gyakorlata alatt igényel-e kollégiumi elhelyezést:</label></td>
                    <td>
                        <div class="controls form-inline">
                            <div class="radio">
                                <label><input type="radio" id="kollegium" name="kollegium" value="1" <?=empty($_POST['kollegium']) ?  '' : 'checked'?>> Igen</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="kollegium" value="0" <?=empty($_POST['kollegium']) ? '' : 'checked'?>> Nem</label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="sep" colspan="2"><label>2. A fogadó intézmény adatai</label></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label label-mand" for="int_nev">Teljes neve*:</label></td>
                    <td><input type="text" id="int_nev" name="int_nev" value="<?=posted( 'int_nev' )?>" size="60" maxlength="35" class="form-control"></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label label-mand" for="int_cim">Címe*:</label></td>
                    <td>
    <?
    if( ! empty($_POST['int_cim']) ) {
        ?>
                        <input type="text" id="int_cim" name="int_cim" value="<?=posted( $_POST['int_cim'] )?>" size="60" maxlength="50" class="form-control">
        <?
    } else {
        ?>
                        <div class="controls form-inline">
                            <input type="text" id="int_cim" name="int_cim_isz" size="4" maxlength="4" placeholder="ir.sz." class="form-control">
                            <input type="text" name="int_cim_var" size="20" maxlength="200" placeholder="város" class="form-control">,
                            <input type="text" name="int_cim_kt" size="12" maxlength="30" placeholder="közterület neve" class="form-control">
                            <input type="text" name="int_cim_hsz" size="5" maxlength="20" placeholder="házszám" class="form-control">
                        </div>
        <?
    }
    ?>
                    </td>
                </tr>
                <tr>
                    <td class="sep" colspan="2"><label>3. Konzulens</label></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label label-mand" for="int_konz_nev">Neve*:</label></td>
                    <td><input type="text" id="int_konz_nev" name="int_konz_nev" value="<?=posted( 'int_konz_nev' )?>" size="60" maxlength="35" class="form-control"></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label" for="int_konz_beoszt">Beosztása:</label></td>
                    <td><input type="text" id="int_konz_beoszt" name="int_konz_beoszt" value="<?=posted( 'int_konz_beoszt' )?>" size="60" maxlength="20" class="form-control"></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label" for="int_konz_tel">Telefonszáma:</label></td>
                    <td><input type="text" id="int_konz_tel" name="int_konz_tel" value="<?=posted( 'int_konz_tel' )?>" size="20" maxlength="20" placeholder="számok elválasztás nélkül" class="form-control"></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label label-mand" for="int_konz_emial">E-mail címe*:</label></td>
                    <td><input type="email" id="int_konz_emial" name="int_konz_emial" value="<?=posted( 'int_konz_emial' )?>" size="60" maxlength="30" class="form-control"></td>
                </tr>
                <tr>
                    <td class="sep" colspan="2"><label>4. Igazoló</label></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label label-mand" for="int_ig_nev">Neve*:</label></td>
                    <td><input type="text" id="int_ig_nev" name="int_ig_nev" value="<?=posted( 'int_ig_nev' )?>" size="60" maxlength="35" class="form-control"></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label" for="int_ig_beoszt">Beosztása:</label></td>
                    <td><input type="text" id="int_ig_beoszt" name="int_ig_beoszt" value="<?=posted( 'int_ig_beoszt' )?>" size="60" maxlength="20" class="form-control"></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label" for="int_ig_tel">Telefonszáma:</label></td>
                    <td><input type="text" id="int_ig_tel" name="int_ig_tel" value="<?=posted( 'int_ig_tel' )?>" size="20" maxlength="20" placeholder="számok elválasztás nélkül" class="form-control"></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label label-mand" for="int_ig_emial">E-mail címe*:</label></td>
                    <td><input type="email" id="int_ig_emial" name="int_ig_emial" value="<?=posted( 'int_ig_emial' )?>" size="60" maxlength="30" class="form-control"></td>
                </tr>
                <tr>
                    <td class="sep" colspan="2"><label>4. A feladat</label></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label" for="cim">Címe:</label></td>
                    <td><input type="text" id="cim" name="cim" value="<?=posted( 'cim' )?>" size="60" maxlength="100" class="form-control"></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label label-mand" for="elje">Kezdete*:</label></td>
                    <td><input type="date" id="eleje" name="elje" value="<?=posted( 'elje' )?>" size="60" maxlength="100" class="form-control"></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label label-mand" for="vege">Vége*:</label></td>
                    <td><input type="date" id="vege" name="vege" value="<?=posted( 'vege' )?>" size="60" maxlength="100" class="form-control"></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label label-mand" for="feladat">Részletezése*:</label></td>
                    <td><textarea id="feladat" name="feladat" rows="8" cols="62" maxlength="1000" placeholder="Maximum 1000 karakter." class="form-control"><?=posted( 'feladat' )?></textarea></td>
                </tr>
                <tr>
                    <td class="form-label"><label class="control-label" for="megjegyzes">Megjegyzés:</label></td>
                    <td><textarea id="megjegyzes" name="megjegyzes" rows="8" cols="62" maxlength="500" placeholder="pl. tanszéki konzulens" class="form-control"><?=posted( 'megjegyzes' )?></textarea></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="btn-group" role="group">
                            <input type="submit" name="<?= isset( $_POST['szerkesztes'] ) ? 'szerkesztes' : 'jelentkezes'; ?>" value="<?= isset( $_POST['szerkesztes'] ) ? 'Módosítás' : 'Jelentkezés'; ?>" class="btn btn-primary">
                            <a href="index.php" role="button" class="btn btn-default">Mégsem</a>
                        </div>
                    </td>
                </tr>
            <tbody>
        </table>
    </form>
</div>
<footer class="fn">
    <p class="label-req">Kötelezően töltendő adatok.</p>
    <p class="label-mand">* A mező kitöltése jelentkezéshez nem, de a feladat jóváhagyásához kötelezően kitöltendő adat!</p>
</footer>
<?
}

require 'footer.php';

?>
