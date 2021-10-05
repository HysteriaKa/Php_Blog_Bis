<?php

namespace Blog\Ctrl;

use Blog\Ctrl\Page;
use Blog\Ctrl\Post;
use Blog\Ctrl\Comments;
use Blog\Ctrl\User;
use Blog\Debug;
use Blog\Ctrl\Contact;

class Front extends Page
{

/**
 * [home description]
 *
 * @param   [type]  $safeData  [$safeData description]
 *
 * @return  [type]             [return description]
 */
    protected function home($safeData)
    {
        global $currentSession, $utils;
        $this->template = "home";
        $articles = new Post("all");
        $this->current_page = "home";
        $this->data = [
            "posts" => []
        ];
        if ($currentSession->get("user")) {
            $this->data["user"] = $currentSession->get("user");
            $this->data["role"] = $currentSession->get("role");
        }
        foreach ($articles->getList() as $value) {
            $value->url = $utils->titleToURI($value->titre);
            array_push($this->data["posts"], $value);
        }
        if ($safeData->post) {
            if ($safeData->post["sendHomeForm"] !== "") {
                $this->sendMessage(
                    $safeData->post["username"],
                    $safeData->post["email"],
                    $safeData->post["message"]
                );
            }
        }
    }


    protected function articles()
    {
        global $currentSession, $utils;
        $this->template = "blogListe";
        $articles = new Post("all");
        // (new Debug)->vardump($articles->getList());

        $this->data = [
            "user" => $currentSession->get("user"),
            "role" => $currentSession->get("role"),
            "posts" => []
        ];

        // var_dump($this->data);
        foreach ($articles->getList() as $key => $value) {

            $value->url = $utils->titleToURI($value->titre);
            // (new Debug)->vardump($value, $this->data);
            array_push($this->data["posts"], $value);
        }
        // (new Debug)->vardump($this->data);

    }

    protected function article($safedata)
    {

        global $currentSession, $utils;
        if ($safedata->method === "POST") {
            //  die(var_dump($safedata));   
            try {
                $newComment = new Comments($safedata);
                $newComment->save($safedata);
               
                $utils->end([
                    "message"=>"le commentaire a bien été envoyé.",
                    "messageType"=>"success"
                ]);
                // die(var_dump($this->data));
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
        $article->initByTitle();

        $commentaires = new Comments(["id_article" => $article->getId()]);
        // die(var_dump($article->getId()));
        $this->data = [
            "user" => $currentSession->get("user"),
            "role" => $currentSession->get("role"),
            "article"     => $article->getAll(),
            "commentaires" => $commentaires->getCommentByArticle()
        ];
        $utils->end([
            "message"=>"le commentaire a bien été envoyé.",
            "messageType"=>"success"
        ]);
        // die(var_dump($this->data));
    }

    protected function contact($safedata)
    {
        $this->template = "contact";
        $this->current_page = "contact";

        if ($safedata->post  !== null) {
            $this->sendMessage($safedata->post["username"], $safedata->post["email"], $safedata->post["message"]);
        }

    }

    protected function registration($safeData)
    {

        $this->template = "registration";
        $this->current_page = "registration";

        if ($safeData->method === "GET") {
            $this->data = []; //données du modele

        }
        if ($safeData->method === "POST") {
            global $currentSession, $utils;
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
            if (!$user->create())  return $utils->end([
                    "message"=>"l'enregistrement à échoué.",
                    "messageType"=>"error",
                    "header"=>"HTTP/1.0 500",
                ]);   
            $utils->end([
                "message"=>"le compte a bien été créé.",
                "messageType"=>"success",
                "header"=>"Location:/login",
                "exit"=>true
            ]);
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
            global $utils;
            // $logged =false;
            $this->data = $safeData->post;
            // $hash = 'pwd';
            $user = new User([
                "password" => $safeData->post["password"],
                "email" => $safeData->post["email"]
            ]);

            unset($safeData->post["password"]);


            $isLogged = $user->login();
            if (!$isLogged)  return $utils->end([
                "message"=>"la connexion à échouée.",
                "messageType"=>"error",
                "header"=>"HTTP/1.0 500",
            ]);
            // $logged =true;
            $utils->end([
                "message"=>"Bienvenue",
                "messageType"=>"success",
                "header"=>"Location:/home",
                "exit"=>true
            ]);
        }
    }

    protected function logout()
    {
        global $utils;
        $user = new User([]);
        $user->logout();
        // $logged =true;
        return $utils->end([
            "header"=>"location:/home",
        ]);   
    }

    private function sendMessage($from, $email, $message)
    {
        global $utils;
        try {
            throw new \Exception();
            Contact::sendMail($from, $email, $message);
            $utils->end([
                "message"=>"le message a bien été envoyé.",
                "messageType"=>"success"
            ]);

        } catch (\Exception $e) {
            new ErrorHandler($e, "le message n'a pas pu être envoyé");
        }
    }
}