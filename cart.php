<?php
// Include header
include __DIR__ . "/header.php";
include("connect.php");

// global $
$totalPriceIncShippingCost = 0;
$totalPriceExcShippingCost = 0;
$totalPriceRow = 0;
$accountDiscount = 0.95;
$priceRule = array();
$taxArr = array();
$taxTotal = 0;

$discountValid = FALSE;
$discountCode = NULL;
$codeComparison = NULL;
$discount = 1.00;
$benefit = 0;
$totalPriceExcShippingCostDiscount = 0;

$additiveDiscount = TRUE;

// Haal sessie op
if (isset($_SESSION["cart"]) && !empty($_SESSION["cart"])) {
    $shoppingCartProducts = $_SESSION["cart"];
} else {
    $shoppingCartProducts = "";
}

// Check ingevulde kortingscode tegenover de database
if (isset($_POST['kortingsCode'])) {
    $discountCode = $_POST['kortingsCode'];

    $Query = "
            SELECT discountName, discountQuantity
            FROM discount
            WHERE discountName = ?";
    $Statement = mysqli_prepare($Connection, $Query);
    mysqli_stmt_bind_param($Statement, "s", $discountCode);
    mysqli_stmt_execute($Statement);
    $discountQuery = mysqli_stmt_get_result($Statement);
    $discountQuery = mysqli_fetch_all($discountQuery, MYSQLI_ASSOC);
    
    if (isset($discountQuery[0]['discountName'])) {
        $discountValid = TRUE;
        $discount = $discountQuery[0]['discountQuantity'];
        $_SESSION['korting'] = $discountCode;
    }
}

// Anders check op bestaande sessie korting, check tegenover database
elseif (isset($_SESSION['korting'])) {
    $discountCode = $_SESSION['korting'];

    $Query = "
            SELECT discountName, discountQuantity
            FROM discount
            WHERE discountName = ?";
    $Statement = mysqli_prepare($Connection, $Query);
    mysqli_stmt_bind_param($Statement, "s", $discountCode);
    mysqli_stmt_execute($Statement);
    $discountQuery = mysqli_stmt_get_result($Statement);
    $discountQuery = mysqli_fetch_all($discountQuery, MYSQLI_ASSOC);

    if (isset($discountQuery[0]['discountName'])) {
        $discountValid = TRUE;
        $discount = $discountQuery[0]['discountQuantity'];
        $_SESSION['korting'] = $discountCode;
    }
}

// Wijzigen winkelmand
if (isset($_GET["id"])) {
    $ID = $_GET["id"];
    $cartItems = $_SESSION['cart'];
    if (isset($_GET["function"])) {
        $function = $_GET["function"];
        if ($function == "deleteItem") {
            deleteItem($ID, $cartItems);
        } elseif ($function == "decreaseItem") {
            decreaseItem($ID, $cartItems);
        } elseif ($function == "increaseItem") {
            increaseItem($ID, $cartItems);
        }
    }
}

