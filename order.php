<?php
include "connect.php";
session_start();
?>

<!DOCTYPE html>

<html lang="en" style="background-color: rgb(35, 35, 47);">
<head>
    <style>
        @font-face {
            font-family: MmrText;
            src: url(/Public/fonts/mmrtext.ttf);
        }
    </style>
    <meta charset="ISO-8859-1">
    <title>NerdyGadgets</title>
    <link rel="stylesheet" href="Public/CSS/Style.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/nha3fuq.css">
</head>
<body>

<?php

// gender
// aantal moet uit de database

// annuleringsknop moet naast de bestellingsknop zitten
// meer duidelijkheid over toevoegen korting (knop/melding)
// kortingsveld moet tussen product boven kosten zitten
// kosten/knoppen rechterkant van de pagina moeten goed uitgelijnd zijn
// misschien grootte knoppen evenredig maken

// korting moet uit de database gehaald worden

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

if(isset($_POST['submit'])) {
    $straat = $_POST['straat'];
    $huisnummer = $_POST['huisnummer'];
    $postcode = $_POST['postcode'];
    $woonplaats = $_POST['woonplaats'];

    $voornaam = $_POST["voornaam"];
    $tussenvoegsel = $_POST["tussenvoegsel"];
    $achternaam = $_POST["achternaam"];
    $gender = $_POST["gender"];
    $email = $_POST["email"];

    
    $paymentInfo = array();
    $paymentInfo[0] = $straat;
    $paymentInfo[1] = $huisnummer;
    $paymentInfo[2] = $postcode;
    $paymentInfo[3] = $woonplaats;

    $paymentInfo[4] = $voornaam;
    $paymentInfo[5] = $tussenvoegsel;
    $paymentInfo[6] = $achternaam;
    $paymentInfo[7] = $gender;
    $paymentInfo[8] = $email;

    $_SESSION['paymentInfo'] = $paymentInfo;
    header("Location: ./pay.php");
}
?>

<!--    <div class="orderRow">-->
<!--        <div class="col-75">-->
<!--        <div class="orderRow">-->

<div class="container orderPageContainer">
    <h3>Bestel gegevens</h3>
    <!-- <form method="post" action="pay.php"> -->
    <form method="post">
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
            <div class="col-10">
                <label for="email"> E-mail</label>
                <input class="opmaakOrder" type="email" name="email" value="<?php print("$email"); ?>" placeholder="E-mailadres" required>
            </div>
            <div class="col-2">
                <label for="gender"> Geslacht</label>
                <select name="gender" class="opmaakOrder" required>
                    <option value="">--Selecteer--</option>
                    <option value="man">Man</option>
                    <option value="vrouw">Vrouw</option>
                    <option value="nvt">n.v.t.</option>
                </select>
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