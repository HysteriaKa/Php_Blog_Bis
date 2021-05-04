<?php

namespace Blog\Models;

use Blog\Models\DataBase;

class CommentModel extends DataBase
{
    /**
     * permet d'obtenir la liste des commentaires à parttir d'un id
     *
     * @param   String  $id_article  l'id de l'article
     *
     * @return   Array              la liste des commantaires
     */
    public function getComments($id_article)
    {
        $req = $this->db
            ->prepare("SELECT * FROM `commentaires`   WHERE id_article=:id_article ");
        $req->execute(["id_article"=>$id_article]);
        return $req->fetchAll ();
    }

    public function postComment($idArticle, $auteur, $content)
    {
        $req = $this->db
            ->prepare('INSERT INTO comments(id_article, auteur, contenu, created_at )VALUE(.,?,?, NOW()');
        $row = $req->execute(array($idArticle, $auteur, $content));
        return $row;
    }
}
