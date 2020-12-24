<?php


session_start();
include "connect.php";

$cart = $_SESSION['cart'];
//verminderen van voorraad na de bestelling.
foreach ($cart as $stockitemID => $amount) {
    $QUERY = '
UPDATE stockitemholdings
SET QuantityOnHand = QuantityOnHand - ?
WHERE StockItemID = ? ';
    $statement = mysqli_prepare($Connection, $QUERY);
    mysqli_stmt_bind_param($statement, 'ii', $amount, $stockitemID);
    mysqli_stmt_execute($statement);
}


//bereken van bezorgdag 
$deliveryDate = date("y/m/d", time() + 86400);

//datum van vandaag
$date = date("y/m/d H:i:s");


// comment is NULL
$comment = NULL;

// laste edited by is NULL
$lastEditedBy = NULL;

// variabele korting
$discount = 1.00;
$discountName = "";
if (isset($_SESSION['korting'])) {
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
        $discountName = $discountQuery[0]['discountName'];
    }
}

$totalPrice = $_SESSION["totaalPrijs"];

//bestellingen in de database zetten.
// invoeren van Orderdatum, verwachte leverdatum, comment, laatstgewijzigd door, laatstgewijzigd & korting
$QUERY = '
INSERT INTO privateorder (OrderDate, ExpectedDeliveryDate, Comment, LastEditedBy, LastEditWhen, DiscountName, TotalPrice)
VALUES (?, ?, ?, ?, ?, ?, ?)';
$statement = mysqli_prepare($Connection, $QUERY);
mysqli_stmt_bind_param($statement, 'sssissd', $date, $deliveryDate, $comment, $lastEditedBy, $date, $discountName, $totalPrice);
mysqli_stmt_execute($statement);

// bestellingen in tabel privateOrderLine zetten
// ophalen van orderID
foreach ($cart as $stockitemID => $amount) {
    $QUERY = '
            SELECT MAX(orderID) AS orderID  
            FROM privateorder';
    $statement = mysqli_prepare($Connection, $QUERY);
    mysqli_stmt_execute($statement);
    $ReturnableResult = mysqli_stmt_get_result($statement);
    $result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
    $orderID = $result["orderID"];

// ophalen van de beschrijving, prijs per stuk en de BTW
    $QUERY = '
            SELECT SearchDetails, (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, TaxRate
            FROM stockitems
            WHERE StockItemID = ?';
    $statement = mysqli_prepare($Connection, $QUERY);
    mysqli_stmt_bind_param($statement, "i", $stockitemID);
    mysqli_stmt_execute($statement);
    $ReturnableResult = mysqli_stmt_get_result($statement);
    $result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
    $searchDetails = $result["SearchDetails"];
    $sellPrice = $result["SellPrice"];
    $taxRate = $result["TaxRate"];

// invoeren van de OrderID, StockitemID, beschrijving, prijs per stuk, btw per stuk, kwantiteit & laatst gewijzigd in de tabel privateOrderLines
    $QUERY = '
    INSERT INTO privateorderlines (OrderID, StockItemID, Description, SellPrice, TaxRate, PickedQuantity, LastEditedWhen)
    VALUES (?,?,?,?,?,?,?)';
    $statement = mysqli_prepare($Connection, $QUERY);
    mysqli_stmt_bind_param($statement, "iisdiis", $orderID, $stockitemID, $searchDetails, $sellPrice, $taxRate, $amount, $date);
    mysqli_stmt_execute($statement);
}


//bestellingen in tabel privateCustomers zetten
// ophalen van de laatst gemaakte OrderID
$QUERY = '
            SELECT MAX(orderID) AS orderID  
            FROM privateorder';
$statement = mysqli_prepare($Connection, $QUERY);
mysqli_stmt_execute($statement);
$ReturnableResult = mysqli_stmt_get_result($statement);
$result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
$orderID = $result["orderID"];

//ophalen van accountID uit de tabel account doormiddel van de geregistreerde email
if (isset($_SESSION["account"])) {
    $account = $_SESSION["account"];
    $QUERY = '
            SELECT AccountID
            FROM account
            WHERE Email = ?';
    $statement = mysqli_prepare($Connection, $QUERY);
    mysqli_stmt_bind_param($statement, "s", $account);
    mysqli_stmt_execute($statement);
    $ReturnableResult = mysqli_stmt_get_result($statement);
    $result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
    $accountID = $result["AccountID"];
} else {
    $accountID = NULL;
}

//sessie van de bestelinformatie ophalen en de variabelen daarvan definieeren
$paymentInfo = $_SESSION["paymentInfo"];

$address = $paymentInfo[0];
$houseNumber = $paymentInfo[1];
$postalCode = $paymentInfo[2];
$city = $paymentInfo[3];
$firstName = $paymentInfo[4];
$insertion = $paymentInfo[5];
$lastName = $paymentInfo[6];
$gender = $paymentInfo[7];
$email = $paymentInfo[8];

// invoeren van orderid, email, voornaam, tussenvoegsel, achternaam, gender, straat, huisnummer, postcode, huisnummer, woonplaats & accountid in de tabel privatecustomers.
$QUERY = '
INSERT INTO privatecustomers 
VALUES (?,?,?,?,?,?,?,?,?,?,?)';
$statement = mysqli_prepare($Connection, $QUERY);
mysqli_stmt_bind_param($statement, ("isssssssssi"), $orderID, $email, $firstName, $insertion, $lastName, $gender, $address, $houseNumber, $postalCode, $city, $accountID);
mysqli_stmt_execute($statement);

//als alles is uitgevoerd van deze pagina wordt je doorgestuurd naar de confirmatie pagina.
header("Location: ./confirmation.php");

