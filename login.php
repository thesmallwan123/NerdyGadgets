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

        // Laat het aanmeldpaginascherm zien als deze optie is gekozen
        if (isset($_POST['aanmelden']) || !$gegevensKloppen) {
        $gegevensKloppen = true;      
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
        
        // Laat het inlogscherm zien nadat deze optie is gekozen
        if (isset($_POST['inloggen']) || isset($_POST['aanmeldenKlaar']) && $gegevensKloppen) {   
            
            // Als er op de aanmeldknop is geklikt
            if (isset($_POST['aanmeldenKlaar'])) {

                //definieëer variabelen
                $voornaam = strval($_POST['voornaam']);
                $tussenvoegsel = strval($_POST['tussenvoegsel']);
                $achternaam = strval($_POST['achternaam']);
                $email = strval($_POST['email']);
                $straat = strval($_POST['straat']);
                $huisnummer = strval($_POST['huisnummer']);
                $postcode = strval($_POST['postcode']);
                $plaats = strval($_POST['plaats']);
                $wachtwoord = strval($_POST['wachtwoord']);
                $wachtwoordConfirmatie = strval($_POST['wachtwoordConfirmatie']);

                // Kijk eerst of alle gegevens wel kloppen. Zo niet stuur ze dan terug naar de aanmeldpagina.
                // Kijk of het emailadres al in de database bestaat
                $Query = "
                SELECT emailadres
                FROM klant
                WHERE emailadres = ?";
                $Statement = mysqli_prepare($Connection2, $Query);
                mysqli_stmt_bind_param($Statement, "s", $email);
                mysqli_stmt_execute($Statement);
                $ReturnableResult = mysqli_stmt_get_result($Statement);
                // Als er rows terugkomen (emailadres bestaat al)
                if (mysqli_num_rows($ReturnableResult) != 0) {
                    $gegevensKloppen = false;
                    ?>
                    <script>alert("Dit e-mailadres hoort al bij een account")</script>
                    <?php
                // Wachtwoorden komen niet overeen
                } else if ($wachtwoord != $wachtwoordConfirmatie) {
                    $gegevensKloppen = false
                    ?>
                        <script>alert("De wachtwoorden komen niet overeen")</script>
                    <?php
                // Alles klopt
                } else {
                    
                    // Maak een hash van het wachtwoord zodat je deze niet in de database kan zien
                    $wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);
                    //Kijk of er een tussenvoegsel in is gezet
                    // de \" moet zodat er geen sql injectie plaatsvind en je gewoon ' kan gebruiken en het de query niet afsluit
                    if ($tussenvoegsel != "") {
                        $Query = "
                        INSERT INTO klant (emailadres, voornaam, tussenvoegsel, achternaam, straat, huisnummer, postcode, plaats, wachtwoord)
                        VALUES (\"$email\", \"$voornaam\", \"$tussenvoegsel\", \"$achternaam\", \"$straat\", \"$huisnummer\", \"$postcode\", \"$plaats\", \"$wachtwoord\")";
                        $Statement = mysqli_prepare($Connection2, $Query);
                        mysqli_stmt_execute($Statement);
                        ?>
                            <script>alert("Uw account is succesvol aangemaakt!")</script>
                        <?php
                    } else {
                        $Query = "
                        INSERT INTO klant (emailadres, voornaam, achternaam, straat, huisnummer, postcode, plaats, wachtwoord)
                        VALUES (\"$email\", \"$voornaam\", \"$achternaam\", \"$straat\", \"$huisnummer\", \"$postcode\", \"$plaats\", \"$wachtwoord\")";
                        $Statement = mysqli_prepare($Connection2, $Query);
                        mysqli_stmt_execute($Statement);
                        ?>
                            <script>alert("Uw account is succesvol aangemaakt!")</script>
                        <?php
                    }
                
                    /*
                    // test met hashed password
                    $Query = "
                    SELECT wachtwoord
                    FROM klant
                    WHERE klantnr = 7";
                    $Statement = mysqli_prepare($Connection2, $Query);
                    mysqli_stmt_execute($Statement);
                    $ReturnableResult = mysqli_stmt_get_result($Statement);
                    $ReturnableResult = mysqli_fetch_all($ReturnableResult);
                    $ReturnableResult = $ReturnableResult[0][0];

                    print("wachtwoord uit database: " . $ReturnableResult . "<br>");

                    if (password_verify($wachtwoord, $ReturnableResult)) {
                        echo "antwoord: hallo";
                    } else {
                        echo "antwoord: doei";
                    }
                    */
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
            }
        }
        ?>
    </body>
</html>