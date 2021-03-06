<!---PDF EXPORT--->
<?php

include_once('connect.php');
session_start();

// bijvoegen autoloader
require_once 'dompdf/autoload.inc.php';
require_once 'dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once 'dompdf/lib/php-svg-lib/src/autoload.php';
require_once 'dompdf/lib/html5lib/Parser.php';

use Dompdf\Dompdf;

// Klantgegevens
$firstName = $_SESSION["paymentInfo"][4];
$insertion = $_SESSION["paymentInfo"][5];
$lastName = $_SESSION["paymentInfo"][6];
$gender = $_SESSION["paymentInfo"][7];
$email = $_SESSION["paymentInfo"][8];

$Dompdf = new Dompdf();

// Session veranderen in variables

$date = date("Y-m-d");
$fileLocation = "./factuur/" . $date . "-1.pdf";
$totalprice = $_SESSION["totaalPrijs"];
$cart = $_SESSION["cart"];


//initialiseren dompdf class
$output = "
    <!doctype html>
    <html>
        <body>
            <div class='invoice-box'>
                <div class='top'>
                    <div class='logo'>
                        <img src='Public/ProductIMGHighRes/NerdyGadgetsLogo.png' style='width:250px;'>
                    </div>                   
                    
                    <div class='infoTop information box'>
                        <div class='nerdyGadgetsGegevens'>
                            <b>Informatie Nerdygadgets</b> <br>
                            NerdyGadgets B.V.<br>
                            customerservice.nerdygadgets@gmail.com <br>
                            
                            <br>
                            <b>Adresgegevens Nerdygadgets:</b> <br>
                            Hogeschool Windesheim<br>
                            Campus 2<br>
                            Zwolle, 8017 CA
                            </div>
                        </div>
                    </div>
                    <div class='infoBottom information box'>
                        <b>Klantgegevens:</b><br>
                        Naam: " . $firstName." ";
                        if(isset($insertion)) {
                            $output.= $insertion." ";
                        }
                        $output .= $lastName . "
                        
                        <br>
                        Email: " . $email . "
                        </div>
                    </div>    
                </div>

                <table class='betalingTable'>
                    <tr class='heading'>
                        <td>Payment Method</td>
                        <td>Check #</td>
                    </tr>

                    <tr class='details'>
                        <td>IDEAL</td>
                        <td>" . $totalprice . "</td>
                    </tr>
                </table>
                <table class='factuurTable'>
                    <tr class='heading'>
                        <td>Item</td>
                        <td>Amount</td>
                        <td>Price</td>
                    </tr>";
                            foreach ($cart as $productID => $amount) {
                            $Query = '
                                SELECT si.StockItemID, StockItemName, ROUND((RecommendedRetailPrice*(1+(TaxRate/100))), 2) AS RecommendedRetailPrice
                                FROM stockitems si
                                LEFT JOIN stockitemimages sii ON si.StockItemID = sii.StockItemID
                                INNER JOIN stockitemholdings sih ON si.StockItemID = sih.StockItemID
                                WHERE si.StockItemID = ?';

                            $Statement = mysqli_prepare($Connection, $Query);
                            mysqli_stmt_bind_param($Statement, 'i', $productID); // i = integer; s = string;
                            mysqli_stmt_execute($Statement);

                            $result = mysqli_stmt_get_result($Statement);
                            $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
                            $output .= "
                    <tr class='item'>
                        <td>
                            " . $result[0]['StockItemName'] . "
                        </td>
                        <td>
                            " . $amount . "
                        </td>
                        <td>
                            " . $result[0]['RecommendedRetailPrice'] . "
                        </td>
                    </tr>";
                        }
                    $output .= "
                    <tr class='total'>
                        <td></td>
                        <td></td>
                        <td class='totalTD'>
                            Total: " . $totalprice . "
                        </td>
                    </tr>
                </table>
            </div>
        </body>
    </html>";

$output .= "<link type = 'text/css' href = './Public/CSS/dompdf.css'>";

$Dompdf->loadHtml($output);

// afmetingen van de pagina
$Dompdf->setPaper('A4', 'Portrait');

//HTML renderen als PDF
$Dompdf->render();

// Output instellen
$pdf = $Dompdf->output();

// Sla de file op
$myFile = fopen("./factuur/" . $date . "-1.pdf", "w");
fwrite($myFile, $pdf);

// Verstuur mail
include("./sendMail.php");
if (verstuurFactuur($firstName, $insertion, $lastName, $email, $fileLocation) == TRUE && verstuurFactuurNerdy($firstName, $insertion, $lastName, $email, $fileLocation) == TRUE) {
    // Verwijder file
    fclose($myFile);
    unlink($fileLocation);
    header("Location: ./processingOrder.php");
}

?>