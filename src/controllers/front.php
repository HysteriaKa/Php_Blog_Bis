<?php

namespace Blog\Ctrl;

use Blog\Ctrl\Page;
use Blog\Ctrl\utils;
use Blog\Ctrl\Post;
use Blog\Ctrl\Comments;
use Blog\Ctrl\User;

class Front extends Page
{


    protected function home()
    {

        global $currentSession;
        //    var_dump($sessionActuelle);
        $this->template = "home";
        $articles = new Post("all");
        $this->current_page = "home";
        if ($currentSession->get("user")){
        $this->data = [
            "user" => $currentSession->get("user"),
            "role" => $currentSession->get("role"),
            "posts" => []
        ];
    } else{
        $this->data = [
            "posts" => []
        ];
    }
        foreach ($articles->getList() as $key => $value) {
            $value->url = Utils::titleToURI($value->titre);
            array_push($this->data["posts"], $value);
        }
    }


    protected function articles()
    {
        $this->template = "blogListe";
        $articles = new Post("all");
        $this->data = [];
        foreach ($articles->getList() as $key => $value) {
            $value->url = Utils::titleToURI($value->titre);
            array_push($this->data, $value);
        }
    }

    protected function article($safedata)
    {
        //TODO saveCOmment Only user connected
        global $currentSession;
        if ($safedata->method === "POST") {
            //  die(var_dump($safedata));   
            try {
                $newComment = new Comments($safedata);
                $newComment->save($safedata);
            } catch (\Throwable $th) {
                //throw $th;
                $this->template = "page500";
                $this->status = 500;
                $this->data = [
                    "msg" => $th
                ];

                return;
            }
        }

        $this->template = 'article';
        $article = new Post(["titre" => $safedata->uri[1]]);
        $commentaires = new Comments(["id_article" => $article->getId()]);
        $this->data = [
            "user" => $currentSession->get("user"),
            "role" => $currentSession->get("role"),
            "article"     => $article->getAll(),
            "commentaires" => $commentaires->getCommentByArticle(),
            "ack" => [
                "type" => "success",
                "message" => "le commentaire a bien été envoyé."
            ]

        ];
        // die(var_dump($this->data));
    }

    protected function contact()
    {

        $this->template = "contact";
        $this->current_page = "contact";
        if (isset($_POST) && !empty($_POST)) {

            $contact = new Contact;
            $contact->getInfos();
        }
        $this->data = []; //données du modele

    }

    protected function registration($safeData)
    {

        $this->template = "registration";
        $this->current_page = "registration";

        if ($safeData->method === "GET") {
            $this->data = []; //données du modele

        }
        if ($safeData->method === "POST") {
            global $currentSession;
            $this->data = $safeData->post;
            if ($safeData->post["password"] !== $safeData->post["password_2"]) {
                $currentSession->addNotification("error", "Les mots de passe ne correspondent pas.");
                return;
            }
            $user = new User([
                "password" => $safeData->post["password"],
                "username" => $safeData->post["username"],
                "email" => $safeData->post["email"]
            ]);
            if (!$user->create()) {

                header("HTTP/1.0 500");
                $currentSession->addNotification("error", "l'enregistrement à échoué");
                return;
            }
            $currentSession->addNotification("success", "le compte a bien été créé");
            return header("Location:/login");
            exit();
        }
    }

    protected function login($safeData)
    {
        $this->template = "login";
        $this->current_page = "login";
        if ($safeData->method === "GET") {
            $this->data = []; //données du modele

        }
        if ($safeData->method === "POST") {
            global $currentSession;
            // $logged =false;
            $this->data = $safeData->post;
            // $hash = 'pwd';
            $user = new User([
                "password" => $safeData->post["password"],
                "email" => $safeData->post["email"]
            ]);

            unset($safeData->post["password"]);


            $isLogged = $user->login();
            if (!$isLogged) {

                header("HTTP/1.0 500");
                $currentSession->addNotification("error", "la connexion à échouée");
                return;
            }
            $currentSession->addNotification("success", "Bienvenue");
            // $logged =true;
            return header("location:/home");
            //TODO trouver pourquoi il n'y a pas les ACK
            exit();
        }
    }
    
    protected function logout()
    {
        $user = new User([]);
        $user->logout();
        // $logged =true;
        return header("location:/home");
    }
}
