<?php
include "connect.php";
session_start();
?>

<!DOCTYPE html>

<html lang="en" style="background-color: rgb(35, 35, 47);">

<head>
    <style>
        @font-face {
            font-family: MmrText;
            src: url(/Public/fonts/mmrtext.ttf);
        }
    </style>
    <meta charset="ISO-8859-1">
    <title>NerdyGadgets</title>
    <link rel="stylesheet" href="Public/CSS/Style.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/nha3fuq.css">
</head>

<body>

<?php
// Variabelen worden gedefiniëerd en leeggemaakt voor het formulier.
// Wanneer er iets in het formulier is ingevuld wordt deze variabele vervangen door die waarde
$firstName = "";
$insertion = "";
$lastName = "";
$email = "";
$address = "";
$houseNumber = "";
$postalCode = "";
$city = "";
$mailAlreadyExists = false;
$passwordDoesNotMatch = false;
$passwordIsStrong = false;
$passwordIsWeak = false;
$passwordCheck = false;

// Als het aanmelden klaar is check of alles klopt en stuur ze daarna naar de inlogpagina
if (isset($_POST['aanmeldenKlaar'])) {

    // Maak variabelen van de gegevens van het aanmeldformulier (voor het gemak)
    $firstName = $_POST['voornaam'];
    $insertion = $_POST['tussenvoegsel'];
    $lastName = $_POST['achternaam'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $address = $_POST['straat'];
    $houseNumber = $_POST['huisnummer'];
    $postalCode = $_POST['postcode'];
    $city = $_POST['plaats'];
    $password = $_POST['wachtwoord'];
    $passwordConfirmation = $_POST['wachtwoordConfirmatie'];

    // Check of het ingevulde e-mailadres niet al in de database staat
    // Eerst vraag je aan de database om het ingevulde emailadres
    // Bestaat dit niet geeft hij 0 rows terug en kan je doorgaan.
    $Query = "
    SELECT email
    FROM account
    WHERE email = ?";
    $Statement = mysqli_prepare($Connection, $Query);
    mysqli_stmt_bind_param($Statement, "s", $email);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);

    if (mysqli_num_rows($ReturnableResult) == 0) {

        // requirements password policy || 1 uppercase || 1 lowercase || 1 cijfer || minimaal 8 tekens
        $uppercase = preg_match('@[A-Z]@', $_POST['wachtwoord']);
        $lowercase = preg_match('@[a-z]@', $_POST['wachtwoord']);
        $number    = preg_match('@[0-9]@', $_POST['wachtwoord']);

        // Check of het wachtwoord minimaal 8 tekens bevat
        if (!$uppercase || !$lowercase || !$number || (strlen($_POST['wachtwoord']) < 8)) {
            $passwordIsWeak = true;
        } else {
            $passwordIsStrong = true;
        }
        // Check of de ingevule wachtwoorden overeenkomen
        if ($password == $passwordConfirmation) {
            $passwordCheck = true;
        } else {
            // Klopt het wachtwoord niet geef dan een alert weer en stuur de bezoeker terug naar het aanmeldscherm
            $passwordDoesNotMatch = true;
        }

        // Check of de ingevulde wachtwooden aan de eisen voldoen en overeenkomen
        // Zijn ze hetzelfde, dan kan je doorgaan
        if ($password == $passwordConfirmation && $passwordIsStrong) {
            // Het wachtwoord wordt gehasht dmv md5 hash zodat het niet uit de database kan worden gelezen
            $password = password_hash($password, PASSWORD_DEFAULT);

            // Alles wat nu niet zou kunnen kloppen is afgehandeld
            // Vul alle gegevens in in de database
            // Is er geen tussenvoegsel ingevuld wordt dit automatisch 'NULL'
            $Query = "
            INSERT INTO account (Email, Firstname, Infix, Surname, Gender, Street, StreetNumber, PostalCode, City, Password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $Statement = mysqli_prepare($Connection, $Query);
            mysqli_stmt_bind_param($Statement, "ssssssssss", $email, $firstName, $insertion, $lastName, $gender, $address, $houseNumber, $postalCode, $city, $password);
            mysqli_stmt_execute($Statement);
            header("Location:./login?aanmeldenKlaar");
        }
    } else {
        // Staat het emailadres al in de database geef dan een alert weer en stuur de bezoeker terug naar het aanmeldscherm
        $mailAlreadyExists = true;
    }
} ?>

<!-- Laat het aanmeldpaginascherm zien als deze optie is gekozen -->
<div class="tekstBoven">
    <h1>Aanmelden</h1>
</div>
<div class="container signUpPageContainer">
    <form method="POST">
        <div class="row tekstInContainer">
            <div class="col-12">
                <h2>Voert u alstublieft deze gegevens in om u aan te melden</h2>
            </div>
        </div>
        <div class="row loginSignupRows">
            <div class="col-1"></div>
            <div class="col-4">
                <input type="text" name="voornaam" value="<?php print("$firstName"); ?>" placeholder="Voornaam" required>
            </div>
            <div class="col-2">
                <input type="text" name="tussenvoegsel" value="<?php print("$insertion"); ?>" placeholder="Tussenvoegsel">
            </div>
            <div class="col-4">
                <input type="text" name="achternaam" value="<?php print("$lastName"); ?>" placeholder="Achternaam" required>
            </div>
            <div class="col-1"></div>
        </div>

        <?php
        if ($mailAlreadyExists) {
            ?>
            <div class="row loginSignupRows">
                <div class="col-1"></div>
                <div class="col-10">
                    <?php if ($mailAlreadyExists) { ?>
                        <label for="email" class="signupWarningLabel">Dit e-mailadres hoort al bij een account!</label>
                    <?php } ?>
                </div>
                <div class="col-1"></div>
            </div>
        <?php } ?>

        <div class="row loginSignupRows">
            <div class="col-1"></div>
            <div class="col-8">
                <input type="email" name="email" value="<?php print("$email"); ?>" placeholder="E-mailadres" required>
            </div>
            <div class="col-2">
                <select name="gender" required>
                    <option value="">--Selecteer--</option>
                    <option value="1">Man</option>
                    <option value="2">Vrouw</option>
                    <option value="3">n.v.t.</option>
                </select>
            </div>
            <div class="col-1"></div>
        </div>

        <div class="row loginSignupRows">
            <div class="col-1"></div>
            <div class="col-8">
                <input type="text" name="straat" value="<?php print("$address"); ?>" placeholder="Straat" required>
            </div>
            <div class="col-2">
                <input type="text" name="huisnummer" value="<?php print("$houseNumber"); ?>" placeholder="Huisnummer" required>
            </div>
            <div class="col-1"></div>
        </div>

        <div class="row loginSignupRows">
            <div class="col-1"></div>
            <div class="col-2">
                <input type="text" name="postcode" value="<?php print("$postalCode"); ?>" placeholder="Postcode" required>
            </div>
            <div class="col-8">
                <input type="text" name="plaats" value="<?php print("$city"); ?>" placeholder="Plaats" required>
            </div>
            <div class="col-1"></div>
        </div>

        <?php
        if ($passwordDoesNotMatch) {
            ?>

            <div class="row loginSignupRows">
                <div class="col-1"></div>
                <div class="col-10">

                    <label for="wachtwoord" class="signupWarningLabel">De wachtwoorden komen niet overeen!</label>
                </div>
                <div class="col-1"></div>
            </div>
            <?php
        }
        if ($passwordIsWeak) {
            ?>
            <div class="row loginSignupRows">
                <div class="col-1"></div>
                <div class="col-10">
                    <label for="wachtwoord" class="signupWarningLabel">Het gekozen wachtwoord voldoet niet aan de eisen!</label>
                </div>
                <div class="col-1"></div>
            </div>
        <?php } ?>

        <div class="row loginSignupRows">
            <div class="col-1"></div>
            <div class="col-5">
                <input type="password" name="wachtwoord" placeholder="Wachtwoord" required>
            </div>
            <div class="col-5">
                <input type="password" name="wachtwoordConfirmatie" placeholder="Wachtwoord (herhalen)" required>
            </div>
            <div class="col-1"></div>
        </div>

        <div class='row loginSignupRows'>
            <div class="col-1"></div>
            <div class="col-11">
                <label for="legenda" class="loginSignupRows"> Het wachtwoord moet minimaal 8 tekens bevatten.
                    <br> Het wachtwoord moet minimaal 1 hoofdletter, 1 kleine letter en 1 cijfer bevatten. </label>
            </div>
        </div>

        <div class="row">
            <div class="col-1"></div>
            <div class="col-4">
                <a href="./loginMainpage" class="">
                    <div class="backToLoginChoice button buttonRed">
                        Terug
                    </div>
                </a>
            </div>
            <div class="col-2"></div>
            <div class="col-4">
                <input type="submit" name="aanmeldenKlaar" value="Aanmelden" class="loginSignupDone button buttonGreen">
            </div>
            <div class="col-1"></div>
    </form>
</div>
</body>

</html>