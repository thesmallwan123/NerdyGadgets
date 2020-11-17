<?php
/* calculating delivery date with the date of today + 1 day*/
$deliveryDate = date("d/m/Y", time() + 86400);

if (isset($_POST["submit"])) {
    session_destroy();
    header("Location: ./index.php");
}


?>

<!DOCTYPE html>
<head>
    <style>
    </style>
    <link rel="stylesheet" href="Public/CSS/Style.css" type="text/css">
</head>
<body>
<div class="confirmationTextHeader">

    <h1>Succes!</h1>

</div>

<div class="confirmationTextP">

<p> Uw bestelling is succesvol afgerond.</p>
<p> Op <?php print($deliveryDate); ?> wordt uw bestelling bezorgd.</p>
<!--<p> Er wordt een factuur naar uw e-mailadres gestuurd.</p>-->
</div>

<div class="backToShop">
    <form method="POST">
        <input type="submit" name="submit" value="Ga terug naar de website" class="backToShopButton">
    </form>
</div>

</body>