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
    private   CommentModel $model;


    public function __construct($datas)
    {

        foreach ($datas as $key => $data) {
            $this->data = $data;
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
            "auteur" => $this->auteur
        ];
    }
    public function getCommentByArticle()
    {
        // var_dump($this->id_article, $this->data);
        $this->commentaires = $this->model->getComments($this->data);
        return $this->commentaires;
    }


    public function save($safedata)
    { 
        //tableau recupère les données de l'objet avec 2 foreach pour le tri. Assignation des des valeurs récupérées aux bonnes variables.
        try {
            $recupData=[];
        foreach ($safedata as $key => $value) {
            foreach ($value as $key => $valuedata) {
                array_push($recupData, $valuedata);
            }
        }
        $this->id_article = $recupData[3];
        $this->auteur = $recupData[1];
        $this->contenu = $recupData[2];

            $this->model->postComment(
                $this->id_article,
                $this->auteur,
                $this->contenu,
            );
        } catch (\Throwable $th) {
           
        }
    }
}
