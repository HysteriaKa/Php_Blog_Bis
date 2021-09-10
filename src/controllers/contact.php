<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use Blog\Ctrl\Entity;
namespace Blog\Ctrl;

use Throwable;

class Contact extends Entity
{

    public function __construct()
    {
        try {
            $mail = new PHPMailer(true);
            $mail->IsSMTP();
            $mail->Host = 'smtp.gmail.com';               //Adresse IP ou DNS du serveur SMTP
            $mail->Port = 587;                          //Port TCP du serveur SMTP
            $mail->SMTPAuth = 1;                        //Utiliser l'identification

            if ($mail->SMTPAuth) {
                $mail->SMTPSecure = 'ssl';               //Protocole de sécurisation des échanges avec le SMTP
                $mail->Username   =  'poupette2310@gmail.com';   //Adresse email à utiliser
                $mail->Password   =  'xxxxx';         //Mot de passe de l'adresse email à utiliser
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // private $PHPMailer;
    public function getInfos()
    {

        try {
            // $data = $_POST;
            // die(var_dump($data));
            // die(var_dump($data));
            // $msg = $data['message'];
            // $email = $data['email'];
            // $name = $data['name'];
            $msg = "aaa";
            $email = "test@mailhog.local";
            $name = "bbb";

            $headers = 'From: poupette2310@gmail.com\r\n' .
                'Reply-To: ' . $email . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            $test = mail('poupette2310@gmail.com', 'contact de : ' . $name, $msg . 'Pour répondre contacter : ' . $email, $headers, error_reporting(E_ALL));
            // die(var_dump($test));
        } catch (\Exception $e) {
            new ErrorHandler($e);
        }
    }


    public function sendMail()
    {
    }
}
