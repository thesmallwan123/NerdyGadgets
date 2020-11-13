<?php
// Include header
include __DIR__ . "/header.php";
// var_dump($_SESSION["cart"]);
// global $
$totaalPrijsIncVerz = 0;
$totaalPrijsExVerz = 0;
$totaalPrijsRow = 0;
$prijsRegel = array();
$taxArr = array();
$taxTotaal = 0;
// Haal sessie op
if (isset($_SESSION["cart"])) {
    $winkelwagenArtikellen = $_SESSION["cart"];
}

// Haal items op uit DB
function controllItem($artikelID){
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
function calcPriceRow($totaalPrijsRow, $prijs, $aantal){
    $totaalPrijsRow = $prijs * $aantal;  
    return $totaalPrijsRow;
}

function calcTaxRow($taxArtikel, $totaalPrijsRow){
    $taxArtikel = $taxArtikel / 100;
    $taxRow = $totaalPrijsRow * $taxArtikel;
    return $taxRow;
}

// Bereken totaalprijs
function calcPriceTotal($prijsRegel, $totaalPrijsExVerz){
    foreach ($prijsRegel as $id => $prijs) {
        $totaalPrijsExVerz += $prijs;
    }
    return $totaalPrijsExVerz;
}

function calcIncVerz($totaalPrijsExVerz){
    if($totaalPrijsExVerz < 30){
        $totaalPrijsIncVerz = $totaalPrijsExVerz + 4.95;
        $_SESSION["totaalPrijs"] = $totaalPrijsIncVerz;
        return $totaalPrijsIncVerz;
    }
    else{
        return $totaalPrijsExVerz;
    }
}

function calcTax($taxArr, $taxTotaal){
    foreach($taxArr as $id => $taxRow){
        $taxTotaal = $taxTotaal + $taxRow;
    }
    return ROUND($taxTotaal,2);
}

// Als bestelling wordt gedaan
if (isset($_POST["bestel"])) {
    echo '<script type="text/javascript">';
    echo 'window.location.href="./order.php";';
    echo '</script>';
}

?>
<div id="Wrap">
    <div class="row returnRow">
        <div class="col-2">
            <input class="returnButton" type="submit" name="return" onclick="window.history.back();" value=" < Ga terug" />
        </div>
    </div>
    <?php
    if ($winkelwagenArtikellen != "") {
        $prijsRegel = array();
        foreach ($winkelwagenArtikellen as $artikelID => $amount) {
            $artikel = controllItem($artikelID);
            $totaalPrijsRow = calcPriceRow($totaalPrijsRow, $artikel[0]["RecommendedRetailPrice"], $amount, $prijsRegel);
            array_push($prijsRegel, $totaalPrijsRow);
            $taxRow = calcTaxRow($artikel[0]["taxRate"], $totaalPrijsRow);
            array_push($taxArr, $taxRow);
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
                    <!-- Buttons to change Amount (Prijs moet ook herberekend worden) -->
                    <div class="row aantalRow">
                        <div class="col-1">Aantal: </div>
                    </div>
                    <div class="row knoppenRow">
                        <div class="col-3"><?php /* VerwijderKnop */ ?></div>
                        <div class="col-3"><?php /* MinKnop */ ?></div>
                        <div class="col-3"><?php print($amount); ?></div>
                        <div class="col-3"><?php /* PlusKnop */ ?></div>
                    </div>
                </div>
            </div>
        <?php
        }

        $totaalPrijsExVerz = calcPriceTotal($prijsRegel, $totaalPrijsExVerz);
        $totaalPrijsTax = calcTax($taxArr, $taxTotaal);
        ?>
        <div class="verzendKosten">
            Verzenkosten: 
                <?php $totaalPrijsIncVerz = calcIncVerz($totaalPrijsExVerz); 
                if($totaalPrijsIncVerz > $totaalPrijsExVerz){
                    echo "4.95";
                    }
                else{
                 echo "0.00";   
                }
                ?>
            <br>
        </div>
        <div class="BTW">
            BTW (Al bij prijs inbegrepen): 
                <?php  
                    echo $totaalPrijsTax;
                ?>
                <br>
        </div>

        <div class="totalPrice">
            Eindtotaal: <?php echo $totaalPrijsIncVerz ?><br>
            <small>Dit is inclusief BTW en Inclusief verzendkosten!</small><br>
        </div>


        <div class="row datumVerzending">
            <div class="col-8"></div>
            <div class="col-4">
                <?php print("Uw bestelling wordt op " . date("d/m/Y", time() + 86400) . " geleverd."); ?>
            </div>
        </div>
        <form action="" method="POST">
            <div class="row">
                <div class="col-10"></div>
                <input type="submit" class="col-1 bestelButton" name="bestel" value="Bestel">
            </div>
        </form>
        <?php
    } 
    else {
        ?>
            <div class="row">
                <h1 class="winkelmandLeeg">De winkelwagen is leeg</h1>
            </div>
            <div class="row terugText">Ga terug naar de vorige pagina</div>
        <?php
    } ?>

</div>