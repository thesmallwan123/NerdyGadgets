<?php
// Include header
include __DIR__ . "/header.php";


// global $
$totaalPrijs = 0;
$totaalPrijsRow = 0;
$prijsRegel = array();

// Haal sessie op
if (isset($_SESSION["cart"])) {
    $winkelwagenArtikellen = $_SESSION["cart"];
}

// Haal items op uit DB
function controllItem($artikelID)
{
    include("connect.php");
    $Query = "
            SELECT si.StockItemID, StockItemName, QuantityOnHand, SearchDetails, colorID, ROUND((RecommendedRetailPrice*(1+(TaxRate/100))), 2) AS RecommendedRetailPrice, ImagePath
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
function calcPriceRow($totaalPrijsRow, $prijs, $aantal, $prijsRegel)
{
    $totaalPrijsRow = $prijs * $aantal;
    array_push($prijsRegel, $totaalPrijsRow);
    return $totaalPrijsRow;
}

// Bereken totaalprijs
function calcPriceTotal($prijsRegel, $totaalPrijs)
{
    foreach ($prijsRegel as $id => $prijs) {
        $totaalPrijs += $prijs;
    }
    $_SESSION["totaalPrijs"] = $totaalPrijs;
    return $totaalPrijs;
}

// Als bestelling wordt gedaan
if (isset($_POST["bestel"])) {
    echo '<script type="text/javascript">';
    echo 'window.location.href="./order.php";';
    echo '</script>';
}

?>
<div id="Wrap">
    <?php
    if (isset($winkelwagenArtikellen)) {
        $prijsRegel = array();
        foreach ($winkelwagenArtikellen as $artikelID => $amount) {
            $artikel = controllItem($artikelID);
            $totaalPrijsRow = calcPriceRow($totaalPrijsRow, $artikel[0]["RecommendedRetailPrice"], $amount, $prijsRegel);
            array_push($prijsRegel, $totaalPrijsRow);
    ?>
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




                    <!-- vanaf hier Ana -->
                </div>
            </div>
        <?php
        }

        $totaalPrijs = calcPriceTotal($prijsRegel, $totaalPrijs);
        ?>
        <div class="totalPrice">
            Totaal: <?php echo $totaalPrijs ?><br>
            <small><br>Dit is inclusief BTW en Inclusief verzendkosten!</small>
        </div>
        <form action="" method="POST">
            <div class="row">
                <div class="col-10"></div>
                <input type="submit" class="col-1 bestelButton" name="bestel" value="Bestel">
            </div>
        </form>
    <?php
    } else {
    ?>
        <div class="wrap">
            <?php  ?>
        </div>
    <?php
    } ?>

</div>