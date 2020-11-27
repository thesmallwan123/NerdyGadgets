<!---PDF EXPORT--->
<?php

include_once('connect.php');
session_start();

// Include autoloader
require_once 'dompdf/autoload.inc.php';
require_once 'dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once 'dompdf/lib/php-svg-lib/src/autoload.php';
require_once 'dompdf/lib/html5lib/Parser.php';

use Dompdf\Dompdf;

$Dompdf = new Dompdf();

// Session veranderen in variables
//$voornaam = $_SESSION["voornaam"];
//$tussenvoegsel = $_SESSION["tussenvoegsel"];
//$achternaam = $_SESSION["achternaam"];
//$email = $_SESSION["email"];
//$straat = $_SESSION["straat"];
//$huisnummer = $_SESSION["huisnummer"];
//$postcode = $_SESSION["postcode"];
//$woonplaats = $_SESSION["woonplaats"];
$date = date("d/m/Y", time() + 86400);
$totalprice = $_SESSION["totaalPrijs"];
$cart = $_SESSION["cart"];

//initialize dompdf class
$output = "
<!doctype html>
<html>
<body>
<div class='invoice-box'>
    <table>
        <tr class='top'>
            <td>
                <table>
                    <tr>
                        <td class='title'>
                            <img src='Public/ProductIMGHighRes/NerdyGadgetsLogo.png' style='width:250px;'>
                        </td>
                        
                        <td>
                            ";
                        $output .='Factuur #: 123'; $output .="<br>";
                        $output .='Created: ' . $date; $output .= "<br>";

                        $output .="
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class='information'>
            <td>
                <table>
                    <tr>
                        <td>
                            Hogeschool Windesheim<br>
                            Campus 2<br>
                            Zwolle, 8017 CA
                        </td>

                        <td>
                            NerdyGadgets B.V.<br>
                            Klantenservice<br>
                            customerservice.nerdygadgets@windesheim.nl
                        </td>
                    </tr>
                </table>
                </td>
            </tr>

            <tr class='heading'>
                <td>
                Payment Method
                </td>

                <td>
                Check #
                </td>
            </tr>

            <tr class='details'>
                <td>
                IDEAL
                </td>

                <td>
                ";
                $output .=$totalprice;
                $output .= "
                </td>
            </tr>

            <tr class='heading'>
                <td>
                Item
                </td>

                <td>
                Price
                </td>
            </tr>";

        foreach($cart as $artikelID => $amount) {
            $Query = '
            SELECT si.StockItemID, StockItemName, ROUND((RecommendedRetailPrice*(1+(TaxRate/100))), 2) AS RecommendedRetailPrice
            FROM stockitems si
            LEFT JOIN stockitemimages sii ON si.StockItemID = sii.StockItemID
            INNER JOIN stockitemholdings sih ON si.StockItemID = sih.StockItemID
            WHERE si.StockItemID = ?';

            $Statement = mysqli_prepare($Connection, $Query);
            mysqli_stmt_bind_param($Statement, 'i', $artikelID); // i = integer; s = string;
            mysqli_stmt_execute($Statement);

            $result = mysqli_stmt_get_result($Statement);
            $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $output .= "
            <tr class='item'>
                <td>";
                    $output .=$result[0]['StockItemName'];
                    $output .= "
                </td>

                <td>";
                    $output .=$result[0]['RecommendedRetailPrice'];
                    $output .= "
                </td>
            </tr>
        "; }
        $output .= "
        <tr class='total'>
             <td></td>
             <td>
                Total: "; $output.= $totalprice;
                $output .= "
             </td>
          </tr>
        </table>
    </div>
    </body>
    </html>";

$output .= "<link type = 'text/css' href = './Public/CSS/dompdf.css'>";

    $Dompdf->loadHtml($output);

    //set page size and orientation
    $Dompdf->setPaper('A4', 'Portrait');

    //Render the HTML as PDF
    $Dompdf->render();

    //Get output of generated pdf in Browser
    //1 = Download
    //0 = Preview
    $Dompdf->stream("12-2020", array("Attachment"=>1));

?>