// Verwijderen artikel
function deleteItem($ID, $cartItems) {
    if (array_key_exists($ID, $cartItems)) {
        unset($cartItems[$ID]);
        $_SESSION['cart'] = $cartItems;
        ?> <script>
        window.location.replace('./cart.php')
        </script> <?php
    }
}

            // Verminderen artikel
            function decreaseItem($ID, $cartItems)
            {
                if (array_key_exists($ID, $cartItems)) {
                    $cartItems[$ID] -= 1;
                    $_SESSION['cart'] = $cartItems;
                    ?> <script>
                    window.location.replace('./cart.php')
                    </script> <?php
                }
            }

            // Vermeerderen artikel
            function increaseItem($ID, $cartItems)
            {
                if (array_key_exists($ID, $cartItems)) {
                    $cartItems[$ID] += 1;
                    $_SESSION['cart'] = $cartItems;
                    ?> <script>
            window.location.replace('./cart.php')
        </script> <?php
                }
            }


            // Haal items op uit DB
            function controllItem($artikelID)
            {
                include("connect.php");
                $Query = "
                SELECT si.StockItemID, StockItemName, QuantityOnHand, SearchDetails, colorID, TaxRate AS taxRate, ROUND((RecommendedRetailPrice*(1+(TaxRate/100))), 2) AS RecommendedRetailPrice, ImagePath
                FROM stockitems si
                LEFT JOIN stockitemimages sii ON si.StockItemID = sii.StockItemID
                INNER JOIN stockitemholdings sih ON si.StockItemID = sih.StockItemID
                WHERE si.StockItemID = ?";

                $Statement = mysqli_prepare($Connection, $Query);
                mysqli_stmt_bind_param($Statement, 'i', $artikelID); // i = integer; s = string;
                mysqli_stmt_execute($Statement);

                $result = mysqli_stmt_get_result($Statement);
                $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

                if ($result[0]["ImagePath"] == NULL) {
                    $Query = "SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = ? LIMIT 1";

                    $Statement = mysqli_prepare($Connection, $Query);
                    mysqli_stmt_bind_param($Statement, 'i', $artikelID); // i = integer; s = string;
                    mysqli_stmt_execute($Statement);

                    $result2 = mysqli_stmt_get_result($Statement);
                    $result2 = mysqli_fetch_all($result2, MYSQLI_ASSOC);

                    $result[0]["ImagePath"] = "Public/StockGroupIMG/".$result2[0]["ImagePath"];
                } else {
                    $result[0]["ImagePath"] = "Public/StockItemIMG/".$result[0]["ImagePath"];
                }
                return $result;
            }

            // Bereken prijs per artikel
            function calcPriceRow($totalPriceRow, $price, $quantity)
            {
                $totalPriceRow = $price * $quantity;
                return $totalPriceRow;
            }

            // Bereken BTW per artikel 
            function calcTaxRow($taxArtikel, $totalPriceRow)
            {
                $taxArtikel = $taxArtikel / 100;
                $taxRow = $totalPriceRow * $taxArtikel;
                return $taxRow;
            }

            // Bereken totaalprijs exclusief verzending
            function calcPriceTotal($priceRow, $totalPriceWithoutShipping)
            {
                foreach ($priceRow as $id => $price) {
                    $totalPriceWithoutShipping += $price;
                }
                return $totalPriceWithoutShipping;
            }

            // Bereken prijs inclusief verzending
            function calcIncVerz($totalPriceWithoutShipping, $discountIsValid, $totalPriceWithoutShippingDiscount)
            {
                if ($discountIsValid == TRUE or isset($_SESSION['account'])) {
                    if ($totalPriceWithoutShippingDiscount < 30) {
                        $totalPriceIncShippingCost = $totalPriceWithoutShippingDiscount + 4.95;
                        $_SESSION["totaalPrijs"] = ROUND($totalPriceIncShippingCost, 2);
                        return ROUND($totalPriceIncShippingCost, 2);
                    } else {
                        $_SESSION["totaalPrijs"] = ROUND($totalPriceWithoutShippingDiscount, 2);
                        return ROUND($totalPriceWithoutShippingDiscount, 2);
                    }
                } elseif ($discountIsValid == FALSE) {
                    if ($totalPriceExcShippingCost < 30) {
                        $totalPriceIncShippingCost = $totalPriceExcShippingCost + 4.95;
                        $_SESSION["totaalPrijs"] = ROUND($totalPriceIncShippingCost, 2);
                        return ROUND($totalPriceIncShippingCost, 2);
                    } else {
                        $_SESSION["totaalPrijs"] = ROUND($totalPriceExcShippingCost, 2);
                        return ROUND($totalPriceExcShippingCost, 2);
                    }
                }
            }

            // Bereken Eindbedrag BTW
            function calcTax($taxArr, $taxTotal)
            {
                foreach ($taxArr as $id => $taxRow) {
                    $taxTotal = $taxTotal + $taxRow;
                }
                return ROUND($taxTotal, 2);
            }
                    ?>

