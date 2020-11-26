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

<div class="container loginMainPageContainer">
    <div class="row vraag">
        <div class="col-12">
            <h1>Wilt u aanmelden of inloggen?</h1>
        </div>
    </div>
    <div class="row">
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