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

$prijsKloptNiet = FALSE;

/*calculating delivery time*/
$deliveryDate = date("d/m/Y", time() + 86400);

/*calculating total price*/
$totaalprijs = $_SESSION["totaalPrijs"];
$_SESSION["totaalPrijs"] = $totaalprijs;
// $totaalprijs = 1;

// Checking if the value from last page is the same as the total price
if (isset($_POST["bevestiging"])) {
    if ($totaalprijs != $_POST["bevestiging"]) {
        $prijsKloptNiet = TRUE;
    } else {
        // $Query = "
        //         SELECT discounts
        //         FROM discount
        //         WHERE discounts = ?";
        // $Statement = mysqli_prepare($Connection, $Query);
        // mysqli_stmt_bind_param($Statement, "i", [[artikelID]]);

        header("Location: ./exportpdf.php");
    }
}

// Betalingsinfo ophalen uit de sessie
if(isset($_SESSION['paymentInfo'])) {
    $paymentInfo = $_SESSION['paymentInfo'];
    $straat = $paymentInfo[0];
    $huisnummer = $paymentInfo[1];
    $postcode = $paymentInfo[2];
    $woonplaats = $paymentInfo[3];
}
?>

<!--tekst die bovenaan de pagina staat-->
<div id="Wrap">
    <div class="confirmationText confirmationTextP">

        <p>U moet €<?php print($totaalprijs); ?> betalen.</p>
        <p>Dit bedrag is inclusief BTW</p>
        <p>Uw bestelling wordt op <?php print($deliveryDate); ?> geleverd. </p>
        <p>Op uw ingevoerde adres:</p>

        <?php
//        variabelen worden gedevinieerd
        print($straat . " ");
        print($huisnummer . " ");
        print($postcode . " ");
        print($woonplaats . " ");
        ?>

<!--        invulbalk & controle of het gegeven bedrag overeenkomt met het ingevulde bedrag-->
        <br>
        <form method="post">
            <div class="bestelRow">
                <div class="col-12"> <label for="bevestiging">Bevestig de totale prijs om te betalen</label><br>
                <?php
                    if ($prijsKloptNiet) {
                    ?>
                    <div class="row loginSignupRows">
                        <div class="col-1"></div>
                        <div class="col-10">
                                <?php 
                            if ($prijsKloptNiet) { 
                                ?> 
                                <label for="email" class="signupWarningLabel">Het betaalde bedrag komt niet overeen met de prijs.</label>
                                <?php 
                            } 
                                ?>
                        </div>
                        <div class="col-1"></div>
                    </div>
                    <?php
                    }
                    ?>

                    <input class="opmaakPayForm" type="text" id="bevestiging" name="bevestiging" required>
                </div>
            </div>
            <p class="boldText">LET OP! de bedragen moeten overeen komen!</p>
            <br>

            <div>
            </div>
            <br>
<!--            knop om te betalen-->
            <div class="toConfirmation">
                <input type="submit" name="gaTerug" value="Betalen" class="toConfirmationButton button buttonGreen">
            </div>
        </form>

    </div>
</div>
