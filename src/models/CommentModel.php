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
            // ->prepare("SELECT commentaires.contenu, commentaires.statut, commentaires.created_at, users.username from ((commentaires INNER JOIN articles ON commentaires.id_article = articles.id) JOIN users ON commentaires.id_user = users.id)");
        //  die(var_dump($req));
            $req->execute(["id_article" => $id_article]);
       
        return $req->fetchAll();
    }

    public function postComment($idArticle, $idAuteur, $content, $statut)
    {

        $req = $this->db
            ->prepare("INSERT INTO commentaires(id_article,id_user,contenu,created_at,statut) VALUES(?,?,?,NOW(),?)");
        $req->execute(array($idArticle, $idAuteur, $content, $statut));
        // var_dump($idArticle, $idAuteur, $content, $statut);
    }
    public function deleteComment($id)
    {
        $req = $this->db
            ->prepare("DELETE FROM commentaires where id=:id");
        // die(var_dump($this));
        $req->execute(["id" => $id]);
    }

    public function getParentArticle($idComment)
    {
        // die(var_dump($idComment)); on récupère bien l id en string 
        $req = $this->db
            ->prepare("SELECT articles.titre, articles.id from commentaires INNER JOIN articles ON commentaires.id_article = articles.id WHERE commentaires.id = ? LIMIT 1");
        // die(var_dump($req->debugDumpParams())); resultat null 
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
        // var_dump($comments);
        return $comments;
    }
    public function updateComment($id)
    {
        $req = $this->db
            ->prepare("UPDATE `commentaires` SET commentaires.statut = 1 WHERE id=:id");
        $req->execute(["id" => $id]);
    }
}
