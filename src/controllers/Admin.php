<?php

namespace Blog\Ctrl;

use Blog\Models\Comments;
use Blog\Ctrl\SafeData;
use Blog\Ctrl\ErrorHandler;
use Blog\Models\Post;

class Admin extends Page
{
    public function __construct(SafeData $safeData)
    {
        global $currentSession, $utils;
        if ($currentSession->get("role") === "0" || ($_SERVER["HTTP_REFERER"]) === null) {
            $utils->end([
                "message" => "vous ne pouvez pas faire cette action.",
                "messageType" => "error",
                "redirection" => "Location:/login",
                "exit" => true
            ]);
        }
        parent::__construct($safeData);
    }

    public function delete_comment($safeData)
    {
        global $utils;
        //die(var_dump($comment));
        try {
            $comment = new Comments(["id" => $safeData->uri[1]]);
            $articleUrl = $utils->titleToURI($comment->getArticleTitle());

            if ($safeData->method === "GET") {
                $this->template = "deleteConfirm";
                $this->data = [
                    "message" => "voulez vous supprimer ce commentaire",
                    "articleUrl" => $articleUrl
                ];
            }
            if ($safeData->method === "POST") {
                if ($safeData->post["removeContent"] !== "") {
                    return $utils->end([
                        "message" => "le commentaire n'a pas été supprimé",
                        "messageType" => "warn",
                        "header" => "Location:/article/$articleUrl"
                    ]);
                }

                $comment->removeComment();
                $utils->end([
                    "message" => "Le commentaire a bien été supprimé.",
                    "messageType" => "success",
                    "header" => "Location:/article/$articleUrl",
                    "exit" => true
                ]);
            }
        } catch (\Throwable $th) {
            die(var_dump($th));
            // new ErrorHandler($th);
        }
    }


    public function listComments($safeData)
    {
        // die(var_dump($safeData));
        global $currentSession, $utils;
        $comment = new Comments($safeData);
        if ($safeData->method === "POST") {
            try {
                $comment->validateComment();
                return $utils->end([
                    "message" => "Le commentaire est en ligne.",
                    "messageType" => "success",
                    "header" => "Location:/admin/listComments"
                ]);
            } catch (\Exception $e) {
                new ErrorHandler($e);
            }
        }
        $this->template = 'listComments';

        $this->data = [
            "user" => $currentSession->get("user"),
            "role" => $currentSession->get("role"),
            "commentaires" => $comment->getCommentsToValidate()
        ];
    }

    public function addArticle($safedata)
    {
        global $currentSession, $utils;

        if ($safedata->method === "POST") {

            try {
                // die(var_dump($safedata, $currentSession));
                $newArticle = new Post($safedata->post);
                $newArticle->addPost();

                return $utils->end([
                    "message" => "l'article a bien été publié.",
                    "messageType" => "success",
                    "header" => "Location:/articles",
                    "exit" => true
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                $this->template = "page500";
                $this->status = 500;
                $this->data = [
                    "msg" => $th
                ];
            }
        }

        $this->template = 'addArticle';
        $this->data = [
            "idUser" => $currentSession->get("idUser"),
            "role" => $currentSession->get("role"),
            "user" => $currentSession->get("user"),
            "titre" => "",
        ];
    }

    public function delete_article($safedata)
    {

        global $currentSession, $utils;

        //   die(var_dump($article->getArticleToDelete($safedata)));
        // si methode GET
        if ($safedata->method === "GET") {
            try {
                // var_dump($article);
                $this->template = "deleteConfirm";
                $this->data = [
                    "message" => "voulez vous supprimer cet article",
                    // "articleUrl" => $articleUrl

                ];
            } catch (\Throwable $th) {
                //throw $th;

            }
        }
        if ($safedata->method === "POST") {
            if ($safedata->post["removeContent"] !== "") {
                $currentSession->addNotification("warn", "l'article n'a pas été supprimé");
                return $utils->end(["header" => "Location:/articles"]);
            }
            try {
                global $utils;
                $article = new Post(["id" => $safedata->uri[1]]);
                $article->removeArticle();
                $utils->end([
                    "message" => "L'article a bien été supprimé.",
                    "messageType" => "success",
                    "header" => "Location:/articles",
                    "exit" => true
                ]);
            } catch (\Exception $e) {
                new ErrorHandler($e);
            }
        }
    }

    public function edit_article($safedata)
    {
        global $utils;
        $article = new Post(["id" => $safedata->uri[1]]);
        $article->initById();

        if ($safedata->method === "POST") {
            try {
                // var_dump($article);
                $article->modifyArticle($safedata);
                return $utils->end([
                    "message" => "La modification a été effectuée.",
                    "messageType" => "success",
                    "header" => "Location:/articles"
                ]);
            } catch (\Exception $e) {
                new ErrorHandler($e);
            }
        }
        $this->template = 'addArticle';
        $this->data = $article->getAll();
    }
}
