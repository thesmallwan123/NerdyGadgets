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
//variabelen worden gemaakt.
    $firstName = "";
    $insertion = "";
    $lastName = "";
    $gender = "";
    $email = "";
    $address = "";
    $houseNumber = "";
    $postalCode = "";
    $city = "";

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

        $firstName = $Result['firstname'];
        $insertion = $Result['infix'];
        $lastName = $Result['surname'];
        $email = $Result['email'];
        $address = $Result['street'];
        $houseNumber = $Result['streetnumber'];
        $postalCode = $Result['postalcode'];
        $city = $Result['city'];
        $gender = $Result['gender'];
    }
    // checken POST en variabelen definiëren gebaseerd op ingevuld formulier. Sessie paymentInfo aanmaken.
    if (isset($_POST['submit'])) {
        $address = $_POST['straat'];
        $houseNumber = $_POST['huisnummer'];
        $postalCode = $_POST['postcode'];
        $city = $_POST['woonplaats'];

        $firstName = $_POST["voornaam"];
        $insertion = $_POST["tussenvoegsel"];
        $lastName = $_POST["achternaam"];
        $gender = $_POST["gender"];
        $email = $_POST["email"];


        $paymentInfo = array();
        $paymentInfo[0] = $address;
        $paymentInfo[1] = $houseNumber;
        $paymentInfo[2] = $postalCode;
        $paymentInfo[3] = $city;

        $paymentInfo[4] = $firstName;
        $paymentInfo[5] = $insertion;
        $paymentInfo[6] = $lastName;
        $paymentInfo[7] = $gender;
        $paymentInfo[8] = $email;

        $_SESSION['paymentInfo'] = $paymentInfo;
        header("Location: ./pay.php");
    }
    ?>

<!--    //alle invoervelden zijn aangegeven met een input en het label is wat er in het veld moet komen te staan.-->
    <div class="container orderPageContainer">
        <h3>Bestel gegevens</h3>
        <form method="post">
            <div class="row orderRow">
                <div class="col-5">
<!--                    //voornaam-->
                    <label for="voornaam"> Voornaam</label><br>
                    <input class="opmaakOrder" type="text" id="voornaam" name="voornaam" value="<?php print($firstName); ?>" placeholder="Voornaam" required>
                </div>
                <div class="col-2">
<!--                    //tussenvoegsel-->
                    <label for="tussenvoegsel"> Tussenvoegsel</label>
                    <input class="opmaakOrder" type="text" id="tussenvoegsel" name="tussenvoegsel" value="<?php print($insertion); ?>" placeholder="Tussenvoegsel">
                </div>
                <div class="col-5">
<!--                    //achternaam-->
                    <label for="achternaam"> Achternaam </label>
                    <input class="opmaakOrder" type="text" id="achternaam" name="achternaam" value="<?php print($lastName); ?>" placeholder="Achternaam" required>
                </div>
            </div>
            <div class="row orderRow">
                <div class="col-10">
<!--                    //email-->
                    <label for="email"> E-mail</label>
                    <input class="opmaakOrder" type="email" name="email" value="<?php print("$email"); ?>" placeholder="E-mailadres" required>
                </div>
                <div class="col-2">
<!--                    //geslacht-->
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
<!--                    //straat-->
                    <label for="address">Straat</label>
                    <input class="opmaakOrder" type="text" id="straat" name="straat" value="<?php print($address); ?>" placeholder="Straat" required>
                </div>
                <div class="col-2">
<!--                    //huisnummer-->
                    <label for="address">Huisnummer</label>
                    <input class="opmaakOrder" type="text" id="huisnummer" name="huisnummer" value="<?php print($houseNumber); ?>" placeholder="Huisnummer" required>
                </div>
            </div>
            <div class="row orderRow">
                <div class="col-2">
<!--                    //postcode-->
                    <label for="postalcode">Postcode</label>
                    <input class="opmaakOrder" type="text" id="postcode" name="postcode" value="<?php print($postalCode); ?>" placeholder="Postcode" required>
                </div>
                <div class="col-10">
<!--                    //woonplaats-->
                    <label for="city"> Woonplaats</label>
                    <input class="opmaakOrder" type="text" id="woonplaats" name="woonplaats" value="<?php print($city); ?>" placeholder="Woonplaats" required>
                </div>
            </div>

            <div class="row orderRow">
                <div class="col-1"></div>
<!--                //terug naar winkelmand knop-->
                <a href="cart.php" class="col-4 toCart button buttonRed">
                    <div class="toCartButton">
                        Terug naar de winkelmand
                    </div>
                </a>
<!--                door naar betalen knop-->
                <div class="col-2"></div>
                <div class="col-4 toPayment button buttonGreen">
                    <input type="submit" name="submit" value="Door naar betalen" class="toPaymentButton">
                </div>
                <div class="col-1"></div>
            </div>
        </form>

    </div>