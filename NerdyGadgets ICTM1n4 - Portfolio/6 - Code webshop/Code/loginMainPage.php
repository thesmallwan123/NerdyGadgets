<?php
include "connect.php";
session_start();
?>

<!DOCTYPE html>

<!-- style bijvoegen -->
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
// uitlogknop
if (isset($_POST['uitloggen'])) {
    unset($_SESSION['account']);
}
?>

<!-- aangeven of de klant is ingelogd of nog in wilt loggen of aanmelden -->
<div class="container loginMainPageContainer">
    <div class="row vraag">
        <div class="col-12">
            <?php 
            if (!isset($_SESSION['account'])) { 
                ?>
                <h1>Wilt u aanmelden of inloggen?</h1>
                <?php
            } else {
                ?>
                <h1>U bent al ingelogd, wilt u uitloggen?</h1>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="row">
        <?php 
        if (!isset($_SESSION['account'])) { 
            ?>
            <div class="col-2"></div>
            <div class="col-3">
                <form method="POST" action="./signup">
                    <input type="submit" name="aanmelden" value="Aanmelden" class="mainLoginButtons">
                </form>
            </div>
            <div class="col-2"></div>
            <div class="col-3">
                <form method="POST" action="./login">
                    <input type="submit" name="inloggen" value="Inloggen" class="mainLoginButtons">
                </form>
            </div>
            <div class="col-2"></div>
        <?php
        } else {
            ?>
            <div class="col-4"></div>
            <div class="col-3">
                <form method="POST">
                    <input type="submit" name="uitloggen" value="Uitloggen" class="logoutButton">
                </form>
            </div>
            <div class="col-4"></div>
            <?php
        }
        ?>
    </div>
    <div class="row">
        <div class="col-4"></div>
        <div class="col-3">
            <form method="POST" action=".\">
                <input type="submit" name="annuleren" value="Annuleren" class="cancelMainLogin button buttonRed">
            </form>
        </div>
        <div class="col-5"></div>
    </div>
</div>