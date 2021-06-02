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
                $comment->removeComment();
                $this->template = 'article';
                $article = new Post(["titre" => $articleUrl]);
                $commentaires = new Comments(["id_article" => $idArticle]);
                $this->data = [
                    "article"     => $article->getAll(),
                    "commentaires" => $commentaires->getCommentByArticle(),
                    "ack"=>[
                        "type"=>"succes",
                        "message"=>"le commentaire a bien été supprimé."
                    ]
                ];

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
