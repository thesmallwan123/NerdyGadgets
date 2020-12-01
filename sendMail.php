<?php
// Include phpMailer
require 'PHPMailer/PHPMailer-master/src/Exception.php';
require 'PHPMailer/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer/PHPMailer-master/src/SMTP.php';

// Use phpMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Content-type of mail
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";


// Verstuur een bericht naar de klantenservice
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
    
    $email->isSMTP();
    $email->Host = 'ssl://smtp.gmail.com';
    $email->Port = 465;
    $email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $email->SMTPAuth = true;
    $email->Username = "customerservice.nerdygadgets@gmail.com";
    $email->Password = "AdMiN312";





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

// Verstuur factuur naar de klant
function verstuurFactuur($klantVNaam, $klantTussen, $klantANaam, $klantMail, $fileLocation){
    // Check if file exists
    $file = fopen($fileLocation, "r");
    if($file){
        fclose($file);


        // Message is written in HTML due to headers of Mail. Inline CSS is granted
        $mailMessage = "
            <html>
                <head>
                <style>
                    .intro{
                        textalign: left;
                        fint-size: 15px;
                    }
                </style>
                </head>
                <body>
                    <p class='intro'>Beste ".$klantVNaam." " .$klantTussen. " ".$klantANaam. "</p>
                    <p>Uw bestelling is bij ons doorgekomen!</p>
                    <p>Wij willen u bij deze het factuur van uw bestelling meegeven.</p>
                    <p>Nogmaals dank voor uw bestelling</p>
                    <p>Met vriendelijke groet,</p>
                    <p>customerservice.nerdygadgets@gmail.com</p>
                </body>
            </html>
        ";

        $email = new PHPMailer();


        $email->isSMTP();
        $email->Host = 'ssl://smtp.gmail.com';
        $email->Port = 465;
        $email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $email->SMTPAuth = true;
        $email->Username = "customerservice.nerdygadgets@gmail.com";
        $email->Password = "AdMiN312";


        $email->SetFrom('customerservice.nerdygadgets@gmail.com');
        $email->Subject = "NerdyGadgets - Factuur";
        $email->Body = $mailMessage;
        $email->addAddress($klantMail);
        $email->isHTML(true);
        $email->addAttachment($fileLocation);

        if (!$email->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $email->ErrorInfo;
            return FALSE;
            exit;
        } else {
            return TRUE;
        }
    }

}


?>