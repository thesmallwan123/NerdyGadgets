<!DOCTYPE html>
<head>
    <style>
    </style>
    <link rel="stylesheet" href="Public/CSS/Style.css" type="text/css">
</head>
<div>

<?php

/* calculating delivery date with the date of today + 1 day*/
$deliveryDate = date("d/m/Y", time() + 86400);
if(isset($_POST["gaTerug2"])){
    if (isset($_POST["gaTerug"])) {
        include "./connect.php";

        $SQL = "UPDATE stockitemholdings
                SET QuantityOnHand = 45
                WHERE stockitemid = 1";

        $Statement = mysqli_prepare($Connection, $SQL);
        mysqli_stmt_execute($Statement);

        session_destroy();
        header("Location: ./index.php");
    }
}

if (isset($_POST["terugNaarOrder"])){
    header("Location: ./order.php");
}

session_start();
/*calculating total price*/
if(isset($_SESSION["totaalPrijs"])){
    $totaalprijs = $_SESSION["totaalPrijs"];
    /*checking if the value from last page is the same as the total price*/
    if ($totaalprijs == $_POST["bevestiging"]){
        ?>
    <div class="confirmationTextHeader">
        <h1>Succes!</h1>

    </div>
    <div class="confirmationTextP">
        <p> De bedragen komen overeen met elkaar.</p>
        <p> Uw bestelling is succesvol afgerond.</p>
        <p> Op <?php print($deliveryDate); ?> wordt uw bestelling bezorgd.</p>
        <!--<p> Er wordt een factuur naar uw e-mailadres gestuurd.</p>-->
    </div>

    <div class="backToShop">
        <form action="index.php">
            <input type="submit" name="gaTerug2" value="Ga terug naar de website" class="backToShopButton">
        </form>
    </div>

<?php
    }
    else {
        ?>
    <div class="confirmationTextHeader"
        <h1>Helaas.</h1>
    </div>
    <div class="confirmationTextP">
        <p>de bedragen komen niet overeen<p>
        <p>Ga terug naar de gegevens pagina<p>
    <div class="backToShop">
        <form action="order.php">
            <input type="submit" name="terugNaarOrder" value="Terug naar bestellen" class="backToShopButton">
        </form>
    </div>

    <?php
    }
}

?>
</body>