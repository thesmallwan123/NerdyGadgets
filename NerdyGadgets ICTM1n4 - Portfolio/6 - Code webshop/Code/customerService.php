<?php
include __DIR__ . "/header.php";
//include("./sendMail.php");

// variabelen aangeven 
$firstName = "";
$insertion = "";
$lastName = "";
$email = "";

// Query voor het automatisch invullen van de voornaam, tussenvoegsel, achternaam en email.
if (isset($_SESSION['account'])) {
    $account = $_SESSION["account"];

    $Query = "
    SELECT firstname, infix, surname, email
    FROM account
    WHERE email = ?";
    $Statement = mysqli_prepare($Connection, $Query);
    mysqli_stmt_bind_param($Statement, "s", $account);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];

    $firstName = $Result['firstname'];
    $insertion = $Result['infix'];
    $lastName = $Result['surname'];
    $email = $Result['email'];
}

// controleren of alles is ingevuld als op de verzendknop is gedrukt & de mail word verzonden als er op de knop gedrukt is
if (isset($_POST["sendMailCustomerService"])) {
    include("./sendMail.php");
    if (berichtKlant($_POST["voornaam"], $_POST["tussenvoegsel"], $_POST["achternaam"], $_POST["email"], $_POST["bericht"]) === TRUE) {
    }
}

?>

<div class="tekstBovenCS">
    <h1>Neem contact met ons op door het volgende formulier in te vullen</h1>
</div>
<div class="container servicePageContainer">
    <!-- <form method="post" action="pay.php"> -->
    <form method="post">
        <div class="row orderRow">
            <div class="col-5">
                <label for="voornaam"> Voornaam</label><br>
                <input class="opmaakOrder" type="text" id="voornaam" name="voornaam" value="<?php print($firstName); ?>" placeholder="Voornaam" required>
            </div>
            <div class="col-2">
                <label for="tussenvoegsel"> Tussenvoegsel</label>
                <input class="opmaakOrder" type="text" id="tussenvoegsel" name="tussenvoegsel" value="<?php print($insertion); ?>" placeholder="Tussenvoegsel">
            </div>
            <div class="col-5">
                <label for="achternaam"> Achternaam </label>
                <input class="opmaakOrder" type="text" id="achternaam" name="achternaam" value="<?php print($lastName); ?>" placeholder="Achternaam" required>
            </div>
        </div>
        <div class="row orderRow">
            <div class="col-12">
                <label for="email">Email</label>
                <input class="opmaakOrder" type="email" id="email" name="email" value="<?php print($email); ?>" placeholder="Email" required>
            </div>
        </div>
        <div class="row orderRow">
            <div class="col-12">
                <label for="bericht">Uw bericht</label>
                <textarea id="bericht" name="bericht" rows="10" class="opmaakOrder" required></textarea>
            </div>
        </div>
        <div class="backToShop">
            <input type="submit" name="sendMailCustomerService" value="Verstuur" class="backToShopButton button buttonGreen">
        </div>
    </form>
</div>