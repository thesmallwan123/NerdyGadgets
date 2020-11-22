<?php
require 'PHPMailer/PHPMailer-master/src/Exception.php';
require 'PHPMailer/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";


function berichtKlant($klantVNaam, $klantANaam, $klantMail, $klantBericht){
    $mailMessage = "
        <html>
            <head>
            <style>
                h1{
                    text-align: center;
                }
            </style>
            </head>
            <body>
                <h1>Vraag van klant</h1><br>
                <p>Beste Servicedeskmedewerker,</p>
                <p>".$klantVNaam." ".$klantANaam." bericht het volgende</p>
                <p>".$klantBericht."</p>
            </body>
        </html>
    ";

    $email = new PHPMailer();

    // Message
    $email->SetFrom('customerservice.nerdygadgets@gmail.com');
    $email->Subject = "NerdyGadgets - Bericht van klant";
    $email->Body = $mailMessage;
    $email->addAddress($klantMail);
    $email->isHTML(true);
    

    // var_dump($email->Sendmail);
    if(!$email->send()){
        echo "Mailer Error: " . $email->ErrorInfo;
    }
    else{
        print("Success");
    }


}

function verstuurFactuur($klantVNaam, $klantANaam, $klantMail){
    // create date
        $year = date("Y");
        $day = date("D");
        $hour = date("H");

        $date = $year;
        $date .= $day;
        $date .= $hour;

        var_dump($date);

    // Message is written in HTML due to headers of Mail. Inline CSS is granted
    $mailMessage = "
        <html>
            <head>
            <style>
                h1{
                    text-align: center;
                }
            </style>
            </head>
            <body>
                <h1>Factuur</h1><br>
                <p>Beste ".$klantVNaam."".$klantANaam."</p>
            </body>
        </html>
    ";
    

    $email = new PHPMailer();
    $email->SetFrom('customerservice.nerdygadgets@gmail.com');
    $email->Subject = "NerdyGadgets - Factuur";
    $email->Body = $mailMessage;
    $email->addAddress($klantMail);
    $email->isHTML(true);
    $email->addAttachment("./factuur/" . $date . ".txt");

    if (!$email->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $email->ErrorInfo;
        exit;
    } else {
        unlink("./factuur/" . $date . ".txt");
    }

}



berichtKlant("123", "123", "customerservice.nerdygadgets@gmail.com", "123");


?>