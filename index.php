<?php

require './vendor/autoload.php';


$safeData = new Blog\Ctrl\SafeData([
    "post"=>[
        "email" => FILTER_SANITIZE_EMAIL,
        "auteur"=> FILTER_SANITIZE_STRING,
        "content"=> FILTER_SANITIZE_STRING,
        "id_article"=> FILTER_SANITIZE_NUMBER_INT
    ]
]);

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates/');
$twig = new Twig\Environment($loader, [
    'cache' => false,
    //__DIR__.'/tmp'
]);

switch($safeData->uri[0]){
    case "admin" : 
        $page = new Blog\Ctrl\Admin($safeData);
        break;
    default:
        $page = new Blog\Ctrl\Front($safeData);
        break;
}
echo $twig->render($page->template.".twig", ["data"=>$page->data], $page->current_page);