<?php
namespace Blog\Ctrl;
class ErrorHandler
{
    public function __construct($err, $msg=null) {
       
        global $currentSession;
        $currentSession->addNotification("error",  $msg !== null ? $msg : "le serveur a rencontré une erreur :(<br>$err");
    }
}