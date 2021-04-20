<?php

namespace Blog\Models;

use Blog\Models\DataBase;

class CommentModel extends DataBase
{
    public function getComments()
    {

        $req = $this->db
            ->query("SELECT * FROM `commentaires` join articles ON commentaires.id_article=articles.id");
        $commentaires = [];
        while ($rows = $req->fetchObject()) {
            $commentaires[] = $rows;
        }

        return $commentaires;
    }
}
