<?php
include __DIR__ . "/header.php";

$deliveryDate = date("d/m/Y", time() + 86400);
$totaalprijs = $_SESSION["totaalPrijs"];
$totaalprijs = 1;
?>

<div id="Wrap">
    <div class="confirmationTextP">

        <p>U moet <?php print($totaalprijs); ?> betalen.</p>
    <p>Dit bedrag is inclusief BTW</p>
    <p>Uw bestelling wordt op <?php print($deliveryDate); ?> geleverd. </p>
    <p>Op uw ingevoerde adres. (adres gegevens)</p>
        <?php
        print($_POST["straat"]. " ");
        print($_POST["huisnummer"]. " ");
        print($_POST["postcode"]. " ");
        print($_POST["woonplaats"]. " ");
        ?>

    </div>
</div>
