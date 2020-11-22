<?php
include "connect.php";
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
// Laat het keuzemenu zien als er nog geen keuze is gegeven
if (!isset($_POST['aanmelden']) && !isset($_POST['inloggen']) && !isset($_POST['aanmeldenKlaar'])) {
    ?>

    <div class="container loginMainPageContainer">
        <div class="row vraag">
            <div class="col-12">
                <h1>Wilt u aanmelden of inloggen?</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-2"></div>
            <div class="col-3">
                <form method="POST">
                    <input type="submit" name="aanmelden" value="Aanmelden" class="mainLoginButtons">
                </form>
            </div>
            <div class="col-2"></div>
            <div class="col-3">
                <form method="POST">
                    <input type="submit" name="inloggen" value="Inloggen" class="mainLoginButtons">
                </form>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row">
            <div class="col-4"></div>
            <div class="col-3">
                <form method="POST" action=".\">
                    <input type="submit" name="annuleren" value="Annuleren" class="cancelMainLogin">
                </form>
            </div>
            <div class="col-5"></div>
        </div>
    </div>

<?php
}

// Laat het aanmeldpaginascherm zien als deze optie is gekozen (of als de gegevens niet kloppen)
if (isset($_POST['aanmelden'])) {
    ?>
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
                    <input type="text" name="voornaam" placeholder="Voornaam" required>
                </div>
                <div class="col-2">
                    <input type="text" name="tussenvoegsel" placeholder="Tussenvoegsel">
                </div>
                <div class="col-4">
                    <input type="text" name="achternaam" placeholder="Achternaam" required>
                </div>
                <div class="col-1"></div>
            </div>
            
            <div class="row loginSignupRows">
                <div class="col-1"></div>
                <div class="col-10">
                    <input type="email" name="email" placeholder="E-mailadres" required>
                </div>
                <div class="col-1"></div>
            </div>

            <div class="row loginSignupRows">
                <div class="col-1"></div>
                <div class="col-8">
                    <input type="text" name="straat" placeholder="Straat" required>
                </div>
                <div class="col-2">
                    <input type="text" name="huisnummer" placeholder="Huisnummer" required>
                </div>
                <div class="col-1"></div>
            </div>

            <div class="row loginSignupRows">
                <div class="col-1"></div>
                <div class="col-2">
                    <input type="text" name="postcode" placeholder="Postcode" required>
                </div>
                <div class="col-8">
                    <input type="text" name="plaats" placeholder="Plaats" required>
                </div>
                <div class="col-1"></div>
            </div>

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
                    <a href="login.php">
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
    <?php
    
}

// Laat het inlogscherm zien nadat deze optie is gekozen of als het aanmelden is gelukt
if (isset($_POST['inloggen']) || isset($_POST['aanmeldenKlaar'])) {
    
    // Verwerk de ingegvulde gegevens in de database als het aanmelden is gelukt. Sla dit over als de gebruiker direct wilt inloggen
    if (isset($_POST['aanmeldenKlaar'])) {
        
        // Maak variabelen van de gegevens van het aanmeldformulier (voor het gemak)
        $voornaam = $_POST['voornaam'];
        $tussenvoegsel = $_POST['tussenvoegsel'];
        $achternaam = $_POST['achternaam'];
        $email = $_POST['email'];
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
        SELECT emailadres
        FROM klant
        WHERE emailadres = ?";
        $Statement = mysqli_prepare($Connection2, $Query);
        mysqli_stmt_bind_param($Statement, "s", $email);
        mysqli_stmt_execute($Statement);
        $ReturnableResult = mysqli_stmt_get_result($Statement);

        if (mysqli_num_rows($ReturnableResult) == 0) {
            
            // Check of de ingevulde wachtwooden overeenkomen
            // Zijn ze hetzelfde, dan kan je doorgaan
            if ($wachtwoord == $wachtwoordConfirmatie) {

                // Alles wat nu niet zou kunnen kloppen is afgehandeld
                // Vul alle gegevens in in de database
                // Is er geen tussenvoegsel ingevuld wordt dit automatisch 'NULL'
                $Query = "
                INSERT INTO klant (emailadres, voornaam, tussenvoegsel, achternaam, straat, huisnummer, postcode, plaats, wachtwoord)
                VALUES (\"$email\", \"$voornaam\", \"$tussenvoegsel\", \"$achternaam\", \"$straat\", \"$huisnummer\", \"$postcode\", \"$plaats\", \"$wachtwoord\")";
                $Statement = mysqli_prepare($Connection2, $Query);
                mysqli_stmt_execute($Statement);

            } else { // Klopt het wachtwoord niet geef dan een alert weer en stuur de bezoeker terug naar het aanmeldscherm

                ?>
                <script>alert("De wachtwoorden komen niet overeen")</script>
                <?php

            }
        } else { // Staat het emailadres al in de database geef dan een alert weer maar laat de bezoeker op het inlogscherm

            ?>
            <script>alert("Dit e-mailadres hoort al bij een account")</script>
            <?php
            
        }
    }

    // Als alles van het aanmelden is afgehandeld of de bezoeker direct wilt inloggen ga je hier verder
    ?>
    <div class="tekstBoven">
        <h1>Inloggen</h1>
    </div>

    <div class="container loginPageContainer">
        <form method="POST">
            <div class="row tekstInContainer">
                <div class="col-12">
                    <h2>Voert u alstublieft deze gegevens in om in te loggen</h2>
                </div>
            </div>

            <div class="row loginSignupRows">
                <div class="col-1"></div>
                <div class="col-10">
                    <input type="email" name="email" placeholder="E-mailadres" required>
                </div>
                <div class="col-1"></div>
            </div>

            <div class="row loginSignupRows">
                <div class="col-1"></div>
                <div class="col-10">
                    <input type="password" name="wachtwoord" placeholder="Wachtwoord" required>
                </div>
                <div class="col-1"></div>
            </div>
            
            <div class="row">
                <div class="col-1"></div>
                <div class="col-4">
                    <a href="login.php">
                        <div class="backToLoginChoice">
                            Terug
                        </div>
                    </a>
                </div>
                <div class="col-2"></div>
                <div class="col-4">
                    <input type="submit" name="inloggenKlaar" value="Inloggen" class="loginSignupDone">
                </div>
            <div class="col-1"></div>
        </form>
    </div>
    <?php
}
?>

</body>
</html>