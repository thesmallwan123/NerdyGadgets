<!DOCTYPE html>
<head>
    <style>
    </style>
    <link rel="stylesheet" href="Public/CSS/Style.css" type="text/css">
</head>
<div>

<?php
session_start();
//include __DIR__ . "/header.php";

/*calculating delivery time*/
$deliveryDate = date("d/m/Y", time() + 86400);

/*calculating total price*/
$totaalprijs = $_SESSION["totaalPrijs"];
$_SESSION["totaalPrijs"] = $totaalprijs;
// $totaalprijs = 1;

/*checking if the value from last page is the same as the total price*/

var_dump($_SESSION["cart"]);


?>


<div id="Wrap">
    <div class="confirmationTextP">

        <p>U moet €<?php print($totaalprijs); ?> betalen.</p>
        <p>Dit bedrag is inclusief BTW</p>
        <p>Uw bestelling wordt op <?php print($deliveryDate); ?> geleverd. </p>
        <p>Op uw ingevoerde adres:</p>

        <?php
        print($_POST["straat"] . " ");
        print($_POST["huisnummer"] . " ");
        print($_POST["postcode"] . " ");
        print($_POST["woonplaats"] . " ");
        ?>
        <br>
        <form method="post">
            <div class="bestelRow">
                <div class="col-12"> <label for="bevestiging"> Bevestig de totale prijs om te betalen</label><br>
                    <input class="opmaakPayForm" type="text" id="bevestiging" name="bevestiging" required>
                </div>
            </div>
            <p class="boldText">LET OP! de bedragen moeten overeen komen!</p>
            <p class="boldText">Als de bedragen niet overeen komen gaat u terug naar de vorige pagina!</p>
            <br>

            <div>
            </div>
            <br>
            <div class="toConfirmation">
                <input type="submit" name="gaTerug" value="Betalen" class="toConfirmationButton">
            </div>
        </form>

    </div>
</div>

    <?php

if (isset($_POST["bevestiging"])){
if ($totaalprijs != $_POST["bevestiging"]){
    header("Location: ./order.php");
}
else header("Location: ./confirmation.php");
}
?>