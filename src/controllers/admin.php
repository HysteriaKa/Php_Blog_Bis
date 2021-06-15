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
        $comment = new Comments(["id"=>$safeData->uri[1]]);
        $articleUrl = Utils::titleToURI($comment->getArticleTitle());
        // die(var_dump($comment->getArticle()));
        // si methode GET
        if ($safeData->method === "GET"){
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
        if ($safeData->method === "POST"){
            if ( $safeData->post["removeContent"] !== ""){
                $this->template = "redirection";
                $this->data = ["url" => "/admin"];
               
                return;
            }
            try{
                $idArticle = $comment->getArticleId();
                // var_dump($idArticle);
                $comment->removeComment();
                $currentSession->addNotification("success","Le commentaire a bien été supprimé.");
                $this->template = 'article';
                header("Location:/article/$articleUrl");

            }
            catch (\Exception $e){
                die(var_dump($e));
            }
        }
        // $this->template ="";
        // $this->data =[
        //     "message" => "commentaire supprimé"
        // ];
    }
}
