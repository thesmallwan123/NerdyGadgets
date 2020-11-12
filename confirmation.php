<?php
/* calculating delivery date with the date of today + 1 day*/
$deliveryDate = date("d/m/Y", time() + 86400);
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
<p> Er wordt een factuur naar uw e-mailadres gestuurd.</p>
</div>

<div class="backToShop">
    <form action="index.php">
        <input type="submit" name="submit" value="Ga terug naar de website" class="backToShopButton">
    </form>
</div>

</body>