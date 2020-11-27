<?php
if (!isset($_SESSION)) {
    session_start();
}
include "connect.php";
?>
<!DOCTYPE html>
<html lang="en" style="background-color: rgb(35, 35, 47);">

<head>
    <script src="Public/JS/fontawesome.js" crossorigin="anonymous"></script>
    <script src="Public/JS/jquery.min.js"></script>
    <script src="Public/JS/bootstrap.min.js"></script>
    <script src="Public/JS/popper.min.js"></script>
    <script src="Public/JS/Resizer.js"></script>
    <script src="Public/JS/jquery-3.4.1.js"></script>
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
    <link rel="apple-touch-icon" sizes="57x57" href="Public/Favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="Public/Favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="Public/Favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="Public/Favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="Public/Favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="Public/Favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="Public/Favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="Public/Favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="Public/Favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="Public/Favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Public/Favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="Public/Favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Public/Favicon/favicon-16x16.png">
    <link rel="manifest" href="Public/Favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="Public/Favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

</head>

<body>
    <div class="Background">
        <!-- <div class="row" id="Header">
            <div class="col-2"><a href="./" id="LogoA">
                    <div id="LogoImage"></div>
                </a></div>
            <div class="col-8" id="CategoriesBar">
                <ul id="ul-class">
                    <?php
                    $Query = "
                    SELECT StockGroupID, StockGroupName, ImagePath
                    FROM stockgroups 
                    WHERE StockGroupID IN (
                                            SELECT StockGroupID 
                                            FROM stockitemstockgroups
                                            ) AND ImagePath IS NOT NULL
                    ORDER BY StockGroupID ASC";
                    $Statement = mysqli_prepare($Connection, $Query);
                    mysqli_stmt_execute($Statement);
                    $HeaderStockGroups = mysqli_stmt_get_result($Statement);

                    foreach ($HeaderStockGroups as $HeaderStockGroup) {
                    ?>
                        <li>
                            <a href="browse.php?category_id=<?php print $HeaderStockGroup['StockGroupID']; ?>"
                            class="HrefDecoration"><?php print $HeaderStockGroup['StockGroupName']; ?></a>
                        </li>
                        <?php
                    }
                        ?>
                    <li>
                        <a href="categories.php" class="HrefDecoration">Alle categorieën</a>
                    </li>
                </ul>
            </div>
            <ul id="ul-class-navigation">
                <li>
                    <a href="cart.php" class="HrefDecoration"><img src="Public/Img/cart.png" alt="Winkelwagen" width="44" heigth="44"></a>
                </li>
                <li>
                    <a href="browse.php" class="HrefDecoration"><i class="fas fa-search" style="color:#676EFF;"></i> Zoeken</a>
                </li>
            </ul>
        </div> -->
        <div class="navbar navbar-dark navbar-expand-lg white">

            <!-- collapse button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- collapsible content -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-brand mr-auto">
                    <a href="./" class="navbar-brand">
                        <div id="LogoImage"></div>
                    </a>
                </ul>
                <ul class="navbar-nav mr-auto">
                    <?php
                    $Query = "
                            SELECT StockGroupID, StockGroupName, ImagePath
                            FROM stockgroups 
                            WHERE StockGroupID IN (
                                SELECT StockGroupID 
                                FROM stockitemstockgroups
                                ) AND ImagePath IS NOT NULL
                            ORDER BY StockGroupID ASC";
                    $Statement = mysqli_prepare($Connection, $Query);
                    mysqli_stmt_execute($Statement);
                    $HeaderStockGroups = mysqli_stmt_get_result($Statement);

                    foreach ($HeaderStockGroups as $HeaderStockGroup) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link HrefDecoration" href="browse.php?category_id=<?php print $HeaderStockGroup['StockGroupID']; ?>"><?php print $HeaderStockGroup['StockGroupName']; ?></a>
                        </li>
                    <?php
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link HrefDecoration" href="categories.php">Alle Categorien</a>
                    </li>
                </ul>
                <ul class="navbar-nav mr-sm-2">
                    <li class="nav-item">
                        <a href="./browse" class="nav-link HrefDecoration"><i class="fas fa-search" style="color:#676EFF;"></i> Zoeken</a>
                    </li>
                    <li class="nav-item">
                        <a href="./cart" class="nav-link HrefDecoration"><i class="fas fa-shopping-cart" style="color:white;"></i></a>
                    </li>
                    <li class="nav-item">
                        <a href="./loginMainPage" class="nav-link HrefDecoration"><i class="fas fa-user" style="color:white;"></i></a>
                    </li>
                </ul>
            </div>

        </div>
        <div id="Content">
            <div id="SubContent">