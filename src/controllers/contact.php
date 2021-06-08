<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

namespace Blog\Ctrl;

class Contact extends Entity
{
    // private $PHPMailer;
    public function getInfos()
    {

        $data = $_POST;
       
        // die(var_dump($data));
        $msg = $data['message'];
        $email = $data['email'];
        $name = $data['name'];
        $headers = 'From: ' . $email . "\r\n" .
            'Reply-To: karine2310@gmail.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail('karine2310@gmail.com', 'contact de : ' . $name, $msg . 'Pour répondre contacter : ' . $email, $headers);
    }

    public function sendMail(){
        $mail = new $this-> PHPMailer(True);
    }
}
