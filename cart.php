<?php
// Include header
include __DIR__ . "/header.php";

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
else{
    $winkelwagenArtikellen = "";
}

// Wijzigen winkelmand
if(isset($_GET["id"])){
    $ID = $_GET["id"];
    $cartItems = $_SESSION['cart'];
        if(isset($_GET["function"])){
            $function = $_GET["function"];
            if($function == "deleteItem"){
                deleteItem($ID, $cartItems);
            }
            elseif($function == "decreaseItem"){
                decreaseItem($ID, $cartItems);
            }
            elseif($function == "increaseItem"){
                increaseItem($ID, $cartItems);
            }
        }
}

// Verwijderen artikel
function deleteItem($ID, $cartItems){
    if(array_key_exists($ID, $cartItems)) {
        unset($cartItems[$ID]);
        $_SESSION['cart'] = $cartItems;
        ?> <script>window.location.replace('./cart.php')</script> <?php
    }
}

// Verminderen artikel
function decreaseItem($ID, $cartItems){
    if(array_key_exists($ID, $cartItems)) {
        $cartItems[$ID] -= 1;
        $_SESSION['cart'] = $cartItems;
        ?> <script>window.location.replace('./cart.php')</script> <?php
    }
}

// Vermeerderen artikel
function increaseItem($ID, $cartItems){
    if(array_key_exists($ID, $cartItems)) {
        $cartItems[$ID] += 1;
        $_SESSION['cart'] = $cartItems;
        ?> <script>window.location.replace('./cart.php')</script> <?php
    }
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

// Bereken BTW per artikel 
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

// Bereken prijs inclusief verzending
function calcIncVerz($totaalPrijsExVerz){
    if($totaalPrijsExVerz < 30){
        $totaalPrijsIncVerz = $totaalPrijsExVerz + 4.95;
        $_SESSION["totaalPrijs"] = $totaalPrijsIncVerz;
        return $totaalPrijsIncVerz;
    }
    else {
        $_SESSION["totaalPrijs"] = $totaalPrijsExVerz;
        return $totaalPrijsExVerz;
    }
}

// Bereken Eindbedrag BTW
function calcTax($taxArr, $taxTotaal){
    foreach($taxArr as $id => $taxRow){
        $taxTotaal = $taxTotaal + $taxRow;
    }
    return ROUND($taxTotaal,2);
}

?>
<div id="Wrap">
    <div class="row returnRow">
        <div class="col-2">
            <a href="./index.php">
                <input class="returnButton" type="submit" name="return" value=" < Ga terug" />
            </a>
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
                        <?php if($amount <= 1){?>
                            <div class="col-3">

                            </div>
                        <?php } else { ?>
                            <div class="col-3">
                                <a href="cart.php?id=<?php echo $artikelID ?>&function=decreaseItem">
                                    <i class="fas fa-minus"></i></a>
                            </div>
                        <?php } ?>

                        <div class="col-3"><?php print($amount); ?></div>
                        <!-- Increase item -->
                        <div class="col-3">
                            <a href="cart.php?id=<?php echo $artikelID ?>&function=increaseItem">
                                <i class="fas fa-plus"></i></a>
                        </div>
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
        <form action="./order.php" method="POST">
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