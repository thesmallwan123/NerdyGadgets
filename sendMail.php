<?php
// php.ini: 
    // [mail function]
    // ; For Win32 only.
    // ; http://php.net/smtp
    // SMTP=localhost
    // ; http://php.net/smtp-port
    // smtp_port=587

    // ; For Win32 only.
    // ; http://php.net/sendmail-from
    // sendmail_from = customerservice.nerdygadgets@gmail.com

    // ; For Unix only.  You may supply arguments as well (default: "sendmail -t -i").
    // ; http://php.net/sendmail-path
    // sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"

    // ; Force the addition of the specified parameters to be passed as extra parameters
    // ; to the sendmail binary. These parameters will always replace the value of
    // ; the 5th parameter to mail().
    // ;mail.force_extra_parameters =

    // ; Add X-PHP-Originating-Script: that will include uid of the script followed by the filename
    // mail.add_x_header=On

    // ; The path to a log file that will log all mail() calls. Log entries include
    // ; the full path of the script, line number, To address and headers.
    // ;mail.log =
    // ; Log mail to syslog (Event Log on Windows).
    // ;mail.log = syslog

// sendmail.ini: 
    // ; configuration for fake sendmail

    // ; if this file doesn't exist, sendmail.exe will look for the settings in
    // ; the registry, under HKLM\Software\Sendmail

    // [sendmail]

    // ; you must change mail.mydomain.com to your smtp server,
    // ; or to IIS's "pickup" directory.  (generally C:\Inetpub\mailroot\Pickup)
    // ; emails delivered via IIS's pickup directory cause sendmail to
    // ; run quicker, but you won't get error messages back to the calling
    // ; application.

    // smtp_server=smtp.gmail.com

    // ; smtp port (normally 25)

    // smtp_port=587

    // ; SMTPS (SSL) support
    // ;   auto = use SSL for port 465, otherwise try to use TLS
    // ;   ssl  = alway use SSL
    // ;   tls  = always use TLS
    // ;   none = never try to use SSL

    // smtp_ssl=auto

    // ; the default domain for this server will be read from the registry
    // ; this will be appended to email addresses when one isn't provided
    // ; if you want to override the value in the registry, uncomment and modify

    // ;default_domain=mydomain.com

    // ; log smtp errors to error.log (defaults to same directory as sendmail.exe)
    // ; uncomment to enable logging

    // error_logfile=error.log

    // ; create debug log as debug.log (defaults to same directory as sendmail.exe)
    // ; uncomment to enable debugging

    // ;debug_logfile=debug.log

    // ; if your smtp server requires authentication, modify the following two lines

    // auth_username=customerservice.nerdygadgets@gmail.com
    // auth_password=AdMiN312

    // ; if your smtp server uses pop3 before smtp authentication, modify the 
    // ; following three lines.  do not enable unless it is required.

    // pop3_server=
    // pop3_username=
    // pop3_password=

    // ; force the sender to always be the following email address
    // ; this will only affect the "MAIL FROM" command, it won't modify 
    // ; the "From: " header of the message content

    // force_sender=customerservice.nerdygadgets@gmail.com

    // ; force the sender to always be the following email address
    // ; this will only affect the "RCTP TO" command, it won't modify 
    // ; the "To: " header of the message content

    // force_recipient=

    // ; sendmail will use your hostname and your default_domain in the ehlo/helo
    // ; smtp greeting.  you can manually set the ehlo/helo name if required

    // hostname=


// Einde config

include("phpMailer\PHPMailer-master\src\PHPMailer.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";


function vraagKlant($klantVNaam, $klantANaam, $klantMail, $klantVraag){
    // Set variables (needs to be from form)
    $mailOntvanger = "customerservice.nerdygadgets@gmail.com";
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
                <h1>Vraag van ".$klantMail."</h1>
                <p>".$klantVNaam." ".$klantANaam." vraagt het volgende:</p>
                <p>".$klantVraag."</p>
                <p> Gelieve op deze mail te reageren: ".$klantMail."</p>
            </body>
        </html>
    ";

    
    $email = new PHPMailer();
    $email->SetFrom('customerservice.nerdygadgets@gmail.com');
    $email->Subject = "Klantvraag";
    $email->Body = $mailMessage;
    $email->addAddress($mailOntvanger);
    $email->isHTML(true);
    $email->addAttachment("./factuur/test.txt");

    if (!$email->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $email->ErrorInfo;
        exit;
    }
    // mail($mailOntvanger, $mailTitle, $mailMessage, $headers);
}




// function verstuurFactuur($klantVNaam, $klantANaam, $klantMail, $headers){
    //     $mailOntvanger = $klantMail;



// }

vraagKlant("Ivar", "Post", "12321.post@gmail.com", "uiahuiwauhdhuihuiawhudhuahuwduhhuiauwdhuihui");



?>