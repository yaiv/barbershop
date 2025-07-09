<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $nombre;
    public $email;
    public $token;

    public function __construct($nombre, $email, $token)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
    }

    public function enviarConfirmacion(){

        //Crear el objeto de email

        $mail = new PHPMailer();
        $mail->isSMTP();
        // Looking to send emails in production? Check out our Email API/SMTP product!
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'a196fd959a9297';
        $mail->Password = '8ce0081a3dbda7';

        //Quien lo envia --Se coloca el dominio una vez realizado el deplyment
        $mail->setFrom('cuentas@barbershop.com');
        $mail->addAddress('cuentas@barbershop.com', 'Barbershop.com');
        $mail->Subject = 'Confirma tu cuenta';

        //Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = "UTF-8";

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . ".</strong> Has creado tu cuenta en BarberShop, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a> </p>";

        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje </p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Se envia el email 
        $mail->send();
    }

    public function enviarInstrucciones(){

                //Crear el objeto de email

        $mail = new PHPMailer();
        $mail->isSMTP();
        // Looking to send emails in production? Check out our Email API/SMTP product!
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'a196fd959a9297';
        $mail->Password = '8ce0081a3dbda7';

        //Quien lo envia --Se coloca el dominio una vez realizado el deplyment
        $mail->setFrom('cuentas@barbershop.com');
        $mail->addAddress('cuentas@barbershop.com', 'Barbershop.com');
        $mail->Subject = 'Restablece tú password';

        //Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = "UTF-8";

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . ".</strong> Has solicitado restablecer tu password, sigue el siguiente enlace para hacerlo.</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/recuperar?token=" . $this->token . "'>Restablecer Password</a> </p>";

        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje </p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Se envia el email 
        $mail->send();

    }
}