<?php
include __DIR__ . "/header.php";
?>
<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="Public/CSS/Style.css" type="text/css">
</head>
<body>
    <div class="orderRow">
        <div class="col-75">
            <div class="container">

        <div class="orderRow">
          <div class="col-75">
              <h3>Bestel gegevens</h3>
            <label for="fname"> Volledige naam</label>
            <input type="text" id="fname" name="fullname">
             <label for="email">Email</label>
             <input type="text" id="email" name="email">
             <label for="address">Straatnaam + huisnummer</label>
             <input type="text" id="address" name="address">
             <label for="city"> Woonplaats</label>
             <input type="text" id="city" name="city">
             <label for="postalcode">Postcode</label>
             <input type="text" id="postalcode" name="postalcode">
            </div>
        </div>
    </div>

            <div class="toCart">
                <form action="cart.php">
                    <input type="submit" name="submit" value="Terug naar winkelmand" class="toCartButton">
                    </form>
            </div>
                <div class="toPayment">
                    <form action="pay.php">
                        <input type="submit" name="submit" value="Door naar betalen" class="toPaymentButton">
                    </form>
                </div>




</body>
