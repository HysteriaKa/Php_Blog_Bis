<?php
namespace Blog\Ctrl;
class ErrorHandler
{
    public function __construct($err, $msg=null) {
        // var_dump($err);
        global $currentSession;
        $currentSession->addNotification("error",  $msg !== null ? $msg : "le serveur a rencontré une erreur :(");
        $currentSession->addNotification("success",  $msg !== null ? $msg : "le serveur a rencontré une erreur :(");
        $currentSession->addNotification("warn",  $msg !== null ? $msg : "le serveur a rencontré une erreur :(");

    }
}