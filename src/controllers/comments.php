<?php

namespace Blog\Ctrl;

use Blog\Ctrl\Entity;
use Blog\Models\CommentModel;

class Comments extends Entity
{

    protected $id;
    protected $created_at;
    protected $id_user;
    protected String $id_article;
    protected $auteur;
    private   CommentModel $model;


    public function __construct($datas=[])
    {

        foreach ($datas as $key => $data) {
            $this->$key = $data;
        }

        // if (isset($data["id_article"])) $this->id_article = $data["id_article"]; 
        $this->model = new CommentModel($data);
    }



    private function InitInOrderToGetListByarticle()
    {
    }

    public function getAll()
    {
        return [
            "id" => $this->id,
            "statut" => $this->statut,
            "content" => $this->contenu,
            "createdAt" => $this->created_at,
            "idArticle" => $this->id_article,
            "idUser" => $this->id_user,
            
        ];
    }
    public function getCommentByArticle()
    {
        $this->commentaires = $this->model->getComments($this->id_article);
        return $this->commentaires;
    }


    public function save($safedata)
    {
        global $currentSession;
        try {

            $this->model->postComment(
                $safedata->post["id_article"],
                $currentSession->get("idUser"),
                
                $safedata->post["content"],   
                0,
                
                // die(var_dump(($currentSession)))
            );
        } catch (\Throwable $th) {
            // die(var_dump($th));
        }
        // die(var_dump($currentSession));
    }



    public function getArticleTitle()
    {
        // die(var_dump($this->model->getParentArticle($this->id)));
        $title = $this->model->getParentArticle($this->id);
        // die(var_dump($title->titre));
        return $title->titre;
    }

    public function getArticleId()
    {
        // die(var_dump($this->model->getParentArticle($this->id)));
        $article = $this->model->getParentArticle($this->id);
        // die(var_dump($article->id));
        return $article->id;
    }
    public function deleteComment($safedata)
    {

        try {
            $this->model->deleteComment(
                $safedata
            );
        } catch (\Throwable $th) {
            
        }
    }
    public function removeComment()
    {
        try {
            $this->model->deleteComment($this->id);
        } catch (\Exception $err) {
            var_dump($err);
            throw $err;
        }
    }
    public function getCommentsToValidate(){

        try {
            $comments = $this->model->getCommentsToValidate();
            return $comments;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function validateComment()
    {
        try{
            $this->model->updateComment($this->post["commentToValidate"]);
        }catch (\Exception $err) {
            var_dump($err);
            throw $err;
        }
    }
}
