<?php
// Include header
include __DIR__ . "/header.php";
include("connect.php");

// global $
$totaalPrijsIncVerz = 0;
$totaalPrijsExVerz = 0;
$totaalPrijsRow = 0;
$accountKorting = 0.97;
$prijsRegel = array();
$taxArr = array();
$taxTotaal = 0;

$kortingGeldig = FALSE;
$kortingsCode = NULL;
$codeComparison = NULL;
$korting = 0.70;
$voordeel = 0;
$totaalPrijsExVerzKorting = 0;

// Haal sessie op
if (isset($_SESSION["cart"]) && !empty($_SESSION["cart"])) {
    $winkelwagenartikelen = $_SESSION["cart"];
} else {
    $winkelwagenartikelen = "";
}

// Check ingevulde kortingscode tegenover de database
if (isset($_POST['kortingsCode'])) {
    $kortingsCode = $_POST['kortingsCode'];

    $Query = "
            SELECT discounts
            FROM discount
            WHERE discounts = ?";
    $Statement = mysqli_prepare($Connection, $Query);
    mysqli_stmt_bind_param($Statement, "s", $kortingsCode);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);

    if (mysqli_num_rows($ReturnableResult) == 1) {
        $kortingGeldig = TRUE;
        $_SESSION['korting'] = $kortingsCode;
    }

    // Anders check op bestaande sessie korting, check tegenover database
} elseif (isset($_SESSION['korting'])) {
    $kortingsCode = $_SESSION['korting'];

    $Query = "
            SELECT discounts
            FROM discount
            WHERE discounts = ?";
    $Statement = mysqli_prepare($Connection, $Query);
    mysqli_stmt_bind_param($Statement, "s", $kortingsCode);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);

    if (mysqli_num_rows($ReturnableResult) == 1) {
        $kortingGeldig = TRUE;
        $_SESSION['korting'] = $kortingsCode;
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
function deleteItem($ID, $cartItems)
{
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

                return $result;
            }

            // Bereken prijs per artikel
            function calcPriceRow($totaalPrijsRow, $prijs, $aantal)
            {
                $totaalPrijsRow = $prijs * $aantal;
                return $totaalPrijsRow;
            }

            // Bereken BTW per artikel 
            function calcTaxRow($taxArtikel, $totaalPrijsRow)
            {
                $taxArtikel = $taxArtikel / 100;
                $taxRow = $totaalPrijsRow * $taxArtikel;
                return $taxRow;
            }

            // Bereken totaalprijs exclusief verzending
            function calcPriceTotal($prijsRegel, $totaalPrijsExVerz)
            {
                foreach ($prijsRegel as $id => $prijs) {
                    $totaalPrijsExVerz += $prijs;
                }
                return $totaalPrijsExVerz;
            }

            // Bereken prijs inclusief verzending
            function calcIncVerz($totaalPrijsExVerz, $kortingGeldig, $totaalPrijsExVerzKorting)
            {
                if ($kortingGeldig == TRUE) {
                    if ($totaalPrijsExVerzKorting < 30) {
                        $totaalPrijsIncVerz = $totaalPrijsExVerzKorting + 4.95;
                        $_SESSION["totaalPrijs"] = ROUND($totaalPrijsIncVerz, 2);
                        return ROUND($totaalPrijsIncVerz, 2);
                    } else {
                        $_SESSION["totaalPrijs"] = ROUND($totaalPrijsExVerzKorting, 2);
                        return ROUND($totaalPrijsExVerzKorting, 2);
                    }
                } elseif ($kortingGeldig == FALSE) {
                    if ($totaalPrijsExVerz < 30) {
                        $totaalPrijsIncVerz = $totaalPrijsExVerz + 4.95;
                        $_SESSION["totaalPrijs"] = ROUND($totaalPrijsIncVerz, 2);
                        return ROUND($totaalPrijsIncVerz, 2);
                    } else {
                        $_SESSION["totaalPrijs"] = ROUND($totaalPrijsExVerz, 2);
                        return ROUND($totaalPrijsExVerz, 2);
                    }
                }
            }

            // Bereken Eindbedrag BTW
            function calcTax($taxArr, $taxTotaal)
            {
                foreach ($taxArr as $id => $taxRow) {
                    $taxTotaal = $taxTotaal + $taxRow;
                }
                return ROUND($taxTotaal, 2);
            }

                    ?>

<!-- Body cart -->
<div id="wrap">
    <?php
    if ($winkelwagenartikelen != "") {
        $prijsRegel = array();
        foreach ($winkelwagenartikelen as $artikelID => $amount) {
            $artikel = controllItem($artikelID);
            $totaalPrijsRow = calcPriceRow($totaalPrijsRow, $artikel[0]["RecommendedRetailPrice"], $amount, $prijsRegel);
            array_push($prijsRegel, $totaalPrijsRow);
            $taxRow = calcTaxRow($artikel[0]["taxRate"], $totaalPrijsRow);
            array_push($taxArr, $taxRow);
    ?>
            <!-- Producten -->
            <div class="cartRow">
                <div class="rowLeft">
                    <!-- ID and Image -->
                    <img class="productImage" src="Public/StockItemIMG/<?php echo $artikel[0]['ImagePath']; ?>">
                    <div class="productID">ID: <?php echo $artikel[0]["StockItemID"]; ?></div>
                </div>
                <div class="rowMiddle">
                    <!-- Name, Description and Supply -->
                    <div class="productName">Name: <?php echo $artikel[0]["StockItemName"] ?></div>
                    <div class="productSearchDetails">Details: <?php echo $artikel[0]["SearchDetails"] ?></div>
                    <div class="productQuantity">In Store: <?php echo $artikel[0]["QuantityOnHand"] ?></div>
                </div>
                <div class="rowRight">
                    <!-- Price(incl BTW), Amount, Remove and add button -->
                    <div class="productPrice">Totaal: <?php echo $totaalPrijsRow ?> (including BTW)</div>

                    <div class="row aantalRow">
                        <div class="col-1">Aantal: </div>
                    </div>

                    <!-- Edit cart -->
                    <div class="row knoppenRow">
                        <!-- Delete item -->
                        <div class="col-3">
                            <a href="cart.php?id=<?php echo $artikelID ?>&function=deleteItem">
                                <i class="far fa-trash-alt"></i></a>
                        </div>

                        <!-- Disable minus button when amount <= 1 -->
                        <?php if ($amount <= 1) { ?>
                            <div class="col-3"></div>
                        <?php } else { ?>
                            <div class="col-3">
                                <a href="cart.php?id=<?php echo $artikelID ?>&function=decreaseItem">
                                    <i class="fas fa-minus"></i></a>
                            </div>
                        <?php } ?>

                        <div class="col-3"><?php print($amount); ?></div>
                        <!-- Increase item -->
                        <?php 
                            if ($amount >= $artikel[0]["QuantityOnHand"]) {
                        ?>
                                <div class="col-3"></div>
                        <?php
                            } else {
                        ?>
                                <div class="col-3">
                                    <a href="cart.php?id=<?php echo $artikelID ?>&function=increaseItem">
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
        $totaalPrijsExVerz = calcPriceTotal($prijsRegel, $totaalPrijsExVerz);
        $totaalPrijsTax = calcTax($taxArr, $taxTotaal);

        // Bereken totaalprijs exc. verz. met korting wanneer geldig
        if ($kortingGeldig) {
            $voordeel = $totaalPrijsExVerz * (1 - $korting);
            $totaalPrijsExVerzKorting = ($totaalPrijsExVerz * $korting);
        }

        if (isset($_SESSION['account'])) {
            $totaalPrijsExVerzKorting = $totaalPrijsExVerzKorting * $accountKorting;
        }

        ?>

        <!-- Kortingscoupon -->
        <div class="row">
                <p class="klantKorting">Klanten met een account krijgen een vaste korting van 3%</p>
        </div>
        <div class="row">
            <form method="POST" class="kortingRow">
                
                <div class="">
                    <?php if ($kortingGeldig) { ?>
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
                    <input type="text" name="kortingsCode button" value="<?php print($kortingsCode); ?>" placeholder="Kortingscode">
                </div>
                <div class="coupon validateCoupon">
                    <input type="submit" name="acceptCoupon button" value="Valideer">
                </div>

            </form>   
        </div>

        <!-- Kosten weergeven -->
        <div class="verzendKosten">
            Verzendkosten:
            <?php $totaalPrijsIncVerz = calcIncVerz($totaalPrijsExVerz, $kortingGeldig, $totaalPrijsExVerzKorting);
            if ($totaalPrijsIncVerz < 30) {
                echo "4.95";
            } else {
                echo "0.00";
            }
            ?>
            <br>
        </div>

        <div class="BTW">
            BTW (al bij de prijs inbegrepen):
            <?php
            echo $totaalPrijsTax;
            ?>
        </div>

        <!-- Korting weergeven -->
        <?php
        if ($kortingGeldig == TRUE) {
        ?>
            <div class="costBreakdown korting">
                Korting:
                <?php
                echo ROUND($voordeel, 2);
                ?>
                <br>
            </div>
        <?php
        }
        ?>

        <br>
        <!-- Totaalprijs-->
        <div class="costBreakdown totalPrice">
            Eindtotaal: <?php echo ROUND($totaalPrijsIncVerz, 2) ?><br>
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
                    <input type="submit" class="toStore" name="return" value=" < Ga terug">
                </form>
            </div>
            <div class="order">
                <form action="./order.php" method="POST">
                    <input type="submit" class="toOrder" name="bestel" value="Bestel">
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
