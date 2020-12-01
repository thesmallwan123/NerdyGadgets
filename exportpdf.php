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

// Klantgegevens
$voornaam = $_SESSION["paymentInfo"][4];
$tussenvoegsel = $_SESSION["tussenvoegsel"][5];
$achternaam = $_SESSION["paymentInfo"][6];
$geslacht = $_SESSION["paymentInfo"][7];
$email = $_SESSION["paymentInfo"][8];

$Dompdf = new Dompdf();

// Session veranderen in variables

$date = date("Y-m-d");
$fileLocation = "./factuur/" . $date . "-1.pdf";
$totalprice = $_SESSION["totaalPrijs"];
$cart = $_SESSION["cart"];


//initialize dompdf class
$output = "
    <!doctype html>
    <html>
        <body>
            <div class='invoice-box'>
            
                <div class='top'>
                    <div class='title'>
                        <img src='Public/ProductIMGHighRes/NerdyGadgetsLogo.png' style='width:250px;'>
                    </div>
                </div>

                <div class='information'>
                    <div class ='factuurgegevens'>";
                        $output .='Factuur #: 123'; $output .="<br>";
                        $output .='Created: ' . $date; $output .= "<br>";

                        $output .="
                    </div>
                                
                    <div>
                        Hogeschool Windesheim<br>
                        Campus 2<br>
                        Zwolle, 8017 CA
                    </div>

                    <div class='information2'>
                    
                        NerdyGadgets B.V.<br>
                        Klantenservice<br>
                        customerservice.nerdygadgets@gmail.com
                    </div>
                </div>
                <table>
                    <tr class='heading'>
                        <td>Payment Method</td>
                        <td></td>
                        <td>Check #</td>
                    </tr>

                    <tr class='details'>
                        <td>IDEAL</td>
                        <td></td>
                        <td>";
                            $output .=$totalprice;
                            $output .= "
                        </td>
                    </tr>

                    <tr class='heading'>
                        <td>Item</td>
                        <td>Amount</td>
                        <td>Price</td>
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
                                $output .=$amount;
                                $output .= "
                            </td>

                            <td>";
                                $output .=$result[0]['RecommendedRetailPrice'];
                                $output .= "
                            </td>
                        </tr>";
                    }

                    $output .= "
                    <tr class='total'>
                        <td></td>
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

// Set output
$pdf = $Dompdf->output();

// Sla de file op
$myFile = fopen("./factuur/" . $date . "-1.pdf", "w");
fwrite($myFile, $pdf);

// Verstuur mail
include("./sendMail.php");
if (verstuurFactuur($voornaam, $achternaam, $email, $fileLocation) == TRUE) {
    // Verwijder file
    fclose($myFile);
    unlink($fileLocation);
    header("Location: ./confirmation.php");
}

?>