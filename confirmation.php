<?php
/* calculating delivery date with the date of today + 1 day*/
$deliveryDate = date("d/m/Y", time() + 86400);

/*calculating total price*/
$totaalprijs = $_SESSION["totaalPrijs"];
// $totaalprijs = 1;

if (isset($_POST["submit"])) {
    session_destroy();
    header("Location: ./index.php");
}

if (isset($_POST["terug"])){
    header("Location: ./order.php");
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

    <?php
    if (isset($_POST["bevestiging"]) == $totaalprijs) {
?>

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
        <input type="submit" name="submit" value="Ga terug naar de website" class="backToShopButton">
    </form>
</div>
<?php
}
else{
?>
<div class="confirmationTextP"></div>
<p> de bedragen komen niet overeen ga terug naar de gegevenspagina.</p>
<div class="backToShop">
    <form action="order.php">
        <input type="submit" name="terug" value="Ga terug naar de gegevenspagina" class="backToShopButton">
    </form>
</div>
</div>
<?php
}
?>


</body>

