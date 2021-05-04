<?php

namespace Blog\Ctrl;
use Blog\Ctrl\Entity;
use Blog\Models\PostModel;

class Post extends Entity{
    protected $id;
    protected $titre;
    protected $content;
    protected $created_at;
    protected $chapo;
    protected $id_user;
    protected $image;
    protected $list;
    private $model;

    public function __construct($data) {
        $this->model = new PostModel($data);

        if ($data === "all") return $this->initInOrderToGetList();
        if( isset($data["titre"])) return $this->initByTitle();

    }

    private function initByTitle(){
        $data = $this->model->getArticle();
        $this->hydrate($data);
    }

    private function initInOrderToGetList(){
        $this->list = $this->model->getArticles();
    }

    public function getList(){
        return $this->list ;
    }

    public function getAll(){
        return [
            "id" => $this->id,
            "titre" => $this->titre,
            "content" => $this->content,
            "createdAt" => $this->created_at,
            "chapo" => $this->chapo,
            "image" =>$this->image,
            "idUser" => $this->id_user
        ];
    }   

    /**
     * permet d'avoir l'id de l'article
     *
     * @return  Integer  l'id de l'article
     */
    public function getId(){
        return $this->id;
    }
}