<?php
session_start();
include __DIR__ . "/header.php";
include "connect.php";

$voornaam = "";
$tussenvoegsel = "";
$achternaam = "";
$email = "";
$bericht = "";

//if (isset($_SESSION['account'])) {
//    $account = $_SESSION["account"];
//
//    $Query = "
//    SELECT firstname, infix, surname, email,
//    FROM account
//    WHERE email = ?";
//    $Statement = mysqli_prepare($Connection2, $Query);
//    mysqli_stmt_bind_param($Statement, "s", $account);
//    mysqli_stmt_execute($Statement);
//    $ReturnableResult = mysqli_stmt_get_result($Statement);
//    $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
//
//    $voornaam = $Result['firstname'];
//    $tussenvoegsel = $Result['infix'];
//    $achternaam = $Result['surname'];
//    $email = $Result['email'];
//}
//
//
//if(isset($_POST['submit'])) {
//    $voornaam = $_POST["voornaam"];
//    $tussenvoegsel = $_POST["tussenvoegsel"];
//    $achternaam = $_POST["achternaam"];
//    $email = $_POST["email"];
//
//
//    $customerService = array();
//    $customerService[0] = $voornaam;
//    $customerService[1] = $tussenvoegsel;
//    $customerService[2] = $achternaam;
//    $customerService[3] = $email;
//
//    $_SESSION['customerService'] = $customerService;
//}
?>

<div class="container orderPageContainer">
    <h3>Neem contact met ons op door het volgende formulier in te vullen.</h3>
    <!-- <form method="post" action="pay.php"> -->
    <form method="post">
        <div class="row bestelRow">
            <div class="col-5">
                <label for="voornaam"> Voornaam</label><br>
                <input class="opmaakOrder" type="text" id="voornaam" name="voornaam" value="<?php print($voornaam); ?>" required>
            </div>
            <div class="col-2">
                <label for="tussenvoegsel"> Tussenvoegsel</label>
                <input class="opmaakOrder" type="text" id="tussenvoegsel" name="tussenvoegsel" value="<?php print($tussenvoegsel); ?>">
            </div>
            <div class="col-5">
                <label for="achternaam"> Achternaam </label>
                <input class="opmaakOrder" type="text" id="achternaam" name="achternaam" value="<?php print($achternaam); ?>" required>
            </div>
        </div>
        <div class="row bestelRow">
            <div class="col-12">
                <label for="email">Email</label>
                <input class="opmaakOrder" type="email" id="email" name="email" value="<?php print($email); ?>" required>
            </div>
        </div>
        <div class="row bestelRow">
            <div class="col-12"?
                 <label for="bericht">Uw bericht</label>
                    <textarea id="bericht" name="bericht" rows="10" class="opmaakOrder" required ></textarea>
        </div>