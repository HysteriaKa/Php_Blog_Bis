<?php

namespace Blog\Models;

use Blog\Models\DataBase;

class CommentModel extends DataBase
{
    public function getComments()
    {

        $req = $this->db
            ->prepare("SELECT * FROM `commentaires` join articles ON commentaires.id_article=articles.id");
        $req->execute();
        while ($rows = $req->fetchObject()) {
            $commentaires[] = $rows;
        }

        return $rows;
    }

    public function postComment($idArticle, $auteur, $content)
    {
        $req = $this->db
            ->prepare('INSERT INTO comments(id_article, auteur, contenu, created_at )VALUE(.,?,?, NOW()');
        $row = $req->execute(array($idArticle, $auteur, $content));
        return $row;
    }
}
