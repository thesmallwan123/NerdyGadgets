<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Set MySQLi to throw exceptions
try {
    $Connection = mysqli_connect("192.168.64.3", "root", "", "nerdygadgets");
    mysqli_set_charset($Connection, 'latin1');
    $DatabaseAvailable = true;
} catch (mysqli_sql_exception $e) {
    $DatabaseAvailable = false;
}

if (!$DatabaseAvailable) {
    ?><h2>Website wordt op dit moment onderhouden.</h2><?php
    die();
}