<?php

namespace Blog\Ctrl;

use Blog\Ctrl\Entity;
use Blog\Models\PostModel;

class Post extends Entity
{
    protected $id;
    protected $titre;
    protected $content;
    protected $created_at;
    protected $chapo;
    protected $id_user;
    protected $image;
    protected $list;
    protected $modify_at;
    private $model;

    public function __construct($data)
    {
        $this->model = new PostModel($data);

        if ($data === "all") return $this->initInOrderToGetList();
        // if (isset($data["titre"])) return $this->initByTitle(); TODO : verifier quand on en vait besoin et adapter
    }

    public function initByTitle()
    {
        $data = $this->model->getArticle();
        $this->hydrate($data);
    }

    private function initInOrderToGetList()
    {
        $this->list = $this->model->getArticles();
    }

    public function initById()
    {
        $data = $this->list = $this->model->getOneArticleById();
        $this->hydrate($data);

    }

    public function getList()
    {
        return $this->list;
    }

    public function getAll()
    {
        global $utils;
        return [
            "id" => $this->id,
            "titre" => $this->titre,
            "content" => $this->content,
            "createdAt" => $this->created_at,
            "chapo" => $this->chapo,
            "image" => $this->image,
            "idUser" => $this->id_user,
            "modifyAt" => $this->modify_at,
            "url" => $utils->titleToURI($this->titre)
        ];
    }

    /**
     * permet d'avoir l'id de l'article
     *
     * @return  Integer  l'id de l'article
     */
    public function getId()
    {
        return $this->id;
    }
    public function addArticle($safedata)
    {
        global $currentSession;
        try {

            $this->model->addArticle(
                $safedata->post["titre"],
                $currentSession->get("idUser"),
                $safedata->post["chapo"],
                $safedata->post["content"],
                $safedata->post["image"],
                $safedata->post["created_at"],
                // $safedata->post["titre"]
                
            );
        } catch (\Exception $e) {
            new ErrorHandler($e);
        }
    }

    public function addPost()
    {
        try {
            $data = $this->model->addArticle();
            $this->hydrate($data);
        } catch (\Exception $e) {
            new ErrorHandler($e);
        }
    }
    public function deleteArticle($safedata)
    {

        try {
            $this->model->deleteArticle(
                $safedata
            );
        } catch (\Throwable $th) {
        }
    }
    public function removeArticle()
    {
        try {
            $this->model->deleteArticle();
        } catch (\Exception $e) {
            new ErrorHandler($e);
        }
    }

    public function modifyArticle($safedata)
    {
        // global $currentSession;
        try {
            $this->model->updateArticle(
                $this->getId(),
                // $currentSession->get("idUser"),
                $safedata->post["titre"],
                $safedata->post["content"],
                $safedata->post["chapo"],
                $safedata->post["image"]
            );
        } catch (\Exception $e) {
            new ErrorHandler($e);
        }
    }
}