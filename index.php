<?php

require './vendor/autoload.php';

// die(var_dump($_POST));
$currentSession = new \Blog\Ctrl\SessionManager();

$safeData = new Blog\Ctrl\SafeData([
    "post"=>[
        "email" => FILTER_SANITIZE_EMAIL,
        "auteur"=> FILTER_SANITIZE_STRING,
        "content"=> FILTER_SANITIZE_STRING,
        "id_article"=> FILTER_SANITIZE_NUMBER_INT,
        "removeContent" =>FILTER_SANITIZE_STRING
    ]
]);

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates/');
$twig = new Twig\Environment($loader, [
    'debug' => true,
    'cache' => false,
    //__DIR__.'/tmp'
]);

$twig->addExtension(new \Twig\Extension\DebugExtension());
//routing
switch($safeData->uri[0]){
    case "admin" : 
        array_shift($safeData->uri);
        $page = new Blog\Ctrl\Admin($safeData);
        break;
    default:
        $page = new Blog\Ctrl\Front($safeData);
        break;
}

//rendu
$templateData = ["data"=>$page->data];
$notifications  = $currentSession->getNotifications();
// var_dump($notifications);
if (!empty($notifications)) $templateData["ack"]= $notifications;
echo $twig->render($page->template.".twig", $templateData , $page->current_page);