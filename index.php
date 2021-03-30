<?php

require 'vendor/autoload.php';


//routing
$page = 'home';
if(isset($_GET['p'])){
    $page = $_GET['p'];
}


//rendu du template

$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$twig = new Twig\Environment($loader,['cache'=>false, 
//__DIR__.'/tmp'
] );

switch ($page)
{
    case 'contact':
        echo $twig ->render('contact.twig');
}

if($page === 'home') {

    echo $twig->render('home.twig', ['title'=>'BlogKa']);
}