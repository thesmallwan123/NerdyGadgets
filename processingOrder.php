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
$deliveryDate = date("d/m/Y", time() + 86400);
//datum van vandaag
$date = date("d/m/y");
// comment is NULL
$comment = NULL;
// laste edited by is NULL
$lastEditedBy = NULL;
// variabele korting
$korting = 0.70;
//bestellingen in de database zetten.
$QUERY = '
INSERT INTO privateorder (OrderDate, ExpectedDeliveryDate, Comment, LastEditedBy, LastEditWhen, Discount)
VALUES (?, ?, ?, ?, ?, ?)';
$statement = mysqli_prepare($Connection2, $QUERY);
mysqli_stmt_bind_param($statement, 'sssisi', $date, $deliveryDate, $comment, $lastEditedBy, $date, $korting);
mysqli_stmt_execute($statement);



header("Location: ./confirmation.php");

