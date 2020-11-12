<?php
// testdata
$_SESSION["cart"] = "";
$_SESSION['cart'] = array(22=>5, 29=>1);


// Include header
include __DIR__ . "/header.php";


// global $
$totalPrice = 0;
$totalPriceRow = 0;
$priceRow = array();

if(isset($_SESSION["cart"])){
    $cartItems = $_SESSION["cart"];
}

function controllItem($artikelID){
    include("connect.php");
    $Query = "
            SELECT si.StockItemID, StockItemName, QuantityOnHand, SearchDetails, colorID, RecommendedRetailPrice, ImagePath
            FROM stockitems si
            LEFT JOIN stockitemimages sii ON si.StockItemID = sii.StockItemID
            INNER JOIN stockitemholdings sih ON si.StockItemID = sih.StockItemID
            WHERE si.StockItemID = ?";

    $Statement = mysqli_prepare($Connection, $Query);
    mysqli_stmt_bind_param($Statement, 'i', $artikelID); // i = integer; s = string;
    mysqli_stmt_execute($Statement);

    $result = mysqli_stmt_get_result($Statement);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $result;
}

function calcPriceRow($totalPriceRow, $price, $quantity, $priceRow){
    $totalPriceRow = $price * $quantity;
    array_push($priceRow, $totalPriceRow);
    return $totalPriceRow;
}

function calcPriceTotal($priceRow, $totalPrice){
    foreach($priceRow as $id => $price){
        $totalPrice += $price;
    }
    return $totalPrice;
}

?>
<div id="Wrap">
    <?php
    // var_dump($cartItems);
    if(isset($cartItems)){
        $priceRow = array();
        foreach ($cartItems as $artikelID => $amount) {
            $item = controllItem($artikelID);
            $totalPriceRow = calcPriceRow($totalPriceRow, $item[0]["RecommendedRetailPrice"], $amount, $priceRow);
            array_push($priceRow, $totalPriceRow);
            ?>
            <div class="cartRow">
                <div class="rowLeft">
                    <!-- ID and Image -->
                    <img class="productImage" src="Public/StockItemIMG/<?php echo $item[0]['ImagePath']; ?>">
                    <div class="productID">ID: <?php echo $item[0]["StockItemID"]; ?></div>
                </div>
                <div class="rowMiddle">
                    <!-- Name, Description and Supply -->
                    <div class="productName">Name: <?php echo $item[0]["StockItemName"] ?></div>
                    <div class="productSearchDetails">Details: <?php echo $item[0]["SearchDetails"] ?></div>
                    <div class="productQuantity">In Store: <?php echo $item[0]["QuantityOnHand"] ?></div>
                </div>
                <div class="rowRight">
                    <!-- Price(incl BTW), Amount, Remove and add button -->
                    <div class="productPrice">Totaal: <?php echo $totalPriceRow ?> (including BTW)</div>




                    <!-- vanaf hier Ana -->
                </div>
            </div>
            <?php
        }

        $totalPrice = calcPriceTotal($priceRow, $totalPrice);
        ?>
        <div class="totalPrice">
            Totaal: <?php echo $totalPrice ?><br>
            <small>Let op!<br>Dit is inclusief BTW en Inclusief verzendkosten!</small>
        </div>
        <?php
    }
    else{
        // var_dump($cartItems);
        ?>
        <div class="wrap">
            <?php  ?>
        </div>
        <?php
    }?>
</div>