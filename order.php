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

    $voornaam = "";
    $tussenvoegsel = "";
    $achternaam = "";
    $gender = "";
    $email = "";
    $straat = "";
    $huisnummer = "";
    $postcode = "";
    $woonplaats = "";

    // checken session account & variabelen definiëren wanneer aanwezig.
    if (isset($_SESSION['account'])) {
        $account = $_SESSION["account"];

        $Query = "
        SELECT firstname, infix, surname, email, street, streetnumber, postalcode, city, gender
        FROM account
        WHERE email = ?";
        $Statement = mysqli_prepare($Connection, $Query);
        mysqli_stmt_bind_param($Statement, "s", $account);
        mysqli_stmt_execute($Statement);
        $ReturnableResult = mysqli_stmt_get_result($Statement);
        $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];

        $voornaam = $Result['firstname'];
        $tussenvoegsel = $Result['infix'];
        $achternaam = $Result['surname'];
        $email = $Result['email'];
        $straat = $Result['street'];
        $huisnummer = $Result['streetnumber'];
        $postcode = $Result['postalcode'];
        $woonplaats = $Result['city'];
        $gender = $Result['gender'];
    }
    // checken POST en variabelen definiëren gebaseerd op ingevuld formulier. Sessie paymentInfo aanmaken.
    if (isset($_POST['submit'])) {
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
            <div class="row orderRow">
                <div class="col-5">
                    <label for="voornaam"> Voornaam</label><br>
                    <input class="opmaakOrder" type="text" id="voornaam" name="voornaam" value="<?php print($voornaam); ?>" placeholder="Voornaam" required>
                </div>
                <div class="col-2">
                    <label for="tussenvoegsel"> Tussenvoegsel</label>
                    <input class="opmaakOrder" type="text" id="tussenvoegsel" name="tussenvoegsel" value="<?php print($tussenvoegsel); ?>" placeholder="Tussenvoegsel">
                </div>
                <div class="col-5">
                    <label for="achternaam"> Achternaam </label>
                    <input class="opmaakOrder" type="text" id="achternaam" name="achternaam" value="<?php print($achternaam); ?>" placeholder="Achternaam" required>
                </div>
            </div>
            <div class="row orderRow">
                <div class="col-10">
                    <label for="email"> E-mail</label>
                    <input class="opmaakOrder" type="email" name="email" value="<?php print("$email"); ?>" placeholder="E-mailadres" required>
                </div>
                <div class="col-2">
                    <label for="gender"> Geslacht</label>
                    <select name="gender" class="opmaakOrder" required>
                        <option value="">--Selecteer--</option>
                        <option value="1" <?php if ($gender == 1) { ?> selected <?php } ?>>Man</option>
                        <option value="2" <?php if ($gender == 2) { ?> selected <?php } ?>>Vrouw</option>
                        <option value="3" <?php if ($gender == 3) { ?> selected <?php } ?>>n.v.t.</option>
                    </select>
                </div>
            </div>
            <div class="row orderRow">
                <div class="col-10">
                    <label for="address">Straat</label>
                    <input class="opmaakOrder" type="text" id="straat" name="straat" value="<?php print($straat); ?>" placeholder="Straat" required>
                </div>
                <div class="col-2">
                    <label for="address">Huisnummer</label>
                    <input class="opmaakOrder" type="text" id="huisnummer" name="huisnummer" value="<?php print($huisnummer); ?>" placeholder="Huisnummer" required>
                </div>
            </div>
            <div class="row orderRow">
                <div class="col-2">
                    <label for="postalcode">Postcode</label>
                    <input class="opmaakOrder" type="text" id="postcode" name="postcode" value="<?php print($postcode); ?>" placeholder="Postcode" required>
                </div>
                <div class="col-10">
                    <label for="city"> Woonplaats</label>
                    <input class="opmaakOrder" type="text" id="woonplaats" name="woonplaats" value="<?php print($woonplaats); ?>" placeholder="Woonplaats" required>
                </div>
            </div>

            <div class="row orderRow">
                <div class="col-1"></div>
                <a href="cart.php" class="col-4 toCart button">
                    <div class="toCartButton">
                        Terug naar de winkelmand
                    </div>
                </a>
                <div class="col-2"></div>
                <div class="col-4 toPayment">
                    <input type="submit" name="submit" value="Door naar betalen" class="toPaymentButton button">
                </div>
                <div class="col-1"></div>
            </div>
        </form>

    </div>