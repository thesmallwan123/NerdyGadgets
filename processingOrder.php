<?php
session_start();
include "connect.php";

$cart = $_SESSION['cart'];
//verminderen van voorraad na de bestelling.
foreach ($cart as $stockitemID => $amount){
$QUERY = '
UPDATE stockitemholdings
SET QuantityOnHand = QuantityOnHand - ?
WHERE StockItemID = ? ';
$statement = mysqli_prepare($Connection, $QUERY);
mysqli_stmt_bind_param($statement, 'ii', $amount, $stockitemID );
mysqli_stmt_execute($statement);
}


/* calculating delivery date with the date of today + 1 day*/
$deliveryDate = date("y/m/d", time() + 86400);

//datum van vandaag
$date = date("y/m/d H:i:s");


// comment is NULL
$comment = NULL;

// laste edited by is NULL
$lastEditedBy = NULL;

// variabele korting
$korting = 0.70;

//bestellingen in de database zetten.
// bestellingen in tabel privateOrder zetten
$QUERY = '
INSERT INTO privateorder (OrderDate, ExpectedDeliveryDate, Comment, LastEditedBy, LastEditWhen, Discount)
VALUES (?, ?, ?, ?, ?, ?)';
$statement = mysqli_prepare($Connection, $QUERY);
mysqli_stmt_bind_param($statement, 'sssisi', $date, $deliveryDate, $comment, $lastEditedBy, $date, $korting);
mysqli_stmt_execute($statement);

// bestellingen in tabel privateOrderLine zetten
foreach ($cart as $stockitemID => $amount) {
    $QUERY = '
SELECT MAX(orderID) AS orderID  
FROM privateorder';
    $statement = mysqli_prepare($Connection, $QUERY);
    mysqli_stmt_execute($statement);
    $ReturnableResult = mysqli_stmt_get_result($statement);
    $result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
    $orderID =$result["orderID"];

    $QUERY = '
SELECT SearchDetails, UnitPrice, TaxRate
FROM stockitems
WHERE StockItemID = ?';
    $statement = mysqli_prepare($Connection, $QUERY);
    mysqli_stmt_bind_param($statement,"i", $stockitemID);
    mysqli_stmt_execute($statement);
    $ReturnableResult = mysqli_stmt_get_result($statement);
    $result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
    $searchDetails = $result["SearchDetails"];
    $unitPrice = $result["UnitPrice"];
    $taxRate = $result["TaxRate"];


    $QUERY = '
    INSERT INTO privateorderlines (OrderID, StockItemID, Description, UnitPrice, TaxRate, PickedQuantity, LastEditedWhen)
    VALUES (?,?,?,?,?,?,?)';
    $statement = mysqli_prepare($Connection, $QUERY);
    mysqli_stmt_bind_param($statement, "iiiiiis", $orderID, $stockitemID, $searchDetails, $unitPrice, $taxRate, $amount, $date);
    mysqli_stmt_execute($statement);
}


//bestellingen in tabel privateCustomers zetten

    $QUERY = '
SELECT MAX(orderID) AS orderID  
FROM privateorder';
    $statement = mysqli_prepare($Connection, $QUERY);
    mysqli_stmt_execute($statement);
    $ReturnableResult = mysqli_stmt_get_result($statement);
    $result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
    $orderID =$result["orderID"];

if (isset($_SESSION["account"])){
    $email = $_SESSION["account"];
    $QUERY = '
SELECT AccountID
FROM account
WHERE Email = ?';
    $statement = mysqli_prepare($Connection, $QUERY);
    mysqli_stmt_bind_param($statement, "s", $email);
    mysqli_stmt_execute($statement);
    $ReturnableResult = mysqli_stmt_get_result($statement);
    $result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
    $accountID =$result["accountID"];
}
else {
    $accountID = NULL;
}
print $accountID;
exit();
$paymentInfo = $_SESSION["paymentInfo"];

$straat = $paymentInfo[0];
$huisnummer= $paymentInfo[1];
$postcode = $paymentInfo[2];
$woonplaats = $paymentInfo[3];
$voornaam = $paymentInfo[4];
$tussenvoegsel = $paymentInfo[5];
$achternaam = $paymentInfo[6];
$gender = $paymentInfo[7];
$email = $paymentInfo[8];

$QUERY = '
INSERT INTO privatecustomers 
VALUES (?,?,?,?,?,?,?,?,?,?,?)';
$statement = mysqli_prepare($Connection, $QUERY);
mysqli_stmt_bind_param($statement, ("isssssssssi"), $orderID, $email, $voornaam, $tussenvoegsel, $achternaam, $gender, $straat, $huisnummer, $postcode, $woonplaats, $accountID);
mysqli_stmt_execute($statement);


header("Location: ./confirmation.php");

