<?php

include __DIR__ . "/header.php";

//check if cart exists
if (isset($_SESSION['cart'])) {
    $cartItems = $_SESSION['cart'];
} else {
    $cartItems = array();
}

//Add product to cart
if (isset($_POST['submit'])) {
    $ID = $_GET["id"];

    //If product doesnt exist in cart, add it
    if (!array_key_exists($ID, $cartItems)) {
        $cartItems[$ID] = 1;
?>
        <script>
            alert("Het item is toegevoegd aan de winkelwagen!");
        </script>
    <?php
    } else {
    ?>
        <script>
            alert("Het item staat al in de winkelwagen.");
        </script>
<?php
    }

    $_SESSION['cart'] = $cartItems;
}

// Globals
$chilledStockTemperature = 1.0;
$ColdRoomSensorNumber = 5;

// Informatie product
$Query = " 
           SELECT SI.StockItemID, 
            (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
            StockItemName,
            QuantityOnHand AS Quantity,
            SearchDetails, 
            IsChillerStock AS IsChilledStock,
            (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts, MarketingComments, CustomFields, SI.Video,
            (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath   
            FROM stockitems SI 
            JOIN stockitemholdings SIH USING(stockitemid)
            JOIN stockitemstockgroups ON SI.StockItemID = stockitemstockgroups.StockItemID
            JOIN stockgroups USING(StockGroupID)
            WHERE SI.stockitemid = ?
            GROUP BY StockItemID";

$ShowStockLevel = 1000;
$Statement = mysqli_prepare($Connection, $Query);
mysqli_stmt_bind_param($Statement, "i", $_GET['id']);
mysqli_stmt_execute($Statement);
$ReturnableResult = mysqli_stmt_get_result($Statement);
if ($ReturnableResult && mysqli_num_rows($ReturnableResult) == 1) {
    $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
} else {
    $Result = null;
}

// Temperatuur product
$Query = "
SELECT Temperature
FROM coldroomtemperatures
WHERE ColdRoomSensorNumber = ?";
$Statement = mysqli_prepare($Connection, $Query);
mysqli_stmt_bind_param($Statement, "s", $ColdRoomSensorNumber);
mysqli_stmt_execute($Statement);
$returnedTemperature = mysqli_stmt_get_result($Statement);
$returnedTemperature = mysqli_fetch_all($returnedTemperature, MYSQLI_ASSOC);

$chilledStockTemperature = $returnedTemperature[0]['Temperature'];

//Get Images
$Query = "
                SELECT ImagePath
                FROM stockitemimages 
                WHERE StockItemID = ?";

$Statement = mysqli_prepare($Connection, $Query);
mysqli_stmt_bind_param($Statement, "i", $_GET['id']);
mysqli_stmt_execute($Statement);
$R = mysqli_stmt_get_result($Statement);
$R = mysqli_fetch_all($R, MYSQLI_ASSOC);


if ($R) {
    $Images = $R;
}
?>
<div id="CenteredContent">
    <?php
    if ($Result != null) {
        if (isset($Result['Video'])) {
    ?>
            <div id="VideoFrame">
                <?php print $Result['Video']; ?>
            </div>
        <?php }
        ?>
        <div id="ArticleHeader">
            <?php
            if (isset($Images)) {
                // print Single
                if (count($Images) == 1) {
            ?>
                    <div id="ImageFrame" style="background-image: url('Public/StockItemIMG/<?php print $Images[0]['ImagePath']; ?>'); background-size: 300px; background-repeat: no-repeat; background-position: center;"></div>
                <?php
                } else if (count($Images) >= 2) { ?>
                    <div id="ImageFrame">
                        <div id="ImageCarousel" class="carousel slide" data-interval="false">
                            <!-- Indicators -->
                            <ul class="carousel-indicators">
                                <?php for ($i = 0; $i < count($Images); $i++) {
                                ?>
                                    <li data-target="#ImageCarousel" data-slide-to="<?php print $i ?>" <?php print(($i == 0) ? 'class="active"' : ''); ?>></li>
                                <?php
                                } ?>
                            </ul>

                            <!-- The slideshow -->
                            <div class="carousel-inner">
                                <?php for ($i = 0; $i < count($Images); $i++) {
                                ?>
                                    <div class="carousel-item <?php print ($i == 0) ? 'active' : ''; ?>">
                                        <img src="Public/StockItemIMG/<?php print $Images[$i]['ImagePath'] ?>">
                                    </div>
                                <?php } ?>
                            </div>

                            <!-- Left and right controls -->
                            <a class="carousel-control-prev" href="#ImageCarousel" data-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </a>
                            <a class="carousel-control-next" href="#ImageCarousel" data-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </a>
                        </div>
                    </div>
                <?php
                }
            } else {
                ?>
                <div id="ImageFrame" style="background-image: url('Public/StockGroupIMG/<?php print $Result['BackupImagePath']; ?>'); background-size: cover;"></div>
            <?php
            }
            ?>


            <h1 class="StockItemID">Artikelnummer: <?php print $Result["StockItemID"]; ?></h1>
            <h2 class="StockItemNameViewSize StockItemName">
                <?php print $Result['StockItemName']; ?>
            </h2>

            <?php if($Result['IsChilledStock'] == 1){ ?>
            <div class="QuantityText">Huidige temperatuur: <?php echo ROUND($chilledStockTemperature, 1)?> °C</div>
            <?php } else { ?>
            <div class="QuantityText"></div>
            <?php } ?>

            <?php if ($Result["Quantity"] < 1000) { ?>
            <div class="QuantityText"  <?php if($Result["Quantity"] < 100) {echo 'style="color: red;"';} ?> >Voorraad: <?php print $Result['Quantity']; ?></div>
            <?php } else { ?>
            <div class="QuantityText">Op voorraad</div>
            <?php } ?>

            <div id="StockItemHeaderLeft">
                <div class="CenterPriceLeft">
                    <div class="CenterPriceLeftChild">
                        <p class="StockItemPriceText"><b><?php print sprintf("€ %.2f", $Result['SellPrice']); ?></b></p>
                        <h6> Inclusief BTW </h6>
                        <!-- Add product to the cart -->
                        <div class="addToCart">
                            <?php
                            //Look if the Quantity > 0
                            $Quantity = $Result['Quantity'];
                            if ($Quantity > 0) {
                            ?>
                                <!-- If it is enable the add to cart button -->
                                <form method="post">
                                    <input type="submit" name="submit" value="Toevoegen aan winkelwagen" class="addToCartButton button">
                                </form>
                            <?php
                            } else {
                            ?>
                                <!-- If it isn't disable the add to cart button -->
                                <form method="post">
                                    <input type="submit" name="submit" value="Toevoegen aan winkelwagen" class="disabledAddToCartButton button" disabled>
                                </form>

                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="StockItemDescription">
            <h3>Artikel beschrijving</h3>
            <p><?php print $Result['SearchDetails']; ?></p>
        </div>
        <div id="StockItemSpecifications">
            <h3>Artikel specificaties</h3>
            <?php
            $CustomFields = json_decode($Result['CustomFields'], true);
            if (is_array($CustomFields)) { ?>
                <table>
                    <thead>
                        <th>Naam</th>
                        <th>Data</th>
                    </thead>
                    <?php
                    foreach ($CustomFields as $SpecName => $SpecText) { ?>
                        <tr>
                            <td>
                                <?php print $SpecName; ?>
                            </td>
                            <td>
                                <?php
                                if (is_array($SpecText)) {
                                    foreach ($SpecText as $SubText) {
                                        print $SubText . " ";
                                    }
                                } else {
                                    print $SpecText;
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table><?php
                    } else { ?>

                <p><?php print $Result['CustomFields']; ?>.</p>
            <?php
                    }
            ?>
        </div>
    <?php
    } else {
        ?><h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2><?php
    } ?>
</div>

<?php
include __DIR__ . "/footer.php";
?>
