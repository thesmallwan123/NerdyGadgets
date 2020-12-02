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
    $voornaam = "";
    $tussenvoegsel = "";
    $achternaam = "";
    $email = "";
    $straat = "";
    $huisnummer = "";
    $postcode = "";
    $plaats = "";
    $mailBestaatAl = false;
    $wachtwoordKloptNiet = false;

// Als het aanmelden klaar is check of alles klopt en stuur ze daarna naar de inlogpagina
if (isset($_POST['aanmeldenKlaar'])) {
    
    // Maak variabelen van de gegevens van het aanmeldformulier (voor het gemak)
    $voornaam = $_POST['voornaam'];
    $tussenvoegsel = $_POST['tussenvoegsel'];
    $achternaam = $_POST['achternaam'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $straat = $_POST['straat'];
    $huisnummer = $_POST['huisnummer'];
    $postcode = $_POST['postcode'];
    $plaats = $_POST['plaats'];
    $wachtwoord = $_POST['wachtwoord'];
    $wachtwoordConfirmatie = $_POST['wachtwoordConfirmatie'];

    // Check of het ingevulde e-mailadres niet al in de database staat
    // Eerst vraag je aan de database om het ingevulde emailadres
    // Bestaat dit niet geeft hij 0 rows terug en kan je doorgaan.
    $Query = "
    SELECT email
    FROM account
    WHERE email = ?";
    $Statement = mysqli_prepare($Connection2, $Query);
    mysqli_stmt_bind_param($Statement, "s", $email);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);

    if (mysqli_num_rows($ReturnableResult) == 0) {
        
        // Check of de ingevulde wachtwooden overeenkomen
        // Zijn ze hetzelfde, dan kan je doorgaan
        if ($wachtwoord == $wachtwoordConfirmatie) {
            // Het wachtwoord wordt gehasht dmv md5 hash zodat het niet uit de database kan worden gelezen
            $wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);

            // Alles wat nu niet zou kunnen kloppen is afgehandeld
            // Vul alle gegevens in in de database
            // Is er geen tussenvoegsel ingevuld wordt dit automatisch 'NULL'
            $Query = "
            INSERT INTO account (email, firstname, infix, surname, gender, street, streetnr, postalcode, city, password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $Statement = mysqli_prepare($Connection2, $Query);
            mysqli_stmt_bind_param($Statement, "ssssssssss", $email, $voornaam, $tussenvoegsel, $achternaam, $gender, $straat, $huisnummer, $postcode, $plaats, $wachtwoord);
            mysqli_stmt_execute($Statement);
            header("Location:./login?aanmeldenKlaar");
        } else { // Klopt het wachtwoord niet geef dan een alert weer en stuur de bezoeker terug naar het aanmeldscherm

           $wachtwoordKloptNiet = true;

        }
    } else { // Staat het emailadres al in de database geef dan een alert weer en stuur de bezoeker terug naar het aanmeldscherm

        $mailBestaatAl = true;

    }
}
?>

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
                    <input type="text" name="voornaam" value="<?php print("$voornaam"); ?>" placeholder="Voornaam" required>
                </div>
                <div class="col-2">
                    <input type="text" name="tussenvoegsel" value="<?php print("$tussenvoegsel"); ?>" placeholder="Tussenvoegsel">
                </div>
                <div class="col-4">
                    <input type="text" name="achternaam" value="<?php print("$achternaam"); ?>" placeholder="Achternaam" required>
                </div>
                <div class="col-1"></div>
            </div>
            
            <?php
            if ($mailBestaatAl) {
            ?>

            <div class="row loginSignupRows">
                <div class="col-1"></div>
                <div class="col-10">
                        <?php 
                    if ($mailBestaatAl) { 
                        ?> 
                        <label for="email" class="signupWarningLabel">Dit e-mailadres hoort al bij een account!</label>
                        <?php 
                    } 
                        ?>
                </div>
                <div class="col-1"></div>
            </div>

            <?php
            }
            ?>

            <div class="row loginSignupRows">
                <div class="col-1"></div>
                <div class="col-8">
                    <input type="email" name="email" value="<?php print("$email"); ?>" placeholder="E-mailadres" required>
                </div>
                <div class="col-2">
                    <select name="gender" required>
                        <option value="">--Selecteer--</option>
                        <option value="man">Man</option>
                        <option value="vrouw">Vrouw</option>
                        <option value="nvt">n.v.t.</option>
                    </select>
                </div>
                <div class="col-1"></div>
            </div>

            <div class="row loginSignupRows">
                <div class="col-1"></div>
                <div class="col-8">
                    <input type="text" name="straat" value="<?php print("$straat"); ?>" placeholder="Straat" required>
                </div>
                <div class="col-2">
                    <input type="text" name="huisnummer" value="<?php print("$huisnummer"); ?>" placeholder="Huisnummer" required>
                </div>
                <div class="col-1"></div>
            </div>

            <div class="row loginSignupRows">
                <div class="col-1"></div>
                <div class="col-2">
                    <input type="text" name="postcode" value="<?php print("$postcode"); ?>" placeholder="Postcode" required>
                </div>
                <div class="col-8">
                    <input type="text" name="plaats" value="<?php print("$plaats"); ?>" placeholder="Plaats" required>
                </div>
                <div class="col-1"></div>
            </div>

            <?php
            if ($wachtwoordKloptNiet) {
            ?>

            <div class="row loginSignupRows">
                <div class="col-1"></div>
                <div class="col-10">
                        <?php 
                    if ($wachtwoordKloptNiet) { 
                        ?> 
                        <label for="wachtwoord" class="signupWarningLabel">De wachtwoorden komen niet overeen!</label>
                        <?php 
                    } 
                        ?>
                </div>
                <div class="col-1"></div>
            </div>

            <?php
            }
            ?>

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
            
            <div class="row">
                <div class="col-1"></div>
                <div class="col-4">
                    <a href="./loginMainpage">
                        <div class="backToLoginChoice">
                            Terug
                        </div>
                    </a>
                </div>
                <div class="col-2"></div>
                <div class="col-4">
                    <input type="submit" name="aanmeldenKlaar" value="Aanmelden" class="loginSignupDone">
                </div>
            <div class="col-1"></div>
        </form>
    </div>
</body>
</html>