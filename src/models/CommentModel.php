<?php

namespace Blog\Models;

use Blog\Models\DataBase;

class CommentModel extends DataBase
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

    public function postComment($idArticle, $auteur, $content, $statut, $email)
    {

        $req = $this->db
            ->prepare("INSERT INTO commentaires(id_article,auteur,contenu,created_at,statut, email) VALUES(?,?,?,NOW(),?, ?)");
        $req->execute(array($idArticle, $auteur, $content, $statut, $email));
        // return $req->fetchAll();
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
    public function getAllComments()
    {
        $req = $this->db

            ->prepare("SELECT * FROM `commentaires`");
        $req->execute();
        $comments = [];
        while ($rows = $req->fetchObject()) {
            $comments[] = $rows;
        }
        // var_dump($comments);
        return $comments;
    }
}
