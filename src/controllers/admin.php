<?php

namespace Blog\Ctrl;

use Blog\Ctrl\Comments;
// use Blog\Models\CommentModel;
use Blog\Ctrl\Utils;


class Admin extends Page
{
    public function __construct(SafeData $safeData)
    {
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
                $idArticle = $comment->getArticleId();
                // var_dump($idArticle);
                $comment->removeComment();
                $currentSession->addNotification("success", "Le commentaire a bien été supprimé.");
                // $this->template = 'article';
                // die(var_dump($_SESSION));
                header("Location:/article/$articleUrl");
                exit();
            } catch (\Exception $e) {
                die(var_dump($e));
            }
        }
    }
    //TODO display all comments publish/delete 

    public function listComments($safeData)
    {

        global $currentSession;
        $this->template = 'listComments';
        $commentaires = new comments($safeData);
        $commentaires->getComments();
        $this->data = [
            "user" => $currentSession->get("user"),
            "role" => $currentSession->get("role"),
            "commentaires" => $commentaires
        ];
    }
}
