<?php
/*
 * Envío de correos electronicos a traves de PHPMailer
 * @Fecha Agosto 2019
 * @Autor Carlos Reyes
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
$conf = include 'conf/mail.php';
$mail = new PHPMailer( true );

// Vars to be send
$name    = filter_var( $_POST[ 'nameContact' ]    , FILTER_SANITIZE_STRING );
$email   = filter_var( $_POST[ 'emailContact' ]   , FILTER_SANITIZE_EMAIL );
$message = filter_var( $_POST[ 'messageContact' ] , FILTER_SANITIZE_STRING );
$captcha = filter_var( $_POST[ 'g-recaptcha-response' ] );

$secretKey    = $conf[ 'secret' ];
$url          =  'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
$response     = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
//$response     = file_get_contents( $url );
$responseKeys = json_decode( $response , true );

header('Content-type: application/json');
if( $responseKeys["success"] ) {
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
        $mail->AddReplyTo( $conf[ 'replyTo' ] , 'Atencion a Clientes');
        $mail->addAddress( 'chentito002@gmail.com' , 'Testing' );
    
        // Content
        $mail->isHTML( true );
        $mail->Subject = 'Contacto desde sitio web';
        $mail->Body    = 'Mensaje de ' . $name. ' con correo electr&oacute;nico: ' . $email . '<br><br>' . $message;
        $mail->AltBody = 'Contacto desde sitio web';
    
        $mail->send();
        echo json_encode( array( 'message' => 'Correo enviado exitosamente' ) );
    } catch ( Exception $e ) {
        echo json_encode( array( 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}") );
    }
} else {
    echo json_encode($responseKeys);
}
