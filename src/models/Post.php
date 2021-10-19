<?php

namespace Blog\Models;

use Blog\Models\Entity;
use Blog\Ctrl\ErrorHandler;

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
    private $props;

    public function __construct($data)
    {
        $this->props = $data;

        if ($data === "all") return $this->initInOrderToGetList();
       
    }

    public function initByTitle()
    {
        $data = $this->getArticle();
        $this->hydrate($data);
    }

    private function initInOrderToGetList()
    {
        $this->list = $this->getArticles();
    }

    public function initById()
    {
        $data = $this->list = $this->getOneArticleById();
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

            $this->requestAddArticle(
                $safedata->post["titre"],
                $currentSession->get("idUser"),
                $safedata->post["chapo"],
                $safedata->post["content"],
                $safedata->post["image"],
                $safedata->post["created_at"] 
                
            );
        } catch (\Exception $e) {
            new ErrorHandler($e);
        }
    }

    public function addPost()
    {
        try {
            $data = $this->requestAddArticle();
            $this->hydrate($data);
        } catch (\Exception $e) {
            new ErrorHandler($e);
        }
    }

    public function deleteArticle($safedata)
    {
        try {
            $this->requestDeleteArticle(
                $safedata
            );
        } catch (\Throwable $th) {
        }
    }
    public function removeArticle()
    { //TODSO voir pour enlever la fonction
        try {
            $this->requestDeleteArticle($this->props["id"]);
        } catch (\Exception $e) {
            new ErrorHandler($e);
        }
    }

    public function modifyArticle($safedata)
    { 
        try {
            $this->updateArticle(
                $this->getId(),
                $safedata->post["titre"],
                $safedata->post["content"],
                $safedata->post["chapo"],
                $safedata->post["image"]
            );
        } catch (\Exception $e) {
            new ErrorHandler($e);
        }
    }

    public function getArticles()
    {

        $req = $this->db->query("SELECT * FROM articles ");

        $articles = [];
        while ($rows = $req->fetchObject()) {
            $articles[] = $rows;
        }
        return $articles;
    }



    public function getArticle()
    {
        global $utils;
        $resultData = $this->db->prepare("SELECT * FROM `articles` WHERE `titre`=:titre ORDER BY `created_at` DESC");
        $resultData->execute(["titre" => $utils->uriToTitle($this->props->titre)]);
        while ($data = $resultData->fetchObject()) {
            return $data;
        }
    }
    public function getOneArticleById()
    {

        $resultData = $this->db->prepare("SELECT * FROM `articles` WHERE `id`=:id ");
        $resultData->execute(["id" => $this->props->id]);

        return $resultData->fetch();
    }

    public function requestAddArticle()
    {
        try {
            $req = $this->db
                ->prepare("INSERT INTO articles(titre,id_user,content,image,created_at,chapo) VALUES(:titre,:id_user,:content,:image,now(),:chapo)");
            $req->execute($this->props);
            
        } catch (\Exception $e) {
            new ErrorHandler($e);
        }
    }
    public function updateArticle($id, $titre, $content, $chapo, $image)
    {

        try {
           
            $req = $this->db
                ->prepare("UPDATE `articles` SET articles.titre=:titre,
                articles.content=:content, articles.chapo=:chapo, 
                articles.image=:image, articles.modify_at= NOW() WHERE id=:id");
            $req->execute([":id" => $id, ":content" => $content, ":chapo" => $chapo, ":titre" => $titre, ":image" => $image]);
          

        } catch (\Exception $e) {
            new ErrorHandler($e);
        }
    }
    public function requestDeleteArticle($id)
    {
        try {
            $req = $this->db
                ->prepare("DELETE FROM articles where id=:id");
            $req->execute(["id"=>$id]);
           
        } catch (\Exception $e) {
            new ErrorHandler($e);
        }
    }

}
