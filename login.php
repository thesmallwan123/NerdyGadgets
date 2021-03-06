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
// Definiëer variabelen van inloggegevens
$mailDoesNotExist = false;
$passwordDoesNotMatch = false;

// Laat het inlogscherm zien nadat deze optie is gekozen of als het aanmelden is gelukt
if (isset($_GET['aanmeldenKlaar']) && !isset($_POST['inloggenKlaar'])) {
    ?>
        <script>alert("Uw account is succesvol aangemaakt!")</script>
    <?php
}

// Controleer of de inloggegevens kloppen. Zo ja maak sessie 'account'
if (isset($_POST['inloggenKlaar'])) {
    // Definiëer variabelen van inloggegevens
    $email = $_POST['email'];
    $password = $_POST['wachtwoord'];

    // Kijk of de email bestaat
    $Query = "
    SELECT Email
    FROM account
    WHERE Email = ?";
    $Statement = mysqli_prepare($Connection, $Query);
    mysqli_stmt_bind_param($Statement, "s", $email);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);

    if (mysqli_num_rows($ReturnableResult) == 1) {
        // Vraag het wachtwoord op uit de database
        $Query = "
        SELECT Password
        FROM account
        WHERE Email = ?";
        $Statement = mysqli_prepare($Connection, $Query);
        mysqli_stmt_bind_param($Statement, "s", $email);
        mysqli_stmt_execute($Statement);
        $ReturnableResult = mysqli_stmt_get_result($Statement);
        $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0]['Password'];

        // Controleer of het wachtwoord overeenkomt met de hash in de database
        if (password_verify($password, $Result)) {
            // Stuur ze terug naar index. Zet ook de e-mail in de sessie. Deze kan je later ophalen
            $_SESSION['account'] = $email;
            header("Location:./index");
        } else {
            $passwordDoesNotMatch = true;
        }
    } else {
        $mailDoesNotExist = true;
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

            <?php
            if ($mailDoesNotExist) {
            ?>

            <div class="row loginSignupRows">
                <div class="col-1"></div>
                <div class="col-10">
                        <label for="email" class="signupWarningLabel">Dit e-mailadres heeft nog geen account bij ons!</label>
                </div>
                <div class="col-1"></div>
            </div>

            <?php
            }
            ?>

            <div class="row loginSignupRows">
                <div class="col-1"></div>
                <div class="col-10">
                    <input type="email" name="email" placeholder="E-mailadres" required>
                </div>
                <div class="col-1"></div>
            </div>

            <?php
            if ($passwordDoesNotMatch) {
            ?>

            <div class="row loginSignupRows">
                <div class="col-1"></div>
                <div class="col-10"> 
                        <label for="email" class="signupWarningLabel">Het wachtwoord hoort niet bij het opgegeven e-mailadres!</label>
                </div>
                <div class="col-1"></div>
            </div>

            <?php
            }
            ?>

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
                    <a href="./loginMainpage" class="">
                        <div class="backToLoginChoice button buttonRed">
                            Terug
                        </div>
                    </a>
                </div>
                <div class="col-2"></div>
                <div class="col-4">
                    <input type="submit" name="inloggenKlaar" value="Inloggen" class="loginSignupDone button buttonGreen">
                </div>
            <div class="col-1"></div>
        </form>
    </div>

</body>
</html>