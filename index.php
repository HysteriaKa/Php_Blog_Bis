<?php

require './vendor/autoload.php';

// die(var_dump($_POST));

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
    'cache' => false,
    //__DIR__.'/tmp'
]);
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
echo $twig->render($page->template.".twig", ["data"=>$page->data], $page->current_page);