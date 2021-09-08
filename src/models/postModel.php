<?php

namespace Models;

// use Models\DataBase;
use Controller\Utils;
use Controller\ErrorHandler;

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



    public function getArticle()
    {

        $resultData = $this->db->prepare("SELECT * FROM `articles` WHERE `titre`=:titre ORDER BY `created_at` DESC");
        $resultData->execute(["titre" => Utils::uriToTitle($this->props->titre)]);
        // die(var_dump($resultData->debugDumpParams()));

        while ($data = $resultData->fetchObject()) {
            return $data;
        }
    }
    public function getOneArticleById()
    {

        $resultData = $this->db->prepare("SELECT * FROM `articles` WHERE `id`=:id ");
        $resultData->execute(["id" => $this->props->id]);
        // die(var_dump($resultData->debugDumpParams()));
        return $resultData->fetch();

        
    }
    public function addArticle()
    {
        try {
            $data = [
                "chapo"   => $this->props->chapo,
                "content" => $this->props->content,
                "id_user" => $this->props->id_user,
                "image"   => $this->props->image,
                "titre"   => $this->props->titre
            ];
            $req = $this->db
                ->prepare("INSERT INTO articles(titre,id_user,content,image,created_at,chapo) VALUES(:titre,:id_user,:content,:image,now(),:chapo)");
            $req->execute($data);
            //  die(var_dump($req->debugDumpParams())); 
        } catch (\Exception $e) {
            new ErrorHandler($e);
        }
    }
    public function updateArticle($id, $titre, $content, $chapo, $image)
    {

        try {
            // var_dump($id, $titre, $chapo,$image, $content);
            $req = $this->db
                ->prepare("UPDATE `articles` SET articles.titre=:titre,
                articles.content=:content, articles.chapo=:chapo, 
                articles.image=:image, articles.modify_at= NOW() WHERE id=:id");
            $req->execute([":id" => $id, ":content"=>$content, ":chapo"=>$chapo,":titre"=>$titre, ":image"=>$image]);
            //   die(var_dump($req->debugDumpParams())); 

        } catch (\Exception $e) {
            new ErrorHandler($e);
        }
    }
    public function deleteArticle()
    {
        try {
            $req = $this->db
                ->prepare("DELETE FROM articles where id=:id");
            $req->execute();
            //  die(var_dump($req->debugDumpParams())); 
        } catch (\Exception $e) {
            new ErrorHandler($e);
        }
    }
}
