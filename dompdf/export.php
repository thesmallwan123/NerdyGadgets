<!---PDF EXPORT--->
<?php
//use Dompdf\Dompdf;
//if (isset($_POST['download']) == 'Download'){
//    $maand = $_POST['Maand'];
//// Include autoloader
//    require_once 'dompdf/autoload.inc.php';
//    require_once 'dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
//    require_once 'dompdf/lib/php-svg-lib/src/autoload.php';
//    require_once 'dompdf/lib/html5lib/Parser.php';



////initialize dompdf class
//    $document = new Dompdf();
//    $output = "
//        <table>
//            <tr>
//                <th>id</th>
//                <th>ip</th>
//                <th>page</th>
//                <th>browser</th>
//                <th>dag</th>
//                <th>maand</th>
//                <th>jaar</th>
//                <th>device</th>
//            </tr>
//    ";
//    require('./components/dbconn.php');
//    $sql = "SELECT * FROM pdf WHERE maand='$maand'";
//    foreach($conn->query($sql, PDO::FETCH_ASSOC) as $row){
//        $id = $row["id"];
//        $ip = $row["ip"];
//        $page = $row["page"];
//        $browser = $row["browser"];
//        $dag = $row["dag"];
//        $maand = $row["maand"];
//        $jaar = $row["jaar"];
//        $deskmob = $row["deskmob"];
//
//        $output .= "
//              <tr>
//                  <td>".$id."</td>
//                  <td>".$ip."</td>
//                  <td>".$page."</td>
//                  <td>".$browser."</td>
//                  <td>".$dag."</td>
//                  <td>".$maand."</td>
//                  <td>".$jaar."</td>
//                  <td>".$deskmob."</td>
//              </tr>
//        ";
//        echo "HI";
//    }
//    $output .= "</table>";
//
//    //echo $output;
//    $document->loadHtml("Hi");
//    var_dump($document);
//
//    //set page size and orientation
//    $document->setPaper('A4', 'Landscape');
//
//    //Render the HTML as PDF
//    $document->render();
//
//    //Get output of generated pdf in Browser
//    //1 = Download
//    //0 = Preview
//    $document->stream("esdrftghj", array("Attachment"=>1));
//}
?>