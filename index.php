<?php

use Blog\Ctrl\ErrorHandler;

require 'vendor/autoload.php';


try {
    $utils = new Blog\Ctrl\Utils();
    $currentSession = new \Blog\Ctrl\SessionManager();

    $safeData =  new Blog\Ctrl\SafeData([
        "post" => [
            "auteur" => FILTER_SANITIZE_STRING,
            "chapo" => FILTER_SANITIZE_STRING,
            "commentToValidate" => FILTER_SANITIZE_NUMBER_INT,
            "content" => FILTER_SANITIZE_STRING,
            "email" => FILTER_SANITIZE_EMAIL,
            "id_article" => FILTER_SANITIZE_NUMBER_INT,
            "id_user" => FILTER_SANITIZE_NUMBER_INT,
            "image" => FILTER_SANITIZE_STRING,
            "password" => FILTER_SANITIZE_STRING,
            "password_2" => FILTER_SANITIZE_STRING,
            "register_btn" => FILTER_SANITIZE_STRING,
            "removeContent" => FILTER_SANITIZE_STRING,
            "sendHomeForm"=>FILTER_SANITIZE_STRING,
            "titre" => FILTER_SANITIZE_STRING,
            "username" => FILTER_SANITIZE_STRING,
            "message" => FILTER_SANITIZE_STRING

        ]
    ]);

    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates/');
    $twig = new Twig\Environment($loader, [
        'debug' => false,
        'cache' => false,
    ]);

    $twig->addExtension(new \Twig\Extension\DebugExtension());
    
    //routing
    switch ($safeData->uri[0]) {
        case "admin":
            array_shift($safeData->uri);
            $page = new Blog\Ctrl\Admin($safeData);
            break;
        default:
            $page = new Blog\Ctrl\Front($safeData);
            break;
    }

} catch (\Throwable $th) {
    new ErrorHandler($th);
}
finally{
    //rendu
  
    $templateData = ["data" => $page->data];
    $notifications  = $currentSession->getNotifications();
    if (!empty($notifications)) $templateData["ack"] = $notifications;
    echo $twig->render($page->template.".twig", $templateData, $page->current_page);

}
