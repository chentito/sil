<?php
/*
 * Envío de correos electronicos a traves 
 * de PHPMailer con PDF adjunto
 * @Fecha Agosto 2019
 * @Autor Carlos Reyes
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
require 'vendor/setasign/fpdf/fpdf.php';
$conf = include 'conf/mail.php';
$mail = new PHPMailer( true );

try {
    //Server settings
    $mail->SMTPDebug   = 2;
    $mail->isSMTP();
    $mail->Host        = $conf[ 'host' ];
    $mail->SMTPAuth    = true;
    $mail->Username    = $conf[ 'username' ];
    $mail->Password    = $conf[ 'password' ];
    $mail->SMTPSecure  = $conf[ 'secure' ];
    $mail->Port        = $conf[ 'port' ];
    $mail->SMTPOptions = array( 'ssl' => array( 'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true ) );

    //Recipients
    $mail->setFrom( $conf[ 'from' ] , $conf[ 'fromName' ] );
    $mail->addAddress( 'cvreyes@mexagon.net' , 'Reyes Carlos' );
    // Attachements
    $pdf = new FPDF;
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(40,10,'¡Hola, Mundo!');
    $pdfdoc = $pdf->Output( '' , 'S' );    
    $mail->addStringAttachment( $pdfdoc , 'doc.pdf' );
    // Content
    $mail->isHTML( true );
    $mail->Subject = 'Envio desde CRM';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo "Correo enviado exitosamente";
} catch ( Exception $e ) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"." El usuario ".$conf[ 'username' ]." es incorrecto";
}
