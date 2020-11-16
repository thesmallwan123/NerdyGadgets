<?php
include __DIR__ . "/header.php";
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
                <input class="opmaakOrder" type="text" id="voornaam" name="voornaam" required>
            </div>
            <div class="col-2">
                <label for="tussenvoegsel"> Tussenvoegsel</label>
                <input class="opmaakOrder" type="text" id="tussenvoegsel" name="tussenvoegsel">
            </div>
            <div class="col-5">
                <label for="achternaam"> Achternaam </label>
                <input class="opmaakOrder" type="text" id="achternaam" name="achternaam" required>
            </div>
        </div>
        <div class="row bestelRow">
            <div class="col-12">
                <label for="email">Email</label>
                <input class="opmaakOrder" type="email" id="email" name="email" required>
            </div>
        </div>
        <div class="row bestelRow">
            <div class="col-10">
                <label for="address">Straat</label>
                <input class="opmaakOrder" type="text" id="straat" name="straat" required>
            </div>
            <div class="col-2">
                <label for="address">Huisnummer</label>
                <input class="opmaakOrder" type="number" id="huisnummer" name="huisnummer" required>
            </div>
        </div>
        <div class="row bestelRow">
            <div class="col-10">
                <label for="city"> Woonplaats</label>
                <input class="opmaakOrder" type="text" id="woonplaats" name="woonplaats" required>
            </div>
            <div class="col-2">
                <label for="postalcode">Postcode</label>
                <input class="opmaakOrder" type="text" id="postcode" name="postcode" required>
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