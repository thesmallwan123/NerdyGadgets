<!DOCTYPE html>
<head>
    <style>
    </style>
    <link rel="stylesheet" href="Public/CSS/Style.css" type="text/css">
</head>
<div>

<?php
session_start();

/* calculating delivery date with the date of today + 1 day*/
$deliveryDate = date("d/m/Y", time() + 86400);

// if the button is pressed delete the following sessions and go to the following page.
if(isset($_POST["terugNaarIndex"])) {
    unset($_SESSION['cart']);
    unset($_SESSION['korting']);
    if (!isset($_SESSION["cart"])){
    header("Location: ./index.php");
    }
}

//confirmation text
?>
    <div class="confirmationTextHeader">
        <h1>Succes!</h1>
    </div>
    <div class="confirmationText">
        <p> De bedragen komen overeen met elkaar.</p>
        <p> Uw bestelling is succesvol afgerond.</p>
        <p> Op <?php print($deliveryDate); ?> wordt uw bestelling bezorgd.</p>
        <p> Er wordt een factuur naar uw e-mailadres gestuurd.</p>
    </div>

<!--    Button to go back to the shop-->
    <div class="backToShop">
        <form method="post">
            <input type="submit" name="terugNaarIndex" value="Ga terug naar de website" class="backToShopButton button buttonGreen">
        </form>
    </div>

</body>