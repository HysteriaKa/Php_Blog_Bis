<?php

namespace Blog\Ctrl;
use Blog\Ctrl\Comments;
// use Blog\Models\CommentModel;
use Blog\Ctrl\Utils;


class Admin extends Page
{
    public function __construct(SafeData $safeData)
    {
        session_start();
        parent::__construct($safeData);
    }

    public function delete_comment($safeData)
    {
        $comment = new Comments(["id"=>$safeData->uri[1]]);
        // die(var_dump($comment->getArticle()));
        // si methode GET
        if ($safeData->method === "GET"){
            try {
                $this->template = "deleteConfirm";
                $this->data = [
                    "message" => "voulez vous supprimer ce commentaire",
                    "articleUrl" => Utils::titleToURI($comment->getArticle())
                    
                ];
            } catch (\Throwable $th) {
                //throw $th;

            }

        }
        else {
            $this->template = "base";
            //appeler le model pour supprimer
            //redirection vers on verra
        }
        // $this->template ="";
        // $this->data =[
        //     "message" => "commentaire supprimé"
        // ];
    }
}
