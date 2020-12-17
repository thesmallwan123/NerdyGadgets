<?php
include __DIR__ . "/header.php";

if (isset($_SESSION['account'])) {
    $account = $_SESSION["account"];

    $Query = "
    SELECT firstname
    FROM account
    WHERE email = ?";
    $Statement = mysqli_prepare($Connection2, $Query);
    mysqli_stmt_bind_param($Statement, "s", $account);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    $voornaam = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0]['firstname'];

    ?>
    <h1 class="welkomstBericht">Welkom <?php print($voornaam); ?></h1>
    <?php
}
?>

<div class="IndexStyle">
    <div class="TextPrice">
        <a href="view.php?id=93">
            <div class="TextMain">"The Gu" red shirt XML tag t-shirt (Black) M</div>
            <ul id="ul-class-price">
                <li class="HomePagePrice">€30.95</li>
            </ul>

    </div>
    </a>
    <div class="HomePageStockItemPicture"></div>
</div>
<?php
include __DIR__ . "/footer.php";
?>

