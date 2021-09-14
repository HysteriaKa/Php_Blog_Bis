<?php



namespace Blog\Ctrl;

use Throwable;

class Contact
{

    // public function __construct()
    // {
    // }
    // private $PHPMailer;
    public static function sendMail($from, $email, $message)
    {

        try {
            $headers = 'From: karine@ksrteam.fr' . "\r\n" .
            'Reply-To:'.$email . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
           mail(
              "karine2310@gmail.com",
              "nouveau message de ".$from,
               "Bonjour Karine, tu as un nouveau message de $from:\n$message",
               $headers
           );
        } catch (\Exception $e) {
             throw $e;
        }
    }

}
