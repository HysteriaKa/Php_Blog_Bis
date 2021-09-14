<?php

namespace Blog\Ctrl;

use Blog\Debug;
// use Blog\Models\CommentModel;
use Blog\Ctrl\Utils;
use Blog\Ctrl\Comments;
use Blog\Ctrl\SafeData;
use Blog\Ctrl\ErrorHandler;

class Admin extends Page
{
    public function __construct(SafeData $safeData)
    {
        global $currentSession;
        if ($currentSession->get("role") === "0" || is_null($_SERVER["HTTP_REFERER"])) {
            $currentSession->addNotification("error", "vous ne pouvez pas faire cette action.");
            header("Location:/login");
            exit(0);
        }
        parent::__construct($safeData);
    }

    public function delete_comment($safeData)
    {
        global $currentSession;
        $comment = new Comments(["id" => $safeData->uri[1]]);
        $articleUrl = Utils::titleToURI($comment->getArticleTitle());
        // die(var_dump($comment->getArticle()));
        // si methode GET
        if ($safeData->method === "GET") {
            try {
                $this->template = "deleteConfirm";
                $this->data = [
                    "message" => "voulez vous supprimer ce commentaire",
                    "articleUrl" => $articleUrl

                ];
            } catch (\Throwable $th) {
                //throw $th;

            }
        }
        if ($safeData->method === "POST") {
            if ($safeData->post["removeContent"] !== "") {
                $currentSession->addNotification("warn", "le commentaire n'a pas été supprimé");
                return header("Location:/article/$articleUrl");
            }
            try {
                // $idArticle = $comment->getArticleId();
                // var_dump($idArticle);
                $comment->removeComment();
                $currentSession->addNotification("success", "Le commentaire a bien été supprimé.");
                // $this->template = 'article';
                // die(var_dump($_SESSION));
                header("Location:/article/$articleUrl");
                exit();
            } catch (\Exception $e) {
                new ErrorHandler($e);
            }
        }
    }


    public function listComments($safeData)
    {
        // die(var_dump($safeData));
        global $currentSession;
        $comment = new Comments($safeData);
// die(var_dump($comment));
        $currentSession->addNotification("success", "test");
        if ($safeData->method === "POST") {
            try {
                $comment->validateComment();
                $currentSession->addNotification("success", "Le commentaire est en ligne.");
                return header("Location:/admin/listComments");
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
        // var_dump($this->data);
    }

    public function addArticle($safedata)
    {
        global $currentSession;

        if ($safedata->method === "POST") {

            try {
                // die(var_dump($safedata, $currentSession));
                $newArticle = new Post($safedata->post);
                $newArticle->addPost();
                return header("location:/articles");
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

            "ack" => [
                "type" => "success",
                "message" => "l'article a bien été publié."
            ],
            "idUser" => $currentSession->get("idUser"),
            "role" => $currentSession->get("role"),
            "user" => $currentSession->get("user"),
            "titre" => "",
        ];
    }

    public function delete_article($safedata)
    {

        global $currentSession;

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
                $currentSession->addNotification("warn", "l'article' n'a pas été supprimé");
                return header("Location:/articles");
            }
            try {
                $article = new Post(["id" => $safedata->uri[1]]);
                $article->removeArticle();
                $currentSession->addNotification("success", "L'article' a bien été supprimé.");
                header("Location:/articles");
                exit();
            } catch (\Exception $e) {
                new ErrorHandler($e);
            }
        }
    }

    public function edit_article($safedata)
    {
        global $currentSession;
        $article = new Post(["id" => $safedata->uri[1]]);
        $article->initById();
       
        if ($safedata->method === "POST") {
            try {
                // var_dump($article);
                $article->modifyArticle($safedata);
                $currentSession->addNotification("success", "La modification a été effectuée.");
                return header("location:/articles");
            } catch (\Exception $e) {
                new ErrorHandler($e);
            }
        }
        $this->template = 'addArticle';
        $this->data = $article->getAll();
        

    }
}
