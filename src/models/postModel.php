<?php

namespace Blog\Models;

use Blog\Models\DataBase;
use Blog\Ctrl\Utils;

class PostModel extends DataBase
{

    public function getArticles()
    {



        $req = $this->db->query("SELECT * FROM articles ");

        $articles = [];
        while ($rows = $req->fetchObject()) {
            $articles[] = $rows;
        }

        return $articles;
    }

  

    public function getArticle(){

        $resultData =$this->db->prepare("SELECT * FROM `articles` WHERE `titre`=:titre ORDER BY `created_at` DESC" );
        $resultData->execute(["titre"=>Utils::uriToTitle($this->props->titre)]);
        // die(var_dump($resultData->debugDumpParams()));
        
        while ($data = $resultData->fetchObject()) {
           return $data;  
           
        }
    }
}
