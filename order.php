<?php
include __DIR__ . "/header.php";

$voornaam = "";
$tussenvoegsel = "";
$achternaam = "";
$email = "";
$straat = "";
$huisnummer = "";
$postcode = "";
$woonplaats = "";

if (isset($_SESSION['account'])) {
    $account = $_SESSION["account"];

    $Query = "
    SELECT firstname, infix, surname, email, street, streetnr, postalcode, city
    FROM account
    WHERE email = ?";
    $Statement = mysqli_prepare($Connection2, $Query);
    mysqli_stmt_bind_param($Statement, "s", $account);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
    
    $voornaam = $Result['firstname'];
    $tussenvoegsel = $Result['infix'];
    $achternaam = $Result['surname'];
    $email = $Result['email'];
    $straat = $Result['street'];
    $huisnummer = $Result['streetnr'];
    $postcode = $Result['postalcode'];
    $woonplaats = $Result['city'];
}
?>
<!--    <div class="orderRow">-->
<!--        <div class="col-75">-->
<!--        <div class="orderRow">-->

<div class="container">
    <h3>Bestel gegevens</h3>
    <form method="post" action="pay.php">
        <div class="row bestelRow">
            <div class="col-5">
                <label for="voornaam"> Voornaam</label><br>
                <input class="opmaakOrder" type="text" id="voornaam" name="voornaam" value="<?php print($voornaam); ?>" required>
            </div>
            <div class="col-2">
                <label for="tussenvoegsel"> Tussenvoegsel</label>
                <input class="opmaakOrder" type="text" id="tussenvoegsel" name="tussenvoegsel" value="<?php print($tussenvoegsel); ?>">
            </div>
            <div class="col-5">
                <label for="achternaam"> Achternaam </label>
                <input class="opmaakOrder" type="text" id="achternaam" name="achternaam" value="<?php print($achternaam); ?>" required>
            </div>
        </div>
        <div class="row bestelRow">
            <div class="col-12">
                <label for="email">Email</label>
                <input class="opmaakOrder" type="email" id="email" name="email" value="<?php print($email); ?>" required>
            </div>
        </div>
        <div class="row bestelRow">
            <div class="col-10">
                <label for="address">Straat</label>
                <input class="opmaakOrder" type="text" id="straat" name="straat" value="<?php print($straat); ?>" required>
            </div>
            <div class="col-2">
                <label for="address">Huisnummer</label>
                <input class="opmaakOrder" type="number" id="huisnummer" name="huisnummer" value="<?php print($huisnummer); ?>" required>
            </div>
        </div>
        <div class="row bestelRow">
            <div class="col-2">
                <label for="postalcode">Postcode</label>
                <input class="opmaakOrder" type="text" id="postcode" name="postcode" value="<?php print($postcode); ?>" required>
            </div>
            <div class="col-10">
                <label for="city"> Woonplaats</label>
                <input class="opmaakOrder" type="text" id="woonplaats" name="woonplaats" value="<?php print($woonplaats); ?>" required>
            </div>
        </div>

        <div class="row bestelRow">
            <div class="col-1"></div>
            <div class="col-4 toCart">
                <a href="cart.php" class="toCartButton">
                    <div class="test123">Terug naar de winkelmand. </div>
                </a>
            </div>
            <div class="col-2"></div>
            <div class="col-4 toPayment">
                <input type="submit" name="submit" value="Door naar betalen" class="toPaymentButton">
            </div>


            <div class="col-1"></div>
        </div>
    </form>

</div>