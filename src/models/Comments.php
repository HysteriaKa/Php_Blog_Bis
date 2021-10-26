<?php

namespace Blog\Models;

use Blog\Models\Entity;



class Comments extends Entity
{

    protected $id;
    protected $created_at;
    protected $id_user;
    protected String $id_article;
    protected $auteur;
    


    public function __construct($datas=[])
    {
        parent::__construct();
        
        foreach ($datas as $key => $data) {
            $this->$key = $data;
        }
        
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
        $this->commentaires = $this->getComments($this->id_article);
        
        return $this->commentaires;
    }


    public function save($safedata)
    {
        global $currentSession;
        try {

            $this->postComment(
                $safedata->post["id_article"],
                $currentSession->get("idUser"),
                $safedata->post["content"],   
                0,
                
                
            );
        } catch (\Throwable $th) {
            
        }
        
    }

    public function getArticleTitle()
    {
        $title = $this->getParentArticle($this->id);
        return $title["titre"];
    }

    public function getArticleId()
    {
      
        $article = $this->getParentArticle($this->id);
        return $article["id"];
    }
    public function deleteComment($safedata)
    {

        try {
            $this->requestDeleteComment(
                $safedata
            );
        } catch (\Throwable $th) {
            
        }
    }
    public function removeComment()
    {
        try {
            $this->requestDeleteComment($this->id);
        } catch (\Exception $err) {
            
            throw $err;
        }
    }
    public function getCommentsToValidate(){

        try {
            $comments = $this->requestCommentsToValidate();
            return $comments;
        } catch (\Throwable $th) {
           
        }
    }
    public function validateComment()
    {
        try{
            $this->updateComment($this->post["commentToValidate"]);
        }catch (\Exception $err) {  
            throw $err;
        }
    }
    public function getComments($id_article)
    {
        $req = $this->db
            ->prepare("SELECT * FROM `commentaires`   WHERE id_article=:id_article ");
        $req->execute(["id_article" => $id_article]);

        return $req->fetchAll();
    }
    /**
     * permet d'insérer un nouveau commentaire à partir de l' id article
     *
     * @param   String  $id_article  l'id de l'article
     *
     * @return   void
     */
    public function postComment($idArticle, $idAuteur, $content, $statut)
    {

        $req = $this->db
            ->prepare("INSERT INTO commentaires(id_article,id_user,contenu,created_at,statut) VALUES(?,?,?,NOW(),?)");
        $req->execute(array($idArticle, $idAuteur, $content, $statut));
    }
    /**
     * permet de supprimer un commentaire à partir d'un id
     *
     * @param   String  $id_article  l'id de l'article
     *
     * @return   Array              la liste des commentaires
     */
    public function requestDeleteComment($id)
    {
        $req = $this->db
            ->prepare("DELETE FROM commentaires where id=:id");
        $req->execute(["id" => $id]);
    }
   /**
     * permet de récupérer le bon commentaire à partir d'un id
     *
     * @param   String  $idcomment
     *
     * @return  String              
     */
    public function getParentArticle($idComment)
    {
        
        $req = $this->db
            ->prepare("SELECT articles.titre, articles.id from commentaires INNER JOIN articles ON commentaires.id_article = articles.id WHERE commentaires.id = ? LIMIT 1");
        $req->execute([$idComment]);
        return $req->fetch();
    }
    public function RequestCommentsToValidate()
    {
        $req = $this->db

            ->prepare("SELECT commentaires.id, commentaires.statut, commentaires.created_at, commentaires.id_article, commentaires.contenu, users.username, articles.titre FROM ((`commentaires` JOIN `users` ON users.id = commentaires.id_user AND commentaires.statut=0)
            JOIN `articles` ON commentaires.id_article = articles.id) ORDER BY created_at DESC");
        $req->execute();
        $comments = [];
        while ($rows = $req->fetchObject()) {
            $comments[] = $rows;
        }
       
        return $comments;
    }
    public function updateComment($id)
    {
        $req = $this->db
            ->prepare("UPDATE `commentaires` SET commentaires.statut = 1 WHERE id=:id");
        $req->execute(["id" => $id]);
    }
}
