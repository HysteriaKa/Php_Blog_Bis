<?php

namespace Blog\Models;

use Blog\Models\Database;

class CommentModel extends Database
{
    /**
     * permet d'obtenir la liste des commentaires à partir d'un id
     *
     * @param   String  $id_article  l'id de l'article
     *
     * @return   Array              la liste des commentaires
     */
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
    public function deleteComment($id)
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
    public function getCommentsToValidate()
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
