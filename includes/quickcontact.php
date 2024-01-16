<?php

require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

$mail = new PHPMailer();


//$mail->SMTPDebug = 3;                               // Enable verbose debug output
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'mail.metratrading.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'php@metratrading.com';                 // SMTP username
$mail->Password = 'v6K06#u$1dd1JJm';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to

$message = "";
$status = "false";

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if( $_POST['form_email'] != '' AND $_POST['form_message'] != '' ) {

		$name = $_POST['form_name'];
        $email = $_POST['form_email'];
        $message = $_POST['form_message'];

        $subject = isset($subject) ? $subject : '[INF] Nuevo Mensaje WEB | Forma: Agendar Cita';

        $botcheck = $_POST['form_botcheck'];

        $toemail = 'comercializacion@metratrading.com'; // Your Email Address
        $toname = 'Metra Trading Hidrocarburos WEB'; // Your Name

        if( $botcheck == '' ) {

            $mail->SetFrom( $email , $name );
            $mail->AddReplyTo( $email , $name );
            $mail->AddAddress( $toemail , $toname );
            $mail->Subject = $subject;

            $name = isset($email) ? "Nombre: $name<br><br>" : '';
			$email = isset($email) ? "Email: $email<br><br>" : '';
            $message = isset($message) ? "Mensaje: $message<br><br>" : '';

            $referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>Enviado desde: ' . $_SERVER['HTTP_REFERER'] : '';

            $body = "$name $email $message $referrer";

            $mail->MsgHTML( $body );
            $sendEmail = $mail->Send();

            if( $sendEmail == true ):
                $message = 'Hemos <strong>correctamente</strong> recibido su mensaje, le contestaremos tan pronto nos sea posible.';
                $status = "true";
            else:
                $message = 'Email <strong>no pudo</strong> ser enviado debido a un error inesperado. por favor intenta otra vez mas tarde.<br /><br /><strong>Razon:</strong><br />' . $mail->ErrorInfo . '';
                $status = "false";
            endif;
        } else {
            $message = 'Bot <strong>Detected</strong>.! Clean yourself Botster.!';
            $status = "false";
        }
    } else {
        $message = 'Por favor <strong>llena</strong> todos los campos he intenta de nuevo.';
        $status = "false";
    }
} else {
    $message = 'Un <strong>error inesperado</strong> ha ocurrido, por favor intenta de nuevo';
    $status = "false";
}

$status_array = array( 'message' => $message, 'status' => $status);
echo json_encode($status_array);
?>