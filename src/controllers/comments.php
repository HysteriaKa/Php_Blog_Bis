<?php

namespace Blog\Ctrl;

use Blog\Ctrl\Entity;
use Blog\Models\CommentModel;

class Comments extends Entity
{

    protected $id;
    protected $contenu;
    protected $statut;
    protected $created_at;
    protected $id_user;
    protected String $id_article;
    protected $auteur;


    public function __construct($data)
    {
        if (isset($data["id_article"])) $this->id_article = $data["id_article"]; 
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
            "auteur" => $this->auteur
        ];
    }
    public function getCommentByArticle()
    {
        $this->commentaires = $this->model->getComments($this->id_article);
        return $this->commentaires;
    }

    public function addComment()
    {
       
    }
}
