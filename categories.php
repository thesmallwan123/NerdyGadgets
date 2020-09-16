<?php

include __DIR__ . "/header.php";

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
$Result = mysqli_stmt_get_result($Statement);
$StockGroups = mysqli_fetch_all($Result, MYSQLI_ASSOC);

?>
<div id="Wrap">
    <?php if (isset($StockGroups)) {
        $i = 0;
        foreach ($StockGroups as $StockGroup) {
            if ($i < 6) {
                ?>
                <a href="<?php print "browse.php?category_id=";
                print $StockGroup["StockGroupID"]; ?>">
                    <div id="StockGroup<?php print $i + 1; ?>"
                         style="background-image: url('Public/StockGroupIMG/<?php print $StockGroup["ImagePath"]; ?>')"
                         class="StockGroups">
                        <h1><?php print $StockGroup["StockGroupName"]; ?></h1>
                    </div>
                </a>
                <?php
            }
            $i++;
        }
    } ?>
</div>