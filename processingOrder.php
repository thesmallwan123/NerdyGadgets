<?php
session_start();
include "connect.php";

$cart = $_SESSION['cart'];

foreach ($cart as $stockitemID => $amount){
$QUERY = '
UPDATE stockitemholdings
SET QuantityOnHand = QuantityOnHand - ?
WHERE StockItemID = ? ';
$statement = mysqli_prepare($Connection, $QUERY);
mysqli_stmt_bind_param($statement, 'ii', $amount, $stockitemID );
mysqli_stmt_execute($statement);
}

header("Location: ./confirmation.php");