<!-- Body cart -->
<div id="wrap">
    <?php
    if ($shoppingCartProducts != "") {
        $priceRule = array();
        foreach ($shoppingCartProducts as $productID => $amount) {
            $product = controllItem($productID);
            $totalPriceRow = calcPriceRow($totalPriceRow, $product[0]["RecommendedRetailPrice"], $amount, $priceRule);
            array_push($priceRule, $totalPriceRow);
            $taxRow = calcTaxRow($product[0]["taxRate"], $totalPriceRow);
            array_push($taxArr, $taxRow);
    ?>
            <!-- Producten -->
            <div class="cartRow">
                <div class="rowLeft">
                    <!-- ID & Foto -->
                    <img class="productImage" src="<?php echo $product[0]['ImagePath']; ?>">
                    <div class="productID">ID: <?php echo $product[0]["StockItemID"]; ?></div>
                </div>
                <div class="rowMiddle">
                    <!-- Naam, Beschrijving and voorraad -->
                    <div class="productName">Name: <?php echo $product[0]["StockItemName"] ?></div>
                    <div class="productSearchDetails">Details: <?php echo $product[0]["SearchDetails"] ?></div>
                    <div class="productQuantity">In Store: <?php echo $product[0]["QuantityOnHand"] ?></div>
                </div>
                <div class="rowRight">
                    <!-- prijs(incl BTW), aantal, min en plus button -->
                    <div class="productPrice">Totaal: € <?php printf("%.2f", $totalPriceRow) ?> (including BTW)</div>

                    <div class="row aantalRow">
                        <div class="col-1">Aantal: </div>
                    </div>

                    <!-- Wijzig winkelmand -->
                    <div class="row knoppenRow">
                        <!-- verwijder item -->
                        <div class="col-3">
                            <a href="cart.php?id=<?php echo $productID ?>&function=deleteItem">
                                <i class="far fa-trash-alt"></i></a>
                        </div>

                        <!-- schakel min knop uit wannneer <= 1 -->
                        <?php if ($amount <= 1) { ?>
                            <div class="col-3"></div>
                        <?php } else { ?>
                            <div class="col-3">
                                <a href="cart.php?id=<?php echo $productID ?>&function=decreaseItem">
                                    <i class="fas fa-minus"></i></a>
                            </div>
                        <?php } ?>

                        <div class="col-3"><?php print($amount); ?></div>
                        <!-- Vermeerder item -->
                        <?php
                        if ($amount >= $product[0]["QuantityOnHand"]) {
                        ?>
                            <div class="col-3"></div>
                        <?php
                        } else {
                        ?>
                            <div class="col-3">
                                <a href="cart.php?id=<?php echo $productID ?>&function=increaseItem">
                                    <i class="fas fa-plus"></i></a>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php
        }

        // Subtotalen Berekenen
        $totalPriceExcShippingCost = calcPriceTotal($priceRule, $totalPriceExcShippingCost);
        $totalPriceTax = calcTax($taxArr, $taxTotal);

        // KORTINGSBEREKENINGEN?!
        if ($additiveDiscount) {
            if(isset($_SESSION['account'])) {
                $totalDiscount = 1 - ((1 - $discount) + (1 - $accountDiscount));
                $totalPriceExcShippingCostDiscount = $totalPriceExcShippingCost * $totalDiscount;
            } else {
                $totalPriceExcShippingCostDiscount = $totalPriceExcShippingCost * $discount;
            }
        } else {
            $totalPriceExcShippingCostDiscount = $totalPriceExcShippingCost * $discount;
            if(isset($_SESSION['account'])) {
                $totalPriceExcShippingCostDiscount = $totalPriceExcShippingCostDiscount * $accountDiscount;
            }
        }
        $benefit = $totalPriceExcShippingCost - $totalPriceExcShippingCostDiscount

        ?>

        <!-- Kortingscoupon -->
            <?php if (isset($_SESSION['account'])) { ?>
            <p class="verzendKosten klantKorting">Als klant heeft u 5% bonus korting.</p>
            <?php } else { ?>
            <p class="verzendKosten klantKorting">Log in voor 5% korting!</p>
            <?php } ?>
        <div class="row">
            <form method="POST" class="kortingRow">
                <div>
                    <?php if ($discountValid) { ?>
                        <div>
                            <i class="fas fa-check"></i>
                        </div>
                    <?php } else { ?>
                        <div>
                            <i class="fas fa-times"></i>
                        </div>
                    <?php } ?>
                </div>
                <!-- Checkmark korting -->
                <div class="coupon kortingsCoupon">
                    <input type="text" name="kortingsCode" value="<?php print($discountCode); ?>" placeholder="Kortingscode">
                </div>
                <div class="coupon validateCoupon">
                    <input type="submit" name="acceptCoupon" value="Valideer">
                </div>

            </form>
        </div>

        <!-- Kosten weergeven -->
        <div class="verzendKosten">
            Verzendkosten: €
            <?php $totalPriceIncShippingCost = calcIncVerz($totalPriceExcShippingCost, $discountValid, $totalPriceExcShippingCostDiscount);
            if ($totalPriceIncShippingCost < 30) {
                echo "4.95";
            } else {
                echo "0.00";
            }
            ?>
            <br>
        </div>

        <div class="BTW">
            BTW (al bij de prijs inbegrepen): €
            <?php
            printf("%.2f", $totalPriceTax);
            ?>
        </div>

        <!-- Korting weergeven -->
        <?php
        if ($discountValid == TRUE OR isset($_SESSION['account'])) {
        ?>
            <div class="costBreakdown korting">
                Korting: €
                <?php
                printf("%.2f", $benefit);
                ?>
                <br>
            </div>
        <?php
        }
        ?>

        <br>
        <!-- Totaalprijs-->
        <div class="costBreakdown totalPrice">
            Eindtotaal: € <?php printf("%.2f", $totalPriceIncShippingCost) ?><br>
            <small>Dit is inclusief BTW en Inclusief verzendkosten!</small><br>
        </div>

        <!-- Verzending -->
        <div class="datumVerzending">
            <?php print("Uw bestelling zal op " . date("d/m/Y", time() + 86400) . " worden geleverd"); ?>
        </div>

        <!-- Bestel & terugknop -->
        <div class="bestelRow">
            <div class="order">
                <form action="./index.php" method="POST">
                    <input type="submit" class="toStore button buttonRed" name="return" value="Ga terug">
                </form>
            </div>
            <div class="order">
                <form action="./order.php" method="POST">
                    <input type="submit" class="toOrder button buttonGreen" name="bestel" value="Bestel">
                </form>
            </div>
        </div>


    <?php
    } else {
    ?>
        <div class="row">
            <h1 class="winkelmandLeeg">De winkelwagen is leeg</h1>
        </div>
        <div class="row" style="width: 100%;">
            <div class="terugText" style="width: 100%; text-align: center;">Ga terug naar de vorige pagina</div>
        </div>
    <?php
    }
    ?>

</div>