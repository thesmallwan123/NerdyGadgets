<?php
include __DIR__ . "/header.php";

/*calculating delivery time*/
$deliveryDate = date("d/m/Y", time() + 86400);

/*calculating total price*/
$totaalprijs = $_SESSION["totaalPrijs"];
// $totaalprijs = 1;
?>

<div id="Wrap">
    <div class="confirmationTextP">

        <p>U moet €<?php print($totaalprijs); ?>,- betalen.</p>
    <p>Dit bedrag is inclusief BTW</p>
    <p>Uw bestelling wordt op <?php print($deliveryDate); ?> geleverd. </p>
    <p>Op uw ingevoerde adres:</p>
        <?php
        print($_POST["straat"]. " ");
        print($_POST["huisnummer"]. " ");
        print($_POST["postcode"]. " ");
        print($_POST["woonplaats"]. " ");
        ?>
        <br>
    <form method="post">
        <div class="bestelRow">
            <div class="col-12"
                <label for="bevestiging"> Bevestig de totale prijs om te betalen</label><br>
                <input class="opmaakPayForm" type="text" id="bevestiging" name="bevestiging" required>
             </div>
         </div>
    </form>
    <p class="boldText">LET OP! de bedragen moeten overeen komen!</p>
    <br>
    <br>
    <div class="toConfirmation">
        <form action="confirmation.php">
            <input type="submit" name="submit" value="Betalen" class="toConfirmationButton">
        </form>
    </div>

    </div>
</div>

