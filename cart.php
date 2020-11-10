<?php


// Get cookies of items in cart 
setcookie("cartItem[1]", 21);
setcookie("cartItem[0]", 227);
$cartItems = $_COOKIE["cartItem"];

// Check if items exist
$amountCartItems = count($cartItems);

function controllItem($cartItems, $i){
    include("connect.php");
    $itemID = intval($cartItems[$i]);
    $Query = "
            SELECT si.StockItemID, StockItemName, QuantityOnHand, SearchDetails, colorID, RecommendedRetailPrice, ImagePath
            FROM stockitems si
            LEFT JOIN stockitemimages sii ON si.StockItemID = sii.StockItemID
            INNER JOIN stockitemholdings sih ON si.StockItemID = sih.StockItemID
            WHERE si.StockItemID = ?";

    $Statement = mysqli_prepare($Connection, $Query);
    mysqli_stmt_bind_param($Statement, 'i', $itemID); // i = integer; s = string;
    mysqli_stmt_execute($Statement);

    $result = mysqli_stmt_get_result($Statement);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $result;
}



// Edited out, No visuals of items underneath the header
include __DIR__ . "/header.php";
?>
<div id="Wrap">
    <?php
    for ($i = 0; $i < $amountCartItems; $i++) {
        $item = controllItem($cartItems, $i);
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
                <div class="productQuantity">Quantity: <?php echo $item[0]["QuantityOnHand"] ?></div>
            </div>
            <div class="rowRight">
                <!-- Price(incl BTW), Amount, Remove and add button -->
                <div class="productPrice">Price: <?php echo $item[0]["RecommendedRetailPrice"] ?> (including BTW)</div>
                
                <!-- vanaf hier Jeremy -->



            </div>
        </div>
    <?php
    }
    ?>
</div